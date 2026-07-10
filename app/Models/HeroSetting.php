<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class HeroSetting extends Model
{
    protected $fillable = [
        'page',
        'image',
    ];

    public function slides(): HasMany
    {
        return $this->hasMany(HeroSlide::class)->orderBy('order');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public static function getForPage(string $page): ?self
    {
        return static::with('slides')->where('page', $page)->first();
    }
}
