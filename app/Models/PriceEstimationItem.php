<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceEstimationItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
        'frequency' => 'integer',
        'unit_price' => 'decimal:2',
        'price_per_person' => 'decimal:2',
        'total' => 'decimal:2',
        'calculation_details' => 'json',
    ];

    public function estimation(): BelongsTo
    {
        return $this->belongsTo(PriceEstimation::class, 'price_estimation_id');
    }

    /**
     * Get the effective multiplier for this item.
     * Uses saved calculation_details if present, otherwise detects from total equation.
     */
    public function getMultiplierAttribute(): int
    {
        $details = $this->calculation_details ?: [];

        // Use saved multiplier if explicitly active
        if (!empty($details['multiplier_active'])) {
            return max(1, (int) ($details['multiplier'] ?? 1));
        }

        // Fallback: detect from total equation (total = qty * freq * unit_price * multiplier)
        $base = $this->quantity * $this->frequency * (float) $this->unit_price;
        if ($base > 0 && (float) $this->total > $base) {
            return max(1, (int) round((float) $this->total / $base));
        }

        return 1;
    }

    /**
     * Whether this item has an active multiplier.
     * Returns true if multiplier is explicitly activated (even if value is 1),
     * or if auto-detected multiplier is greater than 1.
     */
    public function getHasMultiplierAttribute(): bool
    {
        $details = $this->calculation_details ?: [];

        // Explicitly saved as active → always true
        if (!empty($details['multiplier_active'])) {
            return true;
        }

        // Auto-detect: total greater than base equation indicates a multiplier
        return $this->multiplier > 1;
    }
}
