<?php

namespace App\Models;

use App\Enums\ScheduleSourceType;
use App\Enums\ScheduleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'type' => 'string',
        'status' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($schedule) {
            if (empty($schedule->schedule_code)) {
                $schedule->schedule_code = static::generateScheduleCode();
            }
        });
    }

    public static function generateScheduleCode(): string
    {
        $prefix = 'SCH';
        $date = now();
        $year = $date->format('y');
        $month = $date->format('m');

        $last = static::orderBy('id', 'desc')->lockForUpdate()->first();

        $seq = 1;
        if ($last && $last->schedule_code) {
            $lastSeq = (int) substr($last->schedule_code, -3);
            $seq = $lastSeq + 1;
        }

        return $prefix . $year . $month . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

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
     * Get payments for this schedule.
     */
    public function payments()
    {
        return $this->hasMany(SchedulePayment::class);
    }

    /**
     * Get status change history for this schedule.
     */
    public function statusHistories()
    {
        return $this->hasMany(ScheduleStatusHistory::class);
    }

    /**
     * Get the price estimation that was converted to this schedule.
     */
    public function priceEstimation()
    {
        return $this->belongsTo(PriceEstimation::class, 'price_estimation_id');
    }

    /**
     * Get the source model based on source_type and source_id.
     */
    public function source()
    {
        return match ($this->source_type) {
            ScheduleSourceType::PRICE_ESTIMATION => $this->belongsTo(PriceEstimation::class, 'source_id'),
            ScheduleSourceType::PUBLIC_BOOKING => $this->belongsTo(Booking::class, 'source_id'),
            default => null,
        };
    }

    /**
     * Get the schedule rundown (one active rundown per schedule).
     */
    public function scheduleRundown()
    {
        return $this->hasOne(ScheduleRundown::class);
    }

    /**
     * Get all rundowns for this schedule (for versioning support).
     */
    public function scheduleRundowns()
    {
        return $this->hasMany(ScheduleRundown::class);
    }

    /**
     * Legacy: Get items directly (for backward compatibility).
     */
    public function rundownItems()
    {
        return $this->hasMany(ScheduleRundownItem::class);
    }

    /**
     * Get the source type label.
     */
    public function getSourceLabelAttribute(): string
    {
        if ($this->source_type === null) {
            return 'Input Manual / Legacy';
        }
        return ScheduleSourceType::label($this->source_type);
    }

    /**
     * Get the status label using standardized enum.
     */
    public function getStatusLabelAttribute(): string
    {
        return ScheduleStatus::label($this->status);
    }

    /**
     * Get the CSS badge class for the status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return ScheduleStatus::badgeClass($this->status);
    }

    /**
     * Get calendar color for the status.
     */
    public function getCalendarColorAttribute(): string
    {
        return ScheduleStatus::calendarColor($this->status);
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

    /**
     * Scope: filter by source type.
     */
    public function scopeOfSource($query, string $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }

    /**
     * Scope: filter by status.
     */
    public function scopeOfStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Generate a unique schedule number.
     */
    public static function generateScheduleNumber(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $last ? ((int) substr($last->id, 0, 4)) + 1 : 1;

        return 'SCH-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
