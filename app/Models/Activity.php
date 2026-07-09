<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getTitleAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'title_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'description_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function getDurationAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'duration_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function getIncludesAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'includes_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }
}