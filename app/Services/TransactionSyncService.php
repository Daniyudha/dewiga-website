<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Booking;
use App\Models\OpenTripRegistration;
use App\Models\SchedulePayment;

class TransactionSyncService
{
    public function syncFromSchedulePayment(SchedulePayment $payment): ?Transaction
    {
        $schedule = $payment->schedule;
        if (!$schedule) {
            return null;
        }

        $existing = Transaction::where('description', 'like', "%Pembayaran #{$payment->id}%")
            ->first();

        if ($existing) {
            return $existing;
        }

        $latestBalance = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->first();
        $lastBalance = $latestBalance ? $latestBalance->balance : 0;

        $description = 'Pembayaran Schedule #' . $schedule->id;
        $booking = Booking::where('schedule_id', $schedule->id)->first();
        if ($booking) {
            $description .= ' - ' . ($booking->name ?? '');
        } elseif ($schedule->visitor_name) {
            $description .= ' - ' . $schedule->visitor_name;
        }

        $paymentTypeLabel = $payment->type_label ?? $payment->payment_type;
        $description .= ' (' . $paymentTypeLabel . ')';

        return Transaction::create([
            'date' => $payment->payment_date ?? $payment->created_at ?? now(),
            'description' => $description,
            'category' => 'booking',
            'source' => 'pemasukan_booking',
            'debit' => $payment->amount,
            'credit' => 0,
            'balance' => $lastBalance + $payment->amount,
            'notes' => 'Otomatis dari pembayaran #' . $payment->id,
        ]);
    }

    public function syncFromBooking(Booking $booking): ?Transaction
    {
        if (!$booking->amount || $booking->amount <= 0) {
            return null;
        }

        $existing = Transaction::where('source', 'pemasukan_booking')
            ->where('description', 'like', "%Booking #{$booking->id}%")
            ->first();

        if ($existing) {
            return $existing;
        }

        $latestBalance = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->first();
        $lastBalance = $latestBalance ? $latestBalance->balance : 0;

        $date = $booking->start_date ?? $booking->date ?? now();

        return Transaction::create([
            'date' => $date,
            'description' => 'Pembayaran Booking #' . $booking->id . ' - ' . ($booking->name ?? ''),
            'category' => 'booking',
            'source' => 'pemasukan_booking',
            'debit' => $booking->amount,
            'credit' => 0,
            'balance' => $lastBalance + $booking->amount,
            'notes' => 'Otomatis dari booking #' . $booking->id . ' (' . ($booking->institution ?? '-') . ')',
        ]);
    }

    public function syncFromOpenTrip(OpenTripRegistration $registration): ?Transaction
    {
        if (!$registration->amount || $registration->amount <= 0) {
            return null;
        }

        $existing = Transaction::where('source', 'pemasukan_open_trip')
            ->where('description', 'like', "%Open Trip #{$registration->id}%")
            ->first();

        if ($existing) {
            return $existing;
        }

        $latestBalance = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->first();
        $lastBalance = $latestBalance ? $latestBalance->balance : 0;

        $date = $registration->start_date ?? $registration->date ?? now();

        return Transaction::create([
            'date' => $date,
            'description' => 'Pembayaran Open Trip #' . $registration->id . ' - ' . ($registration->name ?? ''),
            'category' => 'open_trip',
            'source' => 'pemasukan_open_trip',
            'debit' => $registration->amount,
            'credit' => 0,
            'balance' => $lastBalance + $registration->amount,
            'notes' => 'Otomatis dari open trip #' . $registration->id . ' (' . ($registration->institution ?? '-') . ')',
        ]);
    }

    public function syncAllExisting(): array
    {
        $counts = ['bookings' => 0, 'open_trips' => 0];

        Booking::where('status', 'confirmed')
            ->whereNotNull('amount')
            ->where('amount', '>', 0)
            ->chunk(100, function ($bookings) use (&$counts) {
                foreach ($bookings as $booking) {
                    $this->syncFromBooking($booking);
                    $counts['bookings']++;
                }
            });

        OpenTripRegistration::whereIn('status', ['confirmed', 'completed'])
            ->whereNotNull('amount')
            ->where('amount', '>', 0)
            ->chunk(100, function ($registrations) use (&$counts) {
                foreach ($registrations as $registration) {
                    $this->syncFromOpenTrip($registration);
                    $counts['open_trips']++;
                }
            });

        $this->recalculateBalances();

        return $counts;
    }

    public function recalculateBalances(): void
    {
        $transactions = Transaction::orderBy('date', 'asc')->orderBy('created_at', 'asc')->get();
        $balance = 0;
        foreach ($transactions as $t) {
            $balance += $t->debit - $t->credit;
            $t->update(['balance' => $balance]);
        }
    }
}