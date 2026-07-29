<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RundownTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'duration_days',
        'duration_nights',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RundownTemplateItem::class)->orderBy('day_number')->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scheduleRundowns(): HasMany
    {
        return $this->hasMany(ScheduleRundown::class, 'rundown_template_id');
    }

    /**
     * Duplicate this template with all its items.
     */
    public function duplicate(string $newName = null): self
    {
        $clone = $this->replicate();
        $clone->name = $newName ?? $this->name . ' (Salinan)';
        $clone->code = null;
        $clone->created_by = auth()->id();
        $clone->save();

        foreach ($this->items as $item) {
            $itemClone = $item->replicate();
            $itemClone->rundown_template_id = $clone->id;
            $itemClone->save();
        }

        return $clone->load('items');
    }
}