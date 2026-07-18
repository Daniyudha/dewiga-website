<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenTripParticipant extends Model
{
    protected $fillable = [
        'open_trip_registration_id',
        'name',
        'email',
        'phone',
    ];

    public function openTripRegistration(): BelongsTo
    {
        return $this->belongsTo(OpenTripRegistration::class);
    }
}