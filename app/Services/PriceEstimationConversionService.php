<?php

namespace App\Services;

use App\Enums\ScheduleSourceType;
use App\Enums\ScheduleStatus;
use App\Models\PriceEstimation;
use App\Models\Schedule;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for converting Price Estimation into Schedule.
 * Handles validation, data copying, and relationship management.
 */
class PriceEstimationConversionService
{
    /**
     * Convert a PriceEstimation to a Schedule.
     *
     * @param PriceEstimation $estimation
     * @param array $overrideData Optional overrides for schedule fields
     * @return Schedule
     * @throws \Exception
     */
    public function convert(PriceEstimation $estimation, array $overrideData = []): Schedule
    {
        // Prevent duplicate conversion
        if ($this->isAlreadyConverted($estimation)) {
            throw new \Exception(
                'Estimasi ' . $estimation->estimation_number . ' sudah dikonversi menjadi jadwal.'
            );
        }

        return DB::transaction(function () use ($estimation, $overrideData) {
            // Find or use first travel package as default
            $travelPackage = TravelPackage::first();
            if (!$travelPackage) {
                throw new \Exception('Belum ada paket wisata yang tersedia. Buat paket wisata terlebih dahulu.');
            }

            // Build schedule data from estimation
            $scheduleTitle = $overrideData['title']
                ?? $estimation->institution_name . ' - '
                   . $estimation->arrival_date->format('d/m/Y')
                   . ' s.d. '
                   . $estimation->departure_date->format('d/m/Y');

            $defaultStatus = $overrideData['status'] ?? ScheduleStatus::PENDING;

            // Calculate total participants
            $totalParticipants = $estimation->service_participant_count;

            $scheduleData = [
                'travel_package_id' => $overrideData['travel_package_id'] ?? $travelPackage->id,
                'type' => $defaultStatus, // Keep backward compat with existing 'type' field
                'source_type' => ScheduleSourceType::PRICE_ESTIMATION,
                'source_id' => $estimation->id,
                'price_estimation_id' => $estimation->id,
                'status' => $defaultStatus,
                'start_date' => $overrideData['start_date'] ?? $estimation->arrival_date,
                'end_date' => $overrideData['end_date'] ?? $estimation->departure_date,
                'visitor_name' => $overrideData['visitor_name']
                    ?? $estimation->institution_name . ' - ' . $estimation->contact_person,
                'number_phone' => $overrideData['number_phone'] ?? $estimation->whatsapp ?? null,
                'quota' => $overrideData['quota'] ?? max($totalParticipants, 1),
                'booked' => 0,
                'is_active' => true,
            ];

            // Apply any remaining override fields
            foreach (['start_time', 'end_time', 'notes'] as $field) {
                if (isset($overrideData[$field])) {
                    $scheduleData[$field] = $overrideData[$field];
                }
            }

            // Create schedule
            $schedule = Schedule::create($scheduleData);

            // Log the conversion
            Log::info('Price Estimation converted to Schedule', [
                'estimation_id' => $estimation->id,
                'estimation_number' => $estimation->estimation_number,
                'schedule_id' => $schedule->id,
                'converted_by' => auth()->id(),
            ]);

            // Reload with relationships
            $schedule->load('priceEstimation');

            return $schedule;
        });
    }

    /**
     * Check if estimation has already been converted to a schedule.
     *
     * @param PriceEstimation $estimation
     * @return bool
     */
    public function isAlreadyConverted(PriceEstimation $estimation): bool
    {
        return Schedule::where('price_estimation_id', $estimation->id)->exists()
            || Schedule::where('source_type', ScheduleSourceType::PRICE_ESTIMATION)
                ->where('source_id', $estimation->id)
                ->exists();
    }

    /**
     * Get the schedule that was converted from this estimation, if any.
     *
     * @param PriceEstimation $estimation
     * @return Schedule|null
     */
    public function getConvertedSchedule(PriceEstimation $estimation): ?Schedule
    {
        return Schedule::where('price_estimation_id', $estimation->id)->first()
            ?? Schedule::where('source_type', ScheduleSourceType::PRICE_ESTIMATION)
                ->where('source_id', $estimation->id)
                ->first();
    }

    /**
     * Get the estimation that was converted to this schedule, if any.
     *
     * @param Schedule $schedule
     * @return PriceEstimation|null
     */
    public function getSourceEstimation(Schedule $schedule): ?PriceEstimation
    {
        if ($schedule->price_estimation_id) {
            return PriceEstimation::find($schedule->price_estimation_id);
        }

        if ($schedule->source_type === ScheduleSourceType::PRICE_ESTIMATION && $schedule->source_id) {
            return PriceEstimation::find($schedule->source_id);
        }

        return null;
    }
}