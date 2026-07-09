<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title_id' => 'Membajak Sawah Tradisional',
                'title_en' => 'Traditional Rice Field Plowing',
                'description_id' => 'Rasakan sensasi menjadi petani tradisional dengan membajak sawah menggunakan kerbau yang jinak dan ramah. Dipandu oleh petani lokal berpengalaman, Anda akan belajar teknik membajak tradisional sambil menikmati pemandangan sawah hijau yang asri.',
                'description_en' => 'Experience the sensation of being a traditional farmer by plowing rice fields using a tame and friendly buffalo. Guided by experienced local farmers.',
                'image' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-2 jam',
                'duration_en' => '1-2 hours',
                'min_pax' => '1 orang',
                'includes_id' => 'Pemandu, alat membajak, air mineral',
                'includes_en' => 'Guide, plowing tools, mineral water',
                'order' => 1,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Menanam Padi',
                'title_en' => 'Rice Planting',
                'description_id' => 'Belajar cara menanam padi yang benar dari petani lokal. Mulai dari persiapan lahan, pemilihan bibit, hingga teknik menanam yang baik. Cocok untuk study tour sekolah dan keluarga.',
                'description_en' => 'Learn how to plant rice properly from local farmers. Starting from land preparation, seed selection, to proper planting techniques.',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-2 jam',
                'duration_en' => '1-2 hours',
                'min_pax' => '1 orang',
                'includes_id' => 'Pemandu, bibit padi, air mineral',
                'includes_en' => 'Guide, rice seeds, mineral water',
                'order' => 2,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Belajar Gamelan & Karawitan',
                'title_en' => 'Learning Gamelan & Karawitan',
                'description_id' => 'Mainkan alat musik tradisional Jawa seperti saron, bonang, gong, dan kendang. Dipandu oleh seniman lokal, Anda akan belajar memainkan lagu-lagu tradisional.',
                'description_en' => 'Play traditional Javanese musical instruments such as saron, bonang, gong, and kendang. Guided by local artists.',
                'image' => 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-2 jam',
                'duration_en' => '1-2 hours',
                'min_pax' => '2 orang',
                'includes_id' => 'Instrumen gamelan, pemandu seni, sertifikat',
                'includes_en' => 'Gamelan instruments, art guide, certificate',
                'order' => 3,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Belajar Membatik Tulis',
                'title_en' => 'Learn Batik Making',
                'description_id' => 'Belajar membatik tulis menggunakan canting langsung dari pengrajin berpengalaman. Hasil karya sendiri bisa dibawa pulang sebagai buah tangan berharga.',
                'description_en' => 'Learn traditional batik making using canting directly from experienced artisans. Take home your own masterpiece.',
                'image' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '2-3 jam',
                'duration_en' => '2-3 hours',
                'min_pax' => '2 orang',
                'includes_id' => 'Kain, canting, malam, pewarna, pemandu',
                'includes_en' => 'Fabric, canting, wax, dye, guide',
                'order' => 4,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Petik Salak Pondoh',
                'title_en' => 'Salak Pondoh Harvesting',
                'description_id' => 'Petik salak pondoh langsung dari pohon di kebun organik. Ditemani petani lokal, belajar memilih salak yang matang sempurna. Salak Pondoh Gabugan terkenal manis!',
                'description_en' => 'Pick salak pondoh directly from the tree in organic gardens. Accompanied by local farmers, learn to choose perfectly ripe salak.',
                'image' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1 jam',
                'duration_en' => '1 hour',
                'min_pax' => '1 orang',
                'includes_id' => 'Pemandu kebun, salak untuk dicicipi, tas buah',
                'includes_en' => 'Garden guide, salak tasting, fruit bag',
                'order' => 5,
                'is_featured' => true,
            ],
            [
                'title_id' => 'River Trekking',
                'title_en' => 'River Trekking',
                'description_id' => 'Susuri aliran sungai jernih dari mata air Gunung Merapi. Air segar, pemandangan bebatuan vulkanik unik, dan ditemani pemandu lokal yang berpengalaman.',
                'description_en' => 'Explore the clear river flow from Mount Merapi springs. Fresh water, unique volcanic rock views, and experienced local guides.',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-2 jam',
                'duration_en' => '1-2 hours',
                'min_pax' => '2 orang',
                'includes_id' => 'Pemandu trekking, alas kaki khusus',
                'includes_en' => 'Trekking guide, special footwear',
                'order' => 6,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Kuliner Ndeso',
                'title_en' => 'Village Cuisine',
                'description_id' => 'Nikmati masakan tradisional khas pedesaan lereng Merapi. Mulai dari Sayur Lodeh Tewel, Sambal Belut Goreng, Nasi Liwet, hingga Wedang Jahe Merapi.',
                'description_en' => 'Enjoy traditional village cuisine on the slopes of Merapi. From Sayur Lodeh Tewel to Wedang Jahe Merapi.',
                'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-2 jam',
                'duration_en' => '1-2 hours',
                'min_pax' => '2 orang',
                'includes_id' => 'Paket menu lengkap, lesehan tradisional',
                'includes_en' => 'Complete menu package, traditional seating',
                'order' => 7,
                'is_featured' => true,
            ],
            [
                'title_id' => 'Outbound & Team Building',
                'title_en' => 'Outbound & Team Building',
                'description_id' => 'Ajak tim Anda untuk kegiatan outbound seru di tengah hamparan sawah. Flying fox, jaring laba-laba, estafet air, dan berbagai games seru lainnya.',
                'description_en' => 'Invite your team for exciting outbound activities amidst rice fields. Flying fox, spider web, water relay, and various fun games.',
                'image' => 'https://images.unsplash.com/photo-1518173946687-a36f968f7c1e?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '3-5 jam',
                'duration_en' => '3-5 hours',
                'min_pax' => '10 orang',
                'includes_id' => 'Instruktur, perlengkapan game, area bilas',
                'includes_en' => 'Instructor, game equipment, washing area',
                'order' => 8,
                'is_featured' => false,
            ],
            [
                'title_id' => 'Live-In Homestay',
                'title_en' => 'Live-In Homestay',
                'description_id' => 'Rasakan kehidupan pedesaan Jawa yang autentik dengan menginap di homestay tradisional Joglo dan Limasan. Nikmati masakan rumahan dan udara segar pegunungan.',
                'description_en' => 'Experience authentic Javanese village life by staying at traditional Joglo and Limasan homestays. Enjoy home cooking and fresh mountain air.',
                'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=600&auto=format&fit=crop',
                'duration_id' => '1-3 malam',
                'duration_en' => '1-3 nights',
                'min_pax' => '1 orang',
                'includes_id' => 'Tidur, makan, aktivitas, pemandu',
                'includes_en' => 'Sleeping, meals, activities, guide',
                'order' => 9,
                'is_featured' => false,
            ],
        ];

        foreach ($activities as $data) {
            $slug = Str::slug($data['title_id']);
            Activity::create([
                'title_id' => $data['title_id'],
                'title_en' => $data['title_en'],
                'slug' => $slug,
                'description_id' => $data['description_id'],
                'description_en' => $data['description_en'],
                'image' => $data['image'],
                'duration_id' => $data['duration_id'],
                'duration_en' => $data['duration_en'],
                'min_pax' => $data['min_pax'],
                'includes_id' => $data['includes_id'],
                'includes_en' => $data['includes_en'],
                'order' => $data['order'],
                'is_featured' => $data['is_featured'],
            ]);
        }

        $this->command->info('9 aktivitas berhasil dibuat!');
    }
}