<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('order')->get();
        return view('activities.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }
}
