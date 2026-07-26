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
}