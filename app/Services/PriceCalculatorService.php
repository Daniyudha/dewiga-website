<?php

namespace App\Services;

use App\Models\ParticipantPriceTier;
use App\Models\PriceEstimation;
use App\Models\PriceEstimationItem;
use App\Models\PricingAddon;
use App\Models\PricingComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PriceCalculatorService
{
    protected ?PricingComponent $liveInComponent = null;
    protected ?PricingComponent $mealComponent = null;
    protected ?PricingComponent $snackComponent = null;
    protected ?PricingComponent $guideFundComponent = null;
    protected ?PricingComponent $regularActivityComponent = null;
    protected ?PricingComponent $participantArtComponent = null;
    protected ?PricingComponent $cookingComponent = null;
    protected ?PricingAddon $pickupAddon = null;
    protected ?PricingAddon $culturalPerformanceAddon = null;
    protected ?PricingAddon $liveMusicAddon = null;
    protected ?PricingAddon $professionalSoundAddon = null;
    protected ?PricingAddon $stageLightingAddon = null;
    protected ?PricingAddon $soundLightingPackageAddon = null;

    protected array $items = [];
    protected float $subtotal = 0;
    protected float $actualPricePerPerson = 0;
    protected string $roundingType = 'none';
    protected float $roundedPricePerPerson = 0;
    protected float $quotationTotal = 0;
    protected float $differenceAmount = 0;

    public function __construct()
    {
        $this->loadComponents();
        $this->loadAddons();
    }

    protected function loadComponents(): void
    {
        $components = PricingComponent::active()->get()->keyBy('code');
        $this->liveInComponent = $components->get('live_in');
        $this->mealComponent = $components->get('meal');
        $this->snackComponent = $components->get('snack');
        $this->guideFundComponent = $components->get('guide_fund');
        $this->regularActivityComponent = $components->get('regular_activity');
        $this->participantArtComponent = $components->get('participant_art_activity');
        $this->cookingComponent = $components->get('cooking_competition');
    }

    protected function loadAddons(): void
    {
        $addons = PricingAddon::active()->get()->keyBy('code');
        $this->pickupAddon = $addons->get('pickup');
        $this->culturalPerformanceAddon = $addons->get('cultural_performance');
        $this->liveMusicAddon = $addons->get('live_music');
        $this->professionalSoundAddon = $addons->get('professional_sound');
        $this->stageLightingAddon = $addons->get('stage_lighting');
        $this->soundLightingPackageAddon = $addons->get('sound_lighting_package');
    }

    /**
     * Calculate estimation based on input data.
     */
    public function calculate(array $data): array
    {
        $this->reset();

        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);
        // Fallback: if activity participants is 0 but student_count exists, use student_count
        $activityParticipants = (int) ($data['activity_participant_count'] ?? 0);
        if ($activityParticipants <= 0 && !empty($data['student_count'])) {
            $activityParticipants = (int) $data['student_count'];
        }

        // 1. Live in
        $this->calculateLiveIn($serviceParticipants, $data);

        // 2. Makan
        $this->calculateMeal($serviceParticipants, $data);

        // 3. Snack
        $this->calculateSnack($serviceParticipants, $data);

        // 4. Kas dan pemandu
        $this->calculateGuideFund($serviceParticipants);

        // 5. Kegiatan reguler
        $this->calculateRegularActivity($activityParticipants, $data);

        // 6. Kegiatan kesenian peserta
        $this->calculateParticipantArtActivity($activityParticipants, $data);

        // 7. Lomba masak
        $this->calculateCookingCompetition($data);

        // 8. Pickup
        $this->calculatePickup($data);

        // 9. Pertunjukan kesenian
        $this->calculateCulturalPerformance($data);

        // 10. Sound / Lighting
        $this->calculateSoundAndLighting($data);

        // 11. Live music
        $this->calculateLiveMusic($data);

        // 12. Add-on lainnya
        $this->calculateOtherAddon($data);

        // 13. Custom add-on items (dynamic)
        $this->calculateCustomAddonItems($data);

        // Calculate totals
        $this->subtotal = array_sum(array_column($this->items, 'total'));

        // Calculate per person
        $this->actualPricePerPerson = $serviceParticipants > 0
            ? $this->subtotal / $serviceParticipants
            : 0;

        // Apply rounding
        $this->roundingType = $data['rounding_type'] ?? 'none';
        $this->applyRounding($serviceParticipants);

        return $this->getResult();
    }

    /**
     * Save estimation to database.
     */
    public function save(array $data): PriceEstimation
    {
        // If a server-calculated result is provided (from controller), use it directly
        // to guarantee items & totals are always correct even if client calc failed.
        $result = $data['_server_result'] ?? $this->calculate($data);
        unset($data['_server_result']);

        return DB::transaction(function () use ($data, $result) {
            $estimation = PriceEstimation::create([
                'estimation_number' => PriceEstimation::generateEstimationNumber(),
                'institution_name' => $data['institution_name'],
                'contact_person' => $data['contact_person'],
                'whatsapp' => $data['whatsapp'],
                'arrival_date' => $data['arrival_date'],
                'departure_date' => $data['departure_date'],
                'student_count' => (int) ($data['student_count'] ?? 0),
                'companion_count' => (int) ($data['companion_count'] ?? 0),
                'service_participant_count' => (int) ($data['service_participant_count'] ?? 0),
                'activity_participant_count' => (int) ($data['activity_participant_count'] ?? 0),
                'subtotal' => $result['subtotal'],
                'actual_price_per_person' => $result['actual_price_per_person'],
                'rounding_type' => $result['rounding_type'],
                'rounded_price_per_person' => $result['rounded_price_per_person'],
                'quotation_total' => $result['quotation_total'],
                'difference_amount' => $result['difference_amount'],
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $sortOrder = 0;
            foreach ($result['items'] as $item) {
                PriceEstimationItem::create([
                    'price_estimation_id' => $estimation->id,
                    'item_code' => $item['code'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'frequency' => $item['frequency'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'price_per_person' => $item['price_per_person'],
                    'total' => $item['total'],
                    'calculation_details' => $item['calculation_details'],
                    'sort_order' => $sortOrder++,
                ]);
            }

            return $estimation->fresh()->load('items');
        });
    }

    /**
     * Recalculate an existing estimation with current master prices.
     */
    public function recalculate(PriceEstimation $estimation): PriceEstimation
    {
        $data = [
            'service_participant_count' => $estimation->service_participant_count,
            'activity_participant_count' => $estimation->activity_participant_count,
            'institution_name' => $estimation->institution_name,
            'contact_person' => $estimation->contact_person,
            'whatsapp' => $estimation->whatsapp,
            'arrival_date' => $estimation->arrival_date->format('Y-m-d'),
            'departure_date' => $estimation->departure_date->format('Y-m-d'),
            'student_count' => $estimation->student_count,
            'companion_count' => $estimation->companion_count,
            'notes' => $estimation->notes,
            'rounding_type' => $estimation->rounding_type,
        ];

        // Extract saved calculation details from existing items
        foreach ($estimation->items as $item) {
            $details = $item->calculation_details ?: [];
            switch ($item->item_code) {
                case 'live_in':
                    $data['live_in_nights'] = $details['nights'] ?? $item->frequency;
                    break;
                case 'meal':
                    $data['meal_count'] = $details['meals'] ?? $item->frequency;
                    break;
                case 'regular_activity':
                    $data['regular_activity_count'] = $details['activities'] ?? $item->frequency;
                    break;
                case 'participant_art_activity':
                    $data['art_sessions'] = $details['sessions'] ?? $item->frequency;
                    break;
                case 'cooking_competition':
                    $data['cooking_active'] = true;
                    $data['cooking_participants'] = $details['participants'] ?? $item->quantity;
                    $data['cooking_capacity'] = $details['capacity'] ?? 10;
                    $data['cooking_price_per_group'] = $details['price_per_group'] ?? $item->unit_price;
                    $data['cooking_manual_groups'] = $details['manual_groups'] ?? null;
                    break;
                case 'pickup':
                    $data['pickup_active'] = true;
                    $data['pickup_users'] = $details['users'] ?? 0;
                    $data['pickup_manual_units'] = $details['manual_units'] ?? null;
                    break;
                case 'cultural_performance':
                    $data['cultural_performances'] = $details['performances'] ?? $item->frequency;
                    break;
                case 'live_music':
                    $data['live_music_performances'] = $details['performances'] ?? $item->frequency;
                    break;
                case 'professional_sound':
                    $data['sound_lighting_option'] = 'sound_only';
                    break;
                case 'stage_lighting':
                    $data['sound_lighting_option'] = 'lighting_only';
                    break;
                case 'sound_lighting_package':
                    $data['sound_lighting_option'] = 'package';
                    break;
                case 'other_addon':
                    $data['other_addon_active'] = true;
                    $data['other_addon_name'] = $item->item_name;
                    $data['other_addon_price'] = (float) $item->unit_price;
                    $data['other_addon_quantity'] = $item->quantity;
                    break;
            }
        }

        return $this->save($data);
    }

    protected function calculateLiveIn(int $participants, array $data): void
    {
        $nights = (int) ($data['live_in_nights'] ?? 0);
        if ($nights <= 0 || !$this->liveInComponent) {
            return;
        }

        $price = (float) ($this->liveInComponent->default_price ?: 0);
        $total = $participants * $nights * $price;
        $pricePerPerson = $nights * $price;

        $this->items[] = [
            'code' => 'live_in',
            'name' => 'Live In',
            'quantity' => $participants,
            'frequency' => $nights,
            'unit' => 'malam',
            'unit_price' => $price,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'nights' => $nights,
                'price_per_night' => $price,
            ],
        ];
    }

    protected function calculateMeal(int $participants, array $data): void
    {
        $meals = (int) ($data['meal_count'] ?? 0);
        if ($meals <= 0 || !$this->mealComponent) {
            return;
        }

        $price = (float) ($this->mealComponent->default_price ?: 0);
        $total = $participants * $meals * $price;
        $pricePerPerson = $meals * $price;

        $this->items[] = [
            'code' => 'meal',
            'name' => 'Makan',
            'quantity' => $participants,
            'frequency' => $meals,
            'unit' => 'kali',
            'unit_price' => $price,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'meals' => $meals,
                'price_per_meal' => $price,
            ],
        ];
    }

    protected function calculateGuideFund(int $participants): void
    {
        if ($participants <= 0 || !$this->guideFundComponent) {
            return;
        }

        $tier = $this->guideFundComponent->getMatchingTier($participants);
        if (!$tier) {
            return;
        }

        $pricePerPerson = (float) $tier->price;
        $total = $participants * $pricePerPerson;

        $this->items[] = [
            'code' => 'guide_fund',
            'name' => 'Pemandu',
            'quantity' => $participants,
            'frequency' => 1,
            'unit' => 'paket',
            'unit_price' => $pricePerPerson,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'tier_min' => $tier->minimum_participants,
                'tier_max' => $tier->maximum_participants,
                'tier_price' => (float) $tier->price,
            ],
        ];
    }

    protected function calculateRegularActivity(int $participants, array $data): void
    {
        $activities = (int) ($data['regular_activity_count'] ?? 0);
        if ($activities <= 0 || !$this->regularActivityComponent) {
            return;
        }

        // Use tier pricing for regular activity
        $tier = $this->regularActivityComponent->getMatchingTier($participants);
        if (!$tier) {
            // Fallback to default price if no tier found
            $price = (float) ($this->regularActivityComponent->default_price ?: 0);
            if ($price <= 0) return;
            $total = $participants * $activities * $price;
            $pricePerPerson = $activities * $price;
            $this->items[] = [
                'code' => 'regular_activity',
                'name' => 'Kegiatan Reguler',
                'quantity' => $participants,
                'frequency' => $activities,
                'unit' => 'kegiatan',
                'unit_price' => $price,
                'price_per_person' => $pricePerPerson,
                'total' => $total,
                'calculation_details' => [
                    'participants' => $participants,
                    'activities' => $activities,
                    'price_per_activity' => $price,
                    'tier_fallback' => true,
                ],
            ];
            return;
        }

        // Use tier pricing: price per participant per activity based on tier
        $pricePerParticipant = (float) $tier->price;
        $total = $participants * $activities * $pricePerParticipant;
        $pricePerPerson = $activities * $pricePerParticipant;

        $this->items[] = [
            'code' => 'regular_activity',
            'name' => 'Kegiatan Reguler',
            'quantity' => $participants,
            'frequency' => $activities,
            'unit' => 'kegiatan',
            'unit_price' => $pricePerParticipant,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'activities' => $activities,
                'tier_min' => $tier->minimum_participants,
                'tier_max' => $tier->maximum_participants,
                'tier_price' => (float) $tier->price,
            ],
        ];
    }

    protected function calculateParticipantArtActivity(int $participants, array $data): void
    {
        $sessions = (int) ($data['art_sessions'] ?? 0);
        if ($sessions <= 0 || !$this->participantArtComponent) {
            return;
        }

        $tier = $this->participantArtComponent->getMatchingTier($participants);
        if (!$tier) {
            return;
        }

        $tierPrice = (float) $tier->price;

        // Additional price for participants over max
        $additionalCost = 0;
        if ($tier->additional_price_per_participant && $tier->maximum_participants && $participants > $tier->maximum_participants) {
            $additionalCost = ($participants - $tier->maximum_participants) * (float) $tier->additional_price_per_participant;
        }

        $sessionPrice = $tierPrice + $additionalCost;
        $total = $sessionPrice * $sessions;

        $pricePerPerson = $participants > 0 ? ($total / $participants) : 0;

        $this->items[] = [
            'code' => 'participant_art_activity',
            'name' => 'Kegiatan Kesenian Peserta',
            'quantity' => $participants,
            'frequency' => $sessions,
            'unit' => 'sesi',
            'unit_price' => $sessionPrice,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'sessions' => $sessions,
                'tier_min' => $tier->minimum_participants,
                'tier_max' => $tier->maximum_participants,
                'tier_price' => $tierPrice,
                'additional_per_participant' => (float) ($tier->additional_price_per_participant ?: 0),
                'additional_cost' => $additionalCost,
                'session_price' => $sessionPrice,
            ],
        ];
    }

    protected function calculateCookingCompetition(array $data): void
    {
        if (!($data['cooking_active'] ?? false) || !$this->cookingComponent) {
            return;
        }

        $participants = (int) ($data['cooking_participants'] ?? 0);
        $capacity = (int) ($data['cooking_capacity'] ?? 10);
        $pricePerGroup = (float) ($data['cooking_price_per_group'] ?? $this->cookingComponent->default_price ?: 100000);
        $manualGroups = isset($data['cooking_manual_groups']) ? (int) $data['cooking_manual_groups'] : null;

        $groups = $manualGroups !== null ? $manualGroups : (int) ceil($participants / max($capacity, 1));
        $total = $groups * $pricePerGroup;
        $pricePerPerson = max($participants, 1) > 0 ? $total / max($participants, 1) : 0;

        $this->items[] = [
            'code' => 'cooking_competition',
            'name' => 'Lomba Masak',
            'quantity' => $groups,
            'frequency' => 1,
            'unit' => 'kelompok',
            'unit_price' => $pricePerGroup,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'capacity' => $capacity,
                'price_per_group' => $pricePerGroup,
                'manual_groups' => $manualGroups,
                'calculated_groups' => $groups,
            ],
        ];
    }

    protected function calculatePickup(array $data): void
    {
        if (!($data['pickup_active'] ?? false) || !$this->pickupAddon) {
            return;
        }

        $users = (int) ($data['pickup_users'] ?? 0);
        $capacity = (int) ($this->pickupAddon->capacity ?: 10);
        $pricePerUnit = (float) ($this->pickupAddon->price ?: 200000);
        $manualUnits = isset($data['pickup_manual_units']) ? (int) $data['pickup_manual_units'] : null;

        $units = $manualUnits !== null ? $manualUnits : (int) ceil(max($users, 0) / max($capacity, 1));
        $total = $units * $pricePerUnit;
        $pricePerPerson = max($users, 1) > 0 ? $total / max($users, 1) : 0;

        $this->items[] = [
            'code' => 'pickup',
            'name' => 'Pickup Wisata',
            'quantity' => $units,
            'frequency' => 1,
            'unit' => 'unit',
            'unit_price' => $pricePerUnit,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'users' => $users,
                'capacity' => $capacity,
                'price_per_unit' => $pricePerUnit,
                'manual_units' => $manualUnits,
                'calculated_units' => $units,
            ],
        ];
    }

    protected function calculateCulturalPerformance(array $data): void
    {
        $performances = (int) ($data['cultural_performances'] ?? 0);
        if ($performances <= 0 || !$this->culturalPerformanceAddon) {
            return;
        }

        $price = (float) ($this->culturalPerformanceAddon->price ?: 1500000);
        $total = $performances * $price;
        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);
        $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;

        $this->items[] = [
            'code' => 'cultural_performance',
            'name' => 'Pertunjukan Kesenian',
            'quantity' => 1,
            'frequency' => $performances,
            'unit' => 'penampilan',
            'unit_price' => $price,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'performances' => $performances,
                'price_per_performance' => $price,
            ],
        ];
    }

    protected function calculateSoundAndLighting(array $data): void
    {
        $option = $data['sound_lighting_option'] ?? 'none';

        if ($option === 'none' || $option === '') {
            return;
        }

        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);

        switch ($option) {
            case 'sound_only':
                if (!$this->professionalSoundAddon) return;
                $price = (float) ($this->professionalSoundAddon->price ?: 700000);
                $total = $price;
                $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;
                $this->items[] = [
                    'code' => 'professional_sound',
                    'name' => 'Sound Profesional',
                    'quantity' => 1,
                    'frequency' => 1,
                    'unit' => 'paket',
                    'unit_price' => $price,
                    'price_per_person' => $pricePerPerson,
                    'total' => $total,
                    'calculation_details' => ['option' => 'sound_only', 'price' => $price],
                ];
                break;

            case 'lighting_only':
                if (!$this->stageLightingAddon) return;
                $price = (float) ($this->stageLightingAddon->price ?: 2000000);
                $total = $price;
                $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;
                $this->items[] = [
                    'code' => 'stage_lighting',
                    'name' => 'Lighting Panggung',
                    'quantity' => 1,
                    'frequency' => 1,
                    'unit' => 'paket',
                    'unit_price' => $price,
                    'price_per_person' => $pricePerPerson,
                    'total' => $total,
                    'calculation_details' => ['option' => 'lighting_only', 'price' => $price],
                ];
                break;

            case 'package':
                if (!$this->soundLightingPackageAddon) return;
                $price = (float) ($this->soundLightingPackageAddon->price ?: 2500000);
                $total = $price;
                $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;
                $this->items[] = [
                    'code' => 'sound_lighting_package',
                    'name' => 'Paket Sound + Lighting',
                    'quantity' => 1,
                    'frequency' => 1,
                    'unit' => 'paket',
                    'unit_price' => $price,
                    'price_per_person' => $pricePerPerson,
                    'total' => $total,
                    'calculation_details' => ['option' => 'package', 'price' => $price],
                ];
                break;
        }
    }

    protected function calculateLiveMusic(array $data): void
    {
        $performances = (int) ($data['live_music_performances'] ?? 0);
        if ($performances <= 0 || !$this->liveMusicAddon) {
            return;
        }

        $price = (float) ($this->liveMusicAddon->price ?: 1500000);
        $total = $performances * $price;
        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);
        $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;

        $this->items[] = [
            'code' => 'live_music',
            'name' => 'Live Music / Organ Tunggal',
            'quantity' => 1,
            'frequency' => $performances,
            'unit' => 'penampilan',
            'unit_price' => $price,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'performances' => $performances,
                'price_per_performance' => $price,
            ],
        ];
    }

    protected function calculateOtherAddon(array $data): void
    {
        if (!($data['other_addon_active'] ?? false)) {
            return;
        }

        $name = $data['other_addon_name'] ?? 'Add-on Lainnya';
        $unitPrice = (float) ($data['other_addon_price'] ?? 0);
        $quantity = (int) ($data['other_addon_quantity'] ?? 0);

        if ($unitPrice <= 0 || $quantity <= 0) {
            return;
        }

        $total = $quantity * $unitPrice;
        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);
        $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;

        $this->items[] = [
            'code' => 'other_addon',
            'name' => $name,
            'quantity' => $quantity,
            'frequency' => 1,
            'unit' => 'item',
            'unit_price' => $unitPrice,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $unitPrice,
            ],
        ];
    }

    protected function calculateSnack(int $participants, array $data): void
    {
        if (!$this->snackComponent || !$this->snackComponent->is_active) {
            return;
        }

        $count = (int) ($data['snack_count'] ?? 0);
        if ($count <= 0) {
            return;
        }

        $price = (float) ($this->snackComponent->default_price ?: 15000);
        $total = $participants * $count * $price;
        $pricePerPerson = $count * $price;

        $this->items[] = [
            'code' => 'snack',
            'name' => 'Snack',
            'quantity' => $participants,
            'frequency' => $count,
            'unit' => 'kali',
            'unit_price' => $price,
            'price_per_person' => $pricePerPerson,
            'total' => $total,
            'calculation_details' => [
                'participants' => $participants,
                'count' => $count,
                'price_per_snack' => $price,
            ],
        ];
    }

    protected function calculateCustomAddonItems(array $data): void
    {
        // Support both old format (custom_items) and new format (addon_items)
        $items = $data['addon_items'] ?? $data['custom_items'] ?? [];
        if (empty($items) || !is_array($items)) {
            return;
        }

        $serviceParticipants = (int) ($data['service_participant_count'] ?? 0);

        foreach ($items as $index => $item) {
            $name = trim($item['name'] ?? '');
            if (empty($name)) {
                continue;
            }

            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $freq = max(1, (int) ($item['frequency'] ?? 1));
            $unit = trim($item['unit'] ?? 'item');
            $unitPrice = max(0, (float) ($item['unit_price'] ?? 0));

            if ($unitPrice <= 0) {
                continue;
            }

            $total = $qty * $freq * $unitPrice;
            $pricePerPerson = $serviceParticipants > 0 ? $total / $serviceParticipants : 0;

            $this->items[] = [
                'code' => 'custom_addon_' . ($index + 1),
                'name' => $name,
                'quantity' => $qty,
                'frequency' => $freq,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'price_per_person' => $pricePerPerson,
                'total' => $total,
                'calculation_details' => [
                    'name' => $name,
                    'quantity' => $qty,
                    'frequency' => $freq,
                    'unit' => $unit,
                    'unit_price' => $unitPrice,
                ],
            ];
        }
    }

    protected function applyRounding(int $serviceParticipants): void
    {
        $this->roundedPricePerPerson = $this->actualPricePerPerson;

        switch ($this->roundingType) {
            case 'up_1000':
                $this->roundedPricePerPerson = ceil($this->actualPricePerPerson / 1000) * 1000;
                break;
            case 'up_5000':
                $this->roundedPricePerPerson = ceil($this->actualPricePerPerson / 5000) * 5000;
                break;
            case 'up_10000':
                $this->roundedPricePerPerson = ceil($this->actualPricePerPerson / 10000) * 10000;
                break;
            case 'down_1000':
                $this->roundedPricePerPerson = floor($this->actualPricePerPerson / 1000) * 1000;
                break;
            case 'down_5000':
                $this->roundedPricePerPerson = floor($this->actualPricePerPerson / 5000) * 5000;
                break;
            case 'down_10000':
                $this->roundedPricePerPerson = floor($this->actualPricePerPerson / 10000) * 10000;
                break;
        }

        $this->quotationTotal = $serviceParticipants > 0
            ? $this->roundedPricePerPerson * $serviceParticipants
            : 0;

        $this->differenceAmount = $this->quotationTotal - $this->subtotal;
    }

    protected function reset(): void
    {
        $this->items = [];
        $this->subtotal = 0;
        $this->actualPricePerPerson = 0;
        $this->roundingType = 'none';
        $this->roundedPricePerPerson = 0;
        $this->quotationTotal = 0;
        $this->differenceAmount = 0;
    }

    public function getResult(): array
    {
        return [
            'items' => $this->items,
            'subtotal' => $this->subtotal,
            'actual_price_per_person' => $this->actualPricePerPerson,
            'rounding_type' => $this->roundingType,
            'rounded_price_per_person' => $this->roundedPricePerPerson,
            'quotation_total' => $this->quotationTotal,
            'difference_amount' => $this->differenceAmount,
        ];
    }
}