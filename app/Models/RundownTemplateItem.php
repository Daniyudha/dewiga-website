<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RundownTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rundown_template_id', 'day_number', 'start_time', 'end_time',
        'activity_id', 'activity_name', 'location', 'person_in_charge',
        'description', 'sort_order',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(RundownTemplate::class, 'rundown_template_id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}