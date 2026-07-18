<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatabaseKnowledgeService
{
    protected int $schemaCacheTtl = 3600;

    protected array $indonesianToEnglishTable = [
        'paket' => 'travel_packages', 'wisata' => 'travel_packages', 'perjalanan' => 'travel_packages',
        'tour' => 'travel_packages', 'harga' => 'travel_packages', 'biaya' => 'travel_packages',
        'tarif' => 'travel_packages', 'fasilitas' => 'travel_packages', 'lokasi' => 'travel_packages',
        'transportasi' => 'travel_packages', 'keluarga' => 'travel_packages', 'rombongan' => 'travel_packages',
        'aktivitas' => 'activities', 'kegiatan' => 'activities', 'seru' => 'activities',
        'seni' => 'activities', 'acara' => 'activities',
        'berita' => 'blogs', 'artikel' => 'blogs', 'blog' => 'blogs', 'kuliner' => 'blogs', 'makanan' => 'blogs',
        'kategori' => 'categories', 'budaya' => 'categories',
        'galeri' => 'galleries', 'foto' => 'galleries',
        'testimoni' => 'testimonials', 'review' => 'testimonials',
        'jadwal' => 'schedules', 'waktu' => 'schedules', 'event' => 'schedules',
        'mitra' => 'partner_logos', 'partner' => 'partner_logos',
        'kontak' => 'contacts', 'hubungi' => 'contacts', 'wa' => 'contacts', 'whatsapp' => 'contacts',
        'penginapan' => 'homestays', 'homestay' => 'homestays', 'menginap' => 'homestays', 'livein' => 'homestays',
    ];

    public function discoverTables(): array
    {
        return Cache::remember('chatbot:table_list', $this->schemaCacheTtl, function () {
            $excluded = config('chatbot.rag.excluded_tables', []);
            $allTables = DB::select('SHOW TABLES');
            $tables = [];
            foreach ($allTables as $table) {
                $tableName = array_values((array) $table)[0];
                if (!in_array($tableName, $excluded)) $tables[] = $tableName;
            }
            return $tables;
        });
    }

    public function getTableSchema(string $tableName): array
    {
        return Cache::remember("chatbot:schema:{$tableName}", $this->schemaCacheTtl, function () use ($tableName) {
            try {
                $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                return array_map(fn($col) => ['name' => $col->Field, 'type' => $col->Type], $columns);
            } catch (\Exception $e) {
                Log::warning("Chatbot: schema error {$tableName}: " . $e->getMessage());
                return [];
            }
        });
    }

    public function searchRelevantData(string $userQuery): array
    {
        $startTime = microtime(true);
        $tables = $this->discoverTables();
        $keywords = $this->extractKeywords($userQuery);
        $priorityTables = config('chatbot.rag.priority_tables', []);
        $maxRowsPerTable = config('chatbot.rag.max_rows_per_table', 20);
        $maxContextLength = config('chatbot.rag.max_context_length', 8000);

        $tableScores = $this->scoreTables($tables, $keywords, $priorityTables);
        arsort($tableScores);

        $results = [];
        $totalChars = 0;

        foreach ($tableScores as $tableName => $score) {
            if ($score <= 0 && count($results) > 0) break;
            if ($totalChars >= $maxContextLength) break;

            // Semantic-matched tables (score>=20) → fetch all rows without keyword filter
            $fetchAll = $score >= 20;
            $tableData = $this->searchTable($tableName, $keywords, $maxRowsPerTable, $fetchAll);

            if (!empty($tableData['rows'])) {
                $dataJson = json_encode($tableData['rows'], JSON_UNESCAPED_UNICODE);
                $charCount = mb_strlen($dataJson);
                if ($totalChars + $charCount > $maxContextLength) {
                    $remaining = $maxContextLength - $totalChars;
                    $trimmed = $this->trimData($tableData['rows'], $remaining);
                    if (!empty($trimmed)) {
                        $results[$tableName] = ['columns' => $tableData['columns'], 'rows' => $trimmed, 'relevance_score' => $score];
                    }
                    break;
                }
                $results[$tableName] = ['columns' => $tableData['columns'], 'rows' => $tableData['rows'], 'relevance_score' => $score];
                $totalChars += $charCount;
            }
        }

        Log::info("Chatbot: Database search completed", [
            'query' => $userQuery,
            'keywords' => $keywords,
            'tables_found' => count($results),
            'total_chars' => $totalChars,
            'time_ms' => round((microtime(true) - $startTime) * 1000),
        ]);

        return $results;
    }

    protected function extractKeywords(string $query): array
    {
        $cleaned = preg_replace('/[^\w\s]/', ' ', $query);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        $cleaned = trim(mb_strtolower($cleaned));
        $words = explode(' ', $cleaned);
        $stopwords = [
            'yang','dan','di','ke','dari','ada','apa','itu','ini',
            'saya','aku','kamu','dia','mereka','kita','kami',
            'dengan','untuk','pada','atau','juga','saja','bisa',
            'the','a','an','is','are','was','were','in','on','at',
            'to','for','of','with','or','and','but','not','be',
            'do','does','did','have','has','had','can','will',
            'ada','gak','ga','nggak','nya','dong','sih','deh',
            'tolong','bantu','informasi','info','tentang','mengenai',
            'apakah','bagaimana','dimana','kapan','berapa',
        ];
        $keywords = array_filter($words, fn($w) => mb_strlen($w) >= 2 && !in_array($w, $stopwords));
        return array_values(array_unique($keywords));
    }

    protected function scoreTables(array $tables, array $keywords, array $priorityTables): array
    {
        $scores = [];
        foreach ($tables as $tableName) {
            $score = 0;
            $tableNameLower = mb_strtolower($tableName);
            $schema = $this->getTableSchema($tableName);
            $columnNames = array_map(fn($col) => mb_strtolower($col['name']), $schema);

            foreach ($keywords as $keyword) {
                if (mb_strpos($tableNameLower, $keyword) !== false) $score += 10;

                if (isset($this->indonesianToEnglishTable[$keyword])) {
                    $target = $this->indonesianToEnglishTable[$keyword];
                    if ($tableNameLower === $target) $score += 25;
                }

                foreach ($this->indonesianToEnglishTable as $idWord => $enTable) {
                    if (mb_strpos($keyword, $idWord) !== false || mb_strpos($idWord, $keyword) !== false) {
                        if ($tableNameLower === $enTable) $score += 20;
                    }
                }

                if (mb_strpos($tableNameLower, Str::singular($keyword)) !== false) $score += 8;
                if (mb_strpos($tableNameLower, Str::plural($keyword)) !== false) $score += 8;

                foreach ($columnNames as $colName) {
                    if (mb_strpos($colName, $keyword) !== false) $score += 5;
                }
            }

            if (in_array($tableName, $priorityTables)) $score += 5;
            $scores[$tableName] = $score;
        }
        return $scores;
    }

    /**
     * Search a table. If $fetchAll=true, return all rows without keyword filter.
     */
    protected function searchTable(string $tableName, array $keywords, int $limit, bool $fetchAll = false): array
    {
        $schema = $this->getTableSchema($tableName);
        if (empty($schema)) return ['columns' => [], 'rows' => []];

        $allColumns = array_map(fn($col) => $col['name'], $schema);

        $query = DB::table($tableName);

        if (!$fetchAll) {
            $textColumns = [];
            foreach ($schema as $col) {
                $colType = mb_strtolower($col['type']);
                $textTypes = ['varchar', 'text', 'longtext', 'mediumtext', 'char', 'json', 'enum'];
                foreach ($textTypes as $textType) {
                    if (mb_strpos($colType, $textType) !== false) {
                        $textColumns[] = $col['name'];
                        break;
                    }
                }
            }
            if (!empty($textColumns) && !empty($keywords)) {
                $query->where(function ($q) use ($textColumns, $keywords) {
                    foreach ($textColumns as $col) {
                        foreach ($keywords as $keyword) {
                            $q->orWhere($col, 'LIKE', "%{$keyword}%");
                        }
                    }
                });
            }
        }

        $rows = $query->limit($limit)->get()->map(function ($row) {
            $data = (array) $row;
            unset($data['password'], $data['remember_token'], $data['api_key'], $data['token']);
            return $data;
        })->toArray();

        return ['columns' => $allColumns, 'rows' => $rows];
    }

    protected function trimData(array $rows, int $maxChars): array
    {
        $trimmed = [];
        $current = 0;
        foreach ($rows as $row) {
            $chars = mb_strlen(json_encode($row, JSON_UNESCAPED_UNICODE));
            if ($current + $chars > $maxChars) break;
            $trimmed[] = $row;
            $current += $chars;
        }
        return $trimmed;
    }

    public function formatResultsForPrompt(array $results): string
    {
        if (empty($results)) return "TIDAK ADA DATA YANG DITEMUKAN di database untuk pertanyaan ini.";
        $out = "DATA DARI DATABASE DESA WISATA GABUGAN:\n\n";
        foreach ($results as $tableName => $data) {
            $out .= "=== {$this->humanizeTableName($tableName)} ===\n";
            foreach ($data['rows'] as $i => $row) {
                $out .= "--- #" . ($i + 1) . " ---\n";
                foreach ($row as $key => $value) {
                    if ($value === null || $value === '') continue;
                    $val = mb_strlen((string)$value) > 200 ? mb_substr((string)$value, 0, 200) . '...' : $value;
                    $out .= "{$key}: {$val}\n";
                }
                $out .= "\n";
            }
        }
        return $out;
    }

    protected function humanizeTableName(string $tableName): string
    {
        $map = [
            'travel_packages' => 'Paket Wisata', 'activities' => 'Aktivitas',
            'blogs' => 'Artikel', 'categories' => 'Kategori', 'galleries' => 'Galeri',
            'activity_galleries' => 'Galeri Aktivitas', 'site_galleries' => 'Galeri Situs',
            'testimonials' => 'Testimoni', 'partner_logos' => 'Logo Mitra',
            'schedules' => 'Jadwal', 'bookings' => 'Reservasi', 'contacts' => 'Kontak',
        ];
        return $map[$tableName] ?? ucwords(str_replace('_', ' ', $tableName));
    }

    public function clearCache(): void
    {
        Cache::forget('chatbot:table_list');
        foreach ($this->discoverTables() as $table) {
            Cache::forget("chatbot:schema:{$table}");
        }
        Log::info('Chatbot: Schema cache cleared');
    }
}