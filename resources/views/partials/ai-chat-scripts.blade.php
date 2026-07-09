{{-- 
=======================================
 AI CHAT ASSISTANT — JAVASCRIPT (UPDATED)
=======================================
 All chat logic: toggle, messages, knowledge base, responses
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

    const WELCOME_MSG = `Sugeng Rawuh! 🙏 Kulo <strong>Mas Pandu</strong>, asisten virtual resmi Desa Wisata Gabugan, Sleman, Yogyakarta.<br><br>Senang sekali bisa menyambut Anda secara virtual! Ada yang bisa kulo bantu mengenai kunjungan Anda?<br><br>Kulo sedia memberikan informasi terlengkap tentang:<br>• 🌿 Paket Agrowisata Salak Pondoh<br>• 🌾 Aktivitas sawah & edukasi pertanian<br>• 🎨 Belajar batik tulis, gamelan, dan budaya Jawa<br>• 🏡 Pengalaman menginap (Live-in) di Homestay Rumah warga<br>• 🍲 Kuliner ndeso khas pedesaan lereng Merapi<br><br><em>Monggo</em>, silakan pilih pertanyaan di bawah atau ketik langsung apa yang ingin Anda ketahui! 😊`;

    // ========================
    // HELPER FUNCTIONS
    // ========================
    function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
    function formatBold(text) { return text.replace(/\*(.*?)\*/g, '<strong>$1</strong>'); }
    function showTyping() { typingIndicator.classList.remove('hidden'); scrollToBottom(); }
    function hideTyping() { typingIndicator.classList.add('hidden'); }

    function addBotMessage(html) {
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 ai-message';
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">MP</div>
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
            return `<a href="${p.url}" target="_blank" class="block bg-white border border-neutral-200 rounded-xl overflow-hidden hover:shadow-md transition mb-2">
                <div class="flex gap-3 p-2">
                    <img src="${img}" class="w-16 h-16 rounded-lg object-cover shrink-0" alt="${p.type}">
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-[#053d2c] leading-tight">${p.type}</h4>
                        <p class="text-[10px] text-neutral-500 mt-0.5 line-clamp-2">${p.description}</p>
                        <span class="text-xs font-bold text-[#00a877] mt-1 block">Rp ${p.price} /orang</span>
                    </div>
                </div>
            </a>`;
        }).join('');
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">MP</div>
            <div class="bot-bubble bg-white border border-neutral-100 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-neutral-700 shadow-sm leading-relaxed w-full max-w-[280px]">
                <p class="mb-2 font-medium">Berikut paket wisata yang tersedia:</p>
                ${cardsHtml}
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
            <div class="w-8 h-8 bg-[#053d2c] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">K</div>
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
    // KNOWLEDGE BASE (UPDATED WITH KEYWORD ROBUSTNESS)
    // ========================
    const knowledgeBase = {
        booking: ['pesan', 'booking', 'order', 'reservasi', 'daftar', 'langkah pesan', 'cara pesan', 'prosedur', 'langkah-langkah', 'carane', 'tumbas', 'beli', 'gimana cara', 'bagaimana cara'],
        paket: ['paket', 'pilihan wisata', 'daftar paket', 'lihat paket', 'tampilkan paket', 'paket wisata', 'wisata apa aja', 'aktivitas', 'kegiatan', 'opsi'],
        harga: ['harga', 'biaya', 'tarif', 'rp', 'bayar', 'ongkos', 'diskon', 'promo', 'murah', 'mahal', 'pembayaran', 'transfer', 'dp', 'price', 'cost'],
        kontak: ['kontak', 'hubungi', 'wa', 'whatsapp', 'nomor', 'telepon', 'call', 'email', 'ig', 'instagram', 'medsos', 'sosmed', 'admin'],
        greeting: ['halo', 'hai', 'hi', 'pagi', 'siang', 'sore', 'malam', 'sugeng rawuh', 'assalamualaikum', 'hey', 'helo', 'test', 'coba'],
        smalltalk: ['siapa', 'kamu', 'nama', 'jenenge', 'pandu', 'mas pandu', 'asli', 'mana', 'robot', 'ai', 'manusia', 'hebat', 'pintar', 'bisa apa', 'fitur', 'bantuan'],
        kuliner: ['kuliner','makanan','makan','masakan','jajanan','camilan','makan siang','makan malam','sarapan','sayur','lauk','sambal','belut','lodeh','tewel','nasi','gudeg','bakpia','geplak','wedang','minuman','jahe','beras kencur','makanan khas','oleh-oleh','oleholeh'],
        transportasi: ['transportasi','kendaraan','mobil','motor','bus','travel','ojek','grab','gojek','angkot','trans jogja','bandara','stasiun','terminal','sampai','perjalanan','naik apa','naik','kendaraan umum','lokasi','akses','rute','jalur','letak','alamat','kesana'],
        waktu: ['jam','waktu','jadwal','buka','tutup','kapan','hari','weekend','libur','liburan','weekday','senin','selasa','rabu','kamis','jumat','sabtu','minggu','bulan','tahun','musim','kemarau','hujan'],
        fasilitas: ['fasilitas','toilet','mushola','masjid','parkir','tempat parkir','musholla','saung','gazebo','wifi','musala','tempat ibadah','ruang ganti','bilas','kamar mandi'],
        budaya: ['budaya','tradisi','adat','jawa','kejawen','sesaji','ritual','upacara','selamatan','bersih desa','kenduri','tayub','jathilan','wayang','ketoprak','kethoprak','seni','tari','tarian','joged'],
        jogja: ['jogja','yogyakarta','sleman','turi','merapi','malioboro','prambanan','borobudur','kraton','taman sari','kota gede','bantul','gunung kidul','kulon progo','pantai','parangtritis','indrayanti','snow bay','heha','kalibiru','pinus','mangunan','tebing breksi','obelix','goa jatijajar','goa pindul','wonosari','kaliurang','kaliadem','bukit bintang'],
        cuaca: ['cuaca','udara','suhu','dingin','panas','sejuk','hawa','iklim','musim hujan','musim kemarau','berawan','cerah','mendung','gerimis'],
        foto: ['foto','fotografi','fotografer','dokumentasi','spot foto','instagramable','instagram','selfie','berfoto','memotret','kamera','video','vlog'],
        rombongan: ['rombongan','group','kelompok','sekolah','kampus','universitas','mahasiswa','pelajar','siswa','pkl','study tour','studytour','outbound','outbond','gathering','family gathering','reuni','komunitas','organisasi','perusahaan','karyawan','company visit'],
        testimonial: ['testimoni','review','ulasan','pengalaman','kesan','recommend','rekomendasi','rating','bintang','komentar','cerita','cerita pengunjung'],
        anak: ['anak','anak-anak','keluarga','bayi','balita','orang tua','lansia','family','kids','family friendly','aman anak','ramah anak'],
        persiapan: ['pakaian','baju','sepatu','sandal','topi','sunscreen','tabir surya','persiapan','bawaan','tas','ransel','jaket','payung','jas hujan','perlengkapan','sedia','siap-siap','tips'],
        pujian: ['terima kasih','makasih','thanks','thank','mantap','keren','bagus','hebat','kagum','wow','nice','good','awesome','sip','ok','oke'],
        negatif: ['jelek','buruk','tidak suka','kecewa','mengecewakan','mahal sekali','mahal banget','gak worth','tidak worth','ngapain','gabut','boring','membosankan','gak seru','tidak seru','gak asik','gak enak'],
        tentang: ['tentang','sejarah','desa','gabugan','donokerto','berdiri','awal','latar belakang','visi','misi','profil','informasi desa','info desa'],
        promo: ['promo','diskon','potongan','hemat','murah','gratis','free','spesial','special offer','bundling','paket hemat','promo rombongan'],
        bahasa: ['bahasa inggris','english','speak english','foreigner','bule','turis asing','international','foreign','translate','terjemahan'],
        kesehatan: ['kesehatan','keamanan','aman','nyaman','chse','protokol','covid','prokes','masker','vaksin','safety','security','p3k','pos kesehatan','klinik','rumah sakit','puskesmas','rs'],
        souvenir: ['souvenir','oleh-oleh','buah tangan','cinderamata','belanja','shopping','salak','manisan salak','keripik salak','kain','kerajinan','handicraft','anyaman','bambu','mendong'],
        merapi: ['merapi','gunung','gunung merapi','erupsi','letusan','bencana','lahar','awan panas','wedus gembel','bunker','museum merapi','bebeng','kaliadem','kinahrejo','mbah marijan','jurug'],
        akomodasi: ['hotel','villa','resort','losmen','penginapan murah','guest house','penginapan sekitar','hotel dekat','lodging','accommodation','homestay','menginap','penginapan','kamar','tidur','joglo','limasan','live'],
        malam: ['malam','malam hari','bonfire','api unggun','ngopi','nongkrong','santai malam','jalan malam','night','night tour','malam minggu'],
        sawah: ['sawah','pertanian','petani','bertani','padi','tanam','panen','membajak','kerbau','cangkul','sawah tadah hujan','irigasi','pematang'],
        salak: ['salak','salak pondoh','buah salak','petik salak','kebun salak','manisan salak','keripik salak','salak goreng','salak bali','salak super'],
        batik: ['batik','batik tulis','batik cap','membatik','canting','malam','kain batik','motif batik','batik jogja','batik solo','batik pekalongan'],
        gamelan: ['gamelan','karawitan','saron','bonang','gong','kendang','slenthem','demung','gender','rebab','suling','musik jawa','musik tradisional'],
        river: ['river','trekking','sungai','susur sungai','tracking','hiking','traking','air terjun','curug','kali','kaliurang','kaliadem'],
        outbound: ['outbound','outbond','game','permainan','team building','fun game','ice breaking','flying fox','jaring laba','tali','merayap','halang rintang'],
        prewedding: ['prewedding','prewed','foto nikah','foto pernikahan','wedding','engagement','tunangan','foto prewed','sesi foto'],
        event: ['event','acara','festival','lomba','perlombaan','karnaval','pesta','syukuran','hajatan','pernikahan','resepsi'],
        random: ['hobi','liburan','rekreasi','refreshing','healing','jalan-jalan','travelling','traveling','trip','tour','berlibur','cuti','weekend','staycation','stay','ngadem','nyari angin','cabut','pergi']
    };

    // ========================
    // RESPONSES DATABASE (UPDATED)
    // ========================
    const responses = {
        greeting: `Sugeng Rawuh! 🙏\n\nKulo <strong>Mas Pandu</strong>, asisten virtual resmi dari <strong>Desa Wisata Gabugan</strong>, Sleman, Yogyakarta.\n\nKulo siap membantu Anda dengan informasi tentang paket wisata, homestay, aktivitas seru, harga, lokasi, dan apa pun yang ingin Anda ketahui tentang desa kami yang asri ini! 🏡🌄\n\nAda yang bisa kulo bantu, <em>Mbak/Mas</em>? 😊`,
        smalltalk: `Kulo Mas Pandu, asisten virtual resmi Desa Wisata Gabugan! 🧑‍🌾\n\nKulo niki bot boso Jowo sing siap mbantu Sampeyan (Saya ini bot bahasa Jawa yang siap membantu Anda) mencari informasi tentang desa kami.\n\nKulo saged mbantu babagan:\n• 🌿 Paket Wisata & Edukasi\n• 🏡 Homestay & Live-in\n• 🍲 Kuliner Ndheso lereng Merapi\n• 🎨 Kegiatan Seni Budaya\n• 🚗 Rute & Transportasi\n\nMonggo, badhe pirsa nopo? (Silakan, mau tanya apa?) 😊`,
        booking: `Sugeng rawuh, Sahabat Wisata! 🙏\n\nMatur nuwun sanget sudah tertarik untuk berkunjung ke Desa Wisata Gabugan.\n\nBerikut <strong>tata cara pemesanan (booking)</strong> paket wisata:\n\n📌 <strong>Langkah 1: Pilih Paket Wisata</strong>\nTentukan paket yang diinginkan (Agro Edukasi, Pertanian, Seni Budaya, Outbound, One-Day Tour, atau Live-In Homestay).\n\n📌 <strong>Langkah 2: Hubungi Admin</strong>\nWhatsApp ke <strong>+62 813-2885-6252</strong> atau DM Instagram @desawisatagabugan.\n\n📌 <strong>Langkah 3: Sampaikan Detail</strong>\nInformasikan nama pemesan, paket pilihan, jumlah peserta, dan tanggal kunjungan.\n\n📌 <strong>Langkah 4: Pembayaran DP</strong>\nTransfer minimal 30-50% untuk mengamankan jadwal.\n\n📌 <strong>Langkah 5: Kunjungan & Pelunasan</strong>\nLunas di hari-H! 🎉\n\nKami siap membantu menyusun itinerary khusus untuk rombongan Anda! 😊`,
        harga: `Untuk informasi harga paket wisata, berikut rinciannya:\n\n• 🌿 <strong>Paket Agro Edukasi Salak Pondoh</strong> — Rp 150.000/orang\n• 🌾 <strong>Paket Edukasi Pertanian & Sawah</strong> — Rp 175.000/orang\n• 🎨 <strong>Paket Seni & Budaya Jawa</strong> — Rp 150.000/orang\n• 🏡 <strong>Paket Live-In Homestay</strong> — Rp 350.000/orang\n• 📋 <strong>Paket One-Day Tour Gabugan</strong> — Rp 175.000/orang\n\nHarga sudah termasuk pemandu lokal dan konsumsi. Untuk rombongan tersedia <strong>harga khusus</strong>! 💰\n\nHubungi kami untuk penawaran terbaik! 😊`,
        kontak: `Hubungi kami melalui:\n\n📞 <strong>WhatsApp:</strong> <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>\n📧 <strong>Email:</strong> info@desawisatagabugan.com\n📸 <strong>Instagram:</strong> @desawisatagabugan\n\n📍 Kantor: Dusun Gabugan, Donokerto, Turi, Sleman, DIY\n\nJam Operasional:\n🕐 <strong>Setiap Hari</strong> — 08.00 - 17.00 WIB\n🏡 Homestay 24 Jam (sesuai pesanan)\n\nJangan ragu untuk menghubungi kami! Tim kami siap membantu! 😊`,
        kuliner: `Kuliner khas yang bisa Anda nikmati di Desa Wisata Gabugan dan sekitarnya! 🍲\n\n<strong>Makanan & Lauk:</strong>\n• 🥘 <strong>Sayur Lodeh Tewel</strong> — Sayur lodeh dengan nangka muda khas pedesaan\n• 🌶️ <strong>Sambal Belut Goreng</strong> — Belut goreng krispi dengan sambal pedas\n• 🍚 <strong>Nasi Liwet</strong> — Nasi gurih dengan santan dan rempah, disajikan dengan lauk komplit\n• 🥗 <strong>Urap-urap</strong> — Sayuran rebus dengan parutan kelapa berbumbu\n\n<strong>Camilan & Minuman:</strong>\n• 🥜 <strong>Manisan Salak Pondoh</strong> — Oleh-oleh khas yang manis dan segar\n• 🍪 <strong>Keripik Salak</strong> — Camilan renyah dari buah salak asli\n• ☕ <strong>Wedang Jahe Merapi</strong> — Minuman jahe hangat khas lereng Merapi\n• 🍵 <strong>Beras Kencur</strong> — Minuman tradisional untuk menghangatkan badan\n\n<strong>Kuliner Malam:</strong>\n• 🌙 <strong>Angkringan Gabugan</strong> — Nasi kucing, sate usus, dan aneka gorengan\n\nSemua kuliner menggunakan bahan-bahan segar dari kebun dan sawah warga! 😊`,
        transportasi: `Informasi transportasi menuju Desa Wisata Gabugan! 🚗\n\n<strong>Dari Kota Yogyakarta:</strong>\n🚗 <strong>Mobil Pribadi</strong> — ±45 menit (17 km) via Jalan Kaliurang km 17, belok kanan ke arah Turi.\n🛵 <strong>Motor</strong> — ±35 menit, jalur lebih fleksibel dan pemandangan asyik!\n🚌 <strong>Bus/Elf</strong> — Tersedia bus kecil dari Terminal Jombor jurusan Turi.\n\n<strong>Dari Bandara YIA (Kulon Progo):</strong>\n🚗 ±1,5 jam via jalur tol Jogja-Solo atau jalur utara.\n\n<strong>Dari Stasiun Tugu:</strong>\n🚗 ±40 menit via Jalan Kaliurang.\n\n<strong>Alternatif Transportasi:</strong>\n• 🚐 <strong>Travel</strong> — Bisa dijemput dari bandara/stasiun (koordinasi via WA)\n• 🛵 <strong>Gojek/Grab</strong> — Tersedia, tarif sekitar Rp 70-100rb dari pusat kota\n• 🚌 <strong>Trans Jogja</strong> — Tersedia halte terdekat, lanjut ojek masuk desa\n\n<strong>Tips:</strong> Jalan sudah beraspal halus, bisa dilalui semua jenis kendaraan termasuk bus besar! Ada area parkir luas di lokasi. 🅿️`,
        waktu: `Informasi jam operasional dan waktu terbaik berkunjung! ⏰\n\n<strong>Jam Operasional:</strong>\n🕐 <strong>Setiap Hari</strong> — 08.00 - 17.00 WIB\n🏡 <strong>Homestay/Live-In</strong> — 24 jam (check-in sesuai kesepakatan)\n\n<strong>Waktu Terbaik Berkunjung:</strong>\n🌤️ <strong>Musim Kemarau (April - Oktober)</strong> — Cuaca cerah, aktivitas outdoor maksimal\n🌧️ <strong>Musim Hujan (November - Maret)</strong> — Tetap asyik, aktivitas dalam ruang (batik, gamelan) lebih cocok\n\n<strong>Rekomendasi Waktu:</strong>\n• 🌅 <strong>Pagi (08.00-10.00)</strong> — Udara sejuk, kabut tipis, pemandangan Merapi jelas\n• 🌄 <strong>Sore (15.00-17.00)</strong> — Golden hour, cocok untuk foto\n• 🌙 <strong>Malam (setelah Maghrib)</strong> — Khusus paket Live-In, nikmati malam pedesaan\n\n<strong>Tips:</strong> Akhir pekan lebih ramai, weekday lebih tenang dan privat! 😊`,
        fasilitas: `Fasilitas yang tersedia di Desa Wisata Gabugan! 🏗️\n\n<strong>Fasilitas Umum:</strong>\n✅ <strong>Area Parkir</strong> — Luas, muat hingga 5 bus besar\n✅ <strong>Mushola/Masjid</strong> — Bersih dan nyaman untuk ibadah\n✅ <strong>Toilet Umum</strong> — Terawat, ada air bersih\n✅ <strong>Saung/Gazebo</strong> — Tempat bersantai dengan pemandangan sawah\n✅ <strong>Ruang Bilas/Ganti</strong> — Untuk aktivitas outdoor\n\n<strong>Fasilitas Homestay:</strong>\n✅ Kamar tidur bersih dengan sprei ganti\n✅ Kamar mandi dalam\n✅ Air mineral & teh/kopi\n✅ Keluarga tuan rumah yang ramah\n\n<strong>Fasilitas Pendukung:</strong>\n✅ <strong>Koneksi Internet</strong> — Sinyal 4G stabil (Telkomsel, Indosat, XL)\n✅ <strong>P3K</strong> — Tersedia di posko utama\n✅ <strong>Pemandu Lokal</strong> — Berpengalaman dan ramah\n✅ <strong>Area Makan</strong> — Saung lesehan dengan kapasitas besar\n\nSemua fasilitas kami jaga kebersihan dan kenyamanannya! 😊`,
        budaya: `Budaya dan tradisi yang bisa Anda pelajari di Desa Wisata Gabugan! 🎭\n\n<strong>Seni & Budaya yang Diajarkan:</strong>\n🎵 <strong>Gamelan & Karawitan</strong> — Belajar memainkan saron, bonang, gong, dan kendang\n🎨 <strong>Membatik Tulis</strong> — Proses membatik dari awal hingga jadi kain\n💃 <strong>Tari Jawa</strong> — Gerakan dasar tari klasik gaya Yogyakarta\n🥁 <strong>Jathilan</strong> — Kesenian rakyat khas pedesaan\n\n<strong>Tradisi Lokal:</strong>\n🌾 <strong>Selamatan Bumi</strong> — Ritual syukur hasil panen (setiap tahun)\n🙏 <strong>Bersih Desa</strong> — Tradisi tolak bala dan doa keselamatan\n🍚 <strong>Kenduri</strong> — Tradisi makan bersama sebagai ungkapan syukur\n\n<strong>Filosofi Jawa:</strong>\n"<em>Memayu Hayuning Bawana</em>" — Manusia hidup untuk memperindah dan melestarikan dunia.`,
        jogja: `Informasi wisata di sekitar Yogyakarta! 🏛️\n\n<strong>Dekat dengan Desa Wisata Gabugan:</strong>\n🌋 <strong>Kaliurang & Kaliadem</strong> — ±15 menit, wisata lereng Merapi\n🏛️ <strong>Museum Gunung Merapi</strong> — ±20 menit, edukasi vulkanologi\n🌲 <strong>Bukit Bintang</strong> — ±25 menit, panorama lampu kota Jogja\n\n<strong>Dalam Kota Yogyakarta (±45 menit):</strong>\n🛍️ <strong>Malioboro</strong> — Pusat oleh-oleh dan kuliner\n🏯 <strong>Kraton Yogyakarta</strong> — Istana Kesultanan\n🌊 <strong>Pantai Parangtritis</strong> — ±1 jam, sunset legendaris\n\n<strong>Wisata Ikonik Lainnya:</strong>\n🛕 <strong>Candi Borobudur</strong> — ±1,5 jam, candi Buddha terbesar di dunia\n🛕 <strong>Candi Prambanan</strong> — ±1 jam, candi Hindu yang megah\n🌅 <strong>Gumuk Pasir</strong> — ±1,5 jam, wisata unik di Bantul`,
        cuaca: `Informasi cuaca di kawasan Desa Wisata Gabugan! 🌤️\n\n<strong>Suhu Udara:</strong>\n🌡️ <strong>Siang Hari:</strong> 24-30°C (lebih sejuk dari kota Jogja)\n🌡️ <strong>Malam Hari:</strong> 18-22°C (cenderung dingin)\n🌡️ <strong>Pagi Hari:</strong> 20-24°C (sejuk dengan kabut tipis)\n\n<strong>Musim:</strong>\n☀️ <strong>Musim Kemarau (April-Oktober)</strong> — Cerah, cocok aktivitas outdoor\n🌧️ <strong>Musim Hujan (November-Maret)</strong> — Sering gerimis, bawa jas hujan/payung\n\n<strong>Tips Berpakaian:</strong>\n👕 Bawa <strong>jaket atau sweater</strong> — terutama jika menginap, malam hari cukup dingin\n🧴 Bawa <strong>sunscreen</strong> — meski sejuk, sinar UV tetap terasa siang hari\n☂️ Bawa <strong>payung/jas hujan</strong> — terutama musim penghujan`,
        foto: `Spot foto keren di Desa Wisata Gabugan! 📸\n\n<strong>Spot Foto Favorit:</strong>\n🌾 <strong>Sawah Terasering</strong> — Latar hijau dengan background Merapi\n🏡 <strong>Homestay Joglo</strong> — Arsitektur tradisional yang instagramable\n🍈 <strong>Kebun Salak</strong> — Berfoto di antara pohon salak berbuah\n🌅 <strong>Pematang Sawah</strong> — Golden hour sore hari, cahaya magis!\n🎨 <strong>Area Membatik</strong> — Proses membatik dengan canting, aesthetic banget\n🎵 <strong>Bale Gamelan</strong> — Berfoto dengan gamelan tradisional\n\n<strong>Tips Foto:</strong>\n📱 <strong>Waktu terbaik:</strong> 06.00-08.00 (pagi) atau 15.00-17.00 (sore)\n📱 <strong>Drone allowed:</strong> Bisa terbangkan drone untuk aerial view sawah`,
        rombongan: `Informasi untuk kunjungan rombongan! 👥\n\n<strong>Paket Rombongan Tersedia:</strong>\n🏫 <strong>Study Tour Sekolah</strong> — Edukasi pertanian, budaya, dan lingkungan\n🎓 <strong>PKL/Magang</strong> — Program live-in beberapa hari\n🏢 <strong>Company Gathering</strong> — Team building dan outbound\n👨‍👩‍👧‍👦 <strong>Family Gathering</strong> — Liburan seru bersama keluarga besar\n🌍 <strong>Komunitas/Organisasi</strong> — Kegiatan sosial dan kebersamaan\n\n<strong>Kapasitas:</strong>\n✅ Rombongan kecil (10-30 orang) — Fleksibel, banyak pilihan aktivitas\n✅ Rombongan sedang (30-100 orang) — Perlu koordinasi H-7\n✅ Rombongan besar (100-500 orang) — Konsultasi dulu ya, kami siapkan!\n\n<strong>Keuntungan Rombongan:</strong>\n💰 <strong>Harga khusus</strong> — Potongan harga untuk rombongan\n🎯 <strong>Itinerary custom</strong> — Bisa disesuaikan kebutuhan`,
        testimonial: `Testimoni & pengalaman pengunjung Desa Wisata Gabugan! ⭐\n\n<strong>Apa Kata Mereka?</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Pengalaman yang luar biasa! Anak-anak sekolah sangat senang belajar membajak sawah dan memetik salak langsung. Pemandu lokalnya ramah banget!"</em> — <strong>Bu Sari, Guru SD</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Homestay-nya nyaman, makanannya enak, suasananya bikin kangen."</em> — <strong>Mas Dito, Yogyakarta</strong>\n\n⭐️⭐️⭐️⭐️⭐️ <em>"Batik tulisnya bagus! Bisa bikin sendiri dan dibawa pulang."</em> — <strong>Mbak Rina, Jakarta</strong>\n\n<strong>Rating:</strong> ⭐ 4.9/5 dari 200+ pengunjung!`,
        anak: `Informasi untuk kunjungan bersama anak dan keluarga! 👨‍👩‍👧‍👦\n\n<strong>Aktivitas Ramah Anak:</strong>\n🧒 <strong>Membajak Sawah</strong> — Naik kerbau pembajak yang jinak, anak-anak suka!\n🎨 <strong>Membatik Sederhana</strong> — Cocok untuk anak, motif simpel dan menyenangkan\n🍈 <strong>Petik Salak</strong> — Aktivitas seru petik buah langsung dari pohon\n🌾 <strong>Bermain di Sawah</strong> — Main air, kejar capung, dan belajar alam\n🐄 <strong>Beri Makan Ternak</strong> — Interaksi dengan kambing, ayam, dan kerbau\n\n<strong>Tips:</strong>\n👕 Bawa baju ganti — anak pasti basah kuyup main di sawah!\n🧴 Bawa lotion anti nyamuk — area sawah alami`,
        persiapan: `Tips persiapan sebelum berkunjung ke Desa Wisata Gabugan! 🎒\n\n<strong>Yang Perlu Dibawa:</strong>\n👕 <strong>Baju ganti</strong> — Aktivitas outdoor bikin basah/berkeringat\n👟 <strong>Sepatu/sandal gunung</strong> — Untuk trekking sawah dan sungai\n🧴 <strong>Sunscreen & topi</strong> — Sinar UV tetap terasa meski sejuk\n☂️ <strong>Payung/jas hujan</strong> — Antisipasi cuaca\n🧥 <strong>Jaket/sweater</strong> — Malam hari cukup dingin\n📱 <strong>Power bank</strong> — Banyak spot foto keren!\n\n<strong>Tips Tambahan:</strong>\n💊 Bawa obat pribadi jika ada kondisi khusus\n📸 Pastikan kamera/hp penuh baterai`,
        pujian: `Matur nuwun sanget! 🙏😊\n\nSenang sekali mendengar apresiasi dari Anda! Mas Pandu dan seluruh tim <strong>Desa Wisata Gabugan</strong> selalu berusaha memberikan pelayanan hangat terbaik khas pedesaan Yogyakarta.\n\nAda lagi yang ingin ditanyakan seputar desa wisata kami? 🏡🌾`,
        negatif: `Maaf jika ada yang kurang berkenan 🙏\n\nKami selalu berusaha meningkatkan kualitas pelayanan di <strong>Desa Wisata Gabugan</strong>. Jika ada masukan atau kritik yang membangun, silakan sampaikan langsung ke kami melalui:\n\n📞 <strong>WhatsApp:</strong> <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" class="text-[#00a877] font-semibold hover:underline">+62 813-2885-6252</a>`,
        tentang: `Tentang Desa Wisata Gabugan! 🏡\n\n<strong>Desa Wisata Gabugan</strong> terletak di <strong>Dusun Gabugan, Desa Donokerto, Kecamatan Turi, Kabupaten Sleman, Daerah Istimewa Yogyakarta</strong>.\n\n<strong>Sekilas:</strong>\n🌄 Berada di <strong>lereng barat Gunung Merapi</strong>, ketinggian ±500 mdpl\n🌾 Dikelola oleh <strong>Kelompok Sadar Wisata (Pokdarwis)</strong> setempat\n🤝 Mengusung konsep <strong>Community Based Tourism</strong>\n🏆 Mendapat berbagai penghargaan desa wisata terbaik tingkat DIY\n\nSelamat datang di Gabugan, rumah kedua Anda di lereng Merapi! 🌋✨`,
        promo: `Informasi promo dan harga spesial! 💰\n\n<strong>Promo Saat Ini:</strong>\n🎉 <strong>Promo Rombongan</strong> — Potongan 10-20% untuk rombongan 30+ orang\n🎉 <strong>Early Bird</strong> — Booking H-14 dapat harga spesial\n🎉 <strong>Weekday Special</strong> — Harga lebih hemat untuk kunjungan Senin-Jumat\n🎉 <strong>Paket Bundling</strong> — Kombinasi beberapa paket dengan harga lebih murah\n\n<strong>Harga Normal:</strong>\n• 🌿 Agro Edukasi Salak — Rp 150.000/orang\n• 🌾 Edukasi Pertanian — Rp 175.000/orang\n• 🎨 Seni & Budaya — Rp 150.000/orang\n• 🏡 Live-In Homestay — Rp 350.000/orang\n• 📋 One-Day Tour — Rp 175.000/orang`,
        bahasa: `Informasi untuk wisatawan asing / Information for foreign tourists! 🌏\n\n<strong>English Service Available:</strong>\nYes! Desa Wisata Gabugan welcomes international visitors. Some of our guides can speak English.\n\n<strong>Activities:</strong>\n🌾 Rice Field Experience — Plow with water buffalo, plant rice\n🍈 Salak Pondoh Harvesting — Pick fresh snake fruit\n🎨 Batik Making — Learn traditional Javanese batik\n🎵 Gamelan Music — Play traditional Javanese instruments\n🏡 Homestay — Stay with local family`,
        kesehatan: `Informasi kesehatan dan keamanan di Desa Wisata Gabugan! 🏥\n\n<strong>CHSE Certified ✅</strong>\nKami telah menerapkan protokol <strong>Cleanliness, Health, Safety & Environmental Sustainability</strong>.\n\n<strong>Fasilitas Kesehatan:</strong>\n🏥 <strong>Puskesmas Turi</strong> — ±10 menit dari lokasi\n🏥 <strong>RS PKU Muhammadiyah</strong> — ±20 menit\n🏥 <strong>RS Sardjito</strong> — ±30 menit (rujukan)`,
        souvenir: `Oleh-oleh khas yang bisa dibeli di Desa Wisata Gabugan! 🛍️\n\n🍈 <strong>Salak Pondoh Segar</strong> — Petik langsung dari kebun, dijamin manis!\n🍬 <strong>Manisan Salak</strong> — Oleh-oleh manis khas yang wajib dibawa pulang\n🥨 <strong>Keripik Salak</strong> — Camilan renyah, cocok untuk buah tangan\n🎨 <strong>Batik Tulis Karya Warga</strong> — Kain batik asli buatan pengrajin lokal\n🧺 <strong>Anyaman Bambu & Mendong</strong> — Kerajinan tangan tradisional`,
        merapi: `Informasi tentang Gunung Merapi! 🌋\n\n📍 Gabugan berada di <strong>lereng barat Gunung Merapi</strong>, ±12 km dari puncak\n🌄 Pemandangan Merapi terlihat jelas dari area desa wisata\n\n<strong>Aktivitas Terkait Merapi:</strong>\n🏛️ <strong>Museum Gunung Merapi</strong> — ±20 menit, edukasi tentang vulkanologi\n🚙 <strong>Jeep Lava Tour</strong> — Jelajahi bekas aliran lahar Merapi\n🌲 <strong>Kaliadem & Bunker</strong> — Spot sejarah erupsi 2010`,
        akomodasi: `Informasi akomodasi di sekitar Desa Wisata Gabugan! 🏨\n\n<strong>Di Dalam Desa Wisata:</strong>\n🏡 <strong>Homestay Joglo & Limasan</strong> — Mulai Rp 350.000/orang (termasuk makan & aktivitas)\n\n<strong>Di Sekitar (10-20 menit):</strong>\n🏨 <strong>Hotel & Resort Kaliurang</strong>\n🏠 <strong>Villa Kaliurang</strong>\n🛌 <strong>Guest House Turi</strong>`,
        malam: `Aktivitas malam yang bisa dinikmati di Desa Wisata Gabugan! 🌙\n\n🔥 <strong>Api Unggun (Bonfire)</strong> — Duduk santai, bakar jagung/ubi\n🎵 <strong>Gamelan Malam</strong> — Belajar gamelan suasana malam yang syahdu\n🌌 <strong>Stargazing</strong> — Langit malam bebas polusi cahaya\n☕ <strong>Ngopi Malam</strong> — Nikmati wedang jahe atau kopi di saung sawah`,
        sawah: `Aktivitas di sawah yang bisa Anda coba! 🌾\n\n🐃 <strong>Membajak dengan Kerbau</strong> — Rasakan jadi petani tradisional!\n🌱 <strong>Menanam Padi</strong> — Belajar cara menanam padi yang benar\n🌾 <strong>Panen Padi</strong> — Khusus musim panen, potong padi dengan ani-ani\n\n<strong>Tips:</strong>\n👖 Pakai celana panjang yang bisa digulung\n👕 Bawa baju ganti — pasti basah kuyup!`,
        salak: `Informasi lengkap tentang Salak Pondoh! 🍈\n\n🌴 Salak Pondoh adalah <strong>buah khas Sleman</strong> yang terkenal manis dan renyah\n🌴 Kebun salak di Gabugan dikelola secara <strong>organik</strong>\n🌴 Musim panen raya: <strong>Desember - Maret</strong>\n\n<strong>Aktivitas Petik Salak:</strong>\n🍈 Petik langsung dari pohon — pilih yang paling manis!\n🍈 Ditemani petani lokal yang menjelaskan cara budidaya\n🍈 Bisa langsung dicicipi di kebun\n\n<strong>Manfaat Salak:</strong>\n✅ Kaya antioksidan\n✅ Baik untuk pencernaan`,
        batik: `Belajar membatik di Desa Wisata Gabugan! 🎨\n\n<strong>Paket Belajar Batik:</strong>\n🖌️ <strong>Batik Tulis Canting</strong> — Proses tradisional dengan canting dan malam\n🖌️ <strong>Batik Cap</strong> — Teknik cap untuk motif yang lebih cepat\n🖌️ <strong>Batik Ikat Celup</strong> — Teknik sederhana, cocok untuk pemula\n\n<strong>Hasil:</strong>\n✅ Kain batik ukuran ±1 meter bisa dibawa pulang\n✅ Sertifikat partisipasi\n\n<strong>Motif Khas Gabugan:</strong>\n🌾 Motif sawah terasering\n🍈 Motif salak pondoh\n🌋 Motif gunung Merapi`,
        gamelan: `Belajar Gamelan & Karawitan Jawa! 🎵\n\n🎶 Belajar memainkan alat musik tradisional Jawa:\n• 🎹 <strong>Saron</strong> — Bilah logam bernada nyaring\n• 🎹 <strong>Bonang</strong> — Gong kecil yang ditabuh\n• 🥁 <strong>Kendang</strong> — Pengatur irama\n• 🔔 <strong>Gong</strong> — Penanda akhir kalimat lagu\n\n<strong>Filosofi:</strong>\nGamelan mengajarkan <em>"harmoni"</em> — setiap instrumen punya peran yang saling melengkapi.`,
        river: `River Trekking di sungai lereng Merapi! 🌊\n\n🚶‍♂️ Menyusuri aliran sungai jernih dari mata air Merapi\n🚶‍♂️ Airnya segar, jernih, dan tidak terlalu dalam\n🚶‍♂️ Ditemani pemandu lokal yang berpengalaman\n\n<strong>Tips:</strong>\n👟 Pakai sepatu/sandal gunung yang anti slip\n👕 Bawa baju ganti — pasti basah!\n📱 Simpan HP di kantong anti air`,
        outbound: `Aktivitas Outbound & Team Building! 🎯\n\n🧗 <strong>Flying Fox</strong> — Meluncur di atas area persawahan\n🕸️ <strong>Jaring Laba-Laba</strong> — Game kerjasama tim\n🏃 <strong>Estafet Air</strong> — Game seru yang bikin basah-basahan\n🧩 <strong>Puzzle Raksasa</strong> — Game pemecahan masalah\n\n<strong>Cocok Untuk:</strong>\n🏢 Company gathering & outing\n🎓 MPLS/OSPEK mahasiswa baru\n👨‍👩‍👧‍👦 Family gathering`,
        prewedding: `Paket Foto Prewedding di Desa Wisata Gabugan! 💑\n\n💑 <strong>Sawah Terasering</strong> — Latar hijau dengan Merapi di belakang\n💑 <strong>Homestay Joglo</strong> — Nuansa tradisional Jawa yang romantis\n💑 <strong>Kebun Salak</strong> — Backdrop alami yang unik\n💑 <strong>Pematang Sawah</strong> — Golden hour yang magis\n\n💰 Mulai Rp 500.000 (akses spot, belum termasuk fotografer)`,
        event: `Informasi Event & Acara di Desa Wisata Gabugan! 🎪\n\n<strong>Event Reguler:</strong>\n🌾 <strong>Festival Salak Pondoh</strong> — Setiap tahun, panen raya salak\n🎭 <strong>Pentas Seni Budaya</strong> — Tari, gamelan, jathilan\n🏃 <strong>Fun Trail & Adventure Run</strong> — Lari lintas alam di sawah\n🍲 <strong>Festival Kuliner Ndeso</strong> — Aneka masakan tradisional\n\n<strong>Kapasitas:</strong>\n✅ Area terbuka: hingga 500 orang`,
        random: `Wah, pertanyaan yang menarik! 😊\n\n<strong>Desa Wisata Gabugan</strong> adalah destinasi wisata edukasi dan budaya di lereng Merapi yang cocok untuk:\n• 🌿 <strong>Healing</strong> — Suasana pedesaan yang tenang dan asri\n• 📚 <strong>Belajar</strong> — Aktivitas interaktif pertanian, batik, gamelan\n• 👨‍👩‍👧‍👦 <strong>Berkumpul</strong> — Bersama keluarga, teman, atau rekan kerja\n• 📸 <strong>Berswafoto</strong> — Spot-spot instagramable dengan latar Merapi\n\nAda topik spesifik yang ingin ditanyakan? 😊`
    };

    // ========================================
    // SMART SCORING & PARSING ENGINE
    // ========================================
    
    // Fungsi membersihkan teks dari emoji & simbol agar pencocokan akurat
    function cleanInput(text) {
        if (!text) return '';
        return text.toLowerCase()
            .replace(/[^\w\s]/g, ' ') // Ganti tanda baca dan emoji dengan spasi
            .replace(/\s+/g, ' ')     // Satukan spasi berlebih
            .trim();
    }

    // Fungsi pencocokan cerdas berbasis akumulasi skor kata kunci
    function findBestCategory(input) {
        const cleaned = cleanInput(input);
        if (!cleaned) return null;

        let scores = {};
        for (const [category, keywords] of Object.entries(knowledgeBase)) {
            scores[category] = 0;
            for (const keyword of keywords) {
                const keywordClean = cleanInput(keyword);
                if (cleaned.includes(keywordClean)) {
                    // Beri bobot berdasarkan panjang kata kunci agar pencocokan spesifik menang
                    scores[category] += keywordClean.length;
                    
                    // Beri bonus poin jika kata tersebut berdiri sendiri (bukan potongan kata)
                    const isExactWord = cleaned.split(' ').includes(keywordClean) || 
                                        cleaned.startsWith(keywordClean + ' ') || 
                                        cleaned.endsWith(' ' + keywordClean) || 
                                        cleaned === keywordClean;
                    if (isExactWord) {
                        scores[category] += 5;
                    }
                }
            }
        }

        // Pengelompokan Kategori Utama vs Percakapan Biasa
        const infoCategories = [
            'booking', 'paket', 'harga', 'kontak', 'kuliner', 'transportasi', 'waktu', 
            'fasilitas', 'budaya', 'jogja', 'cuaca', 'foto', 'rombongan', 'testimonial', 
            'anak', 'persiapan', 'tentang', 'promo', 'bahasa', 'kesehatan', 'souvenir', 
            'merapi', 'akomodasi', 'malam', 'sawah', 'salak', 'batik', 'gamelan', 
            'river', 'outbound', 'prewedding', 'event'
        ];
        const convCategories = ['greeting', 'smalltalk', 'pujian', 'negatif', 'random'];

        // Cari skor tertinggi dari kategori info/intent utama
        let bestInfoCat = null;
        let maxInfoScore = 0;
        infoCategories.forEach(cat => {
            if (scores[cat] > maxInfoScore) {
                maxInfoScore = scores[cat];
                bestInfoCat = cat;
            }
        });

        // Cari skor tertinggi dari kategori obrolan santai/greeting
        let bestConvCat = null;
        let maxConvScore = 0;
        convCategories.forEach(cat => {
            if (scores[cat] > maxConvScore) {
                maxConvScore = scores[cat];
                bestConvCat = cat;
            }
        });

        // Aturan Prioritas:
        // 1. Jika ada kata kunci informasi yang bernilai cukup kuat, dahulukan kategori informasi
        if (maxInfoScore >= 3) {
            return bestInfoCat;
        }
        // 2. Jika tidak ada info, tampilkan kategori percakapan
        if (maxConvScore >= 3) {
            return bestConvCat;
        }
        // 3. Toleransi kecocokan rendah
        if (maxInfoScore > 0) return bestInfoCat;
        if (maxConvScore > 0) return bestConvCat;

        return null;
    }

    function simulateResponse(input) {
        showTyping();
        const delay = 1000 + Math.random() * 1000; // Delay natural 1-2 detik

        const category = findBestCategory(input);

        setTimeout(() => {
            hideTyping();

            // 1. Penanganan Khusus untuk Informasi Paket Wisata
            if (category === 'paket') {
                const msg = makeResponse(`Berikut paket wisata unggulan dari Desa Wisata Gabugan! 🌟\n\nSilakan klik kartu di bawah untuk detail lengkap:`);
                addBotMessage(msg);
                
                const hasCards = addPackageCards(window.chatPackages);
                
                // Backup Teks Indah jika data dari Laravel Blade kosong/tidak terisi
                if (!hasCards) {
                    addBotMessage(makeResponse(`Berikut adalah beberapa paket unggulan kami:\n\n• 🌿 <strong>Agro Edukasi Salak Pondoh</strong> — Mulai Rp 150.000/orang (panen & budidaya)\n• 🌾 <strong>Edukasi Pertanian</strong> — Mulai Rp 175.000/orang (membajak sawah, tanam padi)\n• 🎨 <strong>Seni & Budaya Jawa</strong> — Mulai Rp 150.000/orang (membatik & gamelan)\n• 🏡 <strong>Live-In Homestay</strong> — Mulai Rp 250.000/orang (menginap di rumah warga + makan & aktivitas)\n\nAda paket tertentu yang menarik perhatian Anda? 😊`));
                }
                return;
            }

            // 2. Respons Langsung dari Database Obrolan
            if (category && responses[category]) {
                addBotMessage(makeResponse(responses[category]));
                return;
            }

            // 3. Fallback Interaktif untuk Pertanyaan Acak / Tidak Terbaca
            addBotMessage(makeResponse(`Pangapunten (Maaf) 🙏, kulo dereng patia paham maksud pertanyaan Sampeyan.\n\nSebagai asisten virtual, Mas Pandu sedia memberikan informasi terlengkap tentang:\n\n🌾 <strong>Pertanian & Sawah</strong> (membajak, tanam padi)\n🍈 <strong>Petik Salak Pondoh</strong> (agro edukasi)\n🎨 <strong>Membatik & Gamelan</strong> (budaya Jawa)\n🏡 <strong>Homestay & Live-in</strong> (menginap di desa)\n🚗 <strong>Lokasi, Rute, & Jam Buka</strong>\n\nKira-kira apa yang ingin Sampeyan ketahui? Coba ketik kata kunci sederhana seperti <strong>"paket"</strong>, <strong>"kuliner"</strong>, atau <strong>"harga"</strong>. 😊`));

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

    // Tampilkan pesan sambutan
    addBotMessage(WELCOME_MSG);

    // Tombol pintasan quick reply
    quickBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sendMessage(this.textContent.trim());
        });
    });

    // Kirim form input manual
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage(chatInput.value);
    });

    // ========================
    // PANEL TOGGLE
    // ========================
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