<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the name in the current locale.
     */
    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'name_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }
}