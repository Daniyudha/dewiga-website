<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleRundown;
use App\Models\ScheduleRundownItem;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleRundownEduwisataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Dependency: pastikan user admin (created_by) tersedia ---
        $user = User::find(1) ?? User::create([
            'name' => 'Admin Dewiga',
            'email' => 'admin@dewiga.com',
            'password' => bcrypt('sujatmikotri'),
            'is_admin' => true,
        ]);

        // --- Dependency: pastikan travel package tersedia (tanpa kolom 'duration' yang tidak ada di tabel) ---
        $travelPackage = TravelPackage::where('slug', 'paket-edukasi')->first()
            ?? TravelPackage::create([
                'type' => 'Paket Wisata Edukasi',
                'slug' => 'paket-edukasi',
                'location' => 'Desa Wisata Gabugan',
                'description' => 'Paket edukasi budaya dan alam Desa Wisata Gabugan',
                'price' => 150000,
                'capacity' => 50,
            ]);

        // --- Dependency: pastikan schedule untuk SMP Sekolah Alam Bintaro tersedia ---
        $schedule = Schedule::where('visitor_name', 'SMP Sekolah Alam Bintaro')
            ->whereDate('start_date', '2026-12-09')
            ->first();

        if (!$schedule) {
            $schedule = Schedule::create([
                'travel_package_id' => $travelPackage->id,
                'type' => 'confirmed',
                'status' => 'confirmed',
                'source_type' => 'manual',
                'visitor_name' => 'SMP Sekolah Alam Bintaro',
                'start_date' => '2026-12-09',
                'end_date' => '2026-12-09',
                'quota' => 32,
                'booked' => 32,
                'is_active' => true,
                'notes' => 'Kunjungan eduwisata 32 orang (24 siswa + 8 guru)',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            $this->command->info("Schedule dibuat: " . $schedule->visitor_name . " (" . $schedule->start_date . ")");
        } else {
            $this->command->info("Schedule sudah ada: " . $schedule->visitor_name . " (" . $schedule->start_date . ")");
        }

        // Hapus rundown lama jika ada (idempotent agar aman dijalankan berulang kali)
        if ($schedule->scheduleRundown) {
            $schedule->scheduleRundown->items()->delete();
            $schedule->scheduleRundown->delete();
            $this->command->info("Rundown lama dihapus untuk jadwal ini.");
        }

        // --- Header rundown ---
        $rundown = ScheduleRundown::create([
            'schedule_id' => $schedule->id,
            'rundown_template_id' => null,
            'rundown_number' => ScheduleRundown::generateRundownNumber(),
            'title' => 'Itinerary Eduwisata - SMP Sekolah Alam Bintaro (9 Desember 2026)',
            'notes' => 'Instansi: SMP Sekolah Alam Bintaro | 32 peserta (24 siswa, 8 guru) | '
                .'Waktu 08.00–14.30 WIB | Lokasi: Desa Wisata Gabugan',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $this->command->info("Rundown dibuat: " . $rundown->rundown_number . " - " . $rundown->title);

        // --- Item rundown sesuai itinerary ---
        $items = [
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '08:00',
                'end_time' => '08:15',
                'activity_name' => 'Kedatangan dan Penyambutan',
                'location' => 'Pendopo Desa Wisata Gabugan',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Penyambutan peserta dan pengondisian kelompok.',
                'sort_order' => 1,
            ],
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '08:15',
                'end_time' => '10:15',
                'activity_name' => 'Cerita di Balik Sepiring Nasi',
                'location' => 'Area Sawah / Lapang Pertanian',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Membajak sawah, mengenal proses penanaman padi dan pengolahan gabah menjadi beras.',
                'sort_order' => 2,
            ],
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '10:15',
                'end_time' => '11:45',
                'activity_name' => 'Sik-Salak (Budidaya Salak)',
                'location' => 'Kebun Salak',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Mengenal tanaman salak, budidaya, perawatan, dan potensi perkebunan lokal.',
                'sort_order' => 3,
            ],
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '11:45',
                'end_time' => '12:45',
                'activity_name' => 'Istirahat dan Makan Siang',
                'location' => 'Ruang Makan / Mushola',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Menikmati hidangan khas pedesaan.',
                'sort_order' => 4,
            ],
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '12:45',
                'end_time' => '14:15',
                'activity_name' => 'Praktik UMKM Lokal',
                'location' => 'Sentra UKM Lokal',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Pilihan praktik pembuatan Bakpia atau Eggroll.',
                'sort_order' => 5,
            ],
            [
                'day_number' => 1,
                'activity_date' => '2026-12-09',
                'start_time' => '14:15',
                'end_time' => '14:30',
                'activity_name' => 'Penutupan dan Kepulangan',
                'location' => 'Pendopo Desa Wisata Gabugan',
                'person_in_charge' => 'Pemandu Lokal',
                'description' => 'Foto bersama dan persiapan kepulangan.',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            $item['schedule_rundown_id'] = $rundown->id;
            $item['schedule_id'] = $schedule->id;
            $item['created_by'] = $user->id;
            $item['updated_by'] = $user->id;
            ScheduleRundownItem::create($item);
        }

        $this->command->info("Items rundown: ".count($items)." created.");
        $this->command->info("\n=== SEED ITINERARY EDUWISATA SELESAI ===");
        $this->command->info("ScheduleRundowns: ".ScheduleRundown::count());
        $this->command->info("ScheduleRundownItems: ".ScheduleRundownItem::count());
    }
}