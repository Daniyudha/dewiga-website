<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpenTripRegistration;
use App\Models\Schedule;
use App\Models\TravelPackage;
use Illuminate\Http\Request;

class OpenTripRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $schedules = Schedule::with('travelPackage')
            ->where('type', 'open_trip')
            ->withCount(['openTripRegistrations as registrations_count' => function ($q) {
                $q->whereIn('status', ['pending', 'confirmed']);
            }])
            ->withCount(['openTripRegistrations as confirmed_count' => function ($q) {
                $q->where('status', 'confirmed');
            }])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('admin.open-trip-registrations.index', compact('schedules'));
    }

    public function showSchedule(Schedule $schedule)
    {
        if ($schedule->type !== 'open_trip') {
            abort(404);
        }

        $registrations = OpenTripRegistration::with('participants')
            ->where('schedule_id', $schedule->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.open-trip-registrations.show-event', compact('schedule', 'registrations'));
    }

    public function create()
    {
        $schedules = Schedule::with('travelPackage')
            ->active()
            ->where('type', 'open_trip')
            ->orderBy('start_date', 'asc')
            ->get();
        return view('admin.open-trip-registrations.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'schedule_id' => 'required|exists:schedules,id',
            'people_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $schedule = Schedule::find($validated['schedule_id']);
        $validated['date'] = $schedule->start_date->format('Y-m-d');
        $validated['start_date'] = $validated['date'];
        $validated['end_date'] = $schedule->end_date?->format('Y-m-d');
        $validated['travel_package_id'] = $schedule->travel_package_id;

        $openTripRegistration = OpenTripRegistration::create($validated);

        if ($openTripRegistration->status === 'confirmed') {
            $schedule->increment('booked', $openTripRegistration->people_count);
        }

        return redirect()->route('admin.open-trip-registrations.schedule', $schedule)
            ->with('success', 'Registration created successfully.');
    }

    public function show(OpenTripRegistration $openTripRegistration)
    {
        $openTripRegistration->load('travelPackage', 'schedule.travelPackage', 'participants');
        return view('admin.open-trip-registrations.show', compact('openTripRegistration'));
    }

    public function update(Request $request, OpenTripRegistration $openTripRegistration)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'schedule_id' => 'required|exists:schedules,id',
            'people_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $oldStatus = $openTripRegistration->status;
        $oldScheduleId = $openTripRegistration->schedule_id;
        $oldPeopleCount = $openTripRegistration->people_count;

        $openTripRegistration->update($validated);

        if ($oldStatus === 'confirmed' && $oldScheduleId && $openTripRegistration->status !== 'confirmed') {
            $schedule = Schedule::find($oldScheduleId);
            if ($schedule && $schedule->booked > 0) {
                $schedule->decrement('booked', $oldPeopleCount);
            }
        }

        if ($openTripRegistration->status === 'confirmed' && $openTripRegistration->schedule_id) {
            $schedule = Schedule::find($openTripRegistration->schedule_id);
            if ($schedule && ($oldStatus !== 'confirmed' || $oldScheduleId != $openTripRegistration->schedule_id)) {
                $schedule->increment('booked', $openTripRegistration->people_count);
            }
        }

        return redirect()->route('admin.open-trip-registrations.schedule', $openTripRegistration->schedule)
            ->with('success', 'Registration updated successfully.');
    }

    public function edit(OpenTripRegistration $openTripRegistration)
    {
        $schedules = Schedule::with('travelPackage')
            ->active()
            ->where('type', 'open_trip')
            ->orderBy('start_date', 'asc')
            ->get();
        return view('admin.open-trip-registrations.edit', compact('openTripRegistration', 'schedules'));
    }

    public function destroy(OpenTripRegistration $openTripRegistration)
    {
        $scheduleId = $openTripRegistration->schedule_id;

        // Decrement booked count if was confirmed or completed
        if (in_array($openTripRegistration->status, ['confirmed', 'completed']) && $scheduleId) {
            $schedule = Schedule::find($scheduleId);
            if ($schedule && $schedule->booked > 0) {
                $schedule->decrement('booked', $openTripRegistration->people_count);
            }
        }

        $openTripRegistration->delete();

        return redirect()->route('admin.open-trip-registrations.schedule', $scheduleId)
            ->with('success', 'Registration deleted successfully.');
    }

    /**
     * Recalculate booked count for a schedule.
     */
    public function recalculate(Schedule $schedule)
    {
        $totalBooked = OpenTripRegistration::where('schedule_id', $schedule->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('people_count');

        $schedule->update(['booked' => $totalBooked]);

        return redirect()->back()->with('success', 'Booked count recalculated: ' . $totalBooked);
    }

    public function export(Schedule $schedule)
    {
        if ($schedule->type !== 'open_trip') {
            return redirect()->back()->with('error', 'Invalid schedule type.');
        }

        $registrations = OpenTripRegistration::with('participants')
            ->where('schedule_id', $schedule->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'open_trip_' . str_replace(' ', '_', $schedule->travelPackage->type) . '_' . $schedule->start_date->format('Ymd') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($registrations, $schedule) {
            $file = fopen('php://output', 'w');
            
            fwrite($file, '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">');
            fwrite($file, '<head><meta charset="UTF-8"><style>td, th { padding: 4px 8px; border: 1px solid #ccc; } th { background: #053d2c; color: white; }</style></head><body>');
            
            fwrite($file, '<h2>Open Trip: ' . htmlspecialchars($schedule->travelPackage->type) . ' - ' . htmlspecialchars($schedule->travelPackage->location ?? '') . '</h2>');
            fwrite($file, '<p>Date: ' . $schedule->start_date->format('d M Y') . ($schedule->end_date ? ' - ' . $schedule->end_date->format('d M Y') : '') . '</p>');
            fwrite($file, '<p>Quota: ' . $schedule->booked . '/' . $schedule->quota . '</p><br>');
            
            fwrite($file, '<table>');
            fwrite($file, '<tr><th>No</th><th>Name</th><th>Email</th><th>Phone</th><th>Institution</th><th>People Count</th><th>Status</th><th>Notes</th><th>Participants</th><th>Registered At</th></tr>');
            
            $no = 1;
            foreach ($registrations as $registration) {
                $participants = $registration->participants->pluck('name')->implode(', ');
                fwrite($file, '<tr>');
                fwrite($file, '<td>' . $no++ . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($registration->name) . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($registration->email) . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($registration->number_phone) . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($registration->institution ?? '-') . '</td>');
                fwrite($file, '<td>' . $registration->people_count . '</td>');
                fwrite($file, '<td>' . ucfirst($registration->status) . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($registration->notes ?? '-') . '</td>');
                fwrite($file, '<td>' . htmlspecialchars($participants) . '</td>');
                fwrite($file, '<td>' . $registration->created_at->format('d M Y H:i') . '</td>');
                fwrite($file, '</tr>');
            }
            
            fwrite($file, '</table></body></html>');
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
