<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleRundownItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_rundown_id', 'schedule_id', 'day_number', 'activity_date',
        'start_time', 'end_time', 'activity_id', 'activity_name',
        'location', 'person_in_charge', 'description', 'sort_order',
    ];

    protected $casts = [
        'activity_date' => 'date:Y-m-d',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scheduleRundown(): BelongsTo
    {
        return $this->belongsTo(ScheduleRundown::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}