<?php

namespace App\Services;

use App\Enums\ScheduleStatus;
use App\Models\Schedule;
use App\Models\ScheduleStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleStatusService
{
    /**
     * Update schedule status with validation and history logging.
     *
     * @param Schedule $schedule
     * @param string $newStatus
     * @param string|null $notes
     * @return Schedule
     * @throws ValidationException
     */
    public function updateStatus(Schedule $schedule, string $newStatus, ?string $notes = null): Schedule
    {
        $oldStatus = $schedule->status ?? ScheduleStatus::DRAFT;

        // Validate transition
        if (!$this->isValidTransition($oldStatus, $newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Transisi status dari '{$oldStatus}' ke '{$newStatus}' tidak diizinkan.",
            ]);
        }

        return DB::transaction(function () use ($schedule, $oldStatus, $newStatus, $notes) {
            // Update the status
            $schedule->update(['status' => $newStatus]);

            // Also update the backward-compatible 'type' field
            if (in_array($newStatus, ['pending', 'confirmed', 'completed', 'cancelled'])) {
                $schedule->update(['type' => $newStatus]);
            }

            // Record history
            ScheduleStatusHistory::create([
                'schedule_id' => $schedule->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $notes,
                'changed_by' => auth()->id(),
            ]);

            $schedule->load('statusHistories');
            return $schedule;
        });
    }

    /**
     * Check if a status transition is valid.
     */
    public function isValidTransition(string $from, string $to): bool
    {
        return ScheduleStatus::isValidTransition($from, $to);
    }

    /**
     * Get available next statuses for a schedule.
     */
    public function getAvailableNextStatuses(Schedule $schedule): array
    {
        $currentStatus = $schedule->status ?? ScheduleStatus::DRAFT;
        $transitions = ScheduleStatus::validTransitions();

        $available = [];
        foreach (($transitions[$currentStatus] ?? []) as $nextStatus) {
            $available[$nextStatus] = ScheduleStatus::label($nextStatus);
        }

        return $available;
    }
}