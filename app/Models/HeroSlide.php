<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'hero_setting_id',
        'image',
        'alt_text',
        'order',
    ];

    public function heroSetting(): BelongsTo
    {
        return $this->belongsTo(HeroSetting::class);
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image);
    }
}
