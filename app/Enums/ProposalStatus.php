<?php

namespace App\Enums;

class ProposalStatus
{
    const DRAFT = 'draft';
    const SENT = 'sent';
    const NEGOTIATION = 'negotiation';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const CONVERTED = 'converted';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::DRAFT,
            self::SENT,
            self::NEGOTIATION,
            self::APPROVED,
            self::REJECTED,
            self::CONVERTED,
            self::CANCELLED,
        ];
    }

    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::SENT => 'Terkirim',
            self::NEGOTIATION => 'Negosiasi',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::CONVERTED => 'Terkonversi ke Jadwal',
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
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::SENT => 'bg-blue-100 text-blue-800',
            self::NEGOTIATION => 'bg-yellow-100 text-yellow-800',
            self::APPROVED => 'bg-green-100 text-green-800',
            self::REJECTED => 'bg-red-100 text-red-800',
            self::CONVERTED => 'bg-emerald-100 text-emerald-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function validTransitions(): array
    {
        return [
            self::DRAFT => [self::SENT, self::CANCELLED],
            self::SENT => [self::NEGOTIATION, self::APPROVED, self::REJECTED, self::CANCELLED],
            self::NEGOTIATION => [self::SENT, self::APPROVED, self::REJECTED, self::CANCELLED],
            self::APPROVED => [self::CONVERTED, self::CANCELLED],
            self::REJECTED => [self::DRAFT, self::CANCELLED],
            self::CONVERTED => [],
            self::CANCELLED => [],
        ];
    }
}