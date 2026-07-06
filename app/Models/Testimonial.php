<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'content',
        'content_id',
        'content_en',
        'locale',
        'avatar',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Locale-aware accessor for content.
     * Returns the content in the current app locale,
     * with fallback to the original content column.
     */
    public function getContentAttribute($value)
    {
        $locale = app()->getLocale();

        if ($locale === 'id' && $this->content_id) {
            return $this->content_id;
        }

        if ($locale === 'en' && $this->content_en) {
            return $this->content_en;
        }

        // Fallback to original content column
        return $value;
    }
}
