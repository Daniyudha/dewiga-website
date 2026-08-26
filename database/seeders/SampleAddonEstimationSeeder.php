<?php

namespace Database\Seeders;

use App\Models\PriceEstimation;
use App\Services\PriceCalculatorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleAddonEstimationSeeder extends Seeder
{
    /**
     * Create a sample estimation with 7 add-on items to verify
     * the calculator handles more than 5 add-ons correctly.
     */
    public function run(): void
    {
        $service = app(PriceCalculatorService::class);

        $data = [
            'institution_name' => 'SMA Contoh 7 Add-on',
            'contact_person' => 'Budi Hartanto',
            'whatsapp' => '081234567890',
            'arrival_date' => now()->addDays(7)->format('Y-m-d'),
            'departure_date' => now()->addDays(8)->format('Y-m-d'),
            'student_count' => 40,
            'companion_count' => 5,
            'service_participant_count' => 45,
            'activity_participant_count' => 40,
            'live_in_nights' => 1,
            'meal_count' => 2,
            'snack_count' => 2,
            'regular_activity_count' => 5,
            'art_sessions' => 1,
            'rounding_type' => 'up_1000',
            'notes' => 'Sample data dengan 7 add-on lainnya untuk verifikasi edit.',
            'other_addon_active' => true,
            'addon_items' => [
                [
                    'name' => 'Kereta Keliling',
                    'unit_price' => 1000000,
                    'quantity' => 1,
                    'multiplier' => 1,
                    'multiplier_active' => '1',
                ],
                [
                    'name' => 'Tari Tradisional',
                    'unit_price' => 500000,
                    'quantity' => 1,
                    'multiplier' => 1,
                    'multiplier_active' => '0',
                ],
                [
                    'name' => 'Gamelan',
                    'unit_price' => 800000,
                    'quantity' => 2,
                    'multiplier' => 1,
                    'multiplier_active' => '0',
                ],
                [
                    'name' => 'Sewa Alat Makan',
                    'unit_price' => 250000,
                    'quantity' => 1,
                    'multiplier' => 2,
                    'multiplier_active' => '1',
                ],
                [
                    'name' => 'Dokumentasi Video',
                    'unit_price' => 1500000,
                    'quantity' => 1,
                    'multiplier' => 1,
                    'multiplier_active' => '0',
                ],
                [
                    'name' => 'Tenda Panggung',
                    'unit_price' => 1200000,
                    'quantity' => 1,
                    'multiplier' => 1,
                    'multiplier_active' => '0',
                ],
                [
                    'name' => 'Konsumsi Tambahan Siang',
                    'unit_price' => 15000,
                    'quantity' => 45,
                    'multiplier' => 1,
                    'multiplier_active' => '0',
                ],
            ],
        ];

        DB::transaction(function () use ($service, $data) {
            // Temporarily set auth user if none (for created_by)
            if (!Auth::check()) {
                $firstUser = \App\Models\User::first();
                if ($firstUser) {
                    Auth::login($firstUser);
                }
            }

            $estimation = $service->save($data);

            $addonCount = $estimation->items
                ->filter(fn($i) => str_starts_with($i->item_code, 'custom_addon'))
                ->count();

            $this->command->info("Sample estimation created: {$estimation->estimation_number}");
            $this->command->info("Total items: {$estimation->items->count()}");
            $this->command->info("Add-on items: {$addonCount}");
            $this->command->info("Subtotal: " . number_format($estimation->subtotal, 0, ',', '.'));
        });
    }
}