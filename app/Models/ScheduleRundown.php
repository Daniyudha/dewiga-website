<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleRundown extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'rundown_template_id',
        'rundown_number',
        'title',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * Generate a unique rundown number.
     */
    public static function generateRundownNumber(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $last ? ((int) substr($last->rundown_number, -4)) + 1 : 1;

        return 'RDN-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(RundownTemplate::class, 'rundown_template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ScheduleRundownItem::class)->orderBy('day_number')->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}