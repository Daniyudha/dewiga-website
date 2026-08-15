<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Scope query to only include published blogs, including scheduled blogs
     * whose published_at time has already passed (auto-published).
     */
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_PUBLISHED)
              ->orWhere(function ($q2) {
                  $q2->where('status', self::STATUS_SCHEDULED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
              });
        });
    }

    /**
     * Scope query to only include blogs that are scheduled and not yet past their published_at.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '>', now());
            });
    }

    /**
     * Scope query to only include draft blogs.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Determine if the blog is publicly visible (published and published_at <= now).
     */
    public function getIsPublicAttribute(): bool
    {
        return $this->is_published;
    }

    /**
     * Determine if the blog is effectively published.
     * A scheduled blog whose published_at has passed is considered published.
     */
    public function getIsPublishedAttribute(): bool
    {
        if ($this->status === self::STATUS_PUBLISHED) {
            return true;
        }

        if ($this->status === self::STATUS_SCHEDULED && $this->published_at) {
            return $this->published_at->lte(now());
        }

        return false;
    }

    /**
     * Get the human-readable status label (auto-published if past schedule time).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->effective_status) {
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_SCHEDULED => 'Scheduled',
            default => 'Draft',
        };
    }

    /**
     * Get the status badge CSS class (auto-published if past schedule time).
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->effective_status) {
            self::STATUS_PUBLISHED => 'bg-green-100 text-green-800',
            self::STATUS_SCHEDULED => 'bg-blue-100 text-blue-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    /**
     * Get the effective status: scheduled blogs past their published_at are treated as published.
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->is_published) {
            return self::STATUS_PUBLISHED;
        }

        return $this->status;
    }

    /**
     * Sync status in the database: auto-publish scheduled blogs whose time has come.
     */
    public function syncStatus(): bool
    {
        if ($this->status === self::STATUS_SCHEDULED
            && $this->published_at
            && $this->published_at->lte(now())) {
            $this->status = self::STATUS_PUBLISHED;
            return $this->save();
        }

        return false;
    }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementReadCount() {
        $this->reads++;
        return $this->save();
    }
}