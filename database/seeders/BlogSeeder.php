<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [1, 2, 3, 4, 5];
        $now = now();

        $blogs = [
            [
                'title' => 'Menikmati Keindahan Alam Desa Wisata Gabugan di Lereng Merapi',
                'title_id' => 'Menikmati Keindahan Alam Desa Wisata Gabugan di Lereng Merapi',
                'title_en' => 'Enjoying the Natural Beauty of Gabugan Tourism Village on the Slopes of Merapi',
                'excerpt' => 'Nikmati pesona alam lereng Merapi yang memukau di Desa Wisata Gabugan, Sleman. Udara sejuk dan pemandangan sawah terasering siap memanjakan mata.',
                'excerpt_id' => 'Nikmati pesona alam lereng Merapi yang memukau di Desa Wisata Gabugan, Sleman. Udara sejuk dan pemandangan sawah terasering siap memanjakan mata.',
                'excerpt_en' => 'Enjoy the stunning natural beauty of the Merapi slopes at Gabugan Tourism Village, Sleman. Cool air and terraced rice fields will spoil your eyes.',
                'description' => '<p>Test content 1</p>',
                'description_id' => '<p>Test content 1 ID</p>',
                'description_en' => '<p>Test content 1 EN</p>',
                'category_id' => 1,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Belajar Membatik: Warisan Budaya yang Hidup di Gabugan',
                'title_id' => 'Belajar Membatik: Warisan Budaya yang Hidup di Gabugan',
                'title_en' => 'Learning Batik: Living Cultural Heritage in Gabugan',
                'excerpt' => 'Rasakan pengalaman belajar membatik tulis langsung dari pengrajin lokal di Desa Wisata Gabugan.',
                'excerpt_id' => 'Rasakan pengalaman belajar membatik tulis langsung dari pengrajin lokal di Desa Wisata Gabugan.',
                'excerpt_en' => 'Experience learning traditional batik making directly from local artisans at Gabugan.',
                'description' => '<p>Test content 2</p>',
                'description_id' => '<p>Test content 2 ID</p>',
                'description_en' => '<p>Test content 2 EN</p>',
                'category_id' => 3,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Petik Salak Pondoh: Sensasi Petik Buah Langsung dari Pohon',
                'title_id' => 'Petik Salak Pondoh: Sensasi Petik Buah Langsung dari Pohon',
                'title_en' => 'Salak Pondoh Harvesting: Picking Fruit Directly from the Tree',
                'excerpt' => 'Pengalaman seru memetik salak pondoh langsung dari kebun organik di Desa Wisata Gabugan.',
                'excerpt_id' => 'Pengalaman seru memetik salak pondoh langsung dari kebun organik di Desa Wisata Gabugan.',
                'excerpt_en' => 'Fun experience picking salak pondoh directly from organic gardens at Gabugan.',
                'description' => '<p>Test content 3</p>',
                'description_id' => '<p>Test content 3 ID</p>',
                'description_en' => '<p>Test content 3 EN</p>',
                'category_id' => 2,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'River Trekking Seru di Sungai Lereng Merapi',
                'title_id' => 'River Trekking Seru di Sungai Lereng Merapi',
                'title_en' => 'Exciting River Trekking on the Merapi Slope River',
                'excerpt' => 'Susuri aliran sungai jernih dari mata air Merapi. Sensasi menyegarkan di tengah alam pegunungan.',
                'excerpt_id' => 'Susuri aliran sungai jernih dari mata air Merapi.',
                'excerpt_en' => 'Explore the clear river flow from Merapi springs.',
                'description' => '<p>Test content 4</p>',
                'description_id' => '<p>Test content 4 ID</p>',
                'description_en' => '<p>Test content 4 EN</p>',
                'category_id' => 1,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Pengalaman Menginap di Homestay Tradisional Joglo dan Limasan',
                'title_id' => 'Pengalaman Menginap di Homestay Tradisional Joglo dan Limasan',
                'title_en' => 'Experience Staying at Traditional Joglo and Limasan Homestays',
                'excerpt' => 'Rasakan kehidupan pedesaan Jawa yang autentik dengan menginap di homestay tradisional warga.',
                'excerpt_id' => 'Rasakan kehidupan pedesaan Jawa yang autentik.',
                'excerpt_en' => 'Experience authentic Javanese village life by staying at traditional homestays.',
                'description' => '<p>Test content 5</p>',
                'description_id' => '<p>Test content 5 ID</p>',
                'description_en' => '<p>Test content 5 EN</p>',
                'category_id' => 1,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Belajar Gamelan: Mengenal Alat Musik Tradisional Jawa',
                'title_id' => 'Belajar Gamelan: Mengenal Alat Musik Tradisional Jawa',
                'title_en' => 'Learning Gamelan: Getting to Know Traditional Javanese Musical Instruments',
                'excerpt' => 'Mainkan saron, bonang, gong, dan kendang dalam paket belajar gamelan interaktif.',
                'excerpt_id' => 'Mainkan saron, bonang, gong, dan kendang.',
                'excerpt_en' => 'Play saron, bonang, gong, and kendang in an interactive gamelan learning package.',
                'description' => '<p>Test content 6</p>',
                'description_id' => '<p>Test content 6 ID</p>',
                'description_en' => '<p>Test content 6 EN</p>',
                'category_id' => 3,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Kuliner Ndeso: Menikmati Masakan Tradisional Lereng Merapi',
                'title_id' => 'Kuliner Ndeso: Menikmati Masakan Tradisional Lereng Merapi',
                'title_en' => 'Village Cuisine: Enjoying Traditional Food from Merapi Slopes',
                'excerpt' => 'Jelajahi cita rasa khas pedesaan dengan menu sayur lodeh tewel dan sambal belut goreng.',
                'excerpt_id' => 'Jelajahi cita rasa khas pedesaan.',
                'excerpt_en' => 'Explore typical village flavors.',
                'description' => '<p>Test content 7</p>',
                'description_id' => '<p>Test content 7 ID</p>',
                'description_en' => '<p>Test content 7 EN</p>',
                'category_id' => 4,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Membajak Sawah dengan Kerbau: Pengalaman Jadi Petani Tradisional',
                'title_id' => 'Membajak Sawah dengan Kerbau: Pengalaman Jadi Petani Tradisional',
                'title_en' => 'Plowing Rice Fields with Buffalo: Experience Being a Traditional Farmer',
                'excerpt' => 'Rasakan sensasi menjadi petani tradisional dengan membajak sawah menggunakan kerbau.',
                'excerpt_id' => 'Rasakan sensasi menjadi petani tradisional.',
                'excerpt_en' => 'Feel the sensation of being a traditional farmer by plowing rice fields with a buffalo.',
                'description' => '<p>Test content 8</p>',
                'description_id' => '<p>Test content 8 ID</p>',
                'description_en' => '<p>Test content 8 EN</p>',
                'category_id' => 2,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Study Tour ke Desa Wisata Gabugan: Belajar di Luar Kelas',
                'title_id' => 'Study Tour ke Desa Wisata Gabugan: Belajar di Luar Kelas',
                'title_en' => 'Study Tour to Gabugan: Learning Outside the Classroom',
                'excerpt' => 'Rencanakan study tour sekolah yang edukatif dan menyenangkan di Desa Wisata Gabugan.',
                'excerpt_id' => 'Rencanakan study tour sekolah yang edukatif.',
                'excerpt_en' => 'Plan an educational school study tour at Gabugan Tourism Village.',
                'description' => '<p>Test content 9</p>',
                'description_id' => '<p>Test content 9 ID</p>',
                'description_en' => '<p>Test content 9 EN</p>',
                'category_id' => 5,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Mengenal Lebih Dekat Gunung Merapi dari Desa Wisata Gabugan',
                'title_id' => 'Mengenal Lebih Dekat Gunung Merapi dari Desa Wisata Gabugan',
                'title_en' => 'Getting Closer to Mount Merapi from Gabugan Tourism Village',
                'excerpt' => 'Desa Wisata Gabugan berada di lereng barat Merapi. Simak informasi menarik tentang gunung berapi ini.',
                'excerpt_id' => 'Desa Wisata Gabugan berada di lereng barat Merapi.',
                'excerpt_en' => 'Gabugan Tourism Village is located on the western slope of Merapi.',
                'description' => '<p>Test content 10</p>',
                'description_id' => '<p>Test content 10 ID</p>',
                'description_en' => '<p>Test content 10 EN</p>',
                'category_id' => 1,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Outbound dan Team Building di Alam Terbuka Gabugan',
                'title_id' => 'Outbound dan Team Building di Alam Terbuka Gabugan',
                'title_en' => 'Outbound and Team Building in Gabugan Open Nature',
                'excerpt' => 'Ajak tim anda untuk kegiatan outbound seru di tengah hamparan sawah dan udara sejuk pegunungan.',
                'excerpt_id' => 'Ajak tim anda untuk kegiatan outbound seru.',
                'excerpt_en' => 'Invite your team for exciting outbound activities amidst rice field expanses.',
                'description' => '<p>Test content 11</p>',
                'description_id' => '<p>Test content 11 ID</p>',
                'description_en' => '<p>Test content 11 EN</p>',
                'category_id' => 5,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
            [
                'title' => 'Tradisi dan Kearifan Lokal Masyarakat Desa Wisata Gabugan',
                'title_id' => 'Tradisi dan Kearifan Lokal Masyarakat Desa Wisata Gabugan',
                'title_en' => 'Traditions and Local Wisdom of Gabugan Village Community',
                'excerpt' => 'Mengenal berbagai tradisi unik masyarakat Gabugan seperti selamatan bumi dan bersih desa.',
                'excerpt_id' => 'Mengenal berbagai tradisi unik masyarakat Gabugan.',
                'excerpt_en' => 'Get to know various unique traditions of the Gabugan community.',
                'description' => '<p>Test content 12</p>',
                'description_id' => '<p>Test content 12 ID</p>',
                'description_en' => '<p>Test content 12 EN</p>',
                'category_id' => 5,
                'reads' => rand(50, 500),
                'created_at' => $now->copy()->subDays(rand(1, 365)),
            ],
        ];

        $images = [
            'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1501854140801-50d01698950b?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1505144808419-1957a94ca61e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1518173946687-a36f968f7c1e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=800&auto=format&fit=crop',
        ];

        foreach ($blogs as $i => $blog) {
            $slug = Str::slug($blog['title']);
            $existingSlug = $slug;
            $counter = 1;
            while (Blog::where('slug', $existingSlug)->exists()) {
                $existingSlug = $slug . '-' . $counter;
                $counter++;
            }

            Blog::create([
                'title' => $blog['title'],
                'title_id' => $blog['title_id'],
                'title_en' => $blog['title_en'],
                'slug' => $existingSlug,
                'excerpt' => $blog['excerpt'],
                'excerpt_id' => $blog['excerpt_id'],
                'excerpt_en' => $blog['excerpt_en'],
                'image' => $images[$i % count($images)],
                'description' => $blog['description'],
                'description_id' => $blog['description_id'],
                'description_en' => $blog['description_en'],
                'category_id' => $blog['category_id'],
                'reads' => $blog['reads'],
                'created_at' => $blog['created_at'],
                'updated_at' => $blog['created_at'],
            ]);
        }

        $this->command->info('12 blog berhasil dibuat!');
    }
}