<?php

namespace App\Enums;

/**
 * Source types for schedule entries.
 * Tracks where each schedule originated from.
 */
class ScheduleSourceType
{
    const MANUAL = 'manual';
    const PUBLIC_BOOKING = 'public_booking';
    const PRICE_ESTIMATION = 'price_estimation';
    const OPEN_TRIP = 'open_trip';
    const LEGACY = 'legacy';

    /**
     * Get all available source types.
     */
    public static function all(): array
    {
        return [
            self::MANUAL,
            self::PUBLIC_BOOKING,
            self::PRICE_ESTIMATION,
            self::OPEN_TRIP,
            self::LEGACY,
        ];
    }

    /**
     * Get labels for all source types.
     */
    public static function labels(): array
    {
        return [
            self::MANUAL => 'Input Manual',
            self::PUBLIC_BOOKING => 'Booking Website',
            self::PRICE_ESTIMATION => 'Kalkulator Harga',
            self::OPEN_TRIP => 'Open Trip',
            self::LEGACY => 'Legacy',
        ];
    }

    /**
     * Get label for a specific source type.
     */
    public static function label(string $type): string
    {
        return self::labels()[$type] ?? $type;
    }
}