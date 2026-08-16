@php
    // Gather all pricing data from database to inject into JavaScript
    $priceData = [
        'components' => \App\Models\PricingComponent::active()->get(['code', 'name', 'default_price'])->map(function($c) {
            return ['code' => $c->code, 'name' => $c->name, 'default_price' => (float) ($c->default_price ?: 0)];
        })->values(),
        'addons' => \App\Models\PricingAddon::active()->get(['code', 'name', 'price', 'capacity'])->map(function($a) {
            return ['code' => $a->code, 'name' => $a->name, 'price' => (float) ($a->price ?: 0), 'capacity' => (int) ($a->capacity ?: 0)];
        })->values(),
        'tiers' => \App\Models\ParticipantPriceTier::with('pricingComponent')->get()->map(function($t) {
            return [
                'component_code' => $t->pricingComponent ? $t->pricingComponent->code : null,
                'min' => (int) ($t->minimum_participants ?: 0),
                'max' => (int) ($t->maximum_participants ?: 0),
                'price' => (float) ($t->price ?: 0),
                'additional' => (float) ($t->additional_price_per_participant ?: 0),
            ];
        })->filter(fn($t) => $t['component_code'] !== null)->values(),
    ];
@endphp

<script>
// Client-side calculation engine (no AJAX needed)
// Mirrors app/Services/PriceCalculatorService.php for offline estimation

const PRICE_DATA = @json($priceData);

function getComponent(code) {
    return PRICE_DATA.components.find(c => c.code === code);
}

function getAddon(code) {
    return PRICE_DATA.addons.find(a => a.code === code);
}

function getTier(componentCode, participants) {
    const tiers = PRICE_DATA.tiers.filter(t => t.component_code === componentCode);
    return tiers.find(t => participants >= t.min && (t.max === 0 || participants <= t.max));
}

function clientCalculate(data) {
    const items = [];
    const serviceParticipants = parseInt(data.service_participant_count) || 0;
    let activityParticipants = parseInt(data.activity_participant_count) || 0;
    if (activityParticipants <= 0) {
        activityParticipants = parseInt(data.student_count) || 0;
    }

    // 1. Live In
    const nights = parseInt(data.live_in_nights) || 0;
    const liveIn = getComponent('live_in');
    if (nights > 0 && liveIn) {
        const price = liveIn.default_price;
        items.push({
            code: 'live_in', name: 'Live In', quantity: serviceParticipants, frequency: nights, unit: 'malam',
            unit_price: price, price_per_person: nights * price, total: serviceParticipants * nights * price
        });
    }

    // 2. Makan
    const meals = parseInt(data.meal_count) || 0;
    const meal = getComponent('meal');
    if (meals > 0 && meal) {
        const price = meal.default_price;
        items.push({
            code: 'meal', name: 'Makan', quantity: serviceParticipants, frequency: meals, unit: 'kali',
            unit_price: price, price_per_person: meals * price, total: serviceParticipants * meals * price
        });
    }

    // 3. Snack
    const snacks = parseInt(data.snack_count) || 0;
    const snack = getComponent('snack');
    if (snacks > 0 && snack) {
        const price = snack.default_price;
        items.push({
            code: 'snack', name: 'Snack', quantity: serviceParticipants, frequency: snacks, unit: 'kali',
            unit_price: price, price_per_person: snacks * price, total: serviceParticipants * snacks * price
        });
    }

    // 4. Pemandu
    if (serviceParticipants > 0) {
        const tier = getTier('guide_fund', serviceParticipants);
        // Fallback: find nearest tier
        let tierPrice = 0;
        if (tier) {
            tierPrice = tier.price;
        } else {
            const allTiers = PRICE_DATA.tiers.filter(t => t.component_code === 'guide_fund');
            if (allTiers.length > 0) {
                // Use the last tier (highest participants)
                tierPrice = allTiers[allTiers.length - 1].price;
            }
        }
        if (tierPrice > 0) {
            items.push({
                code: 'guide_fund', name: 'Pemandu', quantity: serviceParticipants, frequency: 1, unit: 'paket',
                unit_price: tierPrice, price_per_person: tierPrice, total: serviceParticipants * tierPrice
            });
        }
    }

    // 5. Kegiatan Reguler
    const activities = parseInt(data.regular_activity_count) || 0;
    if (activities > 0 && activityParticipants > 0) {
        const component = getComponent('regular_activity');
        const tier = getTier('regular_activity', activityParticipants);
        let price = 0;
        if (tier) {
            price = tier.price;
        } else if (component) {
            price = component.default_price;
        }
        if (price > 0) {
            items.push({
                code: 'regular_activity', name: 'Kegiatan Reguler', quantity: activityParticipants, frequency: activities, unit: 'kegiatan',
                unit_price: price, price_per_person: activities * price, total: activityParticipants * activities * price
            });
        }
    }

    // 6. Kesenian Peserta
    const artSessions = parseInt(data.art_sessions) || 0;
    if (artSessions > 0 && activityParticipants > 0) {
        const tier = getTier('participant_art_activity', activityParticipants);
        if (tier && tier.price > 0) {
            const overCount = Math.max(0, activityParticipants - tier.max);
            const extraCost = overCount > 0 ? overCount * tier.additional : 0;
            const total = activityParticipants * artSessions * tier.price + extraCost;
            items.push({
                code: 'participant_art_activity', name: 'Kegiatan Kesenian Peserta', quantity: activityParticipants, frequency: artSessions, unit: 'sesi',
                unit_price: tier.price, price_per_person: artSessions * tier.price, total: total
            });
        }
    }

    // 7. Lomba Masak
    if (data.cooking_active === '1' || data.cooking_active === true || data.cooking_active === 1) {
        const participants = parseInt(data.cooking_participants) || 0;
        const capacity = parseInt(data.cooking_capacity) || 1;
        const pricePerGroup = parseFloat(data.cooking_price_per_group) || 0;
        let groups = parseInt(data.cooking_manual_groups) || 0;
        if (groups <= 0) {
            groups = Math.ceil(participants / capacity);
        }
        if (groups > 0 && pricePerGroup > 0) {
            items.push({
                code: 'cooking_competition', name: 'Lomba Masak', quantity: groups, frequency: 1, unit: 'kelompok',
                unit_price: pricePerGroup, price_per_person: 0, total: groups * pricePerGroup
            });
        }
    }

    // 8. Pickup
    if (data.pickup_active === '1' || data.pickup_active === true || data.pickup_active === 1) {
        const users = parseInt(data.pickup_users) || 0;
        let units = parseInt(data.pickup_manual_units) || 0;
        const addon = getAddon('pickup');
        if (addon) {
            if (units <= 0) {
                units = addon.capacity > 0 ? Math.ceil(users / addon.capacity) : (users > 0 ? 1 : 0);
            }
            if (units > 0) {
                items.push({
                    code: 'pickup', name: 'Pickup Wisata', quantity: units, frequency: 1, unit: 'unit',
                    unit_price: addon.price, price_per_person: 0, total: units * addon.price
                });
            }
        }
    }

    // 9. Pertunjukan Kesenian
    const perfCount = parseInt(data.cultural_performances) || 0;
    const perfAddon = getAddon('cultural_performance');
    if (perfCount > 0 && perfAddon) {
        items.push({
            code: 'cultural_performance', name: 'Pertunjukan Kesenian', quantity: perfCount, frequency: 1, unit: 'penampilan',
            unit_price: perfAddon.price, price_per_person: 0, total: perfCount * perfAddon.price
        });
    }

    // 10. Sound & Lighting
    const soundOption = data.sound_lighting_option || 'none';
    let soundAddonCode = null;
    if (soundOption === 'sound_only') soundAddonCode = 'professional_sound';
    else if (soundOption === 'lighting_only') soundAddonCode = 'stage_lighting';
    else if (soundOption === 'package') soundAddonCode = 'sound_lighting_package';
    if (soundAddonCode) {
        const addon = getAddon(soundAddonCode);
        if (addon && addon.price > 0) {
            items.push({
                code: soundAddonCode, name: addon.name, quantity: 1, frequency: 1, unit: 'paket',
                unit_price: addon.price, price_per_person: 0, total: addon.price
            });
        }
    }

    // 11. Live Music
    const liveCount = parseInt(data.live_music_performances) || 0;
    const liveAddon = getAddon('live_music');
    if (liveCount > 0 && liveAddon) {
        items.push({
            code: 'live_music', name: 'Live Music / Organ Tunggal', quantity: liveCount, frequency: 1, unit: 'penampilan',
            unit_price: liveAddon.price, price_per_person: 0, total: liveCount * liveAddon.price
        });
    }

    // 12. Add-on Lainnya (dynamic items)
    (data.addon_items || []).forEach((item, idx) => {
        const name = item.name || ('Add-on ' + (idx + 1));
        const price = parseFloat(item.unit_price) || 0;
        const qty = parseInt(item.quantity) || 1;
        const multiplier = parseInt(item.multiplier) || 1;
        const multiplierActive = item.multiplier_active === '1' || item.multiplier_active === true || item.multiplier_active === 1;
        if (name && price > 0) {
            items.push({
                code: 'custom_addon_' + (idx + 1), name: name, quantity: qty, frequency: 1, unit: 'unit',
                unit_price: price, price_per_person: 0, total: qty * price * (multiplierActive ? multiplier : 1)
            });
        }
    });

    // Totals
    const subtotal = items.reduce((sum, i) => sum + i.total, 0);
    const actualPricePerPerson = serviceParticipants > 0 ? subtotal / serviceParticipants : 0;

    // Rounding
    const roundingType = data.rounding_type || 'none';
    let roundedPricePerPerson = actualPricePerPerson;
    switch (roundingType) {
        case 'up_1000': roundedPricePerPerson = Math.ceil(actualPricePerPerson / 1000) * 1000; break;
        case 'up_5000': roundedPricePerPerson = Math.ceil(actualPricePerPerson / 5000) * 5000; break;
        case 'up_10000': roundedPricePerPerson = Math.ceil(actualPricePerPerson / 10000) * 10000; break;
        case 'down_1000': roundedPricePerPerson = Math.floor(actualPricePerPerson / 1000) * 1000; break;
        case 'down_5000': roundedPricePerPerson = Math.floor(actualPricePerPerson / 5000) * 5000; break;
        case 'down_10000': roundedPricePerPerson = Math.floor(actualPricePerPerson / 10000) * 10000; break;
    }
    const quotationTotal = serviceParticipants > 0 ? roundedPricePerPerson * serviceParticipants : 0;
    const differenceAmount = quotationTotal - subtotal;

    return {
        items: items,
        subtotal: subtotal,
        actual_price_per_person: actualPricePerPerson,
        rounding_type: roundingType,
        rounded_price_per_person: roundedPricePerPerson,
        quotation_total: quotationTotal,
        difference_amount: differenceAmount,
    };
}
</script>