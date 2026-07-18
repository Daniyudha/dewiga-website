<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenTripRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'number_phone',
        'institution',
        'date',
        'start_date',
        'end_date',
        'travel_package_id',
        'schedule_id',
        'status',
        'notes',
        'amount',
        'people_count',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:0',
        'people_count' => 'integer',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }

    public function participants()
    {
        return $this->hasMany(OpenTripParticipant::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
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
            'completed' => 'Completed',
        ];
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }
}
