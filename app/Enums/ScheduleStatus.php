<?php

namespace App\Enums;

/**
 * Standardized status values for schedules/reservations.
 */
class ScheduleStatus
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    /**
     * Get all available status values.
     */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::PENDING,
            self::CONFIRMED,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    /**
     * Get labels for all statuses.
     */
    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::PENDING => 'Menunggu Konfirmasi',
            self::CONFIRMED => 'Terkonfirmasi',
            self::IN_PROGRESS => 'Sedang Berlangsung',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        ];
    }

    /**
     * Get label for a specific status.
     */
    public static function label(string $status): string
    {
        return self::labels()[$status] ?? $status;
    }

    /**
     * Valid transitions for schedule status.
     * Key: current status, Value: array of allowed next statuses.
     */
    public static function validTransitions(): array
    {
        return [
            self::DRAFT => [self::PENDING, self::CANCELLED],
            self::PENDING => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::IN_PROGRESS, self::CANCELLED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED => [], // Terminal state - no transitions out
            self::CANCELLED => [], // Terminal state - no transitions out
        ];
    }

    /**
     * Check if a status transition is valid.
     */
    public static function isValidTransition(string $from, string $to): bool
    {
        $transitions = self::validTransitions();
        return isset($transitions[$from]) && in_array($to, $transitions[$from]);
    }

    /**
     * Get CSS badge class for a status.
     */
    public static function badgeClass(string $status): string
    {
        return match($status) {
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::CONFIRMED => 'bg-green-100 text-green-800',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::COMPLETED => 'bg-emerald-100 text-emerald-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get calendar color for a status.
     */
    public static function calendarColor(string $status): string
    {
        return match($status) {
            self::DRAFT => '#9ca3af',       // gray
            self::PENDING => '#f59e0b',     // yellow
            self::CONFIRMED => '#10b981',   // green
            self::IN_PROGRESS => '#3b82f6', // blue
            self::COMPLETED => '#059669',   // dark green
            self::CANCELLED => '#ef4444',   // red
            default => '#9ca3af',
        };
    }
}