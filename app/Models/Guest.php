<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'number_phone',
        'email',
        'source',
        'source_id',
        'notes',
    ];

    /**
     * Get the source label.
     */
    public function getSourceLabelAttribute(): string
    {
        $labels = [
            'manual' => 'Manual',
            'booking' => 'Booking',
            'open_trip' => 'Open Trip',
        ];

        return $labels[$this->source] ?? $this->source;
    }

    /**
     * Get source badge color.
     */
    public function getSourceBadgeAttribute(): string
    {
        $badges = [
            'manual' => 'bg-gray-100 text-gray-800',
            'booking' => 'bg-blue-100 text-blue-800',
            'open_trip' => 'bg-purple-100 text-purple-800',
        ];

        return $badges[$this->source] ?? 'bg-gray-100 text-gray-800';
    }
}