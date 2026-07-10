{{--
=======================================
 AI CHAT ASSISTANT — JAVASCRIPT (BILINGUAL)
=======================================
 Now supports Bahasa Indonesia & English based on website locale
 Dependencies: partials/ai-chat-data.blade.php (loaded before)
=======================================
--}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================
    // DOM REFERENCES
    // ========================
    const chatToggle = document.getElementById('ai-chat-toggle');
    const chatToggle2 = document.getElementById('ai-chat-toggle-2');
    const chatPanel = document.getElementById('ai-chat-panel');
    const chatClose = document.getElementById('ai-chat-close');
    const iconOpen = document.getElementById('chat-icon-open');
    const iconClose = document.getElementById('chat-icon-close');
    const chatMessages = document.getElementById('ai-chat-messages');
    const chatForm = document.getElementById('ai-chat-form');
    const chatInput = document.getElementById('ai-chat-input');
    const quickBtns = document.querySelectorAll('.quick-chat-btn');
    const typingIndicator = document.getElementById('ai-typing');

    const locale = window.chatLocale || 'id';

    // ========================
    // LOCALIZED STRINGS
    // ========================
    const LANG = {
        id: {
            BOT_NAME: 'Mas Pandu',
            BOT_INITIAL: 'MP',
            USER_INITIAL: 'A',
            WELCOME: `Halo! 🙏 Saya <strong>Mas Pandu</strong>, asisten virtual resmi Desa Wisata Gabugan, Sleman, Yogyakarta.<br><br>Senang bisa menyambut Anda! Ada yang bisa saya bantu mengenai kunjungan Anda?<br><br>Saya siap memberikan informasi lengkap tentang:<br>• 🌿 Paket Agrowisata Salak Pondoh<br>• 🌾 Aktivitas sawah & edukasi pertanian<br>• 🎨 Belajar batik tulis, gamelan, dan budaya Jawa<br>• 🏡 Pengalaman menginap (Live-in) di Homestay Rumah warga<br>• 🍲 Kuliner khas pedesaan lereng Merapi<br><br><em>Monggo</em>, silakan pilih pertanyaan di bawah atau ketik langsung apa yang ingin Anda ketahui! 😊`,
            CONTACT: `<br><br>📞 <strong>Butuh info lebih detail? Hubungi admin kami:</strong><br><a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a> — Tim kami siap membantu! 😊`,
            greeting: `Halo! 🙏\n\nSaya <strong>Mas Pandu</strong>, asisten virtual resmi dari <strong>Desa Wisata Gabugan</strong>, Sleman, Yogyakarta.\n\nSaya siap membantu Anda dengan informasi tentang paket wisata, homestay, aktivitas seru, harga, lokasi, dan apa pun yang ingin Anda ketahui tentang desa wisata kami! 🏡🌄\n\nAda yang bisa saya bantu? 😊`,
            smalltalk: `Saya Mas Pandu, asisten virtual resmi Desa Wisata Gabugan! 🧑‍🌾\n\nSaya bisa membantu Anda dengan informasi seputar:\n• 🌿 Paket Wisata & Edukasi\n• 🏡 Homestay & Live-in\n• 🍲 Kuliner Khas Lereng Merapi\n• 🎨 Kegiatan Seni Budaya\n• 🚗 Rute & Transportasi\n\n<em>Monggo</em>, mau tanya apa? 😊`,
            booking: `Terima kasih sudah tertarik untuk berkunjung ke Desa Wisata Gabugan! 🙏\n\nBerikut <strong>tata cara pemesanan</strong> paket wisata:\n\n📌 <strong>Langkah 1: Pilih Paket</strong>\nTentukan paket yang diinginkan (Agro Edukasi, Pertanian, Seni Budaya, Outbound, One-Day Tour, atau Live-In Homestay).\n\n📌 <strong>Langkah 2: Hubungi Admin</strong>\nKonfirmasi ketersediaan tempat dan tanggal via WhatsApp:\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>\n\n📌 <strong>Langkah 3: Data Peserta</strong>\nInformasikan nama pemesan, pilihan paket, jumlah peserta, dan rencana tanggal kedatangan.\n\n📌 <strong>Langkah 4: Pembayaran DP</strong>\nTransfer DP sebesar 30-50% sesuai panduan admin.\n\n📌 <strong>Langkah 5: Pelunasan di Lokasi</strong>\nSisa pembayaran dilakukan di lokasi saat hari-H kunjungan. 🎉\n\nAda yang ingin didiskusikan? Jangan ragu hubungi kami via WhatsApp! 😊`,
            harga: `Informasi harga paket wisata:\n\n• 🌿 <strong>Agro Edukasi Salak Pondoh</strong> — Rp 150.000/orang\n• 🌾 <strong>Edukasi Pertanian & Sawah</strong> — Rp 175.000/orang\n• 🎨 <strong>Seni & Budaya Jawa</strong> — Rp 150.000/orang\n• 🏡 <strong>Live-In Homestay</strong> — Rp 250.000/orang\n• 📋 <strong>One-Day Tour Gabugan</strong> — Rp 175.000/orang\n\nHarga sudah termasuk pemandu lokal dan konsumsi. Untuk rombongan tersedia <strong>harga khusus</strong>! 💰\n\nHubungi kami untuk penawaran terbaik! 😊`,
            kontak: `Hubungi kami melalui:\n\n📞 <strong>WhatsApp:</strong> <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>\n📧 <strong>Email:</strong> info@desawisatagabugan.com\n📸 <strong>Instagram:</strong> @desawisatagabugan\n\n📍 Kantor: Dusun Gabugan, Donokerto, Turi, Sleman, DIY\n\nJam Operasional:\n🕐 <strong>Setiap Hari</strong> — 08.00 - 17.00 WIB\n🏡 Homestay 24 Jam (sesuai pesanan)\n\nJangan ragu menghubungi kami! 😊`,
            kuliner: `Kuliner khas yang bisa Anda nikmati di Gabugan! 🍲\n\n<strong>Makanan:</strong>\n• 🥘 Sayur Lodeh Tewel — Sayur lodeh nangka muda\n• 🌶️ Sambal Belut Goreng — Belut goreng krispi\n• 🍚 Nasi Liwet — Nasi gurih dengan lauk komplit\n• 🥗 Urap-urap — Sayur rebus dengan kelapa berbumbu\n\n<strong>Camilan & Minuman:</strong>\n• 🍬 Manisan Salak Pondoh — Oleh-oleh manis khas\n• ☕ Wedang Jahe Merapi — Minuman jahe hangat\n\n<strong>Kuliner Malam:</strong>\n• 🌙 Angkringan Gabugan — Nasi kucing, sate usus, gorengan\n\nSemua bahan segar dari kebun dan sawah warga! 😊`,
            transportasi: `Informasi transportasi menuju Gabugan! 🚗\n\n<strong>Dari Kota Yogyakarta:</strong>\n🚗 <strong>Mobil</strong> — ±45 menit (17 km) via Jl. Kaliurang km 17\n🛵 <strong>Motor</strong> — ±35 menit\n🚌 <strong>Bus/Elf</strong> — Dari Terminal Jombor jurusan Turi\n\n<strong>Dari Bandara YIA:</strong>\n🚗 ±1,5 jam via tol Jogja-Solo\n\n<strong>Dari Stasiun Tugu:</strong>\n🚗 ±40 menit via Jl. Kaliurang\n\n<strong>Alternatif:</strong>\n• Gojek/Grab tersedia (Rp 70-100rb)\n• Bisa dijemput dari bandara/stasiun (koordinasi WA)\n\n<strong>Tips:</strong> Jalan beraspal halus, bisa dilalui bus besar. Area parkir luas! 🅿️`,
            waktu: `Jam operasional & waktu terbaik berkunjung! ⏰\n\n<strong>Jam Operasional:</strong>\n🕐 Setiap Hari — 08.00 - 17.00 WIB\n🏡 Homestay/Live-In — 24 jam (sesuai kesepakatan)\n\n<strong>Waktu Terbaik:</strong>\n🌤️ <strong>Musim Kemarau (April-Oktober)</strong> — Cuaca cerah, outdoor maksimal\n🌧️ <strong>Musim Hujan (Nov-Maret)</strong> — Aktivitas dalam ruang (batik, gamelan)\n\n<strong>Tips:</strong> Akhir pekan lebih ramai, weekday lebih tenang dan privat! 😊`,
            fasilitas: `Fasilitas di Desa Wisata Gabugan! 🏗️\n\n<strong>Fasilitas Umum:</strong>\n✅ Area Parkir luas (muat 5 bus besar)\n✅ Mushola/Masjid bersih nyaman\n✅ Toilet umum terawat\n✅ Saung/Gazebo dengan pemandangan sawah\n✅ Ruang Bilas/Ganti\n\n<strong>Fasilitas Homestay:</strong>\n✅ Kamar tidur bersih\n✅ Kamar mandi dalam\n✅ Air mineral & teh/kopi\n✅ Keluarga tuan rumah yang ramah\n\n<strong>Fasilitas Pendukung:</strong>\n✅ Sinyal 4G stabil\n✅ P3K di posko utama\n✅ Pemandu lokal berpengalaman\n✅ Area makan lesehan kapasitas besar`,
            budaya: `Budaya & tradisi yang bisa dipelajari! 🎭\n\n<strong>Seni & Budaya:</strong>\n🎵 Gamelan & Karawitan — Belajar saron, bonang, gong\n🎨 Membatik Tulis — Proses dari awal hingga jadi kain\n💃 Tari Jawa — Gerakan dasar tari klasik Yogyakarta\n🥁 Jathilan — Kesenian rakyat pedesaan\n\n<strong>Tradisi Lokal:</strong>\n🌾 Selamatan Bumi — Ritual syukur panen tahunan\n🙏 Bersih Desa — Tradisi tolak bala\n🍚 Kenduri — Makan bersama ungkapan syukur`,
            jogja: `Wisata di sekitar Yogyakarta! 🏛️\n\n<strong>Dekat Gabugan:</strong>\n🌋 Kaliurang & Kaliadem — ±15 menit\n🏛️ Museum Gunung Merapi — ±20 menit\n🌲 Bukit Bintang — ±25 menit\n\n<strong>Dalam Kota (±45 menit):</strong>\n🛍️ Malioboro — Pusat oleh-oleh\n🏯 Kraton Yogyakarta\n🌊 Pantai Parangtritis — ±1 jam`,
            cuaca: `Cuaca di kawasan Gabugan! 🌤️\n\n🌡️ <strong>Suhu:</strong>\nSiang: 24-30°C | Malam: 18-22°C | Pagi: 20-24°C\n\n<strong>Musim:</strong>\n☀️ Kemarau (April-Oktober) — Cerah, cocok outdoor\n🌧️ Hujan (Nov-Maret) — Bawa payung/jas hujan\n\n<strong>Tips:</strong> Bawa jaket untuk malam hari 🧥`,
            foto: `Spot foto keren di Gabugan! 📸\n\n🌾 Sawah Terasering — Latar hijau + Merapi\n🏡 Homestay Joglo — Arsitektur tradisional\n🍈 Kebun Salak — Foto di antara pohon salak\n🌅 Pematang Sawah — Golden hour magis\n🎨 Area Membatik — Proses batik aesthetic\n\n<strong>Tips:</strong> Waktu terbaik 06.00-08.00 atau 15.00-17.00. Drone allowed!`,
            rombongan: `Informasi kunjungan rombongan! 👥\n\n✅ <strong>Rombongan Kecil (10-30 orang):</strong>\nCocok untuk paket edukasi, petik salak, batik, live-in. Suasana hangat dan privat!\n\n✅ <strong>Rombongan Besar (30-500 orang):</strong>\nCocok untuk Gathering, Outbound, Study Tour. Disarankan koordinasi H-7.\n\n🎯 <strong>Keuntungan:</strong>\n• Harga khusus / potongan\n• Itinerary bisa custom\n• Pendampingan pemandu lokal\n\n📞 Konsultasi via WA: <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>`,
            testimonial: `Testimoni pengunjung! ⭐\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Pengalaman luar biasa! Anak-anak sekolah sangat senang belajar membajak sawah."</em> — <strong>Bu Sari, Guru SD</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Homestay nyaman, makanan enak, suasananya bikin kangen."</em> — <strong>Mas Dito</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Batik tulisnya bagus! Bisa dibawa pulang."</em> — <strong>Mbak Rina</strong>\n\n<strong>Rating:</strong> ⭐ 4.9/5 dari 200+ pengunjung!`,
            anak: `Kunjungan bersama anak & keluarga! 👨‍👩‍👧‍👦\n\n🧒 Membajak Sawah — Naik kerbau jinak\n🎨 Membatik Sederhana — Motif simpel untuk anak\n🍈 Petik Salak — Petik buah langsung dari pohon\n🌾 Bermain di Sawah — Main air, kejar capung\n🐄 Beri Makan Ternak — Kambing, ayam, kerbau\n\n<strong>Tips:</strong> Bawa baju ganti dan lotion anti nyamuk!`,
            persiapan: `Tips persiapan berkunjung! 🎒\n\n👕 Baju ganti\n👟 Sepatu/sandal gunung\n🧴 Sunscreen & topi\n☂️ Payung/jas hujan\n🧥 Jaket/sweater\n📱 Power bank\n💊 Obat pribadi`,
            pujian: `Terima kasih banyak! 🙏😊\n\nSenang mendengar apresiasi Anda! Mas Pandu dan tim <strong>Desa Wisata Gabugan</strong> selalu berusaha memberikan pelayanan terbaik.\n\nAda lagi yang ingin ditanyakan? 🏡🌾`,
            negatif: `Maaf jika ada yang kurang berkenan 🙏\n\nKami selalu berusaha meningkatkan kualitas pelayanan. Jika ada masukan, sampaikan langsung ke kami:\n\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a>`,
            tentang: `Tentang Desa Wisata Gabugan! 🏡\n\n<strong>Lokasi:</strong> Dusun Gabugan, Donokerto, Turi, Sleman, DIY\n\n<strong>Sekilas:</strong>\n🌄 Lereng barat Gunung Merapi, ±500 mdpl\n🌾 Dikelola Pokdarwis setempat\n🤝 Konsep Community Based Tourism\n🏆 Penghargaan desa wisata terbaik DIY\n\nSelamat datang di Gabugan, rumah kedua Anda di lereng Merapi! 🌋✨`,
            promo: `Promo & harga spesial! 💰\n\n🎉 <strong>Promo Rombongan</strong> — Potongan 10-20% (30+ orang)\n🎉 <strong>Early Bird</strong> — Booking H-14 harga spesial\n🎉 <strong>Weekday Special</strong> — Lebih hemat Senin-Jumat\n\n<strong>Harga Normal:</strong>\n• 🌿 Agro Edukasi Salak — Rp 150.000/orang\n• 🌾 Edukasi Pertanian — Rp 175.000/orang\n• 🎨 Seni & Budaya — Rp 150.000/orang\n• 🏡 Live-In Homestay — Rp 250.000/orang\n• 📋 One-Day Tour — Rp 175.000/orang`,
            bahasa: `Informasi untuk wisatawan asing! 🌏\n\n<strong>English Service Available!</strong>\nDesa Wisata Gabugan welcomes international visitors. Some of our guides can speak English.\n\n<strong>Activities:</strong>\n🌾 Rice Field Experience\n🍈 Salak Pondoh Harvesting\n🎨 Batik Making\n🎵 Gamelan Music\n🏡 Homestay with Local Family`,
            kesehatan: `Informasi kesehatan & keamanan! 🏥\n\n<strong>CHSE Certified ✅</strong>\nProtokol kebersihan & keamanan diterapkan.\n\n<strong>Fasilitas Kesehatan:</strong>\n🏥 Puskesmas Turi — ±10 menit\n🏥 RS PKU Muhammadiyah — ±20 menit\n🏥 RS Sardjito — ±30 menit (rujukan)`,
            souvenir: `Oleh-oleh khas Gabugan! 🛍️\n\n🍈 Salak Pondoh Segar — Petik langsung\n🍬 Manisan Salak — Oleh-oleh manis\n🥨 Keripik Salak — Camilan renyah\n🎨 Batik Tulis Karya Warga\n🧺 Anyaman Bambu & Mendong`,
            merapi: `Informasi Gunung Merapi! 🌋\n\n📍 Gabugan di lereng barat Merapi, ±12 km dari puncak\n🌄 Pemandangan Merapi jelas dari desa\n\n<strong>Aktivitas:</strong>\n🏛️ Museum Gunung Merapi — ±20 menit\n🚙 Jeep Lava Tour — Jelajahi aliran lahar\n🌲 Kaliadem & Bunker — Spot sejarah erupsi 2010`,
            akomodasi: `Akomodasi sekitar Gabugan! 🏨\n\n<strong>Di Dalam Desa:</strong>\n🏡 Homestay Warga — Mulai Rp 250.000/orang (termasuk makan & aktivitas)\n\n<strong>Di Sekitar (10-20 menit):</strong>\n🏨 Hotel & Resort Kaliurang\n🏠 Villa Kaliurang\n🛌 Guest House Turi`,
            livein: `Informasi Live-In / Homestay di Gabugan! 🏡\n\n<strong>Apa itu Live-In?</strong>\nProgram menginap langsung di rumah warga dan merasakan kehidupan sehari-hari masyarakat desa. Anda tinggal bersama <strong>keluarga angkat</strong> dan berpartisipasi dalam aktivitas keseharian.\n\n<strong>Fasilitas:</strong>\n✅ Kamar tidur bersih dengan sprei ganti\n✅ Kamar mandi dalam\n✅ Makanan rumahan 3x sehari\n✅ Air mineral & teh/kopi\n✅ Keluarga angkat yang ramah\n✅ Pemandu lokal\n✅ Listrik & penerangan\n✅ Area parkir\n\n<strong>Aktivitas:</strong>\n🌾 Pagi — Beri makan ternak, sarapan\n🌾 Siang — Bajak sawah, tanam padi, petik salak\n🎨 Sore — Batik atau gamelan\n🌙 Malam — Api unggun, ngopi santai\n\n<strong>Harga:</strong> Mulai Rp 250.000/orang\n\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a>`,
            malam: `Aktivitas malam di Gabugan! 🌙\n\n🔥 Api Unggun — Bakar jagung/ubi\n🎵 Gamelan Malam — Musik syahdu\n🌌 Stargazing — Langit bebas polusi\n☕ Ngopi Malam — Wedang jahe di saung sawah`,
            sawah: `Aktivitas di sawah! 🌾\n\n🐃 Membajak dengan Kerbau — Rasakan jadi petani\n🌱 Menanam Padi — Belajar cara tanam padi\n🌾 Panen Padi — Khusus musim panen\n\n<strong>Tips:</strong> Bawa baju ganti!`,
            salak: `Info Salak Pondoh! 🍈\n\n🌴 Buah khas Sleman, manis & renyah\n🌴 Dikelola secara organik\n🌴 Musim panen raya: Desember - Maret\n\n<strong>Aktivitas:</strong>\n🍈 Petik langsung dari pohon\n🍈 Dicicipi di kebun\n\n✅ Kaya antioksidan\n✅ Baik untuk pencernaan`,
            batik: `Belajar membatik! 🎨\n\n🖌️ Batik Tulis Canting — Tradisional\n🖌️ Batik Cap — Motif lebih cepat\n🖌️ Batik Ikat Celup — Untuk pemula\n\n✅ Kain ±1 meter bisa dibawa pulang\n✅ Sertifikat partisipasi\n\n<strong>Motif Khas Gabugan:</strong>\n🌾 Sawah terasering 🍈 Salak 🌋 Merapi`,
            gamelan: `Belajar Gamelan! 🎵\n\n🎹 Saron — Bilah logam bernada nyaring\n🎹 Bonang — Gong kecil\n🥁 Kendang — Pengatur irama\n🔔 Gong — Penanda akhir lagu\n\nGamelan mengajarkan <em>"harmoni"</em> — setiap instrumen saling melengkapi.`,
            river: `River Trekking! 🌊\n\n🚶 Menyusuri sungai jernih dari mata air Merapi\n🚶 Air segar dan tidak terlalu dalam\n🚶 Ditemani pemandu lokal\n\n<strong>Tips:</strong> Pakai sepatu anti slip, bawa baju ganti!`,
            outbound: `Outbound & Team Building! 🎯\n\n🧗 Flying Fox — Meluncur di atas sawah\n🕸️ Jaring Laba-Laba — Kerjasama tim\n🏃 Estafet Air — Basah-basahan seru\n\n<strong>Cocok untuk:</strong>\n🏢 Company gathering\n🎓 MPLS/OSPEK\n👨‍👩‍👧‍👦 Family gathering`,
            prewedding: `Paket Foto Prewedding! 💑\n\n💑 Sawah Terasering — Latar Merapi\n💑 Homestay Joglo — Nuansa Jawa romantis\n💑 Kebun Salak — Backdrop alami\n💑 Pematang Sawah — Golden hour magis\n\n💰 Mulai Rp 500.000 (akses spot)`,
            event: `Event di Gabugan! 🎪\n\n🌾 Festival Salak Pondoh — Tahunan\n🎭 Pentas Seni Budaya\n🏃 Fun Trail & Adventure Run\n🍲 Festival Kuliner Ndeso\n\n✅ Area terbuka kapasitas 500 orang`,
            random: `Wah, pertanyaan menarik! 😊\n\n<strong>Desa Wisata Gabugan</strong> destinasi wisata edukasi & budaya di lereng Merapi, cocok untuk:\n• 🌿 <strong>Healing</strong> — Suasana pedesaan tenang & asri<br>• 📚 <strong>Belajar</strong> — Aktivitas interaktif pertanian, batik, gamelan<br>• 👨‍👩‍👧‍👦 <strong>Berkumpul</strong> — Keluarga, teman, rekan kerja<br>• 📸 <strong>Foto</strong> — Spot instagramable latar Merapi<br><br>Ada topik spesifik yang ingin ditanyakan? 😊`,
            fallback: `Maaf 🙏, saya belum sepenuhnya paham maksud pertanyaan Anda.\n\nSaya bisa memberikan informasi lengkap tentang:\n\n🌾 <strong>Pertanian & Sawah</strong> (membajak, tanam padi)\n🍈 <strong>Petik Salak Pondoh</strong> (agro edukasi)\n🎨 <strong>Membatik & Gamelan</strong> (budaya Jawa)\n🏡 <strong>Homestay & Live-in</strong> (menginap di desa)\n🚗 <strong>Lokasi, Rute, & Jam Buka</strong>\n\nCoba ketik kata kunci seperti <strong>"paket"</strong>, <strong>"kuliner"</strong>, atau <strong>"harga"</strong>. 😊`,
            pkg_header: 'Berikut paket wisata unggulan dari Desa Wisata Gabugan! 🌟\n\nSilakan klik kartu di bawah untuk detail lengkap:',
            pkg_fallback: 'Berikut paket unggulan kami:\n\n• 🌿 <strong>Agro Edukasi Salak Pondoh</strong> — Mulai Rp 150.000/orang\n• 🌾 <strong>Edukasi Pertanian</strong> — Mulai Rp 175.000/orang\n• 🎨 <strong>Seni & Budaya Jawa</strong> — Mulai Rp 150.000/orang\n• 🏡 <strong>Live-In Homestay</strong> — Mulai Rp 250.000/orang\n\nAda yang menarik? 😊',
            act_header: 'Berikut aktivitas seru yang bisa Anda lakukan di Desa Wisata Gabugan! 🎯\n\nSilakan klik kartu di bawah untuk detail lengkap:',
            act_fallback: 'Berikut aktivitas yang bisa Anda ikuti:\n\n• 🌾 <strong>Membajak Sawah dengan Kerbau</strong>\n• 🍈 <strong>Petik Salak Pondoh</strong>\n• 🎨 <strong>Belajar Membatik Tulis</strong>\n• 🎵 <strong>Bermain Gamelan Jawa</strong>\n• 🏡 <strong>Live-In Homestay</strong>\n\nCoba ketik aktivitas spesifik yang Anda minati. 😊',
            pkg_card_title: 'Berikut paket wisata yang tersedia:',
            act_card_title: 'Berikut aktivitas seru yang tersedia:',
        },
        en: {
            BOT_NAME: 'Mas Pandu',
            BOT_INITIAL: 'MP',
            USER_INITIAL: 'Y',
            WELCOME: `Hello! 🙏 I'm <strong>Mas Pandu</strong>, the official virtual assistant of Gabugan Tourism Village, Sleman, Yogyakarta.<br><br>Nice to meet you! How can I help you with your visit?<br><br>I can provide complete information about:<br>• 🌿 Salak Pondoh Agro-tourism Packages<br>• 🌾 Rice field activities & agricultural education<br>• 🎨 Batik, gamelan & Javanese culture<br>• 🏡 Homestay (Live-in) experience<br>• 🍲 Traditional culinary at the foot of Merapi<br><br>Please choose a question below or just type what you want to know! 😊`,
            CONTACT: `<br><br>📞 <strong>Need more details? Contact our admin:</strong><br><a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a> — Our team is ready to help! 😊`,
            greeting: `Hello! 🙏\n\nI'm <strong>Mas Pandu</strong>, the official virtual assistant of <strong>Gabugan Tourism Village</strong>, Sleman, Yogyakarta.\n\nI'm ready to help you with information about tour packages, homestays, exciting activities, prices, location, and anything you want to know about our village! 🏡🌄\n\nHow can I assist you? 😊`,
            smalltalk: `I'm Mas Pandu, the official virtual assistant of Gabugan Tourism Village! 🧑‍🌾\n\nI can help you with information about:\n• 🌿 Tour Packages & Education\n• 🏡 Homestay & Live-in\n• 🍲 Local Culinary\n• 🎨 Arts & Culture Activities\n• 🚗 Routes & Transportation\n\nWhat would you like to know? 😊`,
            booking: `Thank you for your interest in visiting Gabugan Tourism Village! 🙏\n\nHere are the <strong>booking steps</strong>:\n\n📌 <strong>Step 1: Choose a Package</strong>\nSelect your preferred package (Agro Education, Agriculture, Arts & Culture, Outbound, One-Day Tour, or Live-In Homestay).\n\n📌 <strong>Step 2: Contact Admin</strong>\nConfirm availability via WhatsApp:\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>\n\n📌 <strong>Step 3: Participant Details</strong>\nProvide your name, chosen package, number of participants, and planned arrival date.\n\n📌 <strong>Step 4: Down Payment</strong>\nTransfer 30-50% DP as instructed by admin.\n\n📌 <strong>Step 5: Final Payment</strong>\nPay the remaining balance at the location on the day of your visit. 🎉\n\nFeel free to contact us via WhatsApp! 😊`,
            harga: `Tour package prices:\n\n• 🌿 <strong>Salak Pondoh Agro Education</strong> — Rp 150,000/person\n• 🌾 <strong>Agriculture & Rice Field</strong> — Rp 175,000/person\n• 🎨 <strong>Javanese Arts & Culture</strong> — Rp 150,000/person\n• 🏡 <strong>Live-In Homestay</strong> — Rp 250,000/person\n• 📋 <strong>One-Day Tour Gabugan</strong> — Rp 175,000/person\n\nPrices include local guide and meals. <strong>Special prices</strong> for groups! 💰`,
            kontak: `Contact us:\n\n📞 <strong>WhatsApp:</strong> <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>\n📧 <strong>Email:</strong> info@desawisatagabugan.com\n📸 <strong>Instagram:</strong> @desawisatagabugan\n\n📍 Address: Gabugan, Donokerto, Turi, Sleman, DIY\n\nOperating Hours:\n🕐 <strong>Every Day</strong> — 08:00 - 17:00 WIB\n🏡 Homestay 24 Hours (by request)`,
            kuliner: `Local culinary at Gabugan! 🍲\n\n<strong>Food:</strong>\n• 🥘 Sayur Lodeh Tewel — Young jackfruit vegetable soup\n• 🌶️ Sambal Belut Goreng — Crispy eel with spicy sambal\n• 🍚 Nasi Liwet — Fragrant coconut rice\n• 🥗 Urap-urap — Vegetables with spiced coconut\n\n<strong>Snacks & Drinks:</strong>\n• 🍬 Salak Pondoh Candy — Sweet souvenirs\n• ☕ Wedang Jahe — Warm ginger drink\n\nAll ingredients are fresh from local gardens! 😊`,
            transportasi: `Transportation to Gabugan! 🚗\n\n<strong>From Yogyakarta City:</strong>\n🚗 <strong>Car</strong> — ±45 mins (17 km) via Jl. Kaliurang km 17\n🛵 <strong>Motorcycle</strong> — ±35 mins\n🚌 <strong>Bus</strong> — From Terminal Jombor to Turi\n\n<strong>From YIA Airport:</strong>\n🚗 ±1.5 hours via Jogja-Solo toll road\n\n<strong>From Tugu Station:</strong>\n🚗 ±40 mins via Jl. Kaliurang\n\n<strong>Tips:</strong> Paved road, accessible for big buses. Spacious parking! 🅿️`,
            waktu: `Opening hours & best time to visit! ⏰\n\n<strong>Opening Hours:</strong>\n🕐 Every Day — 08:00 - 17:00 WIB\n🏡 Homestay/Live-In — 24 hours\n\n<strong>Best Time:</strong>\n🌤️ <strong>Dry Season (April-October)</strong> — Sunny, great for outdoor\n🌧️ <strong>Rainy Season (Nov-March)</strong> — Indoor activities (batik, gamelan)`,
            fasilitas: `Facilities at Gabugan Tourism Village! 🏗️\n\n<strong>General:</strong>\n✅ Spacious parking (up to 5 big buses)\n✅ Prayer room / Mosque\n✅ Clean public toilets\n✅ Gazebos with rice field views\n✅ Changing rooms\n\n<strong>Homestay:</strong>\n✅ Clean bedrooms\n✅ Private bathroom\n✅ Mineral water & tea/coffee\n✅ Friendly host family\n\n<strong>Supporting:</strong>\n✅ Stable 4G signal\n✅ First aid kit\n✅ Experienced local guides\n✅ Large dining area`,
            budaya: `Culture & traditions you can learn! 🎭\n\n<strong>Arts & Culture:</strong>\n🎵 Gamelan & Karawitan\n🎨 Batik Making\n💃 Javanese Dance\n🥁 Jathilan Folk Art\n\n<strong>Local Traditions:</strong>\n🌾 Bumi Selamatan — Annual harvest ritual\n🙏 Village Cleansing — Traditional ceremony\n🍚 Kenduri — Communal feast`,
            jogja: `Attractions around Yogyakarta! 🏛️\n\n<strong>Near Gabugan:</strong>\n🌋 Kaliurang & Kaliadem — ±15 mins\n🏛️ Mount Merapi Museum — ±20 mins\n🌲 Bukit Bintang — ±25 mins\n\n<strong>City Center (±45 mins):</strong>\n🛍️ Malioboro Street\n🏯 Yogyakarta Palace\n🌊 Parangtritis Beach — ±1 hour`,
            cuaca: `Weather in Gabugan area! 🌤️\n\n🌡️ <strong>Temperature:</strong>\nDay: 24-30°C | Night: 18-22°C | Morning: 20-24°C\n\n<strong>Season:</strong>\n☀️ Dry (April-October) — Great for outdoor\n🌧️ Rainy (Nov-March) — Bring umbrella\n\n<strong>Tips:</strong> Bring a jacket for the evening 🧥`,
            foto: `Cool photo spots in Gabugan! 📸\n\n🌾 Rice Terraces — Green with Merapi background\n🏡 Joglo Homestay — Traditional architecture\n🍈 Salak Garden — Photo among fruit trees\n🌅 Rice Field Paths — Magical golden hour\n🎨 Batik Area — Aesthetic batik process\n\n<strong>Tips:</strong> Best time 06:00-08:00 or 15:00-17:00. Drone allowed!`,
            rombongan: `Group visit information! 👥\n\n✅ <strong>Small Group (10-30 people):</strong>\nPerfect for educational tours, salak picking, batik, live-in. Warm and private!\n\n✅ <strong>Large Group (30-500 people):</strong>\nSuitable for Gathering, Outbound, Study Tour. Recommended coordination H-7.\n\n🎯 <strong>Benefits:</strong>\n• Special prices / discounts\n• Customizable itinerary\n• Full local guide assistance\n\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a>`,
            testimonial: `Visitor testimonials! ⭐\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Amazing experience! The kids loved learning to plow rice fields and pick salak."</em> — <strong>Ms. Sari</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Comfortable homestay, delicious food, miss the atmosphere already."</em> — <strong>Mas Dito</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"The batik is beautiful! You can make your own."</em> — <strong>Ms. Rina</strong>\n\n<strong>Rating:</strong> ⭐ 4.9/5 from 200+ visitors!`,
            anak: `Visiting with children & family! 👨‍👩‍👧‍👦\n\n🧒 Buffalo Plowing — Kid-friendly buffalo\n🎨 Simple Batik — Easy patterns for kids\n🍈 Salak Picking — Pick fruit from trees\n🌾 Playing in Rice Fields — Splash & fun\n🐄 Feeding Livestock — Goats, chickens, buffalo\n\n<strong>Tips:</strong> Bring change of clothes & mosquito repellent!`,
            persiapan: `Preparation tips! 🎒\n\n👕 Change of clothes\n👟 Hiking sandals/shoes\n🧴 Sunscreen & hat\n☂️ Umbrella/raincoat\n🧥 Jacket/sweater\n📱 Power bank\n💊 Personal medicine`,
            pujian: `Thank you so much! 🙏😊\n\nWe're glad to hear your appreciation! Mas Pandu and the <strong>Gabugan Tourism Village</strong> team always strive to provide the best service.\n\nAnything else you'd like to know? 🏡🌾`,
            negatif: `We apologize if anything was unsatisfactory 🙏\n\nWe always strive to improve our service quality. Please share your feedback directly:\n\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a>`,
            tentang: `About Gabugan Tourism Village! 🏡\n\n<strong>Location:</strong> Gabugan Hamlet, Donokerto, Turi, Sleman, DIY\n\n<strong>Overview:</strong>\n🌄 Western slope of Mount Merapi, ±500 masl\n🌾 Managed by local Pokdarwis\n🤝 Community Based Tourism concept\n🏆 Best tourism village award in DIY\n\nWelcome to Gabugan, your second home on the slopes of Merapi! 🌋✨`,
            promo: `Promos & special prices! 💰\n\n🎉 <strong>Group Promo</strong> — 10-20% off (30+ pax)\n🎉 <strong>Early Bird</strong> — Book H-14 for special price\n🎉 <strong>Weekday Special</strong> — More affordable Mon-Fri\n\n<strong>Regular Prices:</strong>\n• 🌿 Salak Agro Education — Rp 150,000/person\n• 🌾 Agriculture Education — Rp 175,000/person\n• 🎨 Arts & Culture — Rp 150,000/person\n• 🏡 Live-In Homestay — Rp 250,000/person\n• 📋 One-Day Tour — Rp 175,000/person`,
            bahasa: `Information for foreign tourists! 🌏\n\n<strong>English Service Available!</strong>\nDesa Wisata Gabugan welcomes international visitors. Some of our guides can speak English.\n\n<strong>Activities:</strong>\n🌾 Rice Field Experience\n🍈 Salak Pondoh Harvesting\n🎨 Batik Making\n🎵 Gamelan Music\n🏡 Homestay with Local Family`,
            kesehatan: `Health & safety information! 🏥\n\n<strong>CHSE Certified ✅</strong>\nCleanliness, Health, Safety & Environmental protocols implemented.\n\n<strong>Health Facilities:</strong>\n🏥 Turi Clinic — ±10 mins\n🏥 PKU Muhammadiyah Hospital — ±20 mins\n🏥 Sardjito Hospital — ±30 mins (referral)`,
            souvenir: `Souvenirs from Gabugan! 🛍️\n\n🍈 Fresh Salak Pondoh — Pick your own\n🍬 Salak Candy — Sweet souvenirs\n🥨 Salak Chips — Crispy snack\n🎨 Local Batik Fabric\n🧺 Bamboo & Mendong Weaving`,
            merapi: `Mount Merapi Information! 🌋\n\n📍 Gabugan on western slope, ±12 km from peak\n🌄 Merapi view is clear from the village\n\n<strong>Activities:</strong>\n🏛️ Mount Merapi Museum — ±20 mins\n🚙 Jeep Lava Tour — Explore lahar flows\n🌲 Kaliadem & Bunker — 2010 eruption site`,
            akomodasi: `Accommodation around Gabugan! 🏨\n\n<strong>Inside Village:</strong>\n🏡 Local Homestay — From Rp 250,000/person (incl. meals & activities)\n\n<strong>Nearby (10-20 mins):</strong>\n🏨 Kaliurang Hotels & Resorts\n🏠 Kaliurang Villas\n🛌 Turi Guest Houses`,
            livein: `Live-In / Homestay Information! 🏡\n\n<strong>What is Live-In?</strong>\nStay directly with local families and experience daily village life. You'll live with a <strong>host family</strong> and participate in their daily activities.\n\n<strong>Facilities:</strong>\n✅ Clean bedroom with bed sheets\n✅ Private bathroom\n✅ Home-cooked meals 3x daily\n✅ Mineral water & tea/coffee\n✅ Friendly host family\n✅ Local guide\n✅ Electricity & lighting\n✅ Parking area\n\n<strong>Activities:</strong>\n🌾 Morning — Feed livestock, breakfast\n🌾 Noon — Plow fields, plant rice, pick salak\n🎨 Afternoon — Batik or gamelan\n🌙 Evening — Bonfire, coffee, chat\n\n<strong>Price:</strong> From Rp 250,000/person\n\n📞 <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252 (WhatsApp)</a>`,
            malam: `Night activities in Gabugan! 🌙\n\n🔥 Bonfire — Roast corn/sweet potato\n🎵 Night Gamelan — Soulful music\n🌌 Stargazing — Light pollution free\n☕ Night Coffee — Warm ginger drink at the rice field gazebo`,
            sawah: `Rice field activities! 🌾\n\n🐃 Buffalo Plowing — Experience being a farmer\n🌱 Planting Rice — Learn proper techniques\n🌾 Harvesting Rice — During harvest season\n\n<strong>Tips:</strong> Bring change of clothes!`,
            salak: `Salak Pondoh Info! 🍈\n\n🌴 Sleman's signature fruit, sweet & crispy\n🌴 Organically cultivated\n🌴 Peak harvest: December - March\n\n<strong>Activity:</strong>\n🍈 Pick directly from the tree\n🍈 Taste at the garden\n\n✅ Rich in antioxidants\n✅ Good for digestion`,
            batik: `Learn batik making! 🎨\n\n🖌️ Traditional Canting Batik\n🖌️ Stamp Batik — Faster motifs\n🖌️ Tie-dye Batik — For beginners\n\n✅ ±1 meter fabric to take home\n✅ Participation certificate\n\n<strong>Gabugan Special Motifs:</strong>\n🌾 Rice terraces 🍈 Salak 🌋 Merapi`,
            gamelan: `Learn Gamelan! 🎵\n\n🎹 Saron — Bright sounding metal bars\n🎹 Bonang — Small gong set\n🥁 Kendang — Rhythm keeper\n🔔 Gong — Song end marker\n\nGamelan teaches <em>"harmony"</em> — every instrument completes each other.`,
            river: `River Trekking! 🌊\n\n🚶 Explore clear streams from Merapi springs\n🚶 Fresh & shallow water\n🚶 Accompanied by local guide\n\n<strong>Tips:</strong> Wear anti-slip shoes, bring change of clothes!`,
            outbound: `Outbound & Team Building! 🎯\n\n🧗 Flying Fox — Glide over rice fields\n🕸️ Spider Web — Team game\n🏃 Water Relay — Fun splash game\n\n<strong>Suitable for:</strong>\n🏢 Company gathering\n🎓 Student orientation\n👨‍👩‍👧‍👦 Family gathering`,
            prewedding: `Pre-wedding Photo Package! 💑\n\n💑 Rice Terraces — Merapi backdrop\n💑 Joglo Homestay — Romantic Javanese vibe\n💑 Salak Garden — Natural backdrop\n💑 Rice Field Paths — Magical golden hour\n\n💰 From Rp 500,000 (spot access)`,
            event: `Events at Gabugan! 🎪\n\n🌾 Salak Pondoh Festival — Annual\n🎭 Traditional Arts Performance\n🏃 Fun Trail & Adventure Run\n🍲 Traditional Culinary Festival\n\n✅ Open area capacity: 500 people`,
            random: `Great question! 😊\n\n<strong>Gabugan Tourism Village</strong> is an educational and cultural tourism destination on the slopes of Merapi, perfect for:\n• 🌿 <strong>Healing</strong> — Peaceful rural atmosphere<br>• 📚 <strong>Learning</strong> — Interactive farming, batik, gamelan<br>• 👨‍👩‍👧‍👦 <strong>Gathering</strong> — Family, friends, colleagues<br>• 📸 <strong>Photography</strong> — Instagrammable Merapi backdrop<br><br>Any specific topic you'd like to ask? 😊`,
            fallback: `Sorry 🙏, I didn't quite understand your question.\n\nI can provide complete information about:\n\n🌾 <strong>Rice Fields & Farming</strong> (plowing, planting rice)\n🍈 <strong>Salak Pondoh Picking</strong> (agro education)\n🎨 <strong>Batik & Gamelan</strong> (Javanese culture)\n🏡 <strong>Homestay & Live-in</strong> (village stay)\n🚗 <strong>Location, Route & Opening Hours</strong>\n\nTry typing a keyword like <strong>"package"</strong>, <strong>"food"</strong>, or <strong>"price"</strong>. 😊`,
            pkg_header: 'Here are the featured tour packages from Gabugan Tourism Village! 🌟\n\nClick the cards below for complete details:',
            pkg_fallback: 'Here are our featured packages:\n\n• 🌿 <strong>Salak Pondoh Agro Education</strong> — From Rp 150,000/person\n• 🌾 <strong>Agriculture Education</strong> — From Rp 175,000/person\n• 🎨 <strong>Javanese Arts & Culture</strong> — From Rp 150,000/person\n• 🏡 <strong>Live-In Homestay</strong> — From Rp 250,000/person\n\nInterested in any? 😊',
            act_header: 'Here are the exciting activities you can do at Gabugan Tourism Village! 🎯\n\nClick the cards below for complete details:',
            act_fallback: 'Here are activities you can join:\n\n• 🌾 <strong>Buffalo Plowing</strong>\n• 🍈 <strong>Salak Pondoh Picking</strong>\n• 🎨 <strong>Batik Making</strong>\n• 🎵 <strong>Gamelan Music</strong>\n• 🏡 <strong>Live-In Homestay</strong>\n\nTry asking about a specific activity. 😊',
            pkg_card_title: 'Available tour packages:',
            act_card_title: 'Available activities:',
        }
    };

    function t(key) { return LANG[locale] ? (LANG[locale][key] || LANG['id'][key] || '') : (LANG['id'][key] || ''); }

    // ========================
    // DOM & HELPER FUNCTIONS
    // ========================
    function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
    function formatBold(text) { return text.replace(/\*(.*?)\*/g, '<strong>$1</strong>'); }
    function showTyping() { typingIndicator.classList.remove('hidden'); scrollToBottom(); }
    function hideTyping() { typingIndicator.classList.add('hidden'); }

    function addBotMessage(html) {
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 ai-message';
        const initial = t('BOT_INITIAL');
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">${initial}</div>
            <div class="bot-bubble bg-white border border-neutral-100 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-neutral-700 shadow-sm leading-relaxed">${html}</div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function addPackageCards(packages) {
        if (!packages || packages.length === 0) return false;
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 ai-message';
        let cardsHtml = packages.map(p => {
            const img = p.image || 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=200&auto=format&fit=crop';
            const imgError = "this.onerror=null;this.src='https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=200&auto=format&fit=crop'";
            return `<a href="${p.url}" target="_blank" class="block bg-white border border-neutral-200 rounded-xl overflow-hidden hover:shadow-md transition mb-2">
                <div class="flex gap-3 p-2">
                    <img src="${img}" onerror="${imgError}" class="w-16 h-16 rounded-lg object-cover shrink-0" alt="${p.type}">
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-[#053d2c] leading-tight">${p.type}</h4>
                        <p class="text-[10px] text-neutral-500 mt-0.5 line-clamp-2">${p.description}</p>
                        <span class="text-xs font-bold text-[#00a877] mt-1 block">Rp ${p.price} /${locale === 'en' ? 'person' : 'orang'}</span>
                    </div>
                </div>
            </a>`;
        }).join('');
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">${t('BOT_INITIAL')}</div>
            <div class="bot-bubble bg-white border border-neutral-100 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-neutral-700 shadow-sm leading-relaxed w-full max-w-[280px]">
                <p class="mb-2 font-medium">${t('pkg_card_title')}</p>
                ${cardsHtml}
            </div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
        return true;
    }

    function addActivityCards(activities) {
        if (!activities || activities.length === 0) return false;
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 ai-message';
        const mid = Math.ceil(activities.length / 2);
        const leftCol = activities.slice(0, mid);
        const rightCol = activities.slice(mid);
        let colHtml = (items) => {
            return items.map(a => {
                const img = a.image || 'https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=200&auto=format&fit=crop';
                const imgError = "this.onerror=null;this.src='https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=200&auto=format&fit=crop'";
                return `<a href="${a.url}" target="_blank" class="block bg-white border border-neutral-200 rounded-xl overflow-hidden hover:shadow-md transition mb-2">
                    <div class="h-20 overflow-hidden">
                        <img src="${img}" onerror="${imgError}" class="w-full h-full object-cover" alt="${a.title}">
                    </div>
                    <div class="p-2">
                        <h4 class="text-xs font-bold text-[#053d2c] leading-tight">${a.title}</h4>
                        <p class="text-[10px] text-neutral-500 mt-0.5 line-clamp-2">${a.description}</p>
                        ${a.duration ? '<span class="text-[10px] text-[#00a877] mt-1 block"><i class="bx bx-time"></i> ' + a.duration + '</span>' : ''}
                    </div>
                </a>`;
            }).join('');
        };
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">${t('BOT_INITIAL')}</div>
            <div class="bot-bubble bg-white border border-neutral-100 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-neutral-700 shadow-sm leading-relaxed w-full max-w-[320px]">
                <p class="mb-2 font-medium">${t('act_card_title')}</p>
                <div class="grid grid-cols-2 gap-2">
                    <div>${colHtml(leftCol)}</div>
                    <div>${colHtml(rightCol)}</div>
                </div>
            </div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
        return true;
    }

    function addUserMessage(text) {
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 flex-row-reverse';
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#053d2c] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">${t('USER_INITIAL')}</div>
            <div class="bg-[#00a877] text-white rounded-2xl rounded-tr-none px-4 py-3 text-sm shadow-sm leading-relaxed">${text}</div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function formatWA(text) {
        return text.replace(/\+\d[\d\s-]+/g, function(match) {
            const phone = match.replace(/[\s-]/g, '');
            return `<a href="https://api.whatsapp.com/send?phone=${phone.replace('+', '')}" target="_blank" class="text-[#00a877] font-semibold hover:underline">${match}</a>`;
        });
    }

    function nl2br(text) { return text.replace(/\n/g, '<br>'); }
    function makeResponse(text) { return nl2br(formatWA(formatBold(text))); }

    // ========================
    // KNOWLEDGE BASE (shared across languages)
    // ========================
    const knowledgeBase = {
        booking: ['pesan', 'booking', 'order', 'reservasi', 'daftar', 'langkah pesan', 'cara pesan', 'prosedur', 'langkah-langkah', 'carane', 'beli', 'gimana cara', 'bagaimana cara'],
        paket: ['paket', 'pilihan wisata', 'daftar paket', 'lihat paket', 'tampilkan paket', 'paket wisata', 'wisata apa aja', 'opsi', 'package', 'tour package', 'list package'],
        aktivitas: ['aktivitas', 'kegiatan', 'aktivitas seru', 'kegiatan seru', 'aktivitas wisata', 'bisa ngapain', 'ngapain aja', 'bisa apa', 'yang seru', 'hal yang bisa', 'melakukan', 'main', 'bermain', 'things to do', 'activity', 'exciting activities', 'what can i do'],
        harga: ['harga', 'biaya', 'tarif', 'rp', 'bayar', 'ongkos', 'diskon', 'promo', 'murah', 'mahal', 'pembayaran', 'transfer', 'dp', 'price', 'cost', 'how much'],
        kontak: ['kontak', 'hubungi', 'wa', 'whatsapp', 'nomor', 'telepon', 'call', 'email', 'ig', 'instagram', 'medsos', 'admin', 'tanya langsung', 'contact', 'phone'],
        greeting: ['halo', 'hai', 'hi', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum', 'hey', 'helo', 'test', 'coba', 'hello', 'good morning', 'good afternoon'],
        smalltalk: ['siapa', 'kamu', 'nama', 'pandu', 'mas pandu', 'mana', 'robot', 'ai', 'manusia', 'hebat', 'pintar', 'bisa apa', 'fitur', 'bantuan', 'who are you', 'your name', 'help'],
        kuliner: ['kuliner','makanan','makan','masakan','jajanan','camilan','makan siang','makan malam','sarapan','sayur','lauk','sambal','belut','lodeh','tewel','nasi','gudeg','bakpia','wedang','minuman','jahe','makanan khas','oleh-oleh','food','culinary','eat','cuisine','local food'],
        transportasi: ['transportasi','kendaraan','mobil','motor','bus','travel','ojek','grab','gojek','angkot','bandara','stasiun','terminal','sampai','perjalanan','naik apa','lokasi','akses','rute','jalur','letak','alamat','kesana','transportation','car','road','route','location','address','how to get','direction','way'],
        waktu: ['jam','waktu','jadwal','buka','tutup','kapan','hari','weekend','libur','liburan','weekday','senin','selasa','rabu','kamis','jumat','sabtu','minggu','bulan','tahun','musim','kemarau','hujan','time','hour','schedule','open','close','when','day','month','season'],
        fasilitas: ['fasilitas','toilet','mushola','masjid','parkir','tempat parkir','saung','gazebo','wifi','musala','tempat ibadah','ruang ganti','bilas','kamar mandi','facility','toilet','parking','mosque','prayer'],
        budaya: ['budaya','tradisi','adat','jawa','kejawen','ritual','upacara','selamatan','wayang','ketoprak','seni','tari','tarian','culture','tradition','javanese','art','dance','wayang'],
        jogja: ['jogja','yogyakarta','sleman','turi','merapi','malioboro','prambanan','borobudur','kraton','taman sari','bantul','pantai','parangtritis','kaliurang','kaliadem'],
        cuaca: ['cuaca','udara','suhu','dingin','panas','sejuk','hawa','iklim','musim hujan','musim kemarau','berawan','cerah','weather','temperature','cold','hot','rainy','dry','season'],
        foto: ['foto','fotografi','spot foto','instagram','selfie','berfoto','memotret','kamera','video','photo','photography','instagramable','picture','spot'],
        rombongan: ['rombongan','group','kelompok','sekolah','kampus','mahasiswa','pelajar','siswa','pkl','study tour','outbound','gathering','perusahaan','karyawan','peserta','pax','orang','jumlah','kapasitas','group','school','university','student','company'],
        testimonial: ['testimoni','review','ulasan','pengalaman','kesan','rating','bintang','komentar','cerita','testimonial','review','experience','rating','comment'],
        anak: ['anak','anak-anak','keluarga','bayi','balita','orang tua','lansia','family','kids','child','children'],
        persiapan: ['pakaian','baju','sepatu','sandal','topi','sunscreen','persiapan','bawaan','tas','jaket','payung','jas hujan','perlengkapan','siap','tips','preparation','bring','pack','clothes','shoes','jacket'],
        pujian: ['terima kasih','makasih','thanks','thank','mantap','keren','bagus','hebat','kagum','wow','nice','good','awesome','sip','ok'],
        negatif: ['jelek','buruk','tidak suka','kecewa','mengecewakan','mahal sekali','gak worth','tidak worth','ngapain','boring','gak seru','bad','disappointed','expensive','not worth'],
        tentang: ['tentang','sejarah','desa','gabugan','donokerto','berdiri','awal','latar belakang','visi','misi','profil','about','history','village','information'],
        promo: ['promo','diskon','potongan','hemat','murah','gratis','free','spesial','special offer','bundling','discount','promo','cheap','deal'],
        bahasa: ['bahasa inggris','english','speak english','foreigner','bule','turis asing','international','foreign','translate','language'],
        kesehatan: ['kesehatan','keamanan','aman','nyaman','chse','protokol','covid','prokes','masker','vaksin','safety','health','clinic','hospital','first aid'],
        souvenir: ['souvenir','oleh-oleh','buah tangan','cinderamata','belanja','shopping','salak','manisan salak','keripik salak','kain','kerajinan','souvenir','gift','shopping','handicraft'],
        merapi: ['merapi','gunung','gunung merapi','erupsi','letusan','bencana','lahar','museum merapi','kaliadem','merapi','mount','volcano','eruption','museum'],
        livein: ['live in','live-in','livein','homestay','menginap','penginapan','live in desa','nginap di rumah','family stay','host family','keluarga angkat','fasilitas homestay','fasilitas live in'],
        akomodasi: ['hotel','villa','resort','losmen','penginapan murah','guest house','penginapan sekitar','hotel dekat','lodging','accommodation','kamar','tidur'],
        malam: ['malam','malam hari','bonfire','api unggun','ngopi','nongkrong','jalan malam','night','night tour','evening'],
        sawah: ['sawah','pertanian','petani','bertani','padi','tanam','panen','membajak','kerbau','rice field','farming','farmer','rice','plant','harvest','plow','buffalo'],
        salak: ['salak','salak pondoh','buah salak','petik salak','kebun salak','manisan salak','salak goreng','snake fruit','salak fruit','fruit picking'],
        batik: ['batik','batik tulis','batik cap','membatik','canting','malam','kain batik','motif batik'],
        gamelan: ['gamelan','karawitan','saron','bonang','gong','kendang','musik jawa','musik tradisional','gamelan','javanese music','traditional music'],
        river: ['river','trekking','sungai','susur sungai','tracking','hiking','air terjun','curug','kali','trekking','river','waterfall','hiking'],
        outbound: ['outbound','outbond','game','permainan','team building','flying fox','jaring laba','tali'],
        prewedding: ['prewedding','prewed','foto nikah','foto pernikahan','wedding','engagement','prewedding','wedding photo','engagement'],
        event: ['event','acara','festival','lomba','perlombaan','karnaval','pesta','event','festival','competition'],
        random: ['hobi','liburan','rekreasi','refreshing','healing','jalan-jalan','travelling','trip','tour','berlibur','cuti','weekend','staycation','holiday','vacation','recreation','hobby']
    };

    // ========================================
    // SMART PARSING ENGINE
    // ========================================
    function cleanInput(text) {
        if (!text) return '';
        return text.toLowerCase()
            .replace(/[^\w\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function findBestCategory(input) {
        const cleaned = cleanInput(input);
        if (!cleaned) return null;

        let scores = {};
        for (const [category, keywords] of Object.entries(knowledgeBase)) {
            scores[category] = 0;
            for (const keyword of keywords) {
                const keywordClean = cleanInput(keyword);
                if (cleaned.includes(keywordClean)) {
                    scores[category] += keywordClean.length;
                    const isExactWord = cleaned.split(' ').includes(keywordClean) ||
                        cleaned.startsWith(keywordClean + ' ') ||
                        cleaned.endsWith(' ' + keywordClean) ||
                        cleaned === keywordClean;
                    if (isExactWord) scores[category] += 5;
                }
            }
        }

        const infoCategories = [
            'booking', 'paket', 'aktivitas', 'harga', 'kontak', 'kuliner', 'transportasi', 'waktu',
            'fasilitas', 'budaya', 'jogja', 'cuaca', 'foto', 'rombongan', 'testimonial',
            'anak', 'persiapan', 'tentang', 'promo', 'bahasa', 'kesehatan', 'souvenir',
            'merapi', 'livein', 'akomodasi', 'malam', 'sawah', 'salak', 'batik', 'gamelan',
            'river', 'outbound', 'prewedding', 'event'
        ];
        const convCategories = ['greeting', 'smalltalk', 'pujian', 'negatif', 'random'];

        let bestInfoCat = null, maxInfoScore = 0;
        infoCategories.forEach(cat => { if (scores[cat] > maxInfoScore) { maxInfoScore = scores[cat]; bestInfoCat = cat; } });

        let bestConvCat = null, maxConvScore = 0;
        convCategories.forEach(cat => { if (scores[cat] > maxConvScore) { maxConvScore = scores[cat]; bestConvCat = cat; } });

        if (maxInfoScore >= 3) return bestInfoCat;
        if (maxConvScore >= 3) return bestConvCat;
        if (maxInfoScore > 0) return bestInfoCat;
        if (maxConvScore > 0) return bestConvCat;
        return null;
    }

    function simulateResponse(input) {
        showTyping();
        const delay = 1000 + Math.random() * 1000;
        const category = findBestCategory(input);

        setTimeout(() => {
            hideTyping();

            if (category === 'paket') {
                addBotMessage(makeResponse(t('pkg_header')));
                if (!addPackageCards(window.chatPackages)) {
                    addBotMessage(makeResponse(t('pkg_fallback')));
                }
                return;
            }

            if (category === 'aktivitas') {
                addBotMessage(makeResponse(t('act_header')));
                if (!addActivityCards(window.chatActivities)) {
                    addBotMessage(makeResponse(t('act_fallback')));
                }
                return;
            }

            if (category && t(category)) {
                addBotMessage(makeResponse(t(category)));
                return;
            }

            addBotMessage(makeResponse(t('fallback')));
        }, delay);
    }

    // ========================
    // EVENT HANDLERS
    // ========================
    function sendMessage(text) {
        if (!text.trim()) return;
        addUserMessage(text);
        chatInput.value = '';
        simulateResponse(text);
    }

    addBotMessage(t('WELCOME'));

    quickBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sendMessage(this.textContent.trim());
        });
    });

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage(chatInput.value);
    });

    // Panel toggle
    const overlay = document.getElementById('ai-chat-overlay');

    function openChat() {
        overlay.classList.remove('hidden');
        chatPanel.classList.remove('translate-x-full');
        chatToggle.classList.add('hidden');
        document.body.classList.add('overflow-hidden');
        scrollToBottom();
    }

    function closeChat() {
        chatPanel.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        chatToggle.classList.remove('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    chatToggle.addEventListener('click', function() {
        chatPanel.classList.contains('translate-x-full') ? openChat() : closeChat();
    });

    if (chatToggle2) {
        chatToggle2.addEventListener('click', function() {
            chatPanel.classList.contains('translate-x-full') ? openChat() : closeChat();
        });
    }

    chatClose.addEventListener('click', closeChat);
    overlay.addEventListener('click', closeChat);
});
</script>