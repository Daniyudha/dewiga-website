<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\OpenTripRegistration;
use App\Models\Schedule;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    public function store(BookingRequest $request)
    {
        $data = $request->validated();
        
        // If no start_date provided, get it from the schedule
        if (!isset($data['start_date'])) {
            $schedule = Schedule::find($data['schedule_id'] ?? null);
            if ($schedule) {
                $data['start_date'] = $schedule->start_date->format('Y-m-d');
                $data['date'] = $data['start_date'];
            }
        } else {
            // Map start_date to date for the booking model
            $data['date'] = $data['start_date'];
        }
        
        // Set default people_count if not provided
        if (!isset($data['people_count'])) {
            $data['people_count'] = 1;
        }
        
        // Calculate amount based on price and people_count
        if (!isset($data['amount']) && isset($data['travel_package_id'])) {
            $travelPackage = TravelPackage::find($data['travel_package_id']);
            if ($travelPackage && $travelPackage->price) {
                $data['amount'] = $travelPackage->price * $data['people_count'];
            }
        }
        
        // Set status default
        $data['status'] = $data['status'] ?? 'pending';
        
        // Check if this is an open_trip registration
        $schedule = null;
        if (isset($data['schedule_id'])) {
            $schedule = Schedule::find($data['schedule_id']);
        }
        
        if ($schedule && $schedule->type === 'open_trip') {
            // Save to open_trip_registrations table
            $openTripRegistration = OpenTripRegistration::create($data);
            
            // Create participants if provided
            if (isset($data['participants']) && is_array($data['participants'])) {
                foreach ($data['participants'] as $participant) {
                    $openTripRegistration->participants()->create([
                        'name' => $participant['name'],
                        'email' => $participant['email'] ?? null,
                        'phone' => $participant['phone'] ?? null,
                    ]);
                }
            }
            
            // Increment booked count on schedule
            $schedule->increment('booked', $data['people_count'] ?? 1);
            
            // Load travelPackage relation for email
            $openTripRegistration->load('travelPackage');

            // Send email notification
            try {
                Mail::to($openTripRegistration->email)->cc('edpdewiga@gmail.com')->send(new BookingConfirmation($openTripRegistration));
            } catch (\Exception $e) {
                // Log error but don't fail the registration
            }
            
            return redirect()->back()->with([
                'message' => __('messages.booking.success') . ' ' . __('messages.booking.email_notification'),
                'success' => true
            ]);
        }
        
        // Regular booking - save to bookings table
        $booking = Booking::create($data);

        // Create participants if provided
        if (isset($data['participants']) && is_array($data['participants'])) {
            foreach ($data['participants'] as $participant) {
                $booking->participants()->create([
                    'name' => $participant['name'],
                    'email' => $participant['email'] ?? null,
                    'phone' => $participant['phone'] ?? null,
                ]);
            }
        }

        // Create schedule automatically for non-open_trip bookings
        if (!$schedule && isset($data['travel_package_id']) && isset($data['start_date'])) {
            $schedule = Schedule::create([
                'travel_package_id' => $data['travel_package_id'],
                'visitor_name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'quota' => $data['people_count'] ?? 1,
                'booked' => $data['status'] === 'confirmed' ? ($data['people_count'] ?? 1) : 0,
                'type' => $data['status'] === 'confirmed' ? 'confirmed' : 'pending',
                'is_active' => true,
            ]);
            $booking->update(['schedule_id' => $schedule->id]);
        }

        // Increment booked count on schedule if schedule_id is set and status is confirmed
        if ($schedule && $data['status'] === 'confirmed') {
            $schedule->increment('booked', $data['people_count'] ?? 1);
        }

        // Load travel_package relation for email
        $booking->load('travel_package');

        // Send email notification
        try {
            Mail::to($booking->email)->cc('edpdewiga@gmail.com')->send(new BookingConfirmation($booking));
        } catch (\Exception $e) {
            // Log error but don't fail the booking
        }

        // Get travel package for redirect
        $travelPackage = TravelPackage::find($data['travel_package_id']);
        
        return redirect()->route('travel_package.show', $travelPackage->slug ?? $data['travel_package_id'])->with([
            'message' => __('messages.booking.success') . ' ' . __('messages.booking.email_notification'),
            'success' => true
        ]);
    }
}
