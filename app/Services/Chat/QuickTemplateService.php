<?php

namespace App\Services\Chat;

class QuickTemplateService
{
    protected array $dataDrivenCategories = ['paket', 'harga', 'aktivitas'];

    public function match(string $message, string $locale = 'id'): ?string
    {
        $key = $this->findKey($message);
        if (!$key) return null;
        if (in_array($key, $this->dataDrivenCategories)) return null;
        $templates = $locale === 'en' ? $this->templatesEn() : $this->templatesId();
        return $templates[$key] ?? null;
    }

    protected function findKey(string $message): ?string
    {
        $clean = trim(preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $message));
        $map = [
            '📋 Cara Pesan Paket' => 'booking', '📋 How to Book' => 'booking',
            '🌿 Info Paket Wisata' => 'paket', '🌿 Tour Packages' => 'paket',
            '💰 Harga & Biaya' => 'harga', '💰 Prices & Costs' => 'harga',
            '📍 Lokasi & Akses' => 'transportasi', '📍 Location & Access' => 'transportasi',
            '🏠 Info Homestay' => 'livein', '🏠 Homestay Info' => 'livein',
            '🎯 Aktivitas Seru' => 'aktivitas', '🎯 Fun Activities' => 'aktivitas',
            '🍲 Kuliner Khas' => 'kuliner', '🍲 Local Food' => 'kuliner',
            '🎨 Belajar Batik' => 'batik', '🎨 Learn Batik' => 'batik',
            '🎵 Gamelan Jawa' => 'gamelan', '🎵 Javanese Gamelan' => 'gamelan',
            '🌊 River Trekking' => 'river',
            '👥 Rombongan' => 'rombongan', '👥 Groups' => 'rombongan',
            '💡 Tips Persiapan' => 'persiapan', '💡 Preparation Tips' => 'persiapan',
        ];
        foreach ($map as $label => $k) {
            if ($message === $label || $clean === trim(preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $label))) return $k;
        }
        return null;
    }

    protected function templatesId(): array
    {
        $BR = "<br>";
        return [
            'booking' => "Terima kasih sudah tertarik untuk berkunjung ke Desa Wisata Gabugan! 🙏{$BR}{$BR}Berikut <strong>tata cara pemesanan</strong> paket wisata:{$BR}{$BR}📌 <strong>Langkah 1: Pilih Paket</strong>{$BR}Tentukan paket yang diinginkan (Agro Edukasi, Pertanian, Seni Budaya, Outbound, One-Day Tour, atau Live-In Homestay).{$BR}{$BR}📌 <strong>Langkah 2: Hubungi Admin</strong>{$BR}Konfirmasi ketersediaan tempat dan tanggal via WhatsApp:{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252</a>{$BR}{$BR}📌 <strong>Langkah 3: Data Peserta</strong>{$BR}Informasikan nama pemesan, pilihan paket, jumlah peserta, dan rencana tanggal kedatangan.{$BR}{$BR}📌 <strong>Langkah 4: Pembayaran DP</strong>{$BR}Transfer DP sebesar 30-50% sesuai panduan admin.{$BR}{$BR}📌 <strong>Langkah 5: Pelunasan di Lokasi</strong>{$BR}Sisa pembayaran dilakukan di lokasi saat hari-H kunjungan. 🎉{$BR}{$BR}Ada yang ingin didiskusikan? Jangan ragu hubungi kami via WhatsApp! 😊",

            'transportasi' => "<strong>📍 Alamat Desa Wisata Gabugan</strong>{$BR}Dusun Gabugan, Donokerto, Turi, Sleman, Daerah Istimewa Yogyakarta 55551{$BR}{$BR}<strong>Transportasi menuju Gabugan!</strong> 🚗{$BR}{$BR}<strong>Dari Kota Yogyakarta:</strong>{$BR}🚗 <strong>Mobil</strong> — ±45 menit (17 km) via Jl. Kaliurang km 17{$BR}🛵 <strong>Motor</strong> — ±35 menit{$BR}🚌 <strong>Bus/Elf</strong> — Dari Terminal Jombor jurusan Turi{$BR}{$BR}<strong>Dari Bandara YIA:</strong>{$BR}🚗 ±1,5 jam via tol Jogja-Solo{$BR}{$BR}<strong>Dari Stasiun Tugu:</strong>{$BR}🚗 ±40 menit via Jl. Kaliurang{$BR}{$BR}<strong>Tips:</strong> Jalan beraspal halus, bisa dilalui bus besar. Area parkir luas! 🅿️",

            'livein' => "Informasi Live-In / Homestay di Gabugan! 🏡{$BR}{$BR}<strong>Apa itu Live-In?</strong>{$BR}Program menginap langsung di rumah warga dan merasakan kehidupan sehari-hari masyarakat desa. Anda tinggal bersama <strong>keluarga angkat</strong> dan berpartisipasi dalam aktivitas keseharian.{$BR}{$BR}<strong>Fasilitas:</strong>{$BR}✅ Kamar tidur bersih dengan sprei ganti{$BR}✅ Kamar mandi dalam{$BR}✅ Makanan rumahan 3x sehari{$BR}✅ Air mineral & teh/kopi{$BR}✅ Keluarga angkat yang ramah{$BR}✅ Pemandu lokal{$BR}{$BR}<strong>Aktivitas:</strong>{$BR}🌾 Pagi — Beri makan ternak, sarapan{$BR}🌾 Siang — Bajak sawah, tanam padi, petik salak{$BR}🎨 Sore — Batik atau gamelan{$BR}🌙 Malam — Api unggun, ngopi santai{$BR}{$BR}<strong>Harga:</strong> Mulai Rp 250.000/orang{$BR}{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252 (WhatsApp)</a>",

            'kuliner' => "Kuliner khas yang bisa Anda nikmati di Gabugan! 🍲{$BR}{$BR}<strong>Makanan:</strong>{$BR}• 🥘 Sayur Lodeh Tewel — Sayur lodeh nangka muda{$BR}• 🌶️ Sambal Belut Goreng — Belut goreng krispi{$BR}• 🍚 Nasi Liwet — Nasi gurih dengan lauk komplit{$BR}• 🥗 Urap-urap — Sayur rebus dengan kelapa berbumbu{$BR}{$BR}<strong>Camilan & Minuman:</strong>{$BR}• 🍬 Manisan Salak Pondoh — Oleh-oleh manis khas{$BR}• ☕ Wedang Jahe Merapi — Minuman jahe hangat{$BR}{$BR}Semua bahan segar dari kebun dan sawah warga! 😊",

            'batik' => "Belajar membatik! 🎨{$BR}{$BR}🖌️ Batik Tulis Canting — Tradisional{$BR}🖌️ Batik Cap — Motif lebih cepat{$BR}🖌️ Batik Ikat Celup — Untuk pemula{$BR}{$BR}✅ Kain ±1 meter bisa dibawa pulang{$BR}✅ Sertifikat partisipasi{$BR}{$BR}<strong>Motif Khas Gabugan:</strong>{$BR}🌾 Sawah terasering 🍈 Salak 🌋 Merapi",

            'gamelan' => "Belajar Gamelan! 🎵{$BR}{$BR}🎹 Saron — Bilah logam bernada nyaring{$BR}🎹 Bonang — Gong kecil{$BR}🥁 Kendang — Pengatur irama{$BR}🔔 Gong — Penanda akhir lagu{$BR}{$BR}Gamelan mengajarkan <em>\"harmoni\"</em> — setiap instrumen saling melengkapi.",

            'river' => "River Trekking! 🌊{$BR}{$BR}🚶 Menyusuri sungai jernih dari mata air Merapi{$BR}🚶 Air segar dan tidak terlalu dalam{$BR}🚶 Ditemani pemandu lokal{$BR}{$BR}<strong>Tips:</strong> Pakai sepatu anti slip, bawa baju ganti!",

            'rombongan' => "Informasi kunjungan rombongan! 👥{$BR}{$BR}✅ <strong>Rombongan Kecil (10-30 orang):</strong>{$BR}Cocok untuk paket edukasi, petik salak, batik, live-in. Suasana hangat dan privat!{$BR}{$BR}✅ <strong>Rombongan Besar (30-500 orang):</strong>{$BR}Cocok untuk Gathering, Outbound, Study Tour. Disarankan koordinasi H-7.{$BR}{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252</a>",

            'persiapan' => "Tips persiapan berkunjung! 🎒{$BR}{$BR}👕 Baju ganti{$BR}👟 Sepatu/sandal gunung{$BR}🧴 Sunscreen & topi{$BR}☂️ Payung/jas hujan{$BR}🧥 Jaket/sweater{$BR}📱 Power bank{$BR}💊 Obat pribadi{$BR}{$BR}<strong>Tips:</strong> Bawa pakaian yang nyaman untuk aktivitas outdoor! 😊",
        ];
    }

    protected function templatesEn(): array
    {
        $BR = "<br>";
        return [
            'booking' => "Thank you for your interest in visiting Gabugan Tourism Village! 🙏{$BR}{$BR}Here are the <strong>booking steps</strong>:{$BR}{$BR}📌 <strong>Step 1: Choose a Package</strong>{$BR}Select your preferred package (Agro Education, Agriculture, Arts & Culture, Outbound, One-Day Tour, or Live-In Homestay).{$BR}{$BR}📌 <strong>Step 2: Contact Admin</strong>{$BR}Confirm availability via WhatsApp:{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252</a>{$BR}{$BR}📌 <strong>Step 3: Participant Details</strong>{$BR}Provide your name, chosen package, number of participants, and planned arrival date.{$BR}{$BR}📌 <strong>Step 4: Down Payment</strong>{$BR}Transfer 30-50% DP as instructed by admin.{$BR}{$BR}📌 <strong>Step 5: Final Payment</strong>{$BR}Pay the remaining balance at the location on the day of your visit. 🎉{$BR}{$BR}Feel free to contact us via WhatsApp! 😊",

            'transportasi' => "<strong>📍 Address of Gabugan Tourism Village</strong>{$BR}Gabugan Hamlet, Donokerto, Turi, Sleman, Special Region of Yogyakarta 55551{$BR}{$BR}<strong>Transportation to Gabugan!</strong> 🚗{$BR}{$BR}<strong>From Yogyakarta City:</strong>{$BR}🚗 <strong>Car</strong> — ±45 mins (17 km) via Jl. Kaliurang km 17{$BR}🛵 <strong>Motorcycle</strong> — ±35 mins{$BR}🚌 <strong>Bus</strong> — From Terminal Jombor to Turi{$BR}{$BR}<strong>From YIA Airport:</strong>{$BR}🚗 ±1.5 hours via Jogja-Solo toll road{$BR}{$BR}<strong>From Tugu Station:</strong>{$BR}🚗 ±40 mins via Jl. Kaliurang{$BR}{$BR}<strong>Tips:</strong> Paved road, accessible for big buses. Spacious parking! 🅿️",

            'livein' => "Live-In / Homestay Information! 🏡{$BR}{$BR}<strong>What is Live-In?</strong>{$BR}Stay directly with local families and experience daily village life.{$BR}{$BR}<strong>Price:</strong> From Rp 250,000/person{$BR}{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252 (WhatsApp)</a>",

            'kuliner' => "Local culinary at Gabugan! 🍲{$BR}{$BR}<strong>Food:</strong>{$BR}• 🥘 Sayur Lodeh Tewel — Young jackfruit vegetable soup{$BR}• 🌶️ Sambal Belut Goreng — Crispy eel with spicy sambal{$BR}• 🍚 Nasi Liwet — Fragrant coconut rice{$BR}• 🥗 Urap-urap — Vegetables with spiced coconut{$BR}{$BR}<strong>Drinks:</strong>{$BR}• ☕ Wedang Jahe — Warm ginger drink{$BR}{$BR}All ingredients are fresh from local gardens! 😊",

            'batik' => "Learn batik making! 🎨{$BR}{$BR}🖌️ Traditional Canting Batik{$BR}🖌️ Stamp Batik — Faster motifs{$BR}🖌️ Tie-dye Batik — For beginners{$BR}{$BR}<strong>Gabugan Special Motifs:</strong>{$BR}🌾 Rice terraces 🍈 Salak 🌋 Merapi",

            'gamelan' => "Learn Gamelan! 🎵{$BR}{$BR}🎹 Saron — Bright sounding metal bars{$BR}🎹 Bonang — Small gong set{$BR}🥁 Kendang — Rhythm keeper{$BR}🔔 Gong — Song end marker{$BR}{$BR}Gamelan teaches <em>\"harmony\"</em> — every instrument completes each other.",

            'river' => "River Trekking! 🌊{$BR}{$BR}🚶 Explore clear streams from Merapi springs{$BR}🚶 Accompanied by local guide{$BR}{$BR}<strong>Tips:</strong> Wear anti-slip shoes, bring change of clothes!",

            'rombongan' => "Group visit information! 👥{$BR}{$BR}✅ <strong>Small Group (10-30 people):</strong>{$BR}Perfect for educational tours, salak picking, batik, live-in.{$BR}{$BR}✅ <strong>Large Group (30-500 people):</strong>{$BR}Suitable for Gathering, Outbound, Study Tour.{$BR}{$BR}📞 <a href=\"https://api.whatsapp.com/send?phone=6281328856252\" target=\"_blank\" class=\"text-[#00a877] font-semibold hover:underline\">+62 813-2885-6252 (WhatsApp)</a>",

            'persiapan' => "Preparation tips! {$BR}{$BR}👕 Change of clothes{$BR}👟 Hiking sandals/shoes{$BR}🧴 Sunscreen & hat{$BR}☂️ Umbrella/raincoat{$BR}🧥 Jacket/sweater{$BR}📱 Power bank{$BR}<strong>Tips:</strong> Wear comfortable clothes for outdoor activities! 😊",
        ];
    }
}