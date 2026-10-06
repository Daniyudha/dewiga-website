<?php

namespace App\Console\Commands;

use App\Models\RundownTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeLiveInRundownCommand extends Command
{
    /**
     * Nama & signature perintah artisan.
     *
     * Contoh pemakaian:
     *   php artisan rundown:make-livein
     *   php artisan rundown:make-livein --name="Nama Kustom" --code="LIVE-KUSTOM"
     *   php artisan rundown:make-livein --force
     */
    protected $signature = 'rundown:make-livein
                            {--name= : Nama template (default: Live In 4 Hari 3 Malam (Edukasi))}
                            {--code= : Kode template (default: LIVE-4D3N-EDU)}
                            {--force : Hapus & buat ulang jika kode sudah terpakai}';

    protected $description = 'Membuat template rundown live-in 4 hari 3 malam: kedatangan malam, 2 aktivitas pendidikan/hari, kegiatan orang tua asuh, acara penutupan, dan kepulangan.';

    public function handle(): int
    {
        $name = $this->option('name') ?: 'Live In 4 Hari 3 Malam (Edukasi)';
        $code = $this->option('code') ?: 'LIVE-4D3N-EDU';
        $force = (bool) $this->option('force');

        // Cegah duplikasi berdasarkan kode (termasuk yang terhapus sementara).
        $existing = RundownTemplate::withTrashed()->where('code', $code)->first();

        if ($existing && !$force) {
            $this->error("Template dengan kode \"{$code}\" sudah ada (ID: {$existing->id}).");
            $this->line('Gunakan opsi --force untuk menghapus template lama dan membuat ulang.');
            return self::FAILURE;
        }

        $template = DB::transaction(function () use ($name, $code, $existing, $force) {
            if ($existing && $force) {
                $existing->forceDelete(); // cascade menghapus items-nya juga
                $this->warn("Template lama \"{$existing->name}\" (ID: {$existing->id}) dihapus.");
            }

            $template = RundownTemplate::create([
                'name' => $name,
                'code' => $code,
                'description' => 'Program live-in edukasi 4 hari 3 malam: kedatangan malam, kegiatan bersama warga/orang tua asuh, 2 aktivitas pendidikan per hari, acara penutupan, lalu kepulangan.',
                'duration_days' => 4,
                'duration_nights' => 3,
                'is_active' => true,
                'created_by' => null,
            ]);

            foreach ($this->items() as $day => $dayItems) {
                $sortOrder = 1;
                foreach ($dayItems as $item) {
                    $template->items()->create([
                        'day_number' => $day,
                        'start_time' => $item['start_time'] ?? null,
                        'end_time' => $item['end_time'] ?? null,
                        'activity_name' => $item['activity_name'],
                        'location' => $item['location'] ?? null,
                        'person_in_charge' => $item['person_in_charge'] ?? null,
                        'description' => $item['description'] ?? null,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            return $template->fresh('items');
        });

        $this->info('✅ Template rundown berhasil dibuat!');
        $this->table(
            ['Field', 'Nilai'],
            [
                ['Nama', $template->name],
                ['Kode', $template->code],
                ['Durasi', $template->duration_days . ' hari / ' . $template->duration_nights . ' malam'],
                ['Jumlah item', (string) $template->items->count()],
            ]
        );

        $this->line('');
        $this->line('Langkah selanjutnya:');
        $this->line('  1. Buka menu Admin → Rundown Templates untuk mengedit detail aktivitas.');
        $this->line('  2. Pada halaman detail Schedule, pilih "Buat rundown dari template" dan pilih template ini.');
        $this->line('  3. Setelah dipakai di schedule, rundown bisa diedit per-jadwal (waktu, lokasi, PIC, dll).');

        return self::SUCCESS;
    }

    /**
     * Susunan kegiatan 4 hari 3 malam.
     *
     * Hari 1  : Kedatangan (malam) — hanya menginap.
     * Hari 2  : Kegiatan warga/orang tua asuh + 2 aktivitas pendidikan + makan 3x.
     * Hari 3  : Kegiatan orang tua asuh + 2 aktivitas pendidikan + penutupan + makan 3x.
     * Hari 4  : Makan 1x + pulang.
     *
     * Silakan edit waktu/nama kegiatan sesuai kebutuhan di admin panel.
     */
    protected function items(): array
    {
        $homestay = 'Homestay';
        $pendopo = 'Pendopo Desa Wisata Gabugan';
        $areaDesa = 'Lingkungan Desa Wisata Gabugan';
        $areaEdukasi = 'Area Edukasi Desa Wisata Gabugan';

        return [
            // Hari 1 — Kedatangan (malam)
            1 => [
                ['start_time' => '19:00', 'end_time' => '19:30', 'activity_name' => 'Kedatangan dan Penyambutan', 'location' => $pendopo],
                ['start_time' => '19:30', 'end_time' => '20:00', 'activity_name' => 'Pembagian Homestay dan Orientasi Singkat', 'location' => $homestay],
                ['start_time' => '20:00', 'end_time' => '21:00', 'activity_name' => 'Makan Malam', 'location' => $homestay],
                ['start_time' => '21:00', 'end_time' => null, 'activity_name' => 'Istirahat dan Menginap', 'location' => $homestay],
            ],
            // Hari 2
            2 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga dan Piket Bersama Orang Tua Asuh', 'location' => $areaDesa],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay],
                ['start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Kegiatan Bersama Warga / Orang Tua Asuh', 'location' => $areaDesa],
                ['start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Aktivitas Pendidikan 1', 'location' => $areaEdukasi, 'description' => '(Sesuaikan tema aktivitas pendidikan)'],
                ['start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Makan Siang, Istirahat, dan Ibadah', 'location' => $homestay],
                ['start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Aktivitas Pendidikan 2', 'location' => $areaEdukasi, 'description' => '(Sesuaikan tema aktivitas pendidikan)'],
                ['start_time' => '15:30', 'end_time' => '17:00', 'activity_name' => 'Kegiatan Bersama Orang Tua Asuh (Lanjutan)', 'location' => $areaDesa],
                ['start_time' => '17:00', 'end_time' => '18:30', 'activity_name' => 'Makan Malam, Istirahat, dan Ibadah', 'location' => $homestay],
                ['start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Refleksi dan Malam Keakraban', 'location' => $pendopo],
            ],
            // Hari 3
            3 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga dan Piket Bersama Orang Tua Asuh', 'location' => $areaDesa],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay],
                ['start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Kegiatan Bersama Orang Tua Asuh', 'location' => $areaDesa],
                ['start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Aktivitas Pendidikan 1', 'location' => $areaEdukasi, 'description' => '(Sesuaikan tema aktivitas pendidikan)'],
                ['start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Makan Siang, Istirahat, dan Ibadah', 'location' => $homestay],
                ['start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Aktivitas Pendidikan 2', 'location' => $areaEdukasi, 'description' => '(Sesuaikan tema aktivitas pendidikan)'],
                ['start_time' => '15:30', 'end_time' => '17:00', 'activity_name' => 'Persiapan Acara Penutupan', 'location' => $pendopo],
                ['start_time' => '17:00', 'end_time' => '18:30', 'activity_name' => 'Makan Malam, Istirahat, dan Ibadah', 'location' => $homestay],
                ['start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Acara Penutupan dan Perpisahan', 'location' => $pendopo],
            ],
            // Hari 4 — Kepulangan
            4 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Packing dan Berkemas', 'location' => $homestay],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay],
                ['start_time' => '08:00', 'end_time' => '09:00', 'activity_name' => 'Perpisahan dengan Orang Tua Asuh dan Check-out', 'location' => $pendopo],
                ['start_time' => '09:00', 'end_time' => null, 'activity_name' => 'Kepulangan', 'location' => $pendopo],
            ],
        ];
    }
}

