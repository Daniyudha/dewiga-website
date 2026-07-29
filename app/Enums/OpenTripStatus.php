<?php

namespace App\Enums;

class OpenTripStatus
{
    const DRAFT = 'draft';
    const OPEN = 'open';
    const FULL = 'full';
    const CLOSED = 'closed';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [self::DRAFT, self::OPEN, self::FULL, self::CLOSED, self::COMPLETED, self::CANCELLED];
    }

    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::OPEN => 'Dibuka',
            self::FULL => 'Kuota Penuh',
            self::CLOSED => 'Pendaftaran Ditutup',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? $status;
    }
}