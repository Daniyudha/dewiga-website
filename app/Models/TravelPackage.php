<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the type in the current locale.
     */
    public function getTypeAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'type_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    /**
     * Get the location in the current locale.
     */
    public function getLocationAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'location_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    /**
     * Get the description in the current locale.
     */
    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'description_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
