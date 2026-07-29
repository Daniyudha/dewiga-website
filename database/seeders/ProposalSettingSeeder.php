<?php

namespace Database\Seeders;

use App\Models\ProposalSetting;
use Illuminate\Database\Seeder;

class ProposalSettingSeeder extends Seeder
{
    public function run(): void
    {
        ProposalSetting::updateOrCreate(['id' => 1], [
            'short_profile' => 'Desa Wisata Gabugan merupakan destinasi wisata edukasi yang menawarkan pengalaman belajar berbasis budaya dan kearifan lokal. Terletak di kawasan pedesaan yang asri, desa wisata ini menjadi tempat ideal bagi siswa untuk belajar di luar kelas melalui konsep Rural Culture Experience.',
            'vision' => 'Menjadi desa wisata edukasi terdepan yang melestarikan budaya lokal dan memberikan pengalaman belajar bermakna bagi generasi muda.',
            'mission' => '1. Menyediakan program edukasi berbasis budaya dan pertanian yang interaktif. 2. Memberdayakan masyarakat lokal sebagai pelaku utama pariwisata. 3. Mengembangkan potensi desa sebagai laboratorium belajar hidup. 4. Menjaga kelestarian lingkungan dan nilai-nilai kearifan lokal.',
            'advantages' => 'Berada di kawasan pedesaan yang masih alami dan asri. Masyarakat lokal yang ramah dan siap menerima kunjungan. Program edukasi yang variatif dari pertanian, seni budaya, hingga kuliner tradisional. Homestay yang nyaman dengan harga terjangkau. Lokasi strategis dan mudah dijangkau.',
            'location' => 'Desa Gabugan, Kecamatan Tanjungsari, Kabupaten Gunungkidul, Daerah Istimewa Yogyakarta',
            'maps_url' => 'https://maps.google.com/?q=Desa+Wisata+Gabugan',
            'contact' => 'Telp/WA: 0812-3456-7890 | Email: info@dewiga.com',
            'tagline' => 'Belajar, Berbudaya, Berkarakter',
            'commitment' => 'Kami berkomitmen untuk memberikan pelayanan terbaik dan pengalaman belajar yang bermakna bagi setiap peserta. Keselamatan dan kenyamanan peserta adalah prioritas utama kami.',
            'dp_terms' => 'Pembayaran uang muka (DP) sebesar 50% dari total biaya program harus dilunasi paling lambat 7 hari sebelum hari pelaksanaan. DP ini digunakan untuk mempersiapkan kebutuhan operasional seperti homestay, konsumsi, dan perlengkapan kegiatan.',
            'payment_terms' => 'Pelunasan pembayaran dilakukan paling lambat 3 hari sebelum hari pelaksanaan. Pembayaran dapat dilakukan secara tunai atau transfer ke rekening yang telah ditentukan. Bukti transfer wajib dikirimkan melalui WhatsApp untuk konfirmasi.',
            'cancellation_terms' => 'Pembatalan sepihak oleh pihak sekolah/instansi dikenakan biaya sebagai berikut: (a) Pembatalan 14 hari sebelum pelaksanaan: DP dikembalikan 50%. (b) Pembatalan 7–13 hari sebelum pelaksanaan: DP tidak dapat dikembalikan. (c) Pembatalan kurang dari 7 hari: dikenakan biaya 75% dari total biaya.',
            'participant_change_terms' => 'Perubahan jumlah peserta dapat dilakukan paling lambat 5 hari sebelum pelaksanaan. Perubahan jumlah peserta akan mempengaruhi total biaya dan akan disesuaikan dengan harga yang berlaku.',
            'force_majeure_terms' => 'Apabila terjadi bencana alam, cuaca ekstrem, atau kondisi force majeure lainnya yang mengakibatkan kegiatan tidak dapat dilaksanakan, maka pihak penyelenggara berhak menjadwalkan ulang kegiatan. Biaya yang sudah dibayarkan tetap berlaku untuk jadwal yang baru.',
            'check_in_time' => '13:00',
            'check_out_time' => '10:00',
            'homestay_terms' => 'Peserta akan ditempatkan di homestay warga sekitar dengan fasilitas dasar seperti tempat tidur, kamar mandi, dan listrik. Setiap homestay menampung 2–4 orang peserta. Jam check-in mulai pukul 13.00 WIB dan check-out pukul 10.00 WIB. Peserta diharapkan menjaga sopan santun dan mengikuti aturan yang berlaku di homestay.',
        ]);
    }
}