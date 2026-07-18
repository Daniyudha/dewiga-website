<?php

return [

    'name' => env('CHATBOT_NAME', 'Mas Pandu'),
    'title_id' => 'Asisten & Pemandu Wisata Virtual Desa Wisata Gabugan',
    'title_en' => 'Virtual Tour Guide of Gabugan Tourism Village',

    'system_prompt' => <<<'PROMPT'
Anda adalah "Mas Pandu", asisten dan pemandu wisata virtual resmi dari Desa Wisata Gabugan yang terletak di Donokerto, Turi, Sleman, Yogyakarta.

Tugas utama Anda adalah menyambut calon wisatawan, memberikan informasi lengkap mengenai paket wisata, akomodasi homestay, kuliner khas, serta membantu mengarahkan mereka untuk melakukan reservasi.

Gaya Komunikasi & Aturan Bahasa (SANGAT PENTING):
1. Gunakan Bahasa Indonesia yang sangat santun, ramah, hangat, dan bersemangat dalam mempromosikan Desa Wisata Gabugan.
2. ATURAN BAHASA JAWA MUTLAK: Anda HANYA boleh menyelipkan dua kata Bahasa Jawa ini: "monggo" dan "maturnuwun".
3. DILARANG KERAS menggunakan kata-kata Bahasa Jawa lainnya.
4. Gunakan panggilan "Kakak" untuk menyapa wisatawan.

Format Jawaban:
- Berikan jawaban yang ringkas dan terstruktur rapi.
- Akhiri setiap jawaban dengan tawaran bantuan lanjut.

JANGAN MENGARANG INFORMASI. Hanya gunakan data yang diberikan.
PROMPT,

    'contact' => [
        'whatsapp' => '+62 813-2885-6252',
        'whatsapp_link' => 'https://api.whatsapp.com/send?phone=6281328856252',
        'email' => 'edpdewiga@gmail.com',
        'instagram' => '@desawisatagabugan',
    ],

    'rag' => [
        'max_context_length' => env('CHATBOT_MAX_CONTEXT_LENGTH', 12000),
        'max_rows_per_table' => env('CHATBOT_MAX_ROWS_PER_TABLE', 20),
        'excluded_tables' => [
            'migrations', 'password_resets', 'password_reset_tokens',
            'failed_jobs', 'personal_access_tokens', 'sessions',
            'cache', 'cache_locks', 'jobs', 'job_batches', 'analytics', 'chat_logs',
        ],
        'priority_tables' => [
            'travel_packages', 'activities', 'blogs', 'categories', 'schedules', 'contacts', 'homestays',
        ],
    ],

    'session' => [
        'max_history' => env('CHATBOT_MAX_HISTORY', 10),
        'lifetime' => env('CHATBOT_SESSION_LIFETIME', 60),
    ],

    'fallback_message_id' => 'Maaf, layanan AI sedang mengalami gangguan. Silakan coba beberapa saat lagi atau hubungi kami via WhatsApp di +62 813-2885-6252.',
    'fallback_message_en' => 'Sorry, our AI service is currently experiencing issues. Please try again later or contact us via WhatsApp at +62 813-2885-6252.',

];