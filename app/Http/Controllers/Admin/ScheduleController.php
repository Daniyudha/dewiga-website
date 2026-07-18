<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\TravelPackage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules with calendar and table view.
     */
    public function index(Request $request)
    {
        $travel_packages = TravelPackage::all();

        $query = Schedule::with('travelPackage')
            ->whereHas('travelPackage');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $schedules = $query->orderBy('start_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Build calendar events for FullCalendar
        $calendarEvents = Schedule::with('travelPackage')
            ->whereHas('travelPackage')
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function ($s) {
                $color = match($s->type) {
                    'confirmed' => '#059669',
                    'pending' => '#f59e0b',
                    default => '#10b981',
                };
                if (!$s->isAvailable()) $color = '#ef4444';
                return [
                    'id' => $s->id,
                    'title' => ($s->visitor_name ? $s->visitor_name . ' - ' : '') . $s->travelPackage->type,
                    'start' => $s->start_date->format('Y-m-d'),
                    'end' => $s->end_date ? $s->end_date->addDay()->format('Y-m-d') : $s->start_date->addDay()->format('Y-m-d'),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'package_name' => $s->travelPackage->type . ' - ' . $s->travelPackage->location,
                        'package_id' => $s->travel_package_id,
                        'start_date' => $s->start_date->format('Y-m-d'),
                        'end_date' => $s->end_date?->format('Y-m-d'),
                        'quota' => $s->quota,
                        'booked' => $s->booked,
                        'remaining' => $s->remainingQuota(),
                        'visitor_name' => $s->visitor_name,
                        'type' => $s->type_label,
                        'is_active' => $s->is_active,
                    ],
                ];
            });

        return view('admin.schedules.index', compact('schedules', 'travel_packages', 'calendarEvents'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $travel_packages = TravelPackage::all();
        $types = Schedule::types();
        return view('admin.schedules.create', compact('travel_packages', 'types'));
    }

    /**
     * Store a newly created schedule.
     */
    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();
        $data['booked'] = 0;

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with([
            'message' => 'Jadwal berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        $travel_packages = TravelPackage::all();
        $types = Schedule::types();
        return view('admin.schedules.edit', compact('schedule', 'travel_packages', 'types'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return redirect()->route('admin.schedules.index')->with([
            'message' => 'Jadwal berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with([
            'message' => 'Jadwal berhasil dihapus!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Quick toggle active status via AJAX.
     */
    public function toggleActive(Request $request, Schedule $schedule)
    {
        $schedule->update(['is_active' => $request->boolean('is_active')]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with([
            'message' => 'Status jadwal diperbarui!',
            'alert-type' => 'success',
        ]);
    }
}
