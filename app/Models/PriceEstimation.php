<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceEstimation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'estimation_number';
    }

    protected $casts = [
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'student_count' => 'integer',
        'companion_count' => 'integer',
        'service_participant_count' => 'integer',
        'activity_participant_count' => 'integer',
        'subtotal' => 'decimal:2',
        'actual_price_per_person' => 'decimal:2',
        'rounded_price_per_person' => 'decimal:2',
        'quotation_total' => 'decimal:2',
        'difference_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PriceEstimationItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a unique estimation number.
     */
    public static function generateEstimationNumber(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $last ? ((int) substr($last->estimation_number, -4)) + 1 : 1;

        return 'EST-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}