<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ScheduleStatus;
use App\Models\Schedule;
use App\Models\TravelPackage;
use App\Models\RundownTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Services\ScheduleStatusService;
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

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                  ->orWhere('schedule_code', 'like', "%{$search}%")
                  ->orWhereHas('travelPackage', function ($pq) use ($search) {
                      $pq->where('type', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }

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
     * Display the specified schedule with tabs.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load([
            'travelPackage',
            'priceEstimation.items',
            'bookings.participants',
            'openTripRegistrations.participants',
            'payments',
            'statusHistories.changedBy',
        ]);

        $paymentService = app(\App\Services\SchedulePaymentService::class);
        $paymentSummary = $paymentService->getPaymentSummary($schedule);

        $templates = RundownTemplate::where('is_active', true)->orderBy('name')->get();

        return view('admin.schedules.show', compact('schedule', 'paymentSummary', 'templates'));
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
     * Update schedule status with validation and history.
     */
    public function updateStatus(Request $request, Schedule $schedule, ScheduleStatusService $statusService)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', ScheduleStatus::all()),
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $statusService->updateStatus($schedule, $validated['status'], $validated['notes'] ?? null);

            return redirect()->back()->with([
                'message' => 'Status jadwal berhasil diperbarui menjadi ' . ScheduleStatus::label($validated['status']) . '!',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Gagal memperbarui status: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
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
