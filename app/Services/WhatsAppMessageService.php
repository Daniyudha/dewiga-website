<?php

namespace App\Services;

use App\Models\Schedule;

class WhatsAppMessageService
{
    /**
     * Generate WhatsApp message based on schedule status.
     */
    public function generateMessage(Schedule $schedule, string $template): string
    {
        $estimation = $schedule->priceEstimation;
        $paymentSummary = app(SchedulePaymentService::class)->getPaymentSummary($schedule);

        $institution = $estimation?->institution_name ?? ($schedule->visitor_name ?? 'Instansi');
        $contactPerson = $estimation?->contact_person ?? ($schedule->visitor_name ?? '');
        $whatsapp = $estimation?->whatsapp ?? '';
        $dateRange = $schedule->start_date->format('d/m/Y');
        if ($schedule->end_date && $schedule->end_date != $schedule->start_date) {
            $dateRange .= ' - ' . $schedule->end_date->format('d/m/Y');
        }
        $participants = $estimation?->service_participant_count ?? $schedule->booked;

        $vars = [
            '{nama_instansi}' => $institution,
            '{contact_person}' => $contactPerson,
            '{tanggal}' => $dateRange,
            '{jumlah_peserta}' => (string) $participants,
            '{nomor_schedule}' => 'SCH-' . str_pad($schedule->id, 5, '0', STR_PAD_LEFT),
            '{total_tagihan}' => 'Rp ' . number_format($paymentSummary['total_tagihan'], 0, ',', '.'),
            '{total_dibayar}' => 'Rp ' . number_format($paymentSummary['total_dibayar'], 0, ',', '.'),
            '{sisa_pembayaran}' => 'Rp ' . number_format($paymentSummary['sisa_pembayaran'], 0, ',', '.'),
            '{tanggal_kunjungan}' => $schedule->start_date->format('d/m/Y'),
        ];

        $messages = [
            'pending' => "Halo Bapak/Ibu,\n\n"
                . "Terima kasih telah menghubungi Desa Wisata Gabugan.\n\n"
                . "Permintaan kunjungan dari {nama_instansi} untuk tanggal {tanggal} telah kami terima "
                . "dan sedang dalam proses konfirmasi.\n\n"
                . "Jumlah peserta:\n{jumlah_peserta} orang\n\n"
                . "Nomor reservasi:\n{nomor_schedule}\n\n"
                . "Kami akan menghubungi Bapak/Ibu kembali setelah jadwal dan kebutuhan program dikonfirmasi.\n\n"
                . "Salam,\nDesa Wisata Gabugan",

            'confirmed' => "Halo Bapak/Ibu,\n\n"
                . "Reservasi kunjungan {nama_instansi} pada tanggal {tanggal} telah terkonfirmasi.\n\n"
                . "Jumlah peserta:\n{jumlah_peserta} orang\n\n"
                . "Status:\nTerkonfirmasi\n\n"
                . "Nomor reservasi:\n{nomor_schedule}\n\n"
                . "Mohon menginformasikan apabila terdapat perubahan jumlah peserta atau kebutuhan khusus.\n\n"
                . "Salam,\nDesa Wisata Gabugan",

            'reminder_payment' => "Halo Bapak/Ibu,\n\n"
                . "Kami menyampaikan informasi pembayaran program kunjungan Desa Wisata Gabugan.\n\n"
                . "Total tagihan:\n{total_tagihan}\n\n"
                . "Total pembayaran:\n{total_dibayar}\n\n"
                . "Sisa pembayaran:\n{sisa_pembayaran}\n\n"
                . "Mohon konfirmasi setelah pembayaran dilakukan.\n\n"
                . "Salam,\nDesa Wisata Gabugan",

            'upcoming_visit' => "Halo Bapak/Ibu,\n\n"
                . "Kami mengingatkan bahwa kunjungan {nama_instansi} ke Desa Wisata Gabugan dijadwalkan pada:\n\n"
                . "{tanggal_kunjungan}\n\n"
                . "Mohon konfirmasi:\n"
                . "- Jumlah peserta final\n"
                . "- Jam kedatangan\n"
                . "- Jumlah pendamping\n"
                . "- Alergi makanan\n"
                . "- Kebutuhan khusus\n"
                . "- Data kendaraan\n\n"
                . "Terima kasih.",
        ];

        $message = $messages[$template] ?? $messages['pending'];
        return str_replace(array_keys($vars), array_values($vars), $message);
    }

    /**
     * Get WhatsApp URL with generated message.
     */
    public function getWhatsAppUrl(Schedule $schedule, string $template = 'pending'): ?string
    {
        $estimation = $schedule->priceEstimation;
        $whatsapp = $estimation?->whatsapp ?? '';

        if (empty($whatsapp)) return null;

        // Normalize number
        $num = preg_replace('/[^0-9]/', '', $whatsapp);
        if (substr($num, 0, 1) === '0') $num = '62' . substr($num, 1);
        if (substr($num, 0, 2) !== '62') $num = '62' . $num;

        $message = $this->generateMessage($schedule, $template);
        return 'https://wa.me/' . $num . '?text=' . rawurlencode($message);
    }
}