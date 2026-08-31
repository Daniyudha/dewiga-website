<?php

namespace App\Enums;

class QuotationStatus
{
    const DRAFT = 'draft';
    const SENT = 'sent';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::DRAFT,
            self::SENT,
            self::APPROVED,
            self::REJECTED,
            self::CANCELLED,
        ];
    }

    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::SENT => 'Terkirim',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::CANCELLED => 'Dibatalkan',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? $status;
    }

    public static function badgeClass(string $status): string
    {
        return match ($status) {
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::SENT => 'bg-blue-100 text-blue-800',
            self::APPROVED => 'bg-green-100 text-green-800',
            self::REJECTED => 'bg-red-100 text-red-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}