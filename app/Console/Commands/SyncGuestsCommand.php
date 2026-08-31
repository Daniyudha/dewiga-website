<?php

namespace App\Console\Commands;

use App\Services\GuestSyncService;
use Illuminate\Console\Command;

class SyncGuestsCommand extends Command
{
    protected $signature = 'guests:sync';

    protected $description = 'Backfill/sync tamu dari booking, open trip, dan estimasi (kalkulator & proposal) ke tabel guests, dengan deduplikasi.';

    public function handle(GuestSyncService $service): int
    {
        $this->info('Mulai sinkronisasi tamu...');

        $counts = $service->syncAllExisting();

        $this->table(
            ['Sumber', 'Jumlah diproses'],
            [
                ['Booking', $counts['bookings']],
                ['Open Trip', $counts['open_trips']],
                ['Estimasi', $counts['estimations']],
            ]
        );

        $totalGuests = \App\Models\Guest::count();
        $this->info("Jumlah tamu di tabel guests saat ini: {$totalGuests}");

        return self::SUCCESS;
    }
}