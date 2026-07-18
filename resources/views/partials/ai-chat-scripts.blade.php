{{--
=======================================
 AI CHAT ASSISTANT — JAVASCRIPT (BILINGUAL + AI API)
=======================================
 Now integrates with backend Gemini AI via RAG API.
 Falls back to legacy keyword matching if API fails.
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
    // LOCALIZED STRINGS (legacy fallback)
    // ========================
    const LANG = {
        id: {
            BOT_NAME: 'Mas Pandu',
            BOT_INITIAL: 'MP',
            USER_INITIAL: 'A',
            WELCOME: `Halo! 🙏 Saya <strong>Mas Pandu</strong>, asisten virtual resmi Desa Wisata Gabugan, Sleman, Yogyakarta.<br><br>Senang bisa menyambut Anda! Ada yang bisa saya bantu mengenai kunjungan Anda?<br><br>Saya siap memberikan informasi lengkap tentang:<br>• 🌿 Paket Agrowisata Salak Pondoh<br>• 🌾 Aktivitas sawah & edukasi pertanian<br>• 🎨 Belajar batik tulis, gamelan, dan budaya Jawa<br>• 🏡 Pengalaman menginap (Live-in) di Homestay Rumah warga<br>• 🍲 Kuliner khas pedesaan lereng Merapi<br><br><em>Monggo</em>, silakan pilih pertanyaan di bawah atau ketik langsung apa yang ingin Anda ketahui! 😊`,
            greeting: `Halo! 🙏\n\nSaya <strong>Mas Pandu</strong>, asisten virtual resmi dari <strong>Desa Wisata Gabugan</strong>, Sleman, Yogyakarta.`,
            fallback: `Maaf 🙏, saya belum sepenuhnya paham maksud pertanyaan Anda.\n\nCoba ketik kata kunci seperti <strong>"paket"</strong>, <strong>"kuliner"</strong>, atau <strong>"harga"</strong>. 😊`,
            pkg_header: 'Berikut paket wisata unggulan dari Desa Wisata Gabugan! 🌟\n\nSilakan klik kartu di bawah untuk detail lengkap:',
            pkg_fallback: 'Berikut paket unggulan kami:\n\n• 🌿 <strong>Agro Edukasi Salak Pondoh</strong> — Mulai Rp 150.000/orang\n• 🌾 <strong>Edukasi Pertanian</strong> — Mulai Rp 175.000/orang\n• 🎨 <strong>Seni & Budaya Jawa</strong> — Mulai Rp 150.000/orang\n• 🏡 <strong>Live-In Homestay</strong> — Mulai Rp 250.000/orang\n\nAda yang menarik? 😊',
            act_header: 'Berikut aktivitas seru yang bisa Anda lakukan di Desa Wisata Gabugan! 🎯\n\nSilakan klik kartu di bawah untuk detail lengkap:',
            act_fallback: 'Berikut aktivitas yang bisa Anda ikuti:\n\n• 🌾 <strong>Membajak Sawah dengan Kerbau</strong>\n• 🍈 <strong>Petik Salak Pondoh</strong>\n• 🎨 <strong>Belajar Membatik Tulis</strong>\n• 🎵 <strong>Bermain Gamelan Jawa</strong>\n• 🏡 <strong>Live-In Homestay</strong>',
            pkg_card_title: 'Berikut paket wisata yang tersedia:',
            act_card_title: 'Berikut aktivitas seru yang tersedia:',
            booking: 'Untuk booking, silakan hubungi WhatsApp kami di +62 813-2885-6252.',
            harga: 'Harga paket bervariasi mulai Rp 150.000/orang.',
            kontak: 'Hubungi kami via WhatsApp di +62 813-2885-6252.',
            kuliner: 'Nikmati kuliner khas: Sayur Lodeh Tewel, Sambal Belut Goreng, Nasi Liwet, dan lainnya.',
            transportasi: 'Gabugan dapat diakses dari Yogyakarta sekitar 45 menit via Jl. Kaliurang.',
            waktu: 'Jam operasional setiap hari 08.00-17.00 WIB.',
            fasilitas: 'Tersedia parkir luas, mushola, toilet, saung, dan area makan.',
            budaya: 'Belajar gamelan, membatik, tari Jawa, dan tradisi lokal.',
            tentang: 'Desa Wisata Gabugan berlokasi di Donokerto, Turi, Sleman, DIY.',
            livein: 'Program Live-In Homestay mulai Rp 250.000/orang.',
            merapi: 'Gabugan di lereng barat Merapi, ±12 km dari puncak.',
            salak: 'Salak Pondoh adalah buah khas Sleman, panen raya Desember-Maret.',
            batik: 'Belajar membatik tulis dengan canting tradisional.',
            gamelan: 'Belajar gamelan: saron, bonang, kendang, gong.',
            river: 'River Trekking menyusuri sungai jernih dari mata air Merapi.',
            outbound: 'Tersedia Flying Fox, jaring laba-laba, dan team building.',
            sawah: 'Aktivitas sawah: membajak kerbau, tanam padi, panen.',
            anak: 'Cocok untuk keluarga dengan aktivitas anak: petik salak, membatik.',
            rombongan: 'Tersedia paket rombongan dengan harga khusus.',
            pujian: 'Terima kasih! Ada lagi yang bisa saya bantu?',
            negatif: 'Maaf jika ada yang kurang berkenan. Kami terus berusaha meningkatkan pelayanan.',
            random: 'Desa Wisata Gabugan siap menyambut Anda! Ada yang bisa saya bantu?',
            foto: 'Spot foto keren: sawah terasering, homestay joglo, kebun salak.',
            cuaca: 'Suhu di Gabugan: siang 24-30°C, malam 18-22°C.',
            persiapan: 'Bawa baju ganti, sepatu nyaman, sunscreen, dan jaket.',
            promo: 'Promo rombongan: potongan 10-20% untuk 30+ orang.',
            event: 'Festival Salak Pondoh, pentas seni budaya, fun trail.',
            prewedding: 'Paket foto prewedding mulai Rp 500.000.',
            malam: 'Aktivitas malam: api unggun, gamelan, stargazing.',
            akomodasi: 'Homestay warga mulai Rp 250.000/orang.',
            souvenir: 'Oleh-oleh: salak pondoh segar, manisan salak, batik.',
            kesehatan: 'Tersedia P3K, Puskesmas Turi ±10 menit.',
            bahasa: 'English service available! Welcome to Gabugan Tourism Village.',
            jogja: 'Dekat dari Kaliurang (15 menit), Malioboro (45 menit).',
            testimonial: 'Rating ⭐ 4.9/5 dari 200+ pengunjung!',
        },
        en: {
            BOT_NAME: 'Mas Pandu',
            BOT_INITIAL: 'MP',
            USER_INITIAL: 'Y',
            WELCOME: `Hello! 🙏 I'm <strong>Mas Pandu</strong>, the official virtual assistant of Gabugan Tourism Village, Sleman, Yogyakarta.<br><br>I can help with tour packages, activities, prices, location, and more! 😊`,
            greeting: "Hello! I'm Mas Pandu from Gabugan Tourism Village.",
            fallback: "Sorry, I don't understand. Try asking about packages, food, or prices. 😊",
            pkg_header: 'Here are featured packages:',
            pkg_fallback: 'Packages from Rp 150,000/person.',
            act_header: 'Exciting activities available:',
            act_fallback: 'Buffalo plowing, salak picking, batik, gamelan, homestay.',
            pkg_card_title: 'Available tour packages:',
            act_card_title: 'Available activities:',
            booking: 'To book, contact us via WhatsApp at +62 813-2885-6252.',
            harga: 'Prices starting from Rp 150,000/person.',
            kontak: 'Contact us via WhatsApp at +62 813-2885-6252.',
            kuliner: 'Local food: Sayur Lodeh, Sambal Belut, Nasi Liwet.',
            transportasi: '±45 minutes from Yogyakarta via Jl. Kaliurang.',
            waktu: 'Open daily 08:00-17:00 WIB.',
            fasilitas: 'Parking, prayer room, toilets, gazebos available.',
            budaya: 'Learn gamelan, batik, Javanese dance, traditions.',
            tentang: 'Gabugan Tourism Village in Donokerto, Turi, Sleman, DIY.',
            livein: 'Live-In Homestay from Rp 250,000/person.',
            merapi: 'Located on the western slope of Mount Merapi.',
            salak: 'Salak Pondoh is Sleman\'s signature fruit.',
            batik: 'Learn traditional batik with canting.',
            gamelan: 'Learn gamelan: saron, bonang, kendang, gong.',
            river: 'River trekking through clear mountain streams.',
            outbound: 'Flying fox, spider web, water relay available.',
            sawah: 'Rice field: buffalo plowing, planting, harvesting.',
            anak: 'Family-friendly: fruit picking, batik, rice field play.',
            rombongan: 'Group packages with special prices available.',
            pujian: 'Thank you! Anything else?',
            negatif: 'Sorry for the inconvenience. We continue to improve.',
            random: 'Welcome to Gabugan! How can I help?',
            foto: 'Photo spots: rice terraces, joglo, salak garden.',
            cuaca: 'Temperature: 24-30°C day, 18-22°C night.',
            persiapan: 'Bring change of clothes, comfy shoes, sunscreen.',
            promo: 'Group promo: 10-20% off for 30+ people.',
            event: 'Salak Festival, art performances, fun trail.',
            prewedding: 'Pre-wedding photo from Rp 500,000.',
            malam: 'Night: bonfire, gamelan, stargazing.',
            akomodasi: 'Homestay from Rp 250,000/person.',
            souvenir: 'Souvenirs: fresh salak, salak candy, batik.',
            kesehatan: 'First aid available, clinic 10 mins away.',
            bahasa: 'We welcome international visitors!',
            jogja: 'Near Kaliurang (15 min), Malioboro (45 min).',
            testimonial: '⭐ 4.9/5 from 200+ visitors!',
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
        return text.replace(/\+62[\s]?8[\d]{1,2}[\s\-]?[\d]{4}[\s\-]?[\d]{4,5}/g, function(m) {
            const phone = m.replace(/[\s\-]/g, '');
            return `<a href="https://api.whatsapp.com/send?phone=${phone.replace('+', '')}" target="_blank" class="text-[#00a877] font-semibold hover:underline">${m}</a>`;
        });
    }

    function nl2br(text) { return text.replace(/\n/g, '<br>'); }
    function makeResponse(text) { return nl2br(formatWA(formatBold(text))); }

    // ========================
    // KNOWLEDGE BASE (legacy fallback)
    // ========================
    const knowledgeBase = {
        booking: ['pesan', 'booking', 'order', 'reservasi', 'daftar', 'langkah pesan', 'cara pesan'],
        paket: ['paket', 'pilihan wisata', 'daftar paket', 'paket wisata', 'package', 'tour package'],
        aktivitas: ['aktivitas', 'kegiatan', 'aktivitas seru', 'kegiatan seru', 'things to do', 'activity'],
        harga: ['harga', 'biaya', 'tarif', 'rp', 'bayar', 'ongkos', 'price', 'cost', 'how much'],
        kontak: ['kontak', 'hubungi', 'wa', 'whatsapp', 'nomor', 'telepon', 'contact', 'phone'],
        greeting: ['halo', 'hai', 'hi', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum', 'hello'],
        smalltalk: ['siapa', 'kamu', 'nama', 'pandu', 'mas pandu', 'who are you', 'help'],
        kuliner: ['kuliner','makanan','makan','masakan','food','culinary','eat'],
        transportasi: ['transportasi','kendaraan','mobil','motor','bus','transportation','route','location'],
        waktu: ['jam','waktu','jadwal','buka','tutup','kapan','time','schedule','open','close'],
        fasilitas: ['fasilitas','toilet','mushola','masjid','parkir','facility','parking'],
        budaya: ['budaya','tradisi','adat','jawa','seni','tari','culture','tradition','art'],
        tentang: ['tentang','sejarah','desa','gabugan','profil','about','history','village'],
        livein: ['live in','live-in','livein','homestay','menginap','penginapan'],
        merapi: ['merapi','gunung','mount','volcano'],
        salak: ['salak','salak pondoh','fruit picking'],
        batik: ['batik','batik tulis','membatik','canting'],
        gamelan: ['gamelan','karawitan','saron','bonang','gong','music'],
        river: ['river','trekking','sungai','trekking','waterfall','hiking'],
        outbound: ['outbound','outbond','team building','flying fox'],
        sawah: ['sawah','pertanian','petani','padi','rice field','farming'],
        anak: ['anak','keluarga','family','kids','children'],
        rombongan: ['rombongan','group','kelompok','sekolah','gathering'],
        pujian: ['terima kasih','makasih','thanks','nice','good'],
        negatif: ['jelek','buruk','kecewa','bad','disappointed'],
        random: ['liburan','rekreasi','healing','holiday','vacation']
    };

    function cleanInput(text) {
        if (!text) return '';
        return text.toLowerCase().replace(/[^\w\s]/g, ' ').replace(/\s+/g, ' ').trim();
    }

    function findBestCategory(input) {
        const cleaned = cleanInput(input);
        if (!cleaned) return null;
        let scores = {};
        for (const [category, keywords] of Object.entries(knowledgeBase)) {
            scores[category] = 0;
            for (const keyword of keywords) {
                if (cleaned.includes(cleanInput(keyword))) scores[category] += 5;
            }
        }
        let best = null, max = 0;
        for (const [cat, score] of Object.entries(scores)) {
            if (score > max) { max = score; best = cat; }
        }
        return max >= 3 ? best : null;
    }

    /**
     * AI-powered response via backend API.
     * Falls back to legacy keyword matching on error.
     */
    function simulateResponse(input) {
        showTyping();
        var localePath = window.chatLocale === 'en' ? '/en' : '/id';
        var apiUrl = localePath + '/api/chat/send';
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
            },
            body: JSON.stringify({ message: input, locale: window.chatLocale || 'id' }),
        })
        .then(function(r) { if (!r.ok) throw new Error('Network error'); return r.json(); })
        .then(function(data) {
            hideTyping();
            if (data.success && data.data && data.data.text) {
                addBotMessage(nl2br(data.data.text));
            } else {
                useLegacyResponse(input);
            }
        })
        .catch(function(e) {
            console.warn('AI Chat API fallback to legacy:', e.message);
            hideTyping();
            useLegacyResponse(input);
        });
    }

    function useLegacyResponse(input) {
        const category = findBestCategory(input);
        if (category === 'paket') {
            addBotMessage(makeResponse(t('pkg_header')));
            if (!addPackageCards(window.chatPackages)) addBotMessage(makeResponse(t('pkg_fallback')));
            return;
        }
        if (category === 'aktivitas') {
            addBotMessage(makeResponse(t('act_header')));
            if (!addActivityCards(window.chatActivities)) addBotMessage(makeResponse(t('act_fallback')));
            return;
        }
        if (category && t(category)) {
            addBotMessage(makeResponse(t(category)));
            return;
        }
        addBotMessage(makeResponse(t('fallback')));
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

    quickBtns.forEach(function(btn) {
        btn.addEventListener('click', function() { sendMessage(this.textContent.trim()); });
    });

    chatForm.addEventListener('submit', function(e) { e.preventDefault(); sendMessage(chatInput.value); });

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