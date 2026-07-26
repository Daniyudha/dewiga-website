<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantPriceTier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'additional_price_per_participant' => 'decimal:2',
    ];

    public function pricingComponent(): BelongsTo
    {
        return $this->belongsTo(PricingComponent::class);
    }
}