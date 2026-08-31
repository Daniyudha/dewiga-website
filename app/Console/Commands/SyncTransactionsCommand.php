<?php

namespace App\Console\Commands;

use App\Services\TransactionSyncService;
use Illuminate\Console\Command;

class SyncTransactionsCommand extends Command
{
    protected $signature = 'transactions:sync';

    protected $description = 'Backfill/sync transaksi keuangan dari booking, open trip, dan pembayaran schedule, dengan deduplikasi.';

    public function handle(TransactionSyncService $service): int
    {
        $this->info('Mulai sinkronisasi transaksi keuangan...');

        $counts = $service->syncAllExisting();

        $this->table(
            ['Sumber', 'Jumlah diproses'],
            [
                ['Booking', $counts['bookings']],
                ['Open Trip', $counts['open_trips']],
                ['Pembayaran Schedule', $counts['schedule_payments']],
            ]
        );

        $total = \App\Models\Transaction::count();
        $this->info("Jumlah transaksi saat ini: {$total}");

        return self::SUCCESS;
    }
}