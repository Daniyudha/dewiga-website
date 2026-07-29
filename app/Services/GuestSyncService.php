<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Booking;
use App\Models\OpenTripRegistration;

class GuestSyncService
{
    /**
     * Sync guest from a booking record.
     */
    public function syncFromBooking(Booking $booking): ?Guest
    {
        // Check if guest already exists from this booking
        $existing = Guest::where('source', 'booking')
            ->where('source_id', $booking->id)
            ->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'name' => $booking->name,
                'institution' => $booking->institution,
                'number_phone' => $booking->number_phone,
                'email' => $booking->email,
            ]);
            return $existing;
        }

        // Create new guest
        return Guest::create([
            'name' => $booking->name,
            'institution' => $booking->institution,
            'number_phone' => $booking->number_phone,
            'email' => $booking->email,
            'source' => 'booking',
            'source_id' => $booking->id,
            'notes' => 'Otomatis dari booking #' . $booking->id,
        ]);
    }

    /**
     * Sync guest from an open trip registration.
     */
    public function syncFromOpenTrip(OpenTripRegistration $registration): ?Guest
    {
        // Check if guest already exists from this registration
        $existing = Guest::where('source', 'open_trip')
            ->where('source_id', $registration->id)
            ->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'name' => $registration->name,
                'institution' => $registration->institution,
                'number_phone' => $registration->number_phone,
                'email' => $registration->email,
            ]);
            return $existing;
        }

        // Create new guest
        return Guest::create([
            'name' => $registration->name,
            'institution' => $registration->institution,
            'number_phone' => $registration->number_phone,
            'email' => $registration->email,
            'source' => 'open_trip',
            'source_id' => $registration->id,
            'notes' => 'Otomatis dari open trip #' . $registration->id,
        ]);
    }

    /**
     * Sync all existing bookings and open trips to guests table.
     */
    public function syncAllExisting(): array
    {
        $counts = ['bookings' => 0, 'open_trips' => 0];

        // Sync all bookings
        Booking::chunk(100, function ($bookings) use (&$counts) {
            foreach ($bookings as $booking) {
                $this->syncFromBooking($booking);
                $counts['bookings']++;
            }
        });

        // Sync all open trip registrations
        OpenTripRegistration::chunk(100, function ($registrations) use (&$counts) {
            foreach ($registrations as $registration) {
                $this->syncFromOpenTrip($registration);
                $counts['open_trips']++;
            }
        });

        return $counts;
    }
}