@php
    use App\Models\PricingComponent;
    $snackComponent = PricingComponent::where('code', 'snack')->where('is_active', true)->first();
    $snackPrice = $snackComponent ? $snackComponent->default_price : 15000;
    $snackActive = $snackComponent && $snackComponent->is_active;

    // Extract stored values from estimation items' calculation_details
    $stored = [
        'live_in_nights' => 0,
        'meal_count' => 0,
        'snack_count' => 0,
        'regular_activity_count' => 0,
        'art_sessions' => 0,
        'cooking_active' => false,
        'cooking_participants' => 0,
        'cooking_capacity' => 10,
        'cooking_price_per_group' => 100000,
        'cooking_manual_groups' => null,
        'pickup_active' => false,
        'pickup_users' => 0,
        'pickup_manual_units' => null,
        'cultural_performances' => 0,
        'sound_lighting_option' => 'none',
        'live_music_performances' => 0,
        'addon_items' => [],
    ];

    foreach ($estimation->items as $item) {
        $details = $item->calculation_details ?: [];
        switch ($item->item_code) {
            case 'live_in':
                $stored['live_in_nights'] = $details['nights'] ?? $item->frequency;
                break;
            case 'meal':
                $stored['meal_count'] = $details['meals'] ?? $item->frequency;
                break;
            case 'snack':
                $stored['snack_count'] = $details['count'] ?? $item->frequency;
                break;
            case 'regular_activity':
                $stored['regular_activity_count'] = $details['activities'] ?? $item->frequency;
                break;
            case 'participant_art_activity':
                $stored['art_sessions'] = $details['sessions'] ?? $item->frequency;
                break;
            case 'cooking_competition':
                $stored['cooking_active'] = true;
                $stored['cooking_participants'] = $details['participants'] ?? 0;
                $stored['cooking_capacity'] = $details['capacity'] ?? 10;
                $stored['cooking_price_per_group'] = $details['price_per_group'] ?? $item->unit_price;
                $stored['cooking_manual_groups'] = $details['manual_groups'] ?? null;
                break;
            case 'pickup':
                $stored['pickup_active'] = true;
                $stored['pickup_users'] = $details['users'] ?? 0;
                $stored['pickup_manual_units'] = $details['manual_units'] ?? null;
                break;
            case 'cultural_performance':
                $stored['cultural_performances'] = $details['performances'] ?? $item->frequency;
                break;
            case 'professional_sound':
                $stored['sound_lighting_option'] = 'sound_only';
                break;
            case 'stage_lighting':
                $stored['sound_lighting_option'] = 'lighting_only';
                break;
            case 'sound_lighting_package':
                $stored['sound_lighting_option'] = 'package';
                break;
            case 'live_music':
                $stored['live_music_performances'] = $details['performances'] ?? $item->frequency;
                break;
            case 'other_addon':
            case 'custom_addon_1':
            case 'custom_addon_2':
            case 'custom_addon_3':
            case 'custom_addon_4':
            case 'custom_addon_5':
                $stored['addon_items'][] = [
                    'name' => $item->item_name,
                    'unit_price' => (float) $item->unit_price,
                    'quantity' => $item->quantity,
                    'multiplier' => (int) ($details['multiplier'] ?? 1),
                    'multiplier_active' => (bool) ($details['multiplier_active'] ?? false),
                ];
                break;
        }
    }
    $hasAddons = !empty($stored['addon_items']);
@endphp

@extends('layouts.app')

@section('title', 'Edit Estimasi - ' . $estimation->estimation_number)

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ isset($duplicate) && $duplicate ? 'Duplikasi Estimasi' : 'Edit Estimasi' }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $estimation->institution_name }} ({{ $estimation->estimation_number }})</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary admin-btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @php $updateRoute = !(isset($duplicate) && $duplicate); @endphp
        <form method="POST" id="calculatorForm" action="{{ $updateRoute ? route('admin.price-calculator.update', $estimation) : route('admin.price-calculator.store') }}" class="space-y-6">
            @csrf
            @if($updateRoute) @method('PUT') @endif

        {{-- Section A: Informasi Rombongan --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-school text-primary-600 mr-2"></i>
                    Informasi Rombongan
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Nama Sekolah / Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="institution_name" class="admin-input" required value="{{ old('institution_name', $estimation->institution_name) }}">
                    </div>
                    <div>
                        <label class="admin-label">Nama Penanggung Jawab <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_person" class="admin-input" required value="{{ old('contact_person', $estimation->contact_person) }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="whatsapp" class="admin-input" required value="{{ old('whatsapp', $estimation->whatsapp) }}">
                    </div>
                    <div>
                        <label class="admin-label">Catatan</label>
                        <input type="text" name="notes" class="admin-input" value="{{ old('notes', $estimation->notes) }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                        <input type="date" name="arrival_date" class="admin-input" required value="{{ old('arrival_date', $estimation->arrival_date->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label class="admin-label">Tanggal Kepulangan <span class="text-red-500">*</span></label>
                        <input type="date" name="departure_date" class="admin-input" required value="{{ old('departure_date', $estimation->departure_date->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section B: Data Peserta --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-users text-primary-600 mr-2"></i>
                    Data Peserta
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Siswa <span class="text-red-500">*</span></label>
                        <input type="number" name="student_count" id="student_count" class="admin-input" required min="1" value="{{ old('student_count', $estimation->student_count) }}">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Pendamping</label>
                        <input type="number" name="companion_count" id="companion_count" class="admin-input" min="0" value="{{ old('companion_count', $estimation->companion_count) }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Peserta Layanan Utama <span class="text-red-500">*</span></label>
                        <input type="number" name="service_participant_count" id="service_participant_count" class="admin-input" required min="1" value="{{ old('service_participant_count', $estimation->student_count + $estimation->companion_count) }}" disabled>
                        <p class="text-xs text-gray-400 mt-1">Otomatis: jumlah siswa + pendamping</p>
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Peserta Kegiatan</label>
                        <input type="number" name="activity_participant_count" id="activity_participant_count" class="admin-input" min="0" value="{{ old('activity_participant_count', $estimation->student_count) }}" disabled>
                        <p class="text-xs text-gray-400 mt-1">Otomatis: jumlah siswa</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section C: Komponen Paket Utama --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-box text-primary-600 mr-2"></i>
                    Komponen Paket Utama
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Malam Live In</label>
                        <input type="number" name="live_in_nights" id="live_in_nights" class="admin-input" min="0" value="{{ old('live_in_nights', $stored['live_in_nights']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Frekuensi Makan</label>
                        <input type="number" name="meal_count" id="meal_count" class="admin-input" min="0" value="{{ old('meal_count', $stored['meal_count']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Kegiatan Reguler</label>
                        <input type="number" name="regular_activity_count" id="regular_activity_count" class="admin-input" min="0" value="{{ old('regular_activity_count', $stored['regular_activity_count']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Sesi Kegiatan Kesenian Peserta</label>
                        <input type="number" name="art_sessions" id="art_sessions" class="admin-input" min="0" value="{{ old('art_sessions', $stored['art_sessions']) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section D: Snack --}}
        @if($snackActive)
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-cookie text-primary-600 mr-2"></i>
                    Snack
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div>
                    <label class="admin-label">Frekuensi Snack</label>
                    <input type="number" name="snack_count" id="snack_count" class="admin-input" min="0" value="{{ old('snack_count', $stored['snack_count'] ?? 0) }}" placeholder="Jumlah kali snack">
                    <p class="text-xs text-gray-400 mt-1">Harga snack per orang per kali: {{ formatPrice($snackPrice) }} (dapat diubah di Pengaturan Harga)</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Section E: Lomba Masak --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-utensils text-primary-600 mr-2"></i>
                    Lomba Masak
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cooking_active" id="cooking_active" value="1" class="sr-only peer" {{ $stored['cooking_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Aktifkan Lomba Masak</span>
                </div>
                <div id="cookingFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ $stored['cooking_active'] ? '' : 'hidden' }}">
                    <div>
                        <label class="admin-label">Jumlah Peserta Lomba Masak</label>
                        <input type="number" name="cooking_participants" id="cooking_participants" class="admin-input" min="0" value="{{ old('cooking_participants', $stored['cooking_participants']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Kapasitas per Kelompok</label>
                        <input type="number" name="cooking_capacity" id="cooking_capacity" class="admin-input" min="1" value="{{ old('cooking_capacity', $stored['cooking_capacity']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Harga per Kelompok (Rp)</label>
                        <input type="number" name="cooking_price_per_group" id="cooking_price_per_group" class="admin-input" min="0" value="{{ old('cooking_price_per_group', $stored['cooking_price_per_group']) }}">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Kelompok (Manual, opsional)</label>
                        <input type="number" name="cooking_manual_groups" id="cooking_manual_groups" class="admin-input" min="0" placeholder="Biarkan kosong untuk otomatis" value="{{ old('cooking_manual_groups', $stored['cooking_manual_groups']) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section F: Add-on --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-plus-circle text-primary-600 mr-2"></i>
                    Add-on
                </h3>
            </div>
            <div class="admin-card-body space-y-6">

                {{-- F1: Transportasi --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-bus text-primary-500"></i>
                        Transportasi
                    </h4>
                    <div class="flex items-center gap-3 mb-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="pickup_active" id="pickup_active" value="1" class="sr-only peer" {{ $stored['pickup_active'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Pickup Wisata</span>
                    </div>
                    <div id="pickupFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ $stored['pickup_active'] ? '' : 'hidden' }}">
                        <div>
                            <label class="admin-label">Jumlah Pengguna Pickup</label>
                            <input type="number" name="pickup_users" id="pickup_users" class="admin-input" min="0" value="{{ old('pickup_users', $stored['pickup_users']) }}">
                        </div>
                        <div>
                            <label class="admin-label">Jumlah Unit (Manual, opsional)</label>
                            <input type="number" name="pickup_manual_units" id="pickup_manual_units" class="admin-input" min="0" placeholder="Kosong untuk otomatis" value="{{ old('pickup_manual_units', $stored['pickup_manual_units']) }}">
                        </div>
                    </div>
                </div>

                {{-- F2: Kesenian dan Hiburan --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-music text-primary-500"></i>
                        Kesenian & Hiburan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label">Pertunjukan Kesenian</label>
                            <input type="number" name="cultural_performances" id="cultural_performances" class="admin-input" min="0" value="{{ old('cultural_performances', $stored['cultural_performances']) }}" placeholder="Jumlah penampilan">
                        </div>
                        <div>
                            <label class="admin-label">Live Music / Organ Tunggal</label>
                            <input type="number" name="live_music_performances" id="live_music_performances" class="admin-input" min="0" value="{{ old('live_music_performances', $stored['live_music_performances']) }}" placeholder="Jumlah penampilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Opsi Sound & Lighting</label>
                            <select name="sound_lighting_option" id="sound_lighting_option" class="admin-input">
                                <option value="none" {{ $stored['sound_lighting_option'] === 'none' ? 'selected' : '' }}>Tanpa Sound & Lighting</option>
                                <option value="sound_only" {{ $stored['sound_lighting_option'] === 'sound_only' ? 'selected' : '' }}>Sound Profesional Saja (Rp700.000)</option>
                                <option value="lighting_only" {{ $stored['sound_lighting_option'] === 'lighting_only' ? 'selected' : '' }}>Lighting Panggung Saja (Rp2.000.000)</option>
                                <option value="package" {{ $stored['sound_lighting_option'] === 'package' ? 'selected' : '' }}>Paket Sound + Lighting (Rp2.500.000)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- F3: Add-on Lainnya (dinamis, multiple) --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-box-open text-primary-500"></i>
                        Add-on Lainnya
                    </h4>
                    <div class="flex items-center gap-3 mb-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="other_addon_active" id="other_addon_active" value="1" class="sr-only peer" {{ $hasAddons ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Aktifkan Add-on Lainnya</span>
                    </div>
                    <div id="otherAddonFields" class="space-y-3 {{ $hasAddons ? '' : 'hidden' }}">
                        <div id="addonItemsContainer" class="space-y-3">
                            @foreach($stored['addon_items'] as $idx => $addon)
                            <div class="addon-item-row bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Add-on {{ $idx + 1 }}</span>
                                    <button type="button" class="admin-btn-sm admin-btn-danger" onclick="this.closest('.addon-item-row').remove(); debouncedCalculate();"><i class="fas fa-trash"></i> Hapus</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div><label class="admin-label text-xs">Nama Add-on</label><input type="text" class="admin-input ao-name" value="{{ $addon['name'] }}" readonly></div>
                                    <div><label class="admin-label text-xs">Harga Satuan (Rp)</label><input type="number" class="admin-input ao-price" min="0" value="{{ $addon['unit_price'] }}" step="1000" readonly></div>
                                    <div><label class="admin-label text-xs">Jumlah</label><input type="number" class="admin-input ao-qty" min="1" value="{{ $addon['quantity'] }}"></div>
                                </div>
                                <div class="flex items-center gap-3 mt-2">
                                    <label class="flex items-center gap-2 text-xs font-medium text-gray-600">
                                        <input type="checkbox" class="ao-multiplier-active" {{ !empty($addon['multiplier_active']) ? 'checked' : '' }}>
                                        Aktifkan Pengali
                                    </label>
                                    <input type="number" class="admin-input ao-multiplier w-24" min="1" value="{{ $addon['multiplier'] ?? 1 }}" placeholder="Pengali" {{ !empty($addon['multiplier_active']) ? '' : 'disabled' }}>
                                    <span class="text-xs text-gray-400">Digunakan untuk pengalian hari / periode / lainnya</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="addAddonItemBtn" class="admin-btn-secondary admin-btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Add-on
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section G: Pembulatan --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calculator text-primary-600 mr-2"></i>
                    Pembulatan Harga
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div>
                    <label class="admin-label">Pilihan Pembulatan</label>
                    <select name="rounding_type" id="rounding_type" class="admin-input">
                        <option value="none" {{ $estimation->rounding_type === 'none' ? 'selected' : '' }}>Tanpa Pembulatan</option>
                        <option value="up_1000" {{ $estimation->rounding_type === 'up_1000' ? 'selected' : '' }}>Ke atas Rp1.000</option>
                        <option value="up_5000" {{ $estimation->rounding_type === 'up_5000' ? 'selected' : '' }}>Ke atas Rp5.000</option>
                        <option value="up_10000" {{ $estimation->rounding_type === 'up_10000' ? 'selected' : '' }}>Ke atas Rp10.000</option>
                        <option value="down_1000" {{ $estimation->rounding_type === 'down_1000' ? 'selected' : '' }}>Ke bawah Rp1.000</option>
                        <option value="down_5000" {{ $estimation->rounding_type === 'down_5000' ? 'selected' : '' }}>Ke bawah Rp5.000</option>
                        <option value="down_10000" {{ $estimation->rounding_type === 'down_10000' ? 'selected' : '' }}>Ke bawah Rp10.000</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Actions Desktop --}}
        <div class="hidden lg:flex flex-wrap gap-3">
            <button type="button" class="calculate-btn admin-btn-primary">
                <i class="fas fa-calculator mr-2"></i>
                Hitung Estimasi
            </button>
            <button type="submit" class="admin-btn-success">
                <i class="fas fa-save mr-2"></i>
                {{ isset($duplicate) && $duplicate ? 'Duplikasi Estimasi' : 'Simpan Perubahan' }}
            </button>
            <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
        </div>
        </form>
    </div>

    {{-- Results Panel --}}
    <div class="lg:col-span-1 space-y-6 py-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-file-invoice text-primary-600 mr-2"></i>
                    Ringkasan Estimasi
                </h3>
            </div>
            <div class="admin-card-body" id="resultsContainer">
                @if($estimation->items->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="admin-table text-xs">
                            <thead>
                                <tr>
                                    <th>Komponen</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estimation->items as $item)
                                    <tr>
                                        <td class="font-medium">{{ $item->item_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="font-mono">{{ formatPrice($item->unit_price) }}</td>
                                        <td class="font-mono font-semibold">{{ formatPrice($item->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 space-y-2 border-t pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Grand Total Biaya:</span>
                            <span class="font-bold font-mono">{{ formatPrice($estimation->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga Aktual per Orang:</span>
                            <span class="font-mono">{{ formatPrice($estimation->actual_price_per_person) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga per Orang:</span>
                            <span class="font-bold font-mono text-primary-600">{{ formatPrice($estimation->rounded_price_per_person) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Quotation:</span>
                            <span class="font-bold font-mono text-primary-600">{{ formatPrice($estimation->quotation_total) }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-calculator text-4xl mb-3"></i>
                        <p>Klik "Hitung Estimasi" untuk melihat hasil</p>
                    </div>
                @endif
            </div>
        </div>
        {{-- Actions Mobile --}}
        <div class="lg:hidden flex-wrap gap-3 space-y-3">
            <button type="button" class="calculate-btn admin-btn-primary w-full">
                <i class="fas fa-calculator mr-2"></i>
                Hitung Estimasi
            </button>
            <button type="submit" class="admin-btn-success w-full">
                <i class="fas fa-save mr-2"></i>
                {{ isset($duplicate) && $duplicate ? 'Duplikasi Estimasi' : 'Simpan Perubahan' }}
            </button>
            <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary w-full">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.price-calculator._client-calculator')
<script>
const calculatorForm = document.querySelector('#calculatorForm');
const calculateBtns = document.querySelectorAll('.calculate-btn');
const resultsContainer = document.getElementById('resultsContainer');

let lastCalculationResult = null;
let calculateTimeout = null;
let addonItemIndex = {{ max(count($stored['addon_items']), 0) }};

// Auto-calc participant counts like create page
function autoCalcServiceParticipantsEdit() {
    const students = parseInt(document.getElementById('student_count').value) || 0;
    const companions = parseInt(document.getElementById('companion_count').value) || 0;
    const spc = document.getElementById('service_participant_count');
    if (!spc.dataset.manual) { spc.value = students + companions; }
}

function autoCalcActivityParticipantsEdit() {
    const students = parseInt(document.getElementById('student_count').value) || 0;
    const apc = document.getElementById('activity_participant_count');
    if (!apc.dataset.manual) { apc.value = students; }
}

document.getElementById('student_count')?.addEventListener('input', function() {
    delete document.getElementById('service_participant_count').dataset.manual;
    delete document.getElementById('activity_participant_count').dataset.manual;
    autoCalcServiceParticipantsEdit(); autoCalcActivityParticipantsEdit(); debouncedCalculate();
});
document.getElementById('companion_count')?.addEventListener('input', function() {
    delete document.getElementById('service_participant_count').dataset.manual;
    autoCalcServiceParticipantsEdit(); debouncedCalculate();
});
document.getElementById('service_participant_count')?.addEventListener('input', function() {
    this.dataset.manual = '1'; debouncedCalculate();
});
document.getElementById('activity_participant_count')?.addEventListener('input', function() {
    this.dataset.manual = '1'; debouncedCalculate();
});

// Cooking toggle
document.getElementById('cooking_active')?.addEventListener('change', function() {
    document.getElementById('cookingFields').classList.toggle('hidden', !this.checked);
    debouncedCalculate();
});

// Pickup toggle
document.getElementById('pickup_active')?.addEventListener('change', function() {
    document.getElementById('pickupFields').classList.toggle('hidden', !this.checked);
    debouncedCalculate();
});

// Addon toggle
document.getElementById('other_addon_active')?.addEventListener('change', function() {
    document.getElementById('otherAddonFields').classList.toggle('hidden', !this.checked);
    debouncedCalculate();
});

// Rounding change
document.getElementById('rounding_type')?.addEventListener('change', function() {
    if (lastCalculationResult) debouncedCalculate();
});

// All inputs trigger calculation
document.querySelectorAll('#calculatorForm input, #calculatorForm select').forEach(el => {
    el.addEventListener('change', debouncedCalculate);
    if (el.type === 'number') el.addEventListener('input', debouncedCalculate);
});

function debouncedCalculate() {
    clearTimeout(calculateTimeout);
    calculateTimeout = setTimeout(() => performCalculation(), 500);
}

function getAddonItemsData() {
    const items = [];
    document.querySelectorAll('.addon-item-row').forEach(row => {
        const qty = parseInt(row.querySelector('.ao-qty')?.value) || 1;
        const multiplier = parseInt(row.querySelector('.ao-multiplier')?.value) || 1;
        const multiplierActive = row.querySelector('.ao-multiplier-active')?.checked || false;
        // New dropdown format: ao-addon select
        const code = row.querySelector('.ao-addon')?.value;
        if (code) {
            const addon = PRICE_DATA.addons.find(a => a.code === code);
            if (addon && addon.price > 0) {
                items.push({ name: addon.name, unit_price: addon.price, quantity: qty, multiplier, multiplier_active: multiplierActive ? '1' : '0' });
            }
            return;
        }
        // Legacy format: ao-name + ao-price
        const name = row.querySelector('.ao-name')?.value?.trim();
        const price = parseFloat(row.querySelector('.ao-price')?.value) || 0;
        if (name && price > 0) items.push({ name, unit_price: price, quantity: qty, multiplier, multiplier_active: multiplierActive ? '1' : '0' });
    });
    return items;
}

function performCalculation() {
    const formData = new FormData(calculatorForm);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });

    // Baca langsung dari DOM karena input ini disabled dan tidak masuk FormData
    data.service_participant_count = document.getElementById('service_participant_count').value;
    data.activity_participant_count = document.getElementById('activity_participant_count').value;
    data.student_count = document.getElementById('student_count').value;

    data.cooking_active = document.getElementById('cooking_active').checked ? '1' : '0';
    data.pickup_active = document.getElementById('pickup_active').checked ? '1' : '0';
    data.other_addon_active = document.getElementById('other_addon_active').checked ? '1' : '0';
    data.rounding_type = document.getElementById('rounding_type').value;
    data.addon_items = getAddonItemsData();

    // Client-side calculation (no AJAX / no 404)
    const result = clientCalculate(data);
    lastCalculationResult = result;
    renderResults(result);
}

function formatCurrency(amount) {
    return 'Rp ' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function renderResults(result) {
    if (!result.items || result.items.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-calculator text-4xl mb-3"></i><p>Klik "Hitung Estimasi" untuk melihat hasil</p></div>';
        return;
    }
    let html = '<div class="overflow-x-auto"><table class="admin-table text-xs"><thead><tr><th>Komponen</th><th>Qty</th><th>Frekuensi</th><th>Harga Satuan</th><th>Harga/Orang</th><th>Jumlah</th></tr></thead><tbody>';
    result.items.forEach(item => {
        html += `<tr><td class="font-medium">${item.name}</td><td>${item.quantity}</td><td>${item.frequency} ${item.unit}</td><td class="font-mono">${formatCurrency(item.unit_price)}</td><td class="font-mono">${formatCurrency(item.price_per_person)}</td><td class="font-mono font-semibold">${formatCurrency(item.total)}</td></tr>`;
    });
    html += `</tbody></table></div>`;
    html += `<div class="mt-4 space-y-2 border-t pt-4">
        <div class="flex justify-between text-sm"><span class="text-gray-600">Grand Total Biaya:</span><span class="font-bold font-mono">${formatCurrency(result.subtotal)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Harga Aktual per Orang:</span><span class="font-mono">${formatCurrency(result.actual_price_per_person)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Harga per Orang ${result.rounding_type === 'none' ? '' : '(Setelah Pembulatan)'}:</span><span class="font-bold font-mono text-primary-600">${formatCurrency(result.rounded_price_per_person)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Total Quotation:</span><span class="font-bold font-mono text-primary-600">${formatCurrency(result.quotation_total)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Selisih Pembulatan:</span><span class="font-mono ${result.difference_amount >= 0 ? 'text-green-600' : 'text-red-600'}">${result.difference_amount >= 0 ? '+' : ''}${formatCurrency(result.difference_amount)}</span></div>
    </div>`;
    resultsContainer.innerHTML = html;
}

calculateBtns.forEach(function(btn) {
    btn.addEventListener('click', performCalculation);
});

// FIX: Inject addon_items + ensure rounding_type into form before submit
calculatorForm.addEventListener('submit', function() {
    // Remove any previously injected hidden inputs
    this.querySelectorAll('input[name^="addon_items"]').forEach(el => el.remove());
    this.querySelectorAll('input[name="rounding_type"]').forEach(el => el.remove());

    // Ensure rounding_type is always sent (sync from select)
    const roundingSelect = this.querySelector('#rounding_type');
    if (roundingSelect) {
        const hiddenRt = document.createElement('input');
        hiddenRt.type = 'hidden';
        hiddenRt.name = 'rounding_type';
        hiddenRt.value = roundingSelect.value;
        this.appendChild(hiddenRt);
    }

    const addonItems = getAddonItemsData();
    if (!this.querySelector('#other_addon_active').checked || addonItems.length === 0) {
        return; // No addons, skip injection
    }
    addonItems.forEach((item, idx) => {
        ['name', 'unit_price', 'quantity', 'multiplier', 'multiplier_active'].forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `addon_items[${idx}][${field}]`;
            input.value = item[field] ?? '';
            this.appendChild(input);
        });
    });
});

// Auto-calculate on page load so Ringkasan Estimasi appears immediately
setTimeout(function() {
    try {
        performCalculation();
    } catch (e) {
        console.error('Auto-calculate error:', e);
    }
}, 300);

// Addon item button
document.getElementById('addAddonItemBtn')?.addEventListener('click', function() {
    addonItemIndex++;
    const container = document.getElementById('addonItemsContainer');
    const row = document.createElement('div');
    row.className = 'addon-item-row bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3';
    row.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Add-on ${addonItemIndex}</span>
            <button type="button" class="admin-btn-sm admin-btn-danger" onclick="this.closest('.addon-item-row').remove(); debouncedCalculate();"><i class="fas fa-trash"></i> Hapus</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="admin-label text-xs">Nama Add-on</label><input type="text" class="admin-input ao-name" placeholder="Nama add-on"></div>
            <div><label class="admin-label text-xs">Harga Satuan (Rp)</label><input type="number" class="admin-input ao-price" min="0" value="0" step="1000"></div>
            <div><label class="admin-label text-xs">Jumlah</label><input type="number" class="admin-input ao-qty" min="1" value="1"></div>
        </div>
        <div class="flex items-center gap-3 mt-2">
            <label class="flex items-center gap-2 text-xs font-medium text-gray-600">
                <input type="checkbox" class="ao-multiplier-active">
                Aktifkan Pengali
            </label>
            <input type="number" class="admin-input ao-multiplier w-24" min="1" value="1" placeholder="Pengali" disabled>
            <span class="text-xs text-gray-400">Digunakan untuk pengalian hari / periode / lainnya</span>
        </div>`;
    container.appendChild(row);
    row.querySelectorAll('input').forEach(el => el.addEventListener('input', debouncedCalculate));
    row.querySelectorAll('input, select').forEach(el => el.addEventListener('change', debouncedCalculate));

    // Sync multiplier input disabled state with checkbox
    const multCheck = row.querySelector('.ao-multiplier-active');
    const multInput = row.querySelector('.ao-multiplier');
    function syncMult() {
        multInput.disabled = !multCheck.checked;
        if (!multCheck.checked) multInput.value = 1;
    }
    multCheck.addEventListener('change', () => { syncMult(); debouncedCalculate(); });
    syncMult();
});

// Sync multiplier state for pre-rendered (stored) rows
document.querySelectorAll('.addon-item-row').forEach(row => {
    const multCheck = row.querySelector('.ao-multiplier-active');
    const multInput = row.querySelector('.ao-multiplier');
    if (!multCheck || !multInput) return;
    function syncStoredMult() {
        multInput.disabled = !multCheck.checked;
        if (!multCheck.checked) multInput.value = 1;
    }
    multCheck.addEventListener('change', () => { syncStoredMult(); debouncedCalculate(); });
    syncStoredMult();
});
</script>
@endpush
