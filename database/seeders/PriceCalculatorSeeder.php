<?php

namespace Database\Seeders;

use App\Models\ParticipantPriceTier;
use App\Models\PricingAddon;
use App\Models\PricingComponent;
use Illuminate\Database\Seeder;

class PriceCalculatorSeeder extends Seeder
{
    public function run(): void
    {
        // --- Pricing Components ---
        $components = [
            [
                'code' => 'live_in',
                'name' => 'Live In',
                'description' => 'Biaya menginap per orang per malam',
                'calculation_type' => 'per_person_per_night',
                'unit' => 'per orang/malam',
                'default_price' => 35000,
                'sort_order' => 1,
            ],
            [
                'code' => 'meal',
                'name' => 'Makan',
                'description' => 'Biaya makan per orang per kali',
                'calculation_type' => 'per_person_per_meal',
                'unit' => 'per orang/kali',
                'default_price' => 30000,
                'sort_order' => 2,
            ],
            [
                'code' => 'snack',
                'name' => 'Snack',
                'description' => 'Biaya snack per orang per kali',
                'calculation_type' => 'per_person_per_frequency',
                'unit' => 'per orang/kali',
                'default_price' => 15000,
                'sort_order' => 3,
            ],
            [
                'code' => 'guide_fund',
                'name' => 'Kas dan Pemandu',
                'description' => 'Biaya kas dan pemandu berdasarkan tier jumlah peserta',
                'calculation_type' => 'tiered',
                'unit' => 'per orang',
                'default_price' => null,
                'sort_order' => 4,
            ],
            [
                'code' => 'regular_activity',
                'name' => 'Kegiatan Reguler',
                'description' => 'Biaya kegiatan reguler per peserta per kegiatan',
                'calculation_type' => 'per_person_per_activity',
                'unit' => 'per peserta/kegiatan',
                'default_price' => 15000,
                'sort_order' => 5,
            ],
            [
                'code' => 'participant_art_activity',
                'name' => 'Kegiatan Kesenian Peserta',
                'description' => 'Biaya kegiatan kesenian yang diikuti peserta berdasarkan tier',
                'calculation_type' => 'tiered',
                'unit' => 'per sesi',
                'default_price' => null,
                'sort_order' => 6,
            ],
            [
                'code' => 'cooking_competition',
                'name' => 'Lomba Masak',
                'description' => 'Biaya lomba masak per kelompok',
                'calculation_type' => 'per_group',
                'unit' => 'per kelompok',
                'default_price' => 100000,
                'sort_order' => 7,
            ],
        ];

        foreach ($components as $component) {
            PricingComponent::updateOrCreate(
                ['code' => $component['code']],
                $component
            );
        }

        // --- Guide Fund Tiers ---
        $guideFundComponent = PricingComponent::where('code', 'guide_fund')->first();
        if ($guideFundComponent) {
            $guideFundComponent->activeTiers()->delete();
            $tiers = [
                ['minimum_participants' => 1, 'maximum_participants' => 24, 'price' => 100000],
                ['minimum_participants' => 25, 'maximum_participants' => 50, 'price' => 70000],
                ['minimum_participants' => 51, 'maximum_participants' => 75, 'price' => 50000],
                ['minimum_participants' => 76, 'maximum_participants' => 100, 'price' => 40000],
                ['minimum_participants' => 101, 'maximum_participants' => 150, 'price' => 35000],
                ['minimum_participants' => 151, 'maximum_participants' => 200, 'price' => 30000],
                ['minimum_participants' => 201, 'maximum_participants' => null, 'price' => 25000],
            ];
            foreach ($tiers as $tier) {
                $guideFundComponent->activeTiers()->create($tier);
            }
        }

        // --- Participant Art Activity Tiers ---
        $artComponent = PricingComponent::where('code', 'participant_art_activity')->first();
        if ($artComponent) {
            $artComponent->activeTiers()->delete();
            $tiers = [
                ['minimum_participants' => 1, 'maximum_participants' => 24, 'price' => 600000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 25, 'maximum_participants' => 50, 'price' => 1000000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 51, 'maximum_participants' => 75, 'price' => 1300000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 76, 'maximum_participants' => 100, 'price' => 1700000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 101, 'maximum_participants' => 150, 'price' => 2200000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 151, 'maximum_participants' => 200, 'price' => 3000000, 'additional_price_per_participant' => null],
                ['minimum_participants' => 201, 'maximum_participants' => null, 'price' => 3000000, 'additional_price_per_participant' => 15000],
            ];
            foreach ($tiers as $tier) {
                $artComponent->activeTiers()->create($tier);
            }
        }

        // --- Regular Activity Tiers ---
        $regularActivityComponent = PricingComponent::where('code', 'regular_activity')->first();
        if ($regularActivityComponent) {
            $regularActivityComponent->activeTiers()->delete();
            $tiers = [
                ['minimum_participants' => 1, 'maximum_participants' => 24, 'price' => 20000],
                ['minimum_participants' => 25, 'maximum_participants' => 50, 'price' => 18000],
                ['minimum_participants' => 51, 'maximum_participants' => 75, 'price' => 16000],
                ['minimum_participants' => 76, 'maximum_participants' => 100, 'price' => 15000],
                ['minimum_participants' => 101, 'maximum_participants' => 150, 'price' => 14000],
                ['minimum_participants' => 151, 'maximum_participants' => 200, 'price' => 13000],
                ['minimum_participants' => 201, 'maximum_participants' => null, 'price' => 12000],
            ];
            foreach ($tiers as $tier) {
                $regularActivityComponent->activeTiers()->create($tier);
            }
        }

        // --- Pricing Addons ---
        $addons = [
            ['code' => 'pickup', 'name' => 'Pickup Wisata', 'description' => 'Layanan penjemputan wisata', 'price' => 200000, 'unit' => 'unit', 'capacity' => 10, 'sort_order' => 1],
            ['code' => 'cultural_performance', 'name' => 'Pertunjukan Kesenian', 'description' => 'Penampilan kesenian untuk penyambutan atau acara malam', 'price' => 1500000, 'unit' => 'penampilan', 'capacity' => null, 'sort_order' => 2],
            ['code' => 'professional_sound', 'name' => 'Sound Profesional', 'description' => 'Sewa sound profesional', 'price' => 700000, 'unit' => 'paket', 'capacity' => null, 'sort_order' => 3],
            ['code' => 'stage_lighting', 'name' => 'Lighting Panggung', 'description' => 'Sewa lighting panggung', 'price' => 2000000, 'unit' => 'paket', 'capacity' => null, 'sort_order' => 4],
            ['code' => 'sound_lighting_package', 'name' => 'Paket Sound + Lighting', 'description' => 'Paket sound profesional dan lighting panggung', 'price' => 2500000, 'unit' => 'paket', 'capacity' => null, 'sort_order' => 5],
            ['code' => 'live_music', 'name' => 'Live Music / Organ Tunggal', 'description' => 'Hiburan live music atau organ tunggal', 'price' => 1500000, 'unit' => 'penampilan', 'capacity' => null, 'sort_order' => 6],
        ];

        foreach ($addons as $addon) {
            PricingAddon::updateOrCreate(
                ['code' => $addon['code']],
                $addon
            );
        }
    }
}