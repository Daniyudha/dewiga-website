<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalSetting extends Model
{
    protected $fillable = [
        'short_profile', 'vision', 'mission', 'advantages', 'location',
        'maps_url', 'contact', 'tagline', 'commitment',
        'dp_terms', 'cancellation_terms', 'participant_change_terms',
        'force_majeure_terms', 'payment_terms',
        'check_in_time', 'check_out_time', 'homestay_terms',
    ];

    public static function getSettings(): self
    {
        return self::firstOrNew([]);
    }
}