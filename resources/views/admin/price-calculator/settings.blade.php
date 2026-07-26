@extends('layouts.app')

@section('title', 'Pengaturan Harga - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Pengaturan Harga</h1>
        <p class="text-sm text-gray-500 mt-1">Atur harga dasar komponen paket dan add-on</p>
    </div>
    <div>
        <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary admin-btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali ke Kalkulator
        </a>
    </div>
</div>

<div class="space-y-6">
    {{-- Komponen Paket Utama --}}
    <div class="admin-card shadow-md">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">
                <i class="fas fa-box text-primary-600 mr-2"></i>
                Komponen Paket Utama
            </h3>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th>Tipe Perhitungan</th>
                            <th>Harga Default</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($components as $component)
                            <tr>
                                <td>
                                    <span class="font-medium">{{ $component->name }}</span>
                                    <p class="text-xs text-gray-400">{{ $component->description }}</p>
                                </td>
                                <td>
                                    <span class="admin-badge-primary text-xs">{{ $component->calculation_type }}</span>
                                </td>
                                <td class="font-mono">
                                    @if($component->default_price)
                                        {{ formatPrice($component->default_price) }}
                                    @else
                                        <span class="text-gray-400 text-xs">Menggunakan tier</span>
                                    @endif
                                </td>
                                <td>
                                    @if($component->is_active)
                                        <span class="admin-badge-success text-xs">Aktif</span>
                                    @else
                                        <span class="admin-badge-danger text-xs">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($component->default_price !== null)
                                        <form method="POST" action="{{ route('admin.price-calculator.update-component-price', $component) }}" class="flex gap-2 items-center">
                                            @csrf @method('PUT')
                                            <input type="number" name="default_price" class="admin-input-sm w-32" value="{{ $component->default_price }}" step="1" min="0">
                                            <button type="submit" class="admin-btn-sm admin-btn-primary">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Lihat tier</span>
                                    @endif
                                </td>
                            </tr>
                            {{-- Show tiers if exists --}}
                            @if($component->activeTiers->isNotEmpty())
                                <tr class="bg-gray-50">
                                    <td colspan="5" class="py-2">
                                        <div class="ml-6">
                                            <table class="text-xs w-full">
                                                <thead>
                                                    <tr class="text-gray-500">
                                                        <th class="text-left py-1">Min Peserta</th>
                                                        <th class="text-left py-1">Max Peserta</th>
                                                        <th class="text-left py-1">Harga</th>
                                                        <th class="text-left py-1">Tambahan/Peserta (lebih max)</th>
                                                        <th class="text-left py-1">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($component->activeTiers as $tier)
                                                        <tr>
                                                            <td class="py-1">{{ $tier->minimum_participants }}</td>
                                                            <td class="py-1">{{ $tier->maximum_participants ?? 'Unlimited' }}</td>
                                                            <td class="font-mono py-1">{{ formatPrice($tier->price) }}</td>
                                                            <td class="font-mono py-1">{{ $tier->additional_price_per_participant ? formatPrice($tier->additional_price_per_participant) : '-' }}</td>
                                                            <td class="py-1">
                                                                <form method="POST" action="{{ route('admin.price-calculator.update-tier', $tier) }}" class="flex gap-1 items-center">
                                                                    @csrf @method('PUT')
                                                                    <input type="number" name="price" class="admin-input-sm w-24" value="{{ $tier->price }}" step="1" min="0">
                                                                    @if($component->code === 'participant_art_activity')
                                                                        <input type="number" name="additional_price_per_participant" class="admin-input-sm w-24" value="{{ $tier->additional_price_per_participant ?? 0 }}" step="1" min="0" placeholder="Extra/orang">
                                                                    @endif
                                                                    <button type="submit" class="admin-btn-sm admin-btn-primary">
                                                                        <i class="fas fa-save"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pricing Addons --}}
    <div class="admin-card shadow-md">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">
                <i class="fas fa-plus-circle text-primary-600 mr-2"></i>
                Add-on & Layanan Tambahan
            </h3>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addons as $addon)
                            <tr>
                                <td>
                                    <span class="font-medium">{{ $addon->name }}</span>
                                    @if($addon->description)
                                        <p class="text-xs text-gray-400">{{ $addon->description }}</p>
                                    @endif
                                </td>
                                <td class="font-mono">{{ formatPrice($addon->price) }}</td>
                                <td>{{ $addon->capacity ? $addon->capacity . ' org' : '-' }}</td>
                                <td>
                                    @if($addon->is_active)
                                        <span class="admin-badge-success text-xs">Aktif</span>
                                    @else
                                        <span class="admin-badge-danger text-xs">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.price-calculator.update-addon-price', $addon) }}" class="flex gap-2 items-center">
                                        @csrf @method('PUT')
                                        <input type="number" name="price" class="admin-input-sm w-28" value="{{ $addon->price }}" step="1" min="0">
                                        @if($addon->capacity)
                                            <input type="number" name="capacity" class="admin-input-sm w-20" value="{{ $addon->capacity }}" min="1">
                                        @endif
                                        <button type="submit" class="admin-btn-sm admin-btn-primary">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection