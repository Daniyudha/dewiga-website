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

        // Penanggung jawab (PIC)
        $panitia = 'Panitia Pendamping';
        $ota = 'Orang Tua Asuh';
        $fasilitator = 'Fasilitator / Tim Edukasi';

        return [
            // Hari 1 — Kedatangan (malam, tanpa makan malam)
            1 => [
                ['start_time' => '19:00', 'end_time' => '19:30', 'activity_name' => 'Kedatangan dan Penyambutan', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Peserta tiba dan disambut. Opsi: sambutan singkat dari kepala desa/pengelola desa wisata dan pengenalan tim pendamping.'],
                ['start_time' => '19:30', 'end_time' => '20:00', 'activity_name' => 'Pembagian Homestay dan Orientasi Singkat', 'location' => $homestay, 'person_in_charge' => $panitia, 'description' => 'Pembagian kelompok homestay beserta orang tua asuh, briefing tata tertib, dan gambaran jadwal kegiatan.'],
                ['start_time' => '20:00', 'end_time' => null, 'activity_name' => 'Istirahat dan Menginap', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Peserta beristirahat dan menginap di homestay masing-masing bersama orang tua asuh.'],
            ],
            // Hari 2
            2 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Bangun pagi, ibadah, dan persiapan diri sebelum memulai kegiatan.'],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga dan Piket Bersama Orang Tua Asuh', 'location' => $areaDesa, 'person_in_charge' => $ota, 'description' => 'Opsi: senam pagi, jalan sehat, menyapu halaman, memberi makan ternak, atau membantu pekerjaan rumah bersama orang tua asuh.'],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Sarapan bersama keluarga homestay.'],
                ['start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Kegiatan Bersama Warga / Orang Tua Asuh', 'location' => $areaDesa, 'person_in_charge' => $ota, 'description' => 'Pilih salah satu kegiatan bersama warga: (1) bertani/berkebun, (2) beternak, (3) gotong royong lingkungan, (4) membantu UMKM setempat.'],
                ['start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Aktivitas Pendidikan 1', 'location' => $areaEdukasi, 'person_in_charge' => $fasilitator, 'description' => 'Pilih salah satu tema edukasi: (1) pertanian & ketahanan pangan, (2) lingkungan & konservasi, (3) kewirausahaan/UMKM, (4) seni & budaya lokal.'],
                ['start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Makan Siang, Istirahat, dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Makan siang, istirahat, dan ibadah di homestay.'],
                ['start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Aktivitas Pendidikan 2', 'location' => $areaEdukasi, 'person_in_charge' => $fasilitator, 'description' => 'Pilih salah satu tema edukasi: (1) pertanian & ketahanan pangan, (2) lingkungan & konservasi, (3) kewirausahaan/UMKM, (4) seni & budaya lokal.'],
                ['start_time' => '15:30', 'end_time' => '17:00', 'activity_name' => 'Kegiatan Bersama Orang Tua Asuh (Lanjutan)', 'location' => $areaDesa, 'person_in_charge' => $ota, 'description' => 'Opsi: membantu aktivitas harian orang tua asuh, belajar memasak kuliner lokal, atau bermain bersama anak-anak desa.'],
                ['start_time' => '17:00', 'end_time' => '18:30', 'activity_name' => 'Makan Malam, Istirahat, dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Makan malam, istirahat, dan ibadah di homestay.'],
                ['start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Refleksi dan Malam Keakraban', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Sesi refleksi harian dan ice breaking. Opsi: permainan kelompok, berbagi cerita, atau pentas mini.'],
            ],
            // Hari 3
            3 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Bangun pagi, ibadah, dan persiapan diri sebelum memulai kegiatan.'],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga dan Piket Bersama Orang Tua Asuh', 'location' => $areaDesa, 'person_in_charge' => $ota, 'description' => 'Opsi: senam pagi, jalan sehat, menyapu halaman, memberi makan ternak, atau membantu pekerjaan rumah bersama orang tua asuh.'],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Sarapan bersama keluarga homestay.'],
                ['start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Kegiatan Bersama Orang Tua Asuh', 'location' => $areaDesa, 'person_in_charge' => $ota, 'description' => 'Opsi: membantu aktivitas harian orang tua asuh, belajar kerajinan lokal, atau mengikuti kegiatan warga setempat.'],
                ['start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Aktivitas Pendidikan 1', 'location' => $areaEdukasi, 'person_in_charge' => $fasilitator, 'description' => 'Pilih salah satu tema edukasi: (1) pertanian & ketahanan pangan, (2) lingkungan & konservasi, (3) kewirausahaan/UMKM, (4) seni & budaya lokal.'],
                ['start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Makan Siang, Istirahat, dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Makan siang, istirahat, dan ibadah di homestay.'],
                ['start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Aktivitas Pendidikan 2', 'location' => $areaEdukasi, 'person_in_charge' => $fasilitator, 'description' => 'Pilih salah satu tema edukasi: (1) pertanian & ketahanan pangan, (2) lingkungan & konservasi, (3) kewirausahaan/UMKM, (4) seni & budaya lokal.'],
                ['start_time' => '15:30', 'end_time' => '17:00', 'activity_name' => 'Persiapan Acara Penutupan', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Persiapan dan gladi resik acara penutupan. Opsi: latihan pentas, menyiapkan dekorasi, dan merapikan perlengkapan.'],
                ['start_time' => '17:00', 'end_time' => '18:30', 'activity_name' => 'Makan Malam, Istirahat, dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Makan malam, istirahat, dan ibadah di homestay.'],
                ['start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Acara Penutupan dan Perpisahan', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Sesi penutupan resmi: sambutan, presentasi hasil, pentas seni, penyerahan kenang-kenangan, dan foto bersama.'],
            ],
            // Hari 4 — Kepulangan
            4 => [
                ['start_time' => '05:30', 'end_time' => '06:00', 'activity_name' => 'Bangun Pagi dan Ibadah', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Bangun pagi, ibadah, dan persiapan diri.'],
                ['start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Packing dan Berkemas', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Membereskan barang bawaan dan memastikan tidak ada barang tertinggal.'],
                ['start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Makan Pagi (Sarapan)', 'location' => $homestay, 'person_in_charge' => $ota, 'description' => 'Sarapan bersama keluarga homestay untuk terakhir kalinya.'],
                ['start_time' => '08:00', 'end_time' => '09:00', 'activity_name' => 'Perpisahan dengan Orang Tua Asuh dan Check-out', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Ucapan terima kasih dan perpisahan dengan orang tua asuh, dilanjutkan check-out homestay.'],
                ['start_time' => '09:00', 'end_time' => null, 'activity_name' => 'Kepulangan', 'location' => $pendopo, 'person_in_charge' => $panitia, 'description' => 'Peserta kembali ke daerah asal masing-masing.'],
            ],
        ];
    }
}

