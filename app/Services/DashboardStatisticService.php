<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\ScheduleStatus;
use App\Models\PriceEstimation;
use App\Models\Schedule;
use App\Models\SchedulePayment;
use App\Models\OpenTripRegistration;
use Illuminate\Support\Facades\DB;

class DashboardStatisticService
{
    /**
     * Get operational statistics for the dashboard.
     *
     * @param \Carbon\Carbon|null $startDate Start date for filtering (default: 3 months ago)
     * @param \Carbon\Carbon|null $endDate End date for filtering (default: now)
     */
    public function getOperationalStats($startDate = null, $endDate = null): array
    {
        $now = now();
        $startDate = $startDate ?? $now->copy()->subMonths(3)->startOfDay();
        $endDate = $endDate ?? $now->copy()->endOfDay();

        // Reservasi pending dalam periode
        $pendingReservations = Schedule::where('status', ScheduleStatus::PENDING)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Reservasi confirmed dalam periode
        $confirmedReservations = Schedule::where('status', ScheduleStatus::CONFIRMED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Kunjungan dalam periode
        $visitsThisMonth = Schedule::whereBetween('start_date', [$startDate, $endDate])
            ->whereIn('status', [ScheduleStatus::CONFIRMED, ScheduleStatus::IN_PROGRESS, ScheduleStatus::COMPLETED])
            ->count();

        // Total peserta dalam periode (dari quotation estimation)
        $participantsThisMonth = PriceEstimation::whereBetween('arrival_date', [$startDate, $endDate])
            ->sum('service_participant_count');

        // Nilai quotation dalam periode
        $quotationValue = PriceEstimation::whereBetween('created_at', [$startDate, $endDate])
            ->sum('quotation_total');

        // Pembayaran diterima dalam periode
        $paymentsReceived = SchedulePayment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // Sisa tagihan (total quotation - total paid) dalam periode
        $sisaTagihan = 0;
        $totalQuotation = PriceEstimation::whereBetween('created_at', [$startDate, $endDate])->sum('quotation_total');
        $totalPaid = SchedulePayment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
        $sisaTagihan = max(0, $totalQuotation - $totalPaid);

        // Open trip aktif dalam periode
        $activeOpenTrips = Schedule::where('type', 'open_trip')
            ->whereIn('status', [ScheduleStatus::PENDING, ScheduleStatus::CONFIRMED])
            ->where('is_active', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Total pendaftar open trip dalam periode
        $totalOpenTripRegistrations = OpenTripRegistration::whereBetween('created_at', [$startDate, $endDate])->count();

        // Quotation belum dikonversi dalam periode
        $unconvertedQuotations = PriceEstimation::whereDoesntHave('schedule')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'pending_reservations' => $pendingReservations,
            'confirmed_reservations' => $confirmedReservations,
            'visits_this_month' => $visitsThisMonth,
            'participants_this_month' => $participantsThisMonth,
            'quotation_value' => $quotationValue,
            'payments_received' => $paymentsReceived,
            'sisa_tagihan' => $sisaTagihan,
            'active_open_trips' => $activeOpenTrips,
            'total_open_trip_registrations' => $totalOpenTripRegistrations,
            'unconverted_quotations' => $unconvertedQuotations,
            'total_quotation' => $totalQuotation,
            'total_paid' => $totalPaid,
        ];
    }

    /**
     * Get upcoming reservations list.
     */
    public function getUpcomingReservations(int $limit = 10)
    {
        return Schedule::with('travelPackage', 'priceEstimation')
            ->whereIn('status', [ScheduleStatus::PENDING, ScheduleStatus::CONFIRMED, ScheduleStatus::IN_PROGRESS])
            ->where('start_date', '>=', now()->subDay())
            ->orderBy('start_date')
            ->limit($limit)
            ->get()
            ->map(function ($s) {
                $paymentSummary = app(SchedulePaymentService::class)->getPaymentSummary($s);
                return [
                    'id' => $s->id,
                    'visitor_name' => $s->visitor_name,
                    'start_date' => $s->start_date,
                    'end_date' => $s->end_date,
                    'participants' => $s->priceEstimation?->service_participant_count ?? $s->booked,
                    'status' => $s->status,
                    'status_label' => $s->status_label,
                    'status_badge' => $s->status_badge,
                    'payment_status' => $paymentSummary['payment_status'],
                    'payment_status_label' => $paymentSummary['payment_status_label'],
                    'package' => $s->travelPackage?->type ?? '-',
                ];
            });
    }

    /**
     * Get quotations needing follow-up (draft/sent, >3 days old, not converted).
     */
    public function getQuotationsNeedFollowUp(int $limit = 10)
    {
        return PriceEstimation::with('createdBy')
            ->whereDoesntHave('schedule')
            ->where('created_at', '<', now()->subDays(3))
            ->orderBy('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get open trips almost full (>=80% quota).
     */
    public function getOpenTripsNearlyFull(int $limit = 10)
    {
        return Schedule::with('travelPackage')
            ->where('type', 'open_trip')
            ->where('is_active', true)
            ->whereColumn('booked', '>=', DB::raw('quota * 0.8'))
            ->whereColumn('booked', '<', 'quota')
            ->orderByDesc(DB::raw('booked / quota'))
            ->limit($limit)
            ->get();
    }
}