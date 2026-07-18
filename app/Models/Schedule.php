<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'type' => 'string',
    ];

    /**
     * Get available types with labels.
     */
    public static function types(): array
    {
        return [
            'open_trip' => 'Open Trip',
            'confirmed' => 'Confirmed',
            'pending' => 'Pending',
        ];
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::types()[$this->type] ?? $this->type;
    }

    public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function openTripRegistrations()
    {
        return $this->hasMany(OpenTripRegistration::class);
    }

    /**
     * Check if schedule still has available quota.
     */
    public function isAvailable(): bool
    {
        return $this->booked < $this->quota;
    }

    /**
     * Get remaining quota.
     */
    public function remainingQuota(): int
    {
        return max(0, $this->quota - $this->booked);
    }

    /**
     * Scope: only active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: schedules that start from today or in the future.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereDate('start_date', '>=', now()->toDateString());
    }

    /**
     * Scope: schedules that are still available (not fully booked).
     */
    public function scopeAvailable($query)
    {
        return $query->whereColumn('booked', '<', 'quota');
    }

    /**
     * Scope: filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
