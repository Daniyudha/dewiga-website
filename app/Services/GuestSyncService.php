<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Booking;
use App\Models\OpenTripRegistration;
use App\Models\PriceEstimation;

class GuestSyncService
{
    /**
     * Sync guest from a booking record.
     */
    public function syncFromBooking(Booking $booking): ?Guest
    {
        return $this->syncGuest([
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
        return $this->syncGuest([
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
     * Sync guest from a price estimation.
     */
    public function syncFromPriceEstimation(PriceEstimation $estimation): ?Guest
    {
        return $this->syncGuest([
            'name' => $estimation->contact_person,
            'institution' => $estimation->institution_name,
            'number_phone' => $estimation->whatsapp ?? null,
            'email' => null,
            'source' => 'estimation',
            'source_id' => $estimation->id,
            'notes' => 'Otomatis dari estimasi #' . $estimation->id,
        ]);
    }

    /**
     * Create or update a guest record while preventing duplicates.
     *
     * Deduplication priority:
     * 1. Same source + source_id (update in place).
     * 2. Same email (case-insensitive).
     * 3. Same normalized phone number.
     * 4. Same name + institution.
     */
    public function syncGuest(array $data): ?Guest
    {
        $source = $data['source'] ?? 'manual';
        $sourceId = $data['source_id'] ?? null;

        // 1. Existing record from the same source
        $existing = null;
        if ($sourceId !== null) {
            $existing = Guest::where('source', $source)
                ->where('source_id', $sourceId)
                ->first();
        }

        if ($existing) {
            $this->applyData($existing, $data);
            return $existing;
        }

        // 2. Find an existing duplicate across sources
        $duplicate = $this->findDuplicate($data);
        if ($duplicate) {
            $this->applyData($duplicate, $data, false);
            return $duplicate;
        }

        // 3. Create a new guest
        return Guest::create($data);
    }

    /**
     * Apply guest attributes to an existing record.
     */
    private function applyData(Guest $guest, array $data, bool $includeSource = true): void
    {
        $updates = [
            'name' => $data['name'] ?? $guest->name,
            'institution' => $data['institution'] ?? null,
            'number_phone' => $data['number_phone'] ?? null,
            'email' => $data['email'] ?? null,
        ];

        if ($includeSource) {
            $updates['notes'] = $data['notes'] ?? $guest->notes;
        } else {
            $updates['notes'] = $guest->notes;
        }

        $guest->update($updates);
    }

    /**
     * Search for an existing guest that matches the incoming data.
     */
    private function findDuplicate(array $data): ?Guest
    {
        $email = isset($data['email']) ? strtolower(trim((string) $data['email'])) : null;
        $phone = isset($data['number_phone']) ? preg_replace('/[^0-9]/', '', (string) $data['number_phone']) : null;
        $name = isset($data['name']) ? strtolower(trim((string) $data['name'])) : null;
        $institution = isset($data['institution']) ? strtolower(trim((string) $data['institution'])) : null;

        if ($email) {
            $match = Guest::whereNotNull('email')->get()->first(function ($g) use ($email) {
                return strtolower(trim((string) $g->email)) === $email;
            });
            if ($match) {
                return $match;
            }
        }

        if ($phone) {
            $match = Guest::whereNotNull('number_phone')->get()->first(function ($g) use ($phone) {
                return preg_replace('/[^0-9]/', '', (string) $g->number_phone) === $phone;
            });
            if ($match) {
                return $match;
            }
        }

        if ($name && $institution) {
            $match = Guest::whereNotNull('name')->get()->first(function ($g) use ($name, $institution) {
                return strtolower(trim((string) $g->name)) === $name
                    && strtolower(trim((string) $g->institution)) === $institution;
            });
            if ($match) {
                return $match;
            }
        }

        return null;
    }

    /**
     * Sync all existing bookings, open trips and estimations to guests table.
     */
    public function syncAllExisting(): array
    {
        $counts = ['bookings' => 0, 'open_trips' => 0, 'estimations' => 0];

        Booking::chunk(100, function ($bookings) use (&$counts) {
            foreach ($bookings as $booking) {
                $this->syncFromBooking($booking);
                $counts['bookings']++;
            }
        });

        OpenTripRegistration::chunk(100, function ($registrations) use (&$counts) {
            foreach ($registrations as $registration) {
                $this->syncFromOpenTrip($registration);
                $counts['open_trips']++;
            }
        });

        PriceEstimation::chunk(100, function ($estimations) use (&$counts) {
            foreach ($estimations as $estimation) {
                $this->syncFromPriceEstimation($estimation);
                $counts['estimations']++;
            }
        });

        return $counts;
    }
}