<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homestay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'name_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function getAddressAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'address_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_homestay')
            ->withPivot('group_name_id', 'group_name_en', 'notes')
            ->withTimestamps();
    }
}
