<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'status' => 'string',
        'people_count' => 'integer',
        'guest_type' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateBookingCode();
            }
        });
    }

    public static function generateBookingCode(): string
    {
        $prefix = 'BO';
        $date = now();
        $year = $date->format('y');
        $month = $date->format('m');

        $lastBooking = static::orderBy('id', 'desc')->lockForUpdate()->first();

        $seq = 1;
        if ($lastBooking && $lastBooking->booking_code) {
            $lastSeq = (int) substr($lastBooking->booking_code, -3);
            $seq = $lastSeq + 1;
        }

        return $prefix . $year . $month . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get available statuses with labels.
     */
    public static function statuses(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function travel_package()
    {
        return $this->belongsTo(TravelPackage::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function participants()
    {
        return $this->hasMany(BookingParticipant::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}