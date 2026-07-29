<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Schedule;
use App\Models\SchedulePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchedulePaymentService
{
    /**
     * Record a payment for a schedule.
     */
    public function recordPayment(Schedule $schedule, array $data, $proofFile = null): SchedulePayment
    {
        return DB::transaction(function () use ($schedule, $data, $proofFile) {
            $payment = new SchedulePayment();
            $payment->schedule_id = $schedule->id;
            $payment->payment_number = SchedulePayment::generatePaymentNumber();
            $payment->payment_type = $data['payment_type'] ?? 'down_payment';
            $payment->amount = $data['amount'];
            $payment->payment_date = $data['payment_date'] ?? now();
            $payment->payment_method = $data['payment_method'] ?? 'bank_transfer';
            $payment->reference_number = $data['reference_number'] ?? null;
            $payment->notes = $data['notes'] ?? null;
            $payment->status = 'paid';
            $payment->created_by = auth()->id();

            if ($proofFile) {
                $payment->proof_file = $proofFile->store('payments/proofs', 'public');
            }

            $payment->save();

            return $payment;
        });
    }

    /**
     * Calculate payment summary for a schedule.
     */
    public function getPaymentSummary(Schedule $schedule): array
    {
        $totalTagihan = (float) ($schedule->priceEstimation?->quotation_total ?? 0);
        $payments = $schedule->payments()->where('status', 'paid')->get();
        $totalDibayar = (float) $payments->sum('amount');
        $sisaPembayaran = max(0, $totalTagihan - $totalDibayar);
        $kelebihan = $totalDibayar > $totalTagihan ? $totalDibayar - $totalTagihan : 0;

        // Auto-determine payment status
        if ($totalDibayar <= 0 || $totalTagihan <= 0) {
            $paymentStatus = PaymentStatus::UNPAID;
        } elseif ($totalDibayar >= $totalTagihan) {
            $paymentStatus = PaymentStatus::PAID;
        } else {
            $paymentStatus = PaymentStatus::PARTIALLY_PAID;
        }

        return [
            'total_tagihan' => $totalTagihan,
            'total_dibayar' => $totalDibayar,
            'sisa_pembayaran' => $sisaPembayaran,
            'kelebihan' => $kelebihan,
            'payment_status' => $paymentStatus,
            'payment_status_label' => PaymentStatus::label($paymentStatus),
        ];
    }
}