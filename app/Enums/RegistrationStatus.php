<?php

namespace App\Enums;

class RegistrationStatus
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const PAID = 'paid';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [self::PENDING, self::CONFIRMED, self::PAID, self::CANCELLED];
    }

    public static function labels(): array
    {
        return [
            self::PENDING => 'Menunggu Konfirmasi',
            self::CONFIRMED => 'Terkonfirmasi',
            self::PAID => 'Sudah Membayar',
            self::CANCELLED => 'Dibatalkan',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? $status;
    }

    public static function badgeClass(string $status): string
    {
        return match($status) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::CONFIRMED => 'bg-green-100 text-green-800',
            self::PAID => 'bg-blue-100 text-blue-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}