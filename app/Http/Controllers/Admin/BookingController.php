<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\OpenTripRegistration;
use App\Models\Schedule;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings with status filter.
     */
    public function index(Request $request)
    {
        $query = Booking::with('travel_package', 'schedule.travelPackage', 'participants')
            ->whereDoesntHave('schedule', function ($q) {
                $q->where('type', 'open_trip');
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show form for creating a new booking manually (admin-side).
     */
    public function create()
    {
        $travel_packages = TravelPackage::all();
        $schedules = Schedule::with('travelPackage')
            ->active()
            ->orderBy('start_date', 'asc')
            ->get();
        return view('admin.bookings.create', compact('travel_packages', 'schedules'));
    }

    /**
     * Store a booking (from admin panel).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'date' => 'nullable|date',
            'travel_package_id' => 'required|exists:travel_packages,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:500',
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Map start_date to date if date is not provided
        if (empty($validated['date']) && !empty($validated['start_date'])) {
            $validated['date'] = $validated['start_date'];
        }

        // Check if this is an open_trip registration
        $schedule = null;
        if (isset($validated['schedule_id'])) {
            $schedule = Schedule::find($validated['schedule_id']);
        }

        if ($schedule && $schedule->type === 'open_trip') {
            // Save to open_trip_registrations table
            $openTripRegistration = OpenTripRegistration::create($validated);

            // If confirmed, increment schedule booked count
            if ($openTripRegistration->status === 'confirmed') {
                $schedule->increment('booked');
            }

            return redirect()->route('admin.open-trip-registrations.index')->with([
                'message' => 'Open Trip Registration berhasil ditambahkan!',
                'alert-type' => 'success',
            ]);
        }

        $booking = Booking::create($validated);

        // Create schedule automatically for non-open_trip bookings
        // Use start_date if available, otherwise use date
        $startDate = $validated['start_date'] ?? $validated['date'];
        if (!$schedule && isset($validated['travel_package_id']) && $startDate) {
            $schedule = Schedule::create([
                'travel_package_id' => $validated['travel_package_id'],
                'visitor_name' => $validated['name'],
                'start_date' => $startDate,
                'end_date' => $validated['end_date'] ?? null,
                'quota' => $validated['people_count'] ?? 1,
                'booked' => $validated['status'] === 'confirmed' ? ($validated['people_count'] ?? 1) : 0,
                'type' => $validated['status'] === 'confirmed' ? 'confirmed' : 'pending',
                'is_active' => true,
            ]);
            $booking->update(['schedule_id' => $schedule->id]);
        }

        // If confirmed, increment schedule booked count
        if ($booking->status === 'confirmed' && $schedule) {
            $schedule->increment('booked');
        }

        return redirect()->route('admin.bookings.index')->with([
            'message' => 'Booking berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Confirm a pending booking.
     */
    public function confirm(Booking $booking)
    {
        $oldStatus = $booking->status;
        $booking->update(['status' => 'confirmed']);

        // Update schedule type and increment booked count
        if ($booking->schedule_id) {
            $schedule = Schedule::find($booking->schedule_id);
            if ($schedule) {
                // If was pending, change to confirmed
                if ($schedule->type === 'pending') {
                    $schedule->update(['type' => 'confirmed']);
                }
                $schedule->increment('booked');
            }
        }

        return redirect()->back()->with([
            'message' => 'Booking berhasil dikonfirmasi!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        $oldStatus = $booking->status;
        $booking->update(['status' => 'cancelled']);

        // Decrement schedule booked count if was confirmed
        if ($oldStatus === 'confirmed' && $booking->schedule_id) {
            $schedule = Schedule::find($booking->schedule_id);
            if ($schedule && $schedule->booked > 0) {
                $schedule->decrement('booked');
            }
        }

        return redirect()->back()->with([
            'message' => 'Booking dibatalkan!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(Booking $booking)
    {
        $travel_packages = TravelPackage::all();
        $schedules = Schedule::with('travelPackage')
            ->active()
            ->where('type', '!=', 'open_trip')
            ->orderBy('start_date', 'asc')
            ->get();
        return view('admin.bookings.edit', compact('booking', 'travel_packages', 'schedules'));
    }

    /**
     * Update booking.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'date' => 'nullable|date',
            'travel_package_id' => 'required|exists:travel_packages,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:500',
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Map start_date to date if date is not provided
        if (empty($validated['date']) && !empty($validated['start_date'])) {
            $validated['date'] = $validated['start_date'];
        }

        $oldStatus = $booking->status;
        $oldScheduleId = $booking->schedule_id;

        $booking->update($validated);

        // Handle booked count changes
        if ($oldStatus === 'confirmed' && $oldScheduleId && $booking->status !== 'confirmed') {
            // Was confirmed, now not confirmed - decrement old
            $schedule = Schedule::find($oldScheduleId);
            if ($schedule && $schedule->booked > 0) $schedule->decrement('booked');
        }

        if ($booking->status === 'confirmed' && $booking->schedule_id) {
            // Check if wasn't confirmed before or schedule changed
            $schedule = Schedule::find($booking->schedule_id);
            if ($schedule) {
                // Only increment if the schedule changed or status was not confirmed before
                if ($oldStatus !== 'confirmed' || $oldScheduleId != $booking->schedule_id) {
                    $schedule->increment('booked');
                }
            }
        }

        return redirect()->route('admin.bookings.index')->with([
            'message' => 'Booking berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Delete a booking.
     */
    public function destroy(Booking $booking)
    {
        // Decrement booked count if was confirmed
        if ($booking->status === 'confirmed' && $booking->schedule_id) {
            $schedule = Schedule::find($booking->schedule_id);
            if ($schedule && $schedule->booked > 0) {
                $schedule->decrement('booked');
            }
        }

        $booking->delete();

        return redirect()->back()->with([
            'message' => 'Booking berhasil dihapus!',
            'alert-type' => 'success',
        ]);
    }
}