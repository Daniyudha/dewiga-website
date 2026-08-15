<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\SchedulePayment;

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
        $whatsapp = $this->resolveCustomerWhatsAppNumber($schedule);

        if (empty($whatsapp)) {
            return null;
        }

        $num = $this->normalizePhoneNumber($whatsapp);

        $message = $this->generateMessage($schedule, $template);
        return 'https://wa.me/' . $num . '?text=' . rawurlencode($message);
    }

    /**
     * Get WhatsApp URL to send a Midtrans payment link to the customer.
     */
    public function getPaymentLinkWhatsAppUrl(Schedule $schedule, SchedulePayment $payment): ?string
    {
        if (empty($payment->midtrans_payment_link)) {
            return null;
        }

        $whatsapp = $this->resolveCustomerWhatsAppNumber($schedule);

        if (empty($whatsapp)) {
            return null;
        }

        $num = $this->normalizePhoneNumber($whatsapp);

        $message = $this->generatePaymentLinkMessage($schedule, $payment);

        return 'https://wa.me/' . $num . '?text=' . rawurlencode($message);
    }

    /**
     * Resolve the customer's WhatsApp number from the schedule's related data.
     *
     * Priority:
     * 1. priceEstimation->whatsapp (schedule converted from estimation)
     * 2. source relation->number_phone or ->whatsapp
     * 3. related booking->number_phone
     * 4. related openTripRegistration->number_phone
     * 5. schedule->number_phone (fallback)
     */
    protected function resolveCustomerWhatsAppNumber(Schedule $schedule): string
    {
        // 1. From schedule->number_phone (direct column on schedules table)
        if (!empty($schedule->number_phone)) {
            return $schedule->number_phone;
        }

        // 2. From price estimation
        $estimation = $schedule->priceEstimation;
        if ($estimation && !empty($estimation->whatsapp)) {
            return $estimation->whatsapp;
        }

        // 3. From source relation (booking or price estimation)
        $sourceRelation = $schedule->source();
        if ($sourceRelation !== null && $schedule->getRelationValue('source')) {
            $source = $schedule->getRelationValue('source');
            if (!empty($source->number_phone)) {
                return $source->number_phone;
            }
            if (!empty($source->whatsapp)) {
                return $source->whatsapp;
            }
        }

        // 4. From related bookings
        $booking = $schedule->bookings->first();
        if ($booking && !empty($booking->number_phone)) {
            return $booking->number_phone;
        }

        // 5. From related open trip registrations
        $openTrip = $schedule->openTripRegistrations->first();
        if ($openTrip && !empty($openTrip->number_phone)) {
            return $openTrip->number_phone;
        }

        // 6. Fallback: empty string
        return '';
    }

    /**
     * Normalize a phone number to international format for wa.me links.
     */
    protected function normalizePhoneNumber(string $whatsapp): string
    {
        $num = preg_replace('/[^0-9]/', '', $whatsapp);
        if (substr($num, 0, 1) === '0') {
            $num = '62' . substr($num, 1);
        }
        if (substr($num, 0, 2) !== '62') {
            $num = '62' . $num;
        }
        return $num;
    }

    /**
     * Build WhatsApp message containing the Midtrans payment link.
     */
    protected function generatePaymentLinkMessage(Schedule $schedule, SchedulePayment $payment): string
    {
        $institution = $schedule->priceEstimation?->institution_name ?? ($schedule->visitor_name ?? 'Instansi');
        $dateRange = $schedule->start_date->format('d/m/Y');
        if ($schedule->end_date && $schedule->end_date != $schedule->start_date) {
            $dateRange .= ' - ' . $schedule->end_date->format('d/m/Y');
        }

        $amount = 'Rp ' . number_format((float) $payment->amount, 0, ',', '.');

        return "Halo Bapak/Ibu,\n\n"
            . "Berikut adalah link pembayaran untuk kunjungan {$institution} ke Desa Wisata Gabugan pada tanggal {$dateRange}.\n\n"
            . "Jenis pembayaran: {$payment->type_label}\n"
            . "Nominal: {$amount}\n"
            . "No. Pembayaran: {$payment->payment_number}\n\n"
            . "Silakan selesaikan pembayaran melalui link berikut:\n"
            . $payment->midtrans_payment_link . "\n\n"
            . "Mohon konfirmasi setelah pembayaran selesai.\n\n"
            . "Salam,\nDesa Wisata Gabugan";
    }
}