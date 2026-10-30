<?php

namespace Database\Seeders;

use App\Models\RundownTemplate;
use App\Models\RundownTemplateItem;
use Illuminate\Database\Seeder;

class ScheduleRundownEduwisataSeeder extends Seeder
{
    public function run(): void
    {
        $template = RundownTemplate::create([
            'name' => 'Eduwisata SMP Sekolah Alam Bintaro - 1 Hari',
            'code' => 'EDUWISATA-1D',
            'description' => 'Template rundown eduwisata SMP Sekolah Alam Bintaro, 9 Desember 2026, 32 peserta (24 siswa + 8 guru), waktu 08.00–14.30 WIB di Desa Wisata Gabugan',
            'duration_days' => 1,
            'duration_nights' => 0,
            'is_active' => true,
        ]);

        $items = [
            [
                'day_number' => 1,
                'start_time' => '08:00',
                'end_time' => '08:15',
                'activity_name' => 'Kedatangan dan Penyambutan',
                'location' => 'Pendopo Desa Wisata Gabugan',
                'description' => 'Penyambutan peserta dan pengondisian kelompok.',
                'sort_order' => 1,
            ],
            [
                'day_number' => 1,
                'start_time' => '08:15',
                'end_time' => '10:15',
                'activity_name' => 'Cerita di Balik Sepiring Nasi',
                'location' => 'Area Sawah / Lapang Pertanian',
                'description' => 'Membajak sawah, mengenal proses penanaman padi dan pengolahan gabah menjadi beras.',
                'sort_order' => 2,
            ],
            [
                'day_number' => 1,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'activity_name' => 'Sik-Salak (Budidaya Salak)',
                'location' => 'Kebun Salak',
                'description' => 'Mengenal tanaman salak, budidaya, perawatan, dan potensi perkebunan lokal.',
                'sort_order' => 3,
            ],
            [
                'day_number' => 1,
                'start_time' => '11:45',
                'end_time' => '12:45',
                'activity_name' => 'Istirahat dan Makan Siang',
                'location' => 'Ruang Makan / Mushola',
                'description' => 'Menikmati hidangan khas pedesaan.',
                'sort_order' => 4,
            ],
            [
                'day_number' => 1,
                'start_time' => '12:45',
                'end_time' => '14:15',
                'activity_name' => 'Praktik UMKM Lokal',
                'location' => 'Sentra UKM Lokal',
                'description' => 'Pilihan praktik pembuatan Bakpia atau Eggroll.',
                'sort_order' => 5,
            ],
            [
                'day_number' => 1,
                'start_time' => '14:15',
                'end_time' => '14:30',
                'activity_name' => 'Penutupan dan Kepulangan',
                'location' => 'Pendopo Desa Wisata Gabugan',
                'description' => 'Foto bersama dan persiapan kepulangan.',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            $template->items()->create($item);
        }
    }
}