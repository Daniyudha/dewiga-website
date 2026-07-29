<?php

namespace App\Enums;

class PaymentStatus
{
    const UNPAID = 'unpaid';
    const PARTIALLY_PAID = 'partially_paid';
    const PAID = 'paid';
    const REFUNDED = 'refunded';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [self::UNPAID, self::PARTIALLY_PAID, self::PAID, self::REFUNDED, self::CANCELLED];
    }

    public static function labels(): array
    {
        return [
            self::UNPAID => 'Belum Dibayar',
            self::PARTIALLY_PAID => 'Dibayar Sebagian',
            self::PAID => 'Lunas',
            self::REFUNDED => 'Dikembalikan',
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
            self::UNPAID => 'bg-red-100 text-red-800',
            self::PARTIALLY_PAID => 'bg-yellow-100 text-yellow-800',
            self::PAID => 'bg-green-100 text-green-800',
            self::REFUNDED => 'bg-purple-100 text-purple-800',
            self::CANCELLED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}