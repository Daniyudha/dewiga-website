<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmartResponseService
{
    public function generate(string $userMessage, array $searchResults, string $locale = 'id'): string
    {
        if (empty($searchResults)) {
            return $this->noDataResponse($locale);
        }

        $parts = [];
        $hasTables = false;

        foreach ($searchResults as $tableName => $data) {
            if (empty($data['rows'])) continue;
            $hasTables = true;
            $response = $this->formatTableData($tableName, $data, $locale);
            if ($response) $parts[] = $response;
        }

        if (!$hasTables) {
            return $this->noDataResponse($locale);
        }

        $closing = "<br><br>Monggo, jika ada yang ingin ditanyakan lagi, Kakak bisa langsung hubungi kami via WhatsApp di <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252</a> ya 🙏";

        return implode("<br><br>", $parts) . $closing;
    }

    protected function formatTableData(string $tableName, array $data, string $locale): string
    {
        $rows = $data['rows'];
        if (empty($rows)) return '';

        return match ($tableName) {
            'travel_packages' => $this->formatTravelPackageCards($rows, $locale),
            'activities' => $this->formatActivityCards($rows, $locale),
            'blogs' => $this->formatBlogs($rows, $locale),
            'testimonials' => $this->formatTestimonials($rows, $locale),
            'schedules' => $this->formatSchedules($rows, $locale),
            'partner_logos' => $this->formatPartners($rows, $locale),
            default => $this->formatGeneric($tableName, $rows, $data['columns'], $locale),
        };
    }

    /**
     * Get first gallery image for a travel package from galleries table.
     * galleries.travel_package_id = package id
     */
    protected function getGalleryImage(int $packageId): ?string
    {
        try {
            $gallery = DB::table('galleries')
                ->where('travel_package_id', $packageId)
                ->first();
            if ($gallery && !empty($gallery->images)) {
                $path = storage_path('app/public/' . $gallery->images);
                if (file_exists($path)) {
                    return asset('storage/' . $gallery->images);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Chatbot: Gallery error: " . $e->getMessage());
        }
        return null;
    }

    protected function formatTravelPackageCards(array $rows, string $locale): string
    {
        $placeholder = 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=300&auto=format&fit=crop';
        $signaturePackage = null;
        foreach ($rows as $row) {
            if (!empty($row['is_signature']) || !empty($row['signature'])) {
                $signaturePackage = $row;
                break;
            }
        }

        $out = "Halo Kak! 🙏 Selamat datang di <strong>Desa Wisata Gabugan</strong>.<br>Berikut paket wisata yang bisa Kakak pilih, monggo dilihat-lihat dulu ya 😊<br><br>";

        foreach ($rows as $i => $row) {
            $slug = $row['slug'] ?? $row['id'] ?? '';
            $url = "/travel-packages/{$slug}";
            $name = $row['type'] ?? $row['type_id'] ?? $row['type_en'] ?? "Package " . ($i+1);
            $price = isset($row['price']) ? 'Rp ' . number_format((float)$row['price'], 0, ',', '.') : '';
            $priceLabel = $locale === 'en' ? '/person' : '/orang';

            // Ambil gambar dari tabel galleries via travel_package_id
            $imgSrc = $this->getGalleryImage($row['id'] ?? 0) ?? $placeholder;

            $isSignature = !empty($row['is_signature']) || !empty($row['signature']);
            $sigBadge = $isSignature ? '<span class="inline-block bg-amber-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full ml-1">⭐ Signature</span>' : '';
            $desc = strip_tags((string)($row['description'] ?? $row['description_id'] ?? $row['description_en'] ?? ''));
            $desc = Str::limit($desc, 80);

            $out .= "<a href=\"{$url}\" target=\"_blank\" class=\"block bg-white border border-neutral-200 rounded-xl overflow-hidden hover:shadow-md transition mb-2\">";
            $out .= "<div class=\"flex gap-3 p-2\">";
            $out .= "<img src=\"{$imgSrc}\" onerror=\"this.src='{$placeholder}'\" class=\"w-16 h-16 rounded-lg object-cover shrink-0\" alt=\"{$name}\">";
            $out .= "<div class=\"min-w-0 flex-1\">";
            $out .= "<h4 class=\"text-xs font-bold text-[#053d2c] leading-tight\">{$name}{$sigBadge}</h4>";
            $out .= "<p class=\"text-[10px] text-neutral-500 mt-0.5 line-clamp-2\">{$desc}</p>";
            $out .= "<span class=\"text-xs font-bold text-[#00a877] mt-1 block\">{$price} {$priceLabel}</span>";
            $out .= "</div></div></a>";
        }

        $out .= "<br>Masing-masing paket sudah termasuk pemandu lokal dan konsumsi. Untuk rombongan ada harga khusus lho! 💰<br><br>";

        if ($signaturePackage) {
            $sigName = $signaturePackage['type'] ?? $signaturePackage['type_id'] ?? 'Signature Package';
            $sigDesc = strip_tags((string)($signaturePackage['description'] ?? $signaturePackage['description_id'] ?? ''));
            $sigDesc = Str::limit($sigDesc, 100);
            $out .= "💡 <strong>Rekomendasi:</strong> <strong>⭐ {$sigName}</strong> adalah paket signature kami! {$sigDesc}";
        } else {
            $first = $rows[0] ?? null;
            $firstName = $first ? ($first['type'] ?? $first['type_id'] ?? '') : '';
            if ($firstName) {
                $out .= "💡 Mulai dari <strong>{$firstName}</strong> untuk pengalaman pertama yang tak terlupakan!";
            }
        }

        return $out;
    }

    protected function formatActivityCards(array $rows, string $locale): string
    {
        $placeholder = 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=300&auto=format&fit=crop';
        $out = "Halo Kak! 🙏 Berikut aktivitas seru yang bisa Kakak lakukan di <strong>Desa Wisata Gabugan</strong> 😊<br><br>";

        foreach ($rows as $row) {
            $slug = $row['slug'] ?? $row['id'] ?? '';
            $url = "/activities/{$slug}";
            $name = $row['title'] ?? $row['title_id'] ?? $row['title_en'] ?? '';
            $desc = strip_tags((string)($row['description'] ?? $row['description_id'] ?? $row['description_en'] ?? ''));
            $desc = Str::limit($desc, 80);
            $dur = $row['duration'] ?? '';

            // Ambil gambar langsung dari kolom image di tabel activities
            $imgSrc = $placeholder;
            if (!empty($row['image'])) {
                $imgPath = storage_path('app/public/' . $row['image']);
                if (file_exists($imgPath)) {
                    $imgSrc = asset('storage/' . $row['image']);
                }
            }

            $out .= "<a href=\"{$url}\" target=\"_blank\" class=\"block bg-white border border-neutral-200 rounded-xl overflow-hidden hover:shadow-md transition mb-2\">";
            $out .= "<div class=\"flex gap-3 p-2\">";
            $out .= "<img src=\"{$imgSrc}\" onerror=\"this.src='{$placeholder}'\" class=\"w-14 h-14 rounded-lg object-cover shrink-0\" alt=\"{$name}\">";
            $out .= "<div class=\"min-w-0\">";
            $out .= "<h4 class=\"text-xs font-bold text-[#053d2c] leading-tight\">{$name}</h4>";
            $out .= "<p class=\"text-[10px] text-neutral-500 mt-0.5 line-clamp-2\">{$desc}</p>";
            if ($dur) $out .= "<span class=\"text-[10px] text-[#00a877] mt-1 block\">⏱ {$dur}</span>";
            $out .= "</div></div></a>";
        }

        $out .= "<br>💡 Semua aktivitas didampingi pemandu lokal berpengalaman. Gabungkan beberapa aktivitas untuk pengalaman yang lebih seru! 😊";

        return $out;
    }

    protected function formatBlogs(array $rows, string $locale): string
    {
        $out = "Halo Kak! 🙏 Berikut artikel yang kami miliki 😊<br><br>";
        foreach ($rows as $row) {
            $title = $row['title_id'] ?? $row['title_en'] ?? $row['title'] ?? '';
            $excerpt = Str::limit(strip_tags((string)($row['content_id'] ?? $row['content_en'] ?? $row['content'] ?? '')), 100);
            $out .= "• <strong>{$title}</strong>";
            if ($excerpt) $out .= " — {$excerpt}<br>";
            else $out .= "<br>";
        }
        return $out;
    }

    protected function formatTestimonials(array $rows, string $locale): string
    {
        $out = "Halo Kak! 🙏 Berikut testimoni dari pengunjung kami 😊<br><br>";
        foreach ($rows as $row) {
            $name = $row['name'] ?? ($locale === 'en' ? 'Guest' : 'Tamu');
            $msg = Str::limit((string)($row['message_id'] ?? $row['message_en'] ?? $row['message'] ?? ''), 150);
            $out .= "• \"{$msg}\" — <strong>{$name}</strong><br>";
        }
        return $out;
    }

    protected function formatSchedules(array $rows, string $locale): string
    {
        $out = "Halo Kak! 🙏 Berikut jadwal kegiatan kami 😊<br><br>";
        foreach ($rows as $row) {
            $day = $row['day_id'] ?? $row['day_en'] ?? $row['day'] ?? '';
            $time = isset($row['time_start'], $row['time_end']) ? "{$row['time_start']} - {$row['time_end']}" : ($row['time'] ?? '');
            $activity = $row['activity_id'] ?? $row['activity_en'] ?? $row['activity'] ?? '';
            $out .= "• <strong>{$day}</strong>: {$activity}";
            if ($time) $out .= " ({$time})";
            $out .= "<br>";
        }
        return $out;
    }

    protected function formatPartners(array $rows, string $locale): string
    {
        $names = array_map(fn($r) => $r['name'] ?? '', $rows);
        $names = array_filter($names);
        if (empty($names)) return '';
        return "🤝 <strong>Mitra Kami:</strong> " . implode(', ', $names);
    }

    protected function formatGeneric(string $tableName, array $rows, array $columns, string $locale): string
    {
        $humanName = ucwords(str_replace('_', ' ', $tableName));
        $out = "Informasi <strong>{$humanName}</strong>:<br>";
        $nameCols = array_filter($columns, fn($c) => in_array($c, ['name', 'title', 'type', 'title_id', 'title_en', 'judul']));

        foreach ($rows as $row) {
            $name = '';
            foreach ($nameCols as $col) {
                if (!empty($row[$col])) { $name = $row[$col]; break; }
            }
            if (empty($name)) {
                foreach ($row as $k => $v) {
                    if (!empty($v) && !is_array($v) && !str_contains($k, 'id') && !str_contains($k, 'at')) {
                        $name = (string)$v;
                        break;
                    }
                }
            }
            $name = Str::limit((string)$name, 60);
            $out .= "• <strong>{$name}</strong><br>";
        }
        return $out;
    }

    protected function noDataResponse(string $locale): string
    {
        return "Maaf Kak, saya belum menemukan informasinya di database kami. Monggo langsung hubungi kami via WhatsApp di <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252</a> ya 😊";
    }
}