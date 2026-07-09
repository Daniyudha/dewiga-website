<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the title in the current locale.
     */
    public function getTitleAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'title_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    /**
     * Get the excerpt in the current locale.
     */
    public function getExcerptAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'excerpt_' . $locale;
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

    /**
     * Get meta_keywords in the current locale.
     */
    public function getMetaKeywordsAttribute($value)
    {
        $locale = app()->getLocale();
        $localeField = 'meta_keywords_' . $locale;
        return $this->attributes[$localeField] ?? $value;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function incrementReadCount() {
        $this->reads++;
        return $this->save();
    }
}