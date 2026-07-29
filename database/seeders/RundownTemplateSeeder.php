<?php

namespace Database\Seeders;

use App\Models\RundownTemplate;
use App\Models\RundownTemplateItem;
use Illuminate\Database\Seeder;

class RundownTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kunjungan 1 Hari
        $template1 = RundownTemplate::create([
            'name' => 'Kunjungan 1 Hari',
            'code' => 'VISIT-1D',
            'description' => 'Template kunjungan edukasi satu hari di Desa Wisata Gabugan',
            'duration_days' => 1,
            'duration_nights' => 0,
            'is_active' => true,
        ]);

        $items1 = [
            ['day_number' => 1, 'start_time' => '08:00', 'end_time' => '08:30', 'activity_name' => 'Kedatangan dan Penyambutan', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 1],
            ['day_number' => 1, 'start_time' => '08:30', 'end_time' => '09:00', 'activity_name' => 'Orientasi Program', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 2],
            ['day_number' => 1, 'start_time' => '09:00', 'end_time' => '11:30', 'activity_name' => 'Kegiatan Edukasi', 'location' => 'Area Desa Wisata Gabugan', 'sort_order' => 3],
            ['day_number' => 1, 'start_time' => '11:30', 'end_time' => '12:30', 'activity_name' => 'Istirahat, Ibadah, dan Makan Siang', 'location' => 'Ruang Makan / Mushola', 'sort_order' => 4],
            ['day_number' => 1, 'start_time' => '12:30', 'end_time' => '14:30', 'activity_name' => 'Kegiatan Lanjutan', 'location' => 'Area Desa Wisata Gabugan', 'sort_order' => 5],
            ['day_number' => 1, 'start_time' => '14:30', 'end_time' => '15:00', 'activity_name' => 'Evaluasi dan Penutupan', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 6],
        ];

        foreach ($items1 as $item) {
            $template1->items()->create($item);
        }

        // 2. Live In 2 Hari 1 Malam
        $template2 = RundownTemplate::create([
            'name' => 'Live In 2 Hari 1 Malam',
            'code' => 'LIVE-2D1N',
            'description' => 'Program live in dua hari satu malam di Desa Wisata Gabugan',
            'duration_days' => 2,
            'duration_nights' => 1,
            'is_active' => true,
        ]);

        $items2 = [
            // Day 1
            ['day_number' => 1, 'start_time' => '08:00', 'end_time' => '09:00', 'activity_name' => 'Kedatangan dan Registrasi', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 1],
            ['day_number' => 1, 'start_time' => '09:00', 'end_time' => '10:00', 'activity_name' => 'Pembagian Homestay dan Aklimatisasi', 'location' => 'Area Desa Wisata Gabugan', 'sort_order' => 2],
            ['day_number' => 1, 'start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Kegiatan Edukasi Lingkungan', 'location' => 'Area Pertanian / Sentra UKM', 'sort_order' => 3],
            ['day_number' => 1, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat, Ibadah, Makan Siang', 'location' => 'Homestay / Mushola', 'sort_order' => 4],
            ['day_number' => 1, 'start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Kegiatan Sosial Budaya', 'location' => 'Area Desa', 'sort_order' => 5],
            ['day_number' => 1, 'start_time' => '15:30', 'end_time' => '17:30', 'activity_name' => 'Belajar Mengolah Produk Lokal', 'location' => 'Sentra UKM', 'sort_order' => 6],
            ['day_number' => 1, 'start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Malam Keakraban dan Refleksi', 'location' => 'Pendopo Desa', 'sort_order' => 7],
            // Day 2
            ['day_number' => 2, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Senam Pagi dan Jalan Sehat', 'location' => 'Lapangan Desa', 'sort_order' => 1],
            ['day_number' => 2, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan Pagi dan Persiapan', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 2, 'start_time' => '08:00', 'end_time' => '11:00', 'activity_name' => 'Kegiatan Edukasi Lanjutan', 'location' => 'Area Desa Wisata Gabugan', 'sort_order' => 3],
            ['day_number' => 2, 'start_time' => '11:00', 'end_time' => '12:00', 'activity_name' => 'Persiapan Kepulangan', 'location' => 'Homestay', 'sort_order' => 4],
            ['day_number' => 2, 'start_time' => '12:00', 'end_time' => '13:00', 'activity_name' => 'Penutupan dan Perpisahan', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 5],
        ];

        foreach ($items2 as $item) {
            $template2->items()->create($item);
        }

        // 3. Live In 3 Hari 2 Malam
        $template3 = RundownTemplate::create([
            'name' => 'Live In 3 Hari 2 Malam',
            'code' => 'LIVE-3D2N',
            'description' => 'Program live in tiga hari dua malam yang lebih mendalam',
            'duration_days' => 3,
            'duration_nights' => 2,
            'is_active' => true,
        ]);

        $items3 = [
            // Day 1
            ['day_number' => 1, 'start_time' => '08:00', 'end_time' => '09:00', 'activity_name' => 'Kedatangan dan Registrasi', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 1],
            ['day_number' => 1, 'start_time' => '09:00', 'end_time' => '10:00', 'activity_name' => 'Pembagian Homestay dan Aklimatisasi', 'location' => 'Area Desa Wisata Gabugan', 'sort_order' => 2],
            ['day_number' => 1, 'start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Pengenalan Potensi Desa', 'location' => 'Area Desa', 'sort_order' => 3],
            ['day_number' => 1, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat, Ibadah, Makan Siang', 'location' => 'Homestay / Mushola', 'sort_order' => 4],
            ['day_number' => 1, 'start_time' => '13:30', 'end_time' => '15:30', 'activity_name' => 'Edukasi Pertanian dan Peternakan', 'location' => 'Area Pertanian', 'sort_order' => 5],
            ['day_number' => 1, 'start_time' => '15:30', 'end_time' => '17:30', 'activity_name' => 'Belajar Mengolah Hasil Tani', 'location' => 'Sentra UKM', 'sort_order' => 6],
            ['day_number' => 1, 'start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Diskusi Kelompok dan Refleksi', 'location' => 'Pendopo Desa', 'sort_order' => 7],
            // Day 2
            ['day_number' => 2, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga Pagi', 'location' => 'Lapangan Desa', 'sort_order' => 1],
            ['day_number' => 2, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan Pagi', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 2, 'start_time' => '08:00', 'end_time' => '11:00', 'activity_name' => 'Kegiatan Sosial dan Bakti Desa', 'location' => 'Area Desa', 'sort_order' => 3],
            ['day_number' => 2, 'start_time' => '11:00', 'end_time' => '12:00', 'activity_name' => 'Belajar Masak Kuliner Lokal', 'location' => 'Sentra UKM', 'sort_order' => 4],
            ['day_number' => 2, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat, Ibadah, Makan Siang', 'location' => 'Homestay / Mushola', 'sort_order' => 5],
            ['day_number' => 2, 'start_time' => '13:30', 'end_time' => '16:00', 'activity_name' => 'Kegiatan Seni dan Budaya', 'location' => 'Pendopo / Sanggar Seni', 'sort_order' => 6],
            ['day_number' => 2, 'start_time' => '19:00', 'end_time' => '21:30', 'activity_name' => 'Pentas Seni dan Malam Keakraban', 'location' => 'Pendopo Desa', 'sort_order' => 7],
            // Day 3
            ['day_number' => 3, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Jalan Sehat dan Senam', 'location' => 'Lapangan Desa', 'sort_order' => 1],
            ['day_number' => 3, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan dan Packing', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 3, 'start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Presentasi Hasil dan Evaluasi', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 3],
            ['day_number' => 3, 'start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Penutupan dan Perpisahan', 'location' => 'Pendopo Desa Wisata Gabugan', 'sort_order' => 4],
        ];

        foreach ($items3 as $item) {
            $template3->items()->create($item);
        }

        // 4. Live In 4 Hari 3 Malam
        $template4 = RundownTemplate::create([
            'name' => 'Live In 4 Hari 3 Malam',
            'code' => 'LIVE-4D3N',
            'description' => 'Program live in empat hari tiga malam yang komprehensif',
            'duration_days' => 4,
            'duration_nights' => 3,
            'is_active' => true,
        ]);

        $items4 = [
            ['day_number' => 1, 'start_time' => '08:00', 'end_time' => '09:00', 'activity_name' => 'Kedatangan dan Registrasi', 'location' => 'Pendopo', 'sort_order' => 1],
            ['day_number' => 1, 'start_time' => '09:00', 'end_time' => '10:00', 'activity_name' => 'Pembagian Homestay', 'location' => 'Area Desa', 'sort_order' => 2],
            ['day_number' => 1, 'start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Pengenalan Desa', 'location' => 'Area Desa', 'sort_order' => 3],
            ['day_number' => 1, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat dan Makan Siang', 'location' => 'Homestay', 'sort_order' => 4],
            ['day_number' => 1, 'start_time' => '13:30', 'end_time' => '17:00', 'activity_name' => 'Eksplorasi Potensi Desa', 'location' => 'Area Desa', 'sort_order' => 5],
            ['day_number' => 1, 'start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Refleksi Hari Pertama', 'location' => 'Pendopo', 'sort_order' => 6],
            ['day_number' => 2, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Olahraga Pagi', 'location' => 'Lapangan', 'sort_order' => 1],
            ['day_number' => 2, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 2, 'start_time' => '08:00', 'end_time' => '12:00', 'activity_name' => 'Kegiatan Edukasi Inti', 'location' => 'Area Edukasi', 'sort_order' => 3],
            ['day_number' => 2, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat dan Makan Siang', 'location' => 'Homestay', 'sort_order' => 4],
            ['day_number' => 2, 'start_time' => '13:30', 'end_time' => '17:00', 'activity_name' => 'Workshop Produk Lokal', 'location' => 'Sentra UKM', 'sort_order' => 5],
            ['day_number' => 2, 'start_time' => '19:00', 'end_time' => '21:00', 'activity_name' => 'Diskusi Tematik', 'location' => 'Pendopo', 'sort_order' => 6],
            ['day_number' => 3, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Senam Pagi', 'location' => 'Lapangan', 'sort_order' => 1],
            ['day_number' => 3, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 3, 'start_time' => '08:00', 'end_time' => '12:00', 'activity_name' => 'Bakti Sosial dan Interaksi Warga', 'location' => 'Area Desa', 'sort_order' => 3],
            ['day_number' => 3, 'start_time' => '12:00', 'end_time' => '13:30', 'activity_name' => 'Istirahat dan Makan Siang', 'location' => 'Homestay', 'sort_order' => 4],
            ['day_number' => 3, 'start_time' => '13:30', 'end_time' => '17:00', 'activity_name' => 'Kegiatan Seni dan Budaya', 'location' => 'Sanggar Seni', 'sort_order' => 5],
            ['day_number' => 3, 'start_time' => '19:00', 'end_time' => '21:30', 'activity_name' => 'Pentas Seni', 'location' => 'Pendopo', 'sort_order' => 6],
            ['day_number' => 4, 'start_time' => '06:00', 'end_time' => '07:00', 'activity_name' => 'Jalan Sehat', 'location' => 'Lapangan', 'sort_order' => 1],
            ['day_number' => 4, 'start_time' => '07:00', 'end_time' => '08:00', 'activity_name' => 'Sarapan dan Packing', 'location' => 'Homestay', 'sort_order' => 2],
            ['day_number' => 4, 'start_time' => '08:00', 'end_time' => '10:00', 'activity_name' => 'Presentasi dan Evaluasi', 'location' => 'Pendopo', 'sort_order' => 3],
            ['day_number' => 4, 'start_time' => '10:00', 'end_time' => '12:00', 'activity_name' => 'Penutupan', 'location' => 'Pendopo', 'sort_order' => 4],
        ];

        foreach ($items4 as $item) {
            $template4->items()->create($item);
        }
    }
}