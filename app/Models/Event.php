<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'participant_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public $routeKey = 'slug';

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'name_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'description_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function homestays()
    {
        return $this->belongsToMany(Homestay::class, 'event_homestay')
            ->withPivot('group_name_id', 'group_name_en', 'notes')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'event_activity')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today());
    }
}
