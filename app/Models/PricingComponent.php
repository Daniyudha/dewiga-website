<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingComponent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function priceTiers(): HasMany
    {
        return $this->hasMany(ParticipantPriceTier::class, 'pricing_component_id');
    }

    public function activeTiers(): HasMany
    {
        return $this->hasMany(ParticipantPriceTier::class, 'pricing_component_id');
    }

    /**
     * Scope a query to only include active components.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the price for a given number of participants based on tiers.
     */
    public function getTieredPrice(int $participants): ?float
    {
        $tier = $this->activeTiers()
            ->where('minimum_participants', '<=', $participants)
            ->where(function ($q) use ($participants) {
                $q->where('maximum_participants', '>=', $participants)
                  ->orWhereNull('maximum_participants');
            })
            ->first();

        if (!$tier) {
            return null;
        }

        $price = $tier->price;
        if ($tier->additional_price_per_participant && $participants > $tier->maximum_participants) {
            $price += ($participants - $tier->maximum_participants) * $tier->additional_price_per_participant;
        }

        return (float) $price;
    }

    /**
     * Get the matching tier for a given number of participants.
     */
    public function getMatchingTier(int $participants): ?ParticipantPriceTier
    {
        return $this->activeTiers()
            ->where('minimum_participants', '<=', $participants)
            ->where(function ($q) use ($participants) {
                $q->where('maximum_participants', '>=', $participants)
                  ->orWhereNull('maximum_participants');
            })
            ->first();
    }
}