<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGallery extends Model
{
    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}