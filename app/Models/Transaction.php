<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'category',
        'source',
        'debit',
        'credit',
        'balance',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:0',
        'credit' => 'decimal:0',
        'balance' => 'decimal:0',
    ];

    public function getTypeAttribute(): string
    {
        if ($this->debit > 0) return 'debit';
        if ($this->credit > 0) return 'credit';
        return '-';
    }

    public function getTypeLabelAttribute(): string
    {
        if ($this->debit > 0) return 'Debit';
        if ($this->credit > 0) return 'Kredit';
        return '-';
    }

    public function getTypeBadgeAttribute(): string
    {
        if ($this->debit > 0) return 'bg-green-100 text-green-800';
        if ($this->credit > 0) return 'bg-red-100 text-red-800';
        return 'bg-gray-100 text-gray-800';
    }

    public function getAmountFormattedAttribute(): string
    {
        if ($this->debit > 0) {
            return number_format($this->debit, 0, ',', '.');
        }
        if ($this->credit > 0) {
            return number_format($this->credit, 0, ',', '.');
        }
        return '-';
    }

    public static function categories(): array
    {
        return [
            'booking' => 'Booking',
            'open_trip' => 'Open Trip',
            'operasional' => 'Operasional',
            'gaji' => 'Gaji',
            'maintenance' => 'Maintenance',
            'lainya' => 'Lainnya',
        ];
    }

    public static function sources(): array
    {
        return [
            'pemasukan_booking' => 'Pemasukan Booking',
            'pemasukan_open_trip' => 'Pemasukan Open Trip',
            'pengeluaran_operasional' => 'Pengeluaran Operasional',
            'pengeluaran_gaji' => 'Pengeluaran Gaji',
            'pengeluaran_maintenance' => 'Pengeluaran Maintenance',
            'lainya' => 'Lainnya',
        ];
    }
}