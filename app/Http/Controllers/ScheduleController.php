<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\TravelPackage;
use App\Models\HeroSetting;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display events page for public (3 types: open_trip, confirmed, pending).
     */
    public function index()
    {
        $travel_packages = TravelPackage::all();
        $heroSetting = HeroSetting::getForPage('schedules');

        // Get active schedules (including past, shown with different styling)
        $allSchedules = Schedule::with('travelPackage')
            ->active()
            ->whereHas('travelPackage')
            ->orderBy('start_date', 'asc')
            ->get();


        // Group by type
        $openTrips = $allSchedules->where('type', 'open_trip');
        $confirmed = $allSchedules->where('type', 'confirmed');
        $pending = $allSchedules->where('type', 'pending');

        // Build calendar events for FullCalendar - color by type
        $calendarEvents = $allSchedules->map(function ($s) {
            $color = match($s->type) {
                'confirmed' => '#059669',
                'pending' => '#f59e0b',
                default => '#3b82f6',
            };
            if (!$s->isAvailable()) $color = '#ef4444';
            return [
                'id' => $s->id,
                'title' => $s->visitor_name ?? $s->travelPackage->type,
                'start' => $s->start_date->format('Y-m-d'),
                'end' => $s->end_date ? $s->end_date->addDay()->format('Y-m-d') : $s->start_date->addDay()->format('Y-m-d'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                    'extendedProps' => [
                        'package_name' => $s->travelPackage->type,
                        'package_location' => $s->travelPackage->location,
                        'package_slug' => $s->travelPackage->slug,
                        'package_id' => $s->travelPackage->id,
                        'package_url' => route('travel_package.show', $s->travelPackage->slug ?? $s->travelPackage->id),
                        'quota' => $s->quota,
                        'booked' => $s->booked,
                        'remaining' => $s->remainingQuota(),
                        'visitor_name' => $s->visitor_name,
                        'type' => $s->type,
                        'type_label' => $s->type_label,
                        'start_date' => $s->start_date->format('d M Y'),
                        'end_date' => $s->end_date?->format('d M Y'),
                        'is_available' => $s->isAvailable(),
                    ],
            ];
        });

        // Build schedule data for registration modal
        $scheduleData = $allSchedules->map(function ($s) {
            return [
                'id' => $s->id,
                'travel_package_id' => $s->travel_package_id,
                'package_name' => $s->travelPackage->type,
                'package_location' => $s->travelPackage->location,
                'start_date' => $s->start_date->format('Y-m-d'),
                'end_date' => $s->end_date?->format('Y-m-d')
            ];
        });

        return view('schedules.index', compact(
            'allSchedules',
            'openTrips',
            'confirmed',
            'pending',
            'travel_packages',
            'calendarEvents',
            'scheduleData',
            'heroSetting'
        ));
    }
}
