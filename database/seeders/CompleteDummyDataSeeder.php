<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Guest;
use App\Models\Homestay;
use App\Models\PartnerLogo;
use App\Models\Schedule;
use App\Models\SiteGallery;
use App\Models\Testimonial;
use App\Models\Transaction;
use App\Models\TravelPackage;
use Illuminate\Database\Seeder;

class CompleteDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ===== 1. Categories =====
        if (Category::count() === 0) {
            $categories = [
                ['name' => 'Wisata Alam', 'name_id' => 'Wisata Alam', 'name_en' => 'Nature Tourism', 'slug' => 'wisata-alam'],
                ['name' => 'Budaya & Kesenian', 'name_id' => 'Budaya & Kesenian', 'name_en' => 'Culture & Arts', 'slug' => 'budaya-kesenian'],
                ['name' => 'Kuliner', 'name_id' => 'Kuliner', 'name_en' => 'Culinary', 'slug' => 'kuliner'],
                ['name' => 'Edukasi', 'name_id' => 'Edukasi', 'name_en' => 'Education', 'slug' => 'edukasi'],
                ['name' => 'Info & Event', 'name_id' => 'Info & Event', 'name_en' => 'Info & Events', 'slug' => 'info-event'],
            ];
            foreach ($categories as $c) {
                Category::firstOrCreate(['slug' => $c['slug']], $c);
            }
            $this->command->info('Categories: ' . Category::count() . ' created');
        }

        // ===== 2. Travel Packages =====
        if (TravelPackage::count() < 6) {
            $packages = [
                ['type' => 'Paket Live In 2H1M', 'type_id' => 'Paket Live In 2H1M', 'type_en' => 'Live In Package 2D1N', 'slug' => 'paket-live-in-2h1m', 'location' => 'Desa Wisata Gabugan, Turi, Sleman', 'location_id' => 'Desa Wisata Gabugan, Turi, Sleman', 'location_en' => 'Gabugan Tourism Village, Turi, Sleman', 'price' => 150000, 'description' => 'Menginap di homestay warga, belajar budaya, pertanian, dan kesenian bersama masyarakat.', 'description_id' => 'Menginap di homestay warga, belajar budaya, pertanian, dan kesenian bersama masyarakat.', 'description_en' => 'Stay at local homestays, learn culture, farming, and arts with the community.', 'is_signature' => true],
                ['type' => 'Paket Edukasi 1 Hari', 'type_id' => 'Paket Edukasi 1 Hari', 'type_en' => '1-Day Education Package', 'slug' => 'paket-edukasi-1-hari', 'location' => 'Desa Wisata Gabugan, Turi, Sleman', 'location_id' => 'Desa Wisata Gabugan, Turi, Sleman', 'location_en' => 'Gabugan Tourism Village, Turi, Sleman', 'price' => 90000, 'description' => 'Study tour sehari penuh: membajak sawah, membatik, gamelan, dan makan siang tradisional.', 'description_id' => 'Study tour sehari penuh: membajak sawah, membatik, gamelan, dan makan siang tradisional.', 'description_en' => 'Full-day study tour: rice plowing, batik, gamelan, and traditional lunch.', 'is_signature' => false],
                ['type' => 'Paket Outbound & Team Building', 'type_id' => 'Paket Outbound & Team Building', 'type_en' => 'Outbound & Team Building Package', 'slug' => 'paket-outbound-team-building', 'location' => 'Lapangan Desa Gabugan', 'location_id' => 'Lapangan Desa Gabugan', 'location_en' => 'Gabugan Village Field', 'price' => 120000, 'description' => 'Kegiatan outbound seru untuk perusahaan dan komunitas di tengah hamparan sawah.', 'description_id' => 'Kegiatan outbound seru untuk perusahaan dan komunitas di tengah hamparan sawah.', 'description_en' => 'Exciting outbound activities for companies and communities amidst rice fields.', 'is_signature' => false],
                ['type' => 'Paket Keluarga Weekend', 'type_id' => 'Paket Keluarga Weekend', 'type_en' => 'Weekend Family Package', 'slug' => 'paket-keluarga-weekend', 'location' => 'Desa Wisata Gabugan', 'location_id' => 'Desa Wisata Gabugan', 'location_en' => 'Gabugan Tourism Village', 'price' => 200000, 'description' => 'Liburan akhir pekan keluarga dengan aktivitas ringan dan kuliner khas desa.', 'description_id' => 'Liburan akhir pekan keluarga dengan aktivitas ringan dan kuliner khas desa.', 'description_en' => 'Family weekend getaway with light activities and local village cuisine.', 'is_signature' => false],
                ['type' => 'Paket Kuliner Tradisional', 'type_id' => 'Paket Kuliner Tradisional', 'type_en' => 'Traditional Culinary Package', 'slug' => 'paket-kuliner-tradisional', 'location' => 'Dapur Komunitas Desa Gabugan', 'location_id' => 'Dapur Komunitas Desa Gabugan', 'location_en' => 'Gabugan Village Community Kitchen', 'price' => 75000, 'description' => 'Belajar memasak dan menikmati masakan tradisional khas lereng Merapi.', 'description_id' => 'Belajar memasak dan menikmati masakan tradisional khas lereng Merapi.', 'description_en' => 'Learn to cook and enjoy traditional dishes from the slopes of Merapi.', 'is_signature' => false],
                ['type' => 'Paket Fotografi Alam', 'type_id' => 'Paket Fotografi Alam', 'type_en' => 'Nature Photography Package', 'slug' => 'paket-fotografi-alam', 'location' => 'Persawahan & Kaliurang', 'location_id' => 'Persawahan & Kaliurang', 'location_en' => 'Rice Fields & Kaliurang', 'price' => 110000, 'description' => 'Berburu momen terbaik pemandangan sawah, gunung Merapi, dan kehidupan desa.', 'description_id' => 'Berburu momen terbaik pemandangan sawah, gunung Merapi, dan kehidupan desa.', 'description_en' => 'Capture the best moments of rice fields, Mount Merapi, and village life.', 'is_signature' => false],
            ];
            foreach ($packages as $p) {
                TravelPackage::firstOrCreate(['slug' => $p['slug']], $p);
            }
            $this->command->info('Travel Packages: ' . TravelPackage::count() . ' created');
        }

        // ===== 3. Blogs =====
        if (Blog::count() === 0) {
            $cats = Category::pluck('id', 'slug');
            $blogs = [
                ['title' => 'Serunya Membajak Sawah dengan Kerbau', 'title_id' => 'Serunya Membajak Sawah dengan Kerbau', 'title_en' => 'The Fun of Plowing Rice Fields with Buffalo', 'slug' => 'serunya-membajak-sawah-kerbau', 'excerpt' => 'Pengalaman unik menjadi petani tradisional di Desa Gabugan.', 'excerpt_id' => 'Pengalaman unik menjadi petani tradisional di Desa Gabugan.', 'excerpt_en' => 'A unique experience of being a traditional farmer in Gabugan Village.', 'image' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=800&auto=format&fit=crop', 'description' => 'Membajak sawah dengan kerbau adalah pengalaman yang tidak terlupakan. Dipandu petani lokal, Anda belajar teknik tradisional sambil menikmati udara segar desa.', 'description_id' => 'Membajak sawah dengan kerbau adalah pengalaman yang tidak terlupakan. Dipandu petani lokal, Anda belajar teknik tradisional sambil menikmati udara segar desa.', 'description_en' => 'Plowing rice fields with buffalo is an unforgettable experience. Guided by local farmers, you learn traditional techniques.', 'category_id' => $cats['edukasi'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 120],
                ['title' => 'Belajar Membatik Tulis Bersama Pengrajin', 'title_id' => 'Belajar Membatik Tulis Bersama Pengrajin', 'title_en' => 'Learning Batik Tulis with Artisans', 'slug' => 'belajar-membatik-tulis', 'excerpt' => 'Kenali proses membatik tulis dari canting hingga pewarnaan alami.', 'excerpt_id' => 'Kenali proses membatik tulis dari canting hingga pewarnaan alami.', 'excerpt_en' => 'Discover the batik tulis process from canting to natural dyeing.', 'image' => 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?q=80&w=800&auto=format&fit=crop', 'description' => 'Membatik tulis adalah warisan budaya yang harus dilestarikan. Di Desa Gabugan, Anda bisa belajar langsung dari pengrajin berpengalaman.', 'description_id' => 'Membatik tulis adalah warisan budaya yang harus dilestarikan. Di Desa Gabugan, Anda bisa belajar langsung dari pengrajin berpengalaman.', 'description_en' => 'Batik tulis is a cultural heritage that must be preserved. Learn directly from experienced artisans.', 'category_id' => $cats['budaya-kesenian'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 95],
                ['title' => 'Kuliner Tradisional Lereng Merapi', 'title_id' => 'Kuliner Tradisional Lereng Merapi', 'title_en' => 'Traditional Cuisine of Merapi Slopes', 'slug' => 'kuliner-tradisional-lereng-merapi', 'excerpt' => 'Sayur lodeh tewel, sambal belut, hingga wedang jahe.', 'excerpt_id' => 'Sayur lodeh tewel, sambal belut, hingga wedang jahe.', 'excerpt_en' => 'Sayur lodeh tewel, sambal belut, and wedang jahe.', 'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?q=80&w=800&auto=format&fit=crop', 'description' => 'Nikmati sajian khas pedesaan lereng Merapi yang hangat dan menggugah selera.', 'description_id' => 'Nikmati sajian khas pedesaan lereng Merapi yang hangat dan menggugah selera.', 'description_en' => 'Enjoy warm and appetizing dishes typical of the Merapi slope countryside.', 'category_id' => $cats['kuliner'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 78],
                ['title' => 'Live In Homestay: Hidup Bersama Warga Desa', 'title_id' => 'Live In Homestay: Hidup Bersama Warga Desa', 'title_en' => 'Live In Homestay: Living with Villagers', 'slug' => 'live-in-homestay-hidup-warga', 'excerpt' => 'Rasakan kehidupan autentik di rumah joglo dan limasan.', 'excerpt_id' => 'Rasakan kehidupan autentik di rumah joglo dan limasan.', 'excerpt_en' => 'Experience authentic life in joglo and limasan houses.', 'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=800&auto=format&fit=crop', 'description' => 'Program live in mengajak Anda tinggal bersama keluarga angkat, merasakan keseharian, dan belajar kearifan lokal.', 'description_id' => 'Program live in mengajak Anda tinggal bersama keluarga angkat, merasakan keseharian, dan belajar kearifan lokal.', 'description_en' => 'The live in program invites you to stay with a host family and learn local wisdom.', 'category_id' => $cats['wisata-alam'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 150],
                ['title' => 'Gamelan & Karawitan: Belajar Musik Jawa', 'title_id' => 'Gamelan & Karawitan: Belajar Musik Jawa', 'title_en' => 'Gamelan & Karawitan: Learning Javanese Music', 'slug' => 'gamelan-karawitan-musik-jawa', 'excerpt' => 'Mainkan saron, bonang, dan gong bersama seniman lokal.', 'excerpt_id' => 'Mainkan saron, bonang, dan gong bersama seniman lokal.', 'excerpt_en' => 'Play saron, bonang, and gong with local artists.', 'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=800&auto=format&fit=crop', 'description' => 'Karawitan adalah seni musik gamelan Jawa. Belajar memainkannya memberi pengalaman budaya yang mendalam.', 'description_id' => 'Karawitan adalah seni musik gamelan Jawa. Belajar memainkannya memberi pengalaman budaya yang mendalam.', 'description_en' => 'Karawitan is the art of Javanese gamelan music, a deep cultural experience.', 'category_id' => $cats['budaya-kesenian'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 64],
                ['title' => 'Potensi Wisata Edukasi Desa Gabugan', 'title_id' => 'Potensi Wisata Edukasi Desa Gabugan', 'title_en' => 'Education Tourism Potential of Gabugan Village', 'slug' => 'potensi-wisata-edukasi-gabugan', 'excerpt' => 'Kenapa Desa Gabugan cocok untuk study tour dan live in.', 'excerpt_id' => 'Kenapa Desa Gabugan cocok untuk study tour dan live in.', 'excerpt_en' => 'Why Gabugan Village is suitable for study tours and live in.', 'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=800&auto=format&fit=crop', 'description' => 'Desa Gabugan memiliki beragam potensi wisata edukasi, mulai dari pertanian hingga kesenian tradisional.', 'description_id' => 'Desa Gabugan memiliki beragam potensi wisata edukasi, mulai dari pertanian hingga kesenian tradisional.', 'description_en' => 'Gabugan Village has diverse education tourism potential, from farming to traditional arts.', 'category_id' => $cats['info-event'] ?? 1, 'status' => 'published', 'user_id' => 1, 'reads' => 88],
            ];
            foreach ($blogs as $b) {
                Blog::firstOrCreate(['slug' => $b['slug']], $b);
            }
            $this->command->info('Blogs: ' . Blog::count() . ' created');
        }

        // ===== 4. Testimonials =====
        if (Testimonial::count() === 0) {
            $testimonials = [
                ['name' => 'Budi Santoso', 'content' => 'Pengalaman live in yang luar biasa! Anak-anak kami belajar banyak tentang pertanian dan budaya Jawa.', 'content_id' => 'Pengalaman live in yang luar biasa! Anak-anak kami belajar banyak tentang pertanian dan budaya Jawa.', 'content_en' => 'An amazing live in experience! Our students learned a lot about farming and Javanese culture.', 'locale' => 'id', 'is_active' => true],
                ['name' => 'Siti Rahmawati', 'content' => 'Homestay-nya bersih dan nyaman. Makanan rumahan yang disajikan sangat lezat dan otentik.', 'content_id' => 'Homestay-nya bersih dan nyaman. Makanan rumahan yang disajikan sangat lezat dan otentik.', 'content_en' => 'The homestay is clean and comfortable. The home-cooked meals are delicious and authentic.', 'locale' => 'id', 'is_active' => true],
                ['name' => 'Andi Wijaya', 'content' => 'Kegiatan membajak sawah dan membatik sangat berkesan untuk study tour sekolah kami.', 'content_id' => 'Kegiatan membajak sawah dan membatik sangat berkesan untuk study tour sekolah kami.', 'content_en' => 'The rice plowing and batik activities were very memorable for our school study tour.', 'locale' => 'id', 'is_active' => true],
                ['name' => 'Maria Lopez', 'content' => 'Beautiful village, warm people, and a truly authentic cultural experience. Highly recommended!', 'content_id' => 'Desa yang indah, warganya ramah, dan pengalaman budaya yang sangat autentik. Sangat direkomendasikan!', 'content_en' => 'Beautiful village, warm people, and a truly authentic cultural experience. Highly recommended!', 'locale' => 'en', 'is_active' => true],
                ['name' => 'Dewi Lestari', 'content' => 'Outbound-nya seru dan instrukturnya profesional. Cocok untuk kegiatan team building perusahaan.', 'content_id' => 'Outbound-nya seru dan instrukturnya profesional. Cocok untuk kegiatan team building perusahaan.', 'content_en' => 'The outbound was fun and the instructors were professional. Great for corporate team building.', 'locale' => 'id', 'is_active' => true],
                ['name' => 'John Carter', 'content' => 'The gamelan workshop was fantastic. We learned so much about Javanese music and traditions.', 'content_id' => 'Workshop gamelannya fantastis. Kami belajar banyak tentang musik dan tradisi Jawa.', 'content_en' => 'The gamelan workshop was fantastic. We learned so much about Javanese music and traditions.', 'locale' => 'en', 'is_active' => true],
                ['name' => 'Rina Kurnia', 'content' => 'Anak-anak sangat senang belajar menanam padi dan memberi makan ternak. Edukatif dan menyenangkan.', 'content_id' => 'Anak-anak sangat senang belajar menanam padi dan memberi makan ternak. Edukatif dan menyenangkan.', 'content_en' => 'The kids loved learning to plant rice and feed the livestock. Educational and fun.', 'locale' => 'id', 'is_active' => true],
                ['name' => 'David Brown', 'content' => 'The traditional food cooking class was a highlight of our trip. Everything tasted amazing!', 'content_id' => 'Kelas memasak makanan tradisional adalah sorotan perjalanan kami. Semuanya terasa lezat!', 'content_en' => 'The traditional food cooking class was a highlight of our trip. Everything tasted amazing!', 'locale' => 'en', 'is_active' => true],
            ];
            foreach ($testimonials as $t) {
                Testimonial::firstOrCreate(['name' => $t['name']], $t);
            }
            $this->command->info('Testimonials: ' . Testimonial::count() . ' created');
        }

        // ===== 5. Partner Logos =====
        if (PartnerLogo::count() === 0) {
            $logos = [
                ['name' => 'Kementerian Pariwisata', 'image' => 'https://placehold.co/200x100/2B6B47/ffffff?text=Kemenpar', 'url' => null, 'order' => 1, 'is_active' => true],
                ['name' => 'Dinas Pariwisata Sleman', 'image' => 'https://placehold.co/200x100/1F5236/ffffff?text=Dispar+Sleman', 'url' => null, 'order' => 2, 'is_active' => true],
                ['name' => 'Pemkab Sleman', 'image' => 'https://placehold.co/200x100/E8A838/ffffff?text=Pemkab+Sleman', 'url' => null, 'order' => 3, 'is_active' => true],
                ['name' => 'Universitas Gadjah Mada', 'image' => 'https://placehold.co/200x100/153B27/ffffff?text=UGM', 'url' => null, 'order' => 4, 'is_active' => true],
                ['name' => 'BRI', 'image' => 'https://placehold.co/200x100/3D8B63/ffffff?text=BRI', 'url' => null, 'order' => 5, 'is_active' => true],
                ['name' => 'Traveloka', 'image' => 'https://placehold.co/200x100/0C2418/ffffff?text=Traveloka', 'url' => null, 'order' => 6, 'is_active' => true],
            ];
            foreach ($logos as $l) {
                PartnerLogo::firstOrCreate(['name' => $l['name']], $l);
            }
            $this->command->info('Partner Logos: ' . PartnerLogo::count() . ' created');
        }

        // ===== 6. Site Galleries =====
        if (SiteGallery::count() === 0) {
            $galleryImages = [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1467003909585-2f8a72700288?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=800&auto=format&fit=crop',
            ];
            $titles = ['Hamparan Sawah', 'Membajak Sawah', 'Rumah Joglo', 'Membatik', 'Kuliner Desa', 'Gamelan', 'Pemandangan Desa', 'Pegunungan'];
            foreach ($galleryImages as $i => $img) {
                SiteGallery::firstOrCreate(['image' => $img], ['image' => $img, 'title' => $titles[$i], 'order' => $i + 1]);
            }
            $this->command->info('Site Galleries: ' . SiteGallery::count() . ' created');
        }

        // ===== 7. Homestays =====
        if (Homestay::count() < 10) {
            $homestays = [
                ['name_id' => 'Homestay Pak Slamet', 'name_en' => 'Pak Slamet Homestay', 'address_id' => 'Dusun Gabugan RT 01, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 01, Donokerto, Turi, Sleman', 'latitude' => -7.7480, 'longitude' => 110.2280, 'capacity' => 20, 'is_active' => true, 'order' => 1],
                ['name_id' => 'Homestay Bu Sri Rahayu', 'name_en' => 'Bu Sri Rahayu Homestay', 'address_id' => 'Dusun Gabugan RT 02, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 02, Donokerto, Turi, Sleman', 'latitude' => -7.7510, 'longitude' => 110.2310, 'capacity' => 15, 'is_active' => true, 'order' => 2],
                ['name_id' => 'Homestay Pak Joko', 'name_en' => 'Pak Joko Homestay', 'address_id' => 'Dusun Gabugan RT 03, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 03, Donokerto, Turi, Sleman', 'latitude' => -7.7530, 'longitude' => 110.2260, 'capacity' => 25, 'is_active' => true, 'order' => 3],
                ['name_id' => 'Homestay Bu Tari', 'name_en' => 'Bu Tari Homestay', 'address_id' => 'Dusun Gabugan RT 01, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 01, Donokerto, Turi, Sleman', 'latitude' => -7.7470, 'longitude' => 110.2340, 'capacity' => 18, 'is_active' => true, 'order' => 4],
                ['name_id' => 'Homestay Pak Waluyo', 'name_en' => 'Pak Waluyo Homestay', 'address_id' => 'Dusun Gabugan RT 04, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 04, Donokerto, Turi, Sleman', 'latitude' => -7.7550, 'longitude' => 110.2330, 'capacity' => 22, 'is_active' => true, 'order' => 5],
                ['name_id' => 'Homestay Bu Siti Aminah', 'name_en' => 'Bu Siti Aminah Homestay', 'address_id' => 'Dusun Gabugan RT 02, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 02, Donokerto, Turi, Sleman', 'latitude' => -7.7490, 'longitude' => 110.2210, 'capacity' => 16, 'is_active' => true, 'order' => 6],
                ['name_id' => 'Homestay Pak Hadi', 'name_en' => 'Pak Hadi Homestay', 'address_id' => 'Dusun Gabugan RT 05, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 05, Donokerto, Turi, Sleman', 'latitude' => -7.7520, 'longitude' => 110.2380, 'capacity' => 30, 'is_active' => true, 'order' => 7],
                ['name_id' => 'Homestay Bu Endang', 'name_en' => 'Bu Endang Homestay', 'address_id' => 'Dusun Gabugan RT 03, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 03, Donokerto, Turi, Sleman', 'latitude' => -7.7460, 'longitude' => 110.2250, 'capacity' => 14, 'is_active' => true, 'order' => 8],
                ['name_id' => 'Homestay Pak Supardi', 'name_en' => 'Pak Supardi Homestay', 'address_id' => 'Dusun Gabugan RT 06, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 06, Donokerto, Turi, Sleman', 'latitude' => -7.7560, 'longitude' => 110.2200, 'capacity' => 20, 'is_active' => true, 'order' => 9],
                ['name_id' => 'Homestay Bu Warsi', 'name_en' => 'Bu Warsi Homestay', 'address_id' => 'Dusun Gabugan RT 01, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 01, Donokerto, Turi, Sleman', 'latitude' => -7.7440, 'longitude' => 110.2320, 'capacity' => 12, 'is_active' => true, 'order' => 10],
                ['name_id' => 'Homestay Pak Mulyadi', 'name_en' => 'Pak Mulyadi Homestay', 'address_id' => 'Dusun Gabugan RT 07, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 07, Donokerto, Turi, Sleman', 'latitude' => -7.7580, 'longitude' => 110.2360, 'capacity' => 26, 'is_active' => true, 'order' => 11],
                ['name_id' => 'Homestay Bu Ngatini', 'name_en' => 'Bu Ngatini Homestay', 'address_id' => 'Dusun Gabugan RT 04, Donokerto, Turi, Sleman', 'address_en' => 'Gabugan Hamlet RT 04, Donokerto, Turi, Sleman', 'latitude' => -7.7500, 'longitude' => 110.2420, 'capacity' => 19, 'is_active' => true, 'order' => 12],
            ];
            foreach ($homestays as $h) {
                Homestay::firstOrCreate(['name_id' => $h['name_id']], $h);
            }
            $this->command->info('Homestays: ' . Homestay::count() . ' total');
        }

        // ===== 8. Update Activities with coordinates =====
        $activityCoords = [
            [-7.7490, 110.2270],
            [-7.7520, 110.2300],
            [-7.7470, 110.2330],
            [-7.7540, 110.2240],
            [-7.7450, 110.2290],
            [-7.7570, 110.2310],
            [-7.7510, 110.2220],
            [-7.7430, 110.2350],
            [-7.7550, 110.2380],
        ];
        $acts = Activity::orderBy('order')->get();
        foreach ($acts as $i => $a) {
            if (isset($activityCoords[$i]) && $a->latitude === null) {
                $a->update(['latitude' => $activityCoords[$i][0], 'longitude' => $activityCoords[$i][1], 'is_active' => true]);
            }
        }
        $this->command->info('Activities: ' . Activity::count() . ' (coordinates updated)');

        // ===== 9. Schedules =====
        if (Schedule::count() < 6) {
            $pkg = TravelPackage::pluck('id', 'slug');
            $schedules = [
                ['travel_package_id' => $pkg['paket-live-in-2h1m'], 'type' => 'confirmed', 'status' => 'in_progress', 'source_type' => 'manual', 'visitor_name' => 'SMA N 1 Sleman - Live In', 'number_phone' => '081234567890', 'guest_type' => 'lokal', 'start_date' => '2026-10-25', 'end_date' => '2026-10-28', 'quota' => 160, 'booked' => 156, 'is_active' => true],
                ['travel_package_id' => $pkg['paket-edukasi-1-hari'], 'type' => 'confirmed', 'status' => 'confirmed', 'source_type' => 'public_booking', 'visitor_name' => 'SMK N 2 Yogyakarta', 'number_phone' => '087654321000', 'guest_type' => 'lokal', 'start_date' => '2026-11-10', 'end_date' => '2026-11-10', 'quota' => 120, 'booked' => 98, 'is_active' => true],
                ['travel_package_id' => $pkg['paket-live-in-2h1m'], 'type' => 'pending', 'status' => 'pending', 'source_type' => 'price_estimation', 'visitor_name' => 'SMA Cita Hati - Live In', 'number_phone' => '081298765432', 'guest_type' => 'asing', 'start_date' => '2026-12-05', 'end_date' => '2026-12-08', 'quota' => 100, 'booked' => 30, 'is_active' => true],
                ['travel_package_id' => $pkg['paket-keluarga-weekend'], 'type' => 'confirmed', 'status' => 'completed', 'source_type' => 'manual', 'visitor_name' => 'Komunitas Keluarga Jogja', 'number_phone' => '085643218765', 'guest_type' => 'lokal', 'start_date' => '2026-09-15', 'end_date' => '2026-09-16', 'quota' => 50, 'booked' => 48, 'is_active' => false],
                ['travel_package_id' => $pkg['paket-outbound-team-building'], 'type' => 'open_trip', 'status' => 'pending', 'source_type' => 'open_trip', 'visitor_name' => 'Open Trip Team Building', 'number_phone' => '081356789012', 'guest_type' => 'lokal', 'start_date' => '2026-11-20', 'end_date' => '2026-11-21', 'quota' => 60, 'booked' => 22, 'is_active' => true],
                ['travel_package_id' => $pkg['paket-kuliner-tradisional'], 'type' => 'pending', 'status' => 'draft', 'source_type' => 'manual', 'visitor_name' => null, 'number_phone' => null, 'guest_type' => 'lokal', 'start_date' => '2026-12-15', 'end_date' => '2026-12-15', 'quota' => 40, 'booked' => 0, 'is_active' => true],
            ];
            foreach ($schedules as $s) {
                Schedule::firstOrCreate(
                    ['visitor_name' => $s['visitor_name'], 'start_date' => $s['start_date']],
                    $s
                );
            }
            $this->command->info('Schedules: ' . Schedule::count() . ' created');
        }

        // ===== 10. Assign homestays + activities to schedules (peta event) =====
        $allSchedules = Schedule::orderBy('id')->get();
        $hsIds = Homestay::pluck('id')->all();
        $actIds = Activity::pluck('id')->all();
        $groups = ['Kelompok A', 'Kelompok B', 'Kelompok C', 'Kelompok D'];
        $assigned = 0;
        foreach ($allSchedules as $idx => $sch) {
            if ($sch->homestays()->count() > 0) {
                continue; // sudah punya data peta
            }
            $start = ($idx * 3) % count($hsIds);
            $pickedHs = array_slice($hsIds, $start, 4);
            foreach ($pickedHs as $j => $hid) {
                $sch->homestays()->attach($hid, [
                    'group_name_id' => $groups[$j % 4],
                    'group_name_en' => 'Group ' . chr(65 + ($j % 4)),
                ]);
            }
            $actStart = ($idx * 3) % count($actIds);
            $pickedActs = array_slice($actIds, $actStart, 4);
            $sch->activities()->attach($pickedActs);
            $assigned++;
        }
        $this->command->info('Map assignments: ' . $assigned . ' schedules assigned');

        // ===== 11. Guests =====
        if (Guest::count() < 6) {
            $guests = [
                ['name' => 'Rudi Hermawan', 'institution' => 'SMA N 1 Sleman', 'number_phone' => '081234567890', 'email' => 'rudi@sman1sleman.sch.id', 'source' => 'booking', 'notes' => 'Guru pendamping'],
                ['name' => 'Siti Nurhaliza', 'institution' => 'SMK N 2 Yogyakarta', 'number_phone' => '087654321000', 'email' => 'siti@smkn2jogja.sch.id', 'source' => 'booking', 'notes' => null],
                ['name' => 'Agus Prasetyo', 'institution' => 'SMA Cita Hati', 'number_phone' => '081298765432', 'email' => 'agus@citahati.sch.id', 'source' => 'estimation', 'notes' => 'PIC study tour'],
                ['name' => 'Dewi Anggraini', 'institution' => 'Komunitas Keluarga Jogja', 'number_phone' => '085643218765', 'email' => 'dewi@gmail.com', 'source' => 'booking', 'notes' => null],
                ['name' => 'Bambang Sutejo', 'institution' => 'PT Maju Bersama', 'number_phone' => '081356789012', 'email' => 'bambang@majubersama.co.id', 'source' => 'open_trip', 'notes' => 'Team building'],
                ['name' => 'Yuni Astuti', 'institution' => 'Universitas Gadjah Mada', 'number_phone' => '082134567890', 'email' => 'yuni@ugm.ac.id', 'source' => 'booking', 'notes' => null],
            ];
            foreach ($guests as $g) {
                Guest::firstOrCreate(['email' => $g['email']], $g);
            }
            $this->command->info('Guests: ' . Guest::count() . ' created');
        }

        // ===== 12. Transactions =====
        if (Transaction::count() === 0) {
            $tx = [
                ['date' => '2026-10-20', 'description' => 'DP Live In SMA N 1 Sleman', 'category' => 'Pemasukan', 'source' => 'schedule', 'debit' => 5000000, 'credit' => 0, 'balance' => 5000000, 'notes' => 'Transfer bank'],
                ['date' => '2026-10-25', 'description' => 'Pelunasan Live In SMA N 1 Sleman', 'category' => 'Pemasukan', 'source' => 'schedule', 'debit' => 10000000, 'credit' => 0, 'balance' => 15000000, 'notes' => 'QRIS'],
                ['date' => '2026-10-26', 'description' => 'Pembelian bahan baku kuliner', 'category' => 'Pengeluaran', 'source' => 'operational', 'debit' => 0, 'credit' => 750000, 'balance' => 14250000, 'notes' => null],
                ['date' => '2026-11-02', 'description' => 'DP SMK N 2 Yogyakarta', 'category' => 'Pemasukan', 'source' => 'schedule', 'debit' => 3000000, 'credit' => 0, 'balance' => 17250000, 'notes' => 'Transfer bank'],
                ['date' => '2026-11-05', 'description' => 'Sewa peralatan outbound', 'category' => 'Pengeluaran', 'source' => 'operational', 'debit' => 0, 'credit' => 500000, 'balance' => 16750000, 'notes' => null],
                ['date' => '2026-11-08', 'description' => 'Pembayaran homestay warga', 'category' => 'Pengeluaran', 'source' => 'operational', 'debit' => 0, 'credit' => 2500000, 'balance' => 14250000, 'notes' => 'Bagi hasil homestay'],
            ];
            foreach ($tx as $t) {
                Transaction::firstOrCreate(['description' => $t['description'], 'date' => $t['date']], $t);
            }
            $this->command->info('Transactions: ' . Transaction::count() . ' created');
        }

        $this->command->info("\n=== DATA DUMMY SELESAI ===");
    }
}
