@extends('layouts.frontend')

@section('title', __('Peta Event') . ': ' . $schedule->event_name . ' - ' . config('app.name'))
@section('meta_description', __('Peta lokasi homestay dan kegiatan untuk jadwal :event.', ['event' => $schedule->event_name]))

@push('style-alt')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 600px; width: 100%; border-radius: 1rem; border: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="mb-12">
                <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-4">
                    <i class="fas fa-calendar-alt"></i>
                    {{ __('Event Sedang Berjalan') }}
                </div>
                <h1 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] mb-3">{{ $schedule->event_name }}</h1>
                <p class="text-neutral-600 max-w-2xl">
                    {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}
                    - {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                    <span class="text-[#00a877] font-semibold mx-2">|</span>
                    {{ $schedule->quota }} {{ __('orang') }}
                </p>
            </div>
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Peta: desktop di kiri (order-1), mobile/tablet di bawah (order-2) -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="bg-[#f8fdfb] border border-neutral-100 p-6 rounded-[2rem] mb-6">
                        <h3 class="font-heading font-semibold text-[#053d2c] mb-3">{{ __('Peta Event') }}</h3>
                        <p class="text-sm text-neutral-600 mb-4">
                            {{ __('Peta ini hanya menampilkan homestay dan kegiatan yang diaktifkan untuk event ini.') }}
                        </p>
                        @include('partials._map-canvas', [
                            'markersJson' => json_encode($markers),
                            'centerLat' => -7.75,
                            'centerLng' => 110.23,
                            'zoom' => 13,
                        ])
                    </div>
                </div>

                <!-- Daftar: mobile/tablet di atas (order-1), desktop di kanan (order-2) -->
                <div class="lg:col-span-1 order-1 lg:order-2 sticky top-24 lg:sticky-top-24">

                    <!-- Homestay untuk Event Ini -->
                    <div onclick="toggleSection(this)" class="collapse-header flex items-center justify-between cursor-pointer p-3 bg-[#053d2c]/5 rounded-xl hover:bg-[#053d2c]/10 transition border border-[#053d2c]/10 mb-0">
                        <h3 class="font-heading font-semibold text-[#053d2c] mb-0 flex items-center gap-2">
                            {{ __('Homestay untuk Event Ini') }}
                            <i class="bx bx-chevron-down text-xs transform transition-transform duration-200"></i>
                        </h3>
                    </div>
                    <div class="collapse-content hidden overflow-hidden pt-3 pb-3 bg-[#f8fdfb] border border-neutral-100 p-6 rounded-[2rem]">
                        <p class="text-sm text-neutral-600 mb-4">{{ __('Homestay berikut ditetapkan dan ditinggali oleh kelompok:') }}</p>
                        <input type="text" class="map-list-search" placeholder="Cari homestay..." onkeyup="filterList(this, 'homestay-list-show')">
                        <div id="homestay-empty-show" class="search-empty text-center py-4">{{ __('Tidak ditemukan.') }}</div>
                        <ul id="homestay-list-show" class="space-y-3">
                            @php $__locale = app()->getLocale(); @endphp
                            @forelse($homestays as $h)
                            <li class="p-3 border border-neutral-100 rounded-xl bg-white cursor-pointer" onclick="flyToMarker({{ $h->latitude }}, {{ $h->longitude }})" data-name="{{ $h->name_id }}">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-medium text-gray-800">{{ $h->name_id }}</span>
                                    <span class="text-[10px] bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-semibold">{{ __('Homestay') }}</span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2" data-lat="{{ $h->latitude }}" data-lng="{{ $h->longitude }}">{{ $h->address_id }}</div>
                                @php
                                    $__groupName = $__locale === 'en'
                                        ? ($h->pivot->group_name_en ?? $h->pivot->group_name_id)
                                        : ($h->pivot->group_name_id);
                                @endphp
                                @if($__groupName)
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-users text-[#00a877] text-xs"></i>
                                    <span class="text-sm font-medium text-[#053d2c]">{{ __('Occupied by') }}: <strong>{{ $__groupName }}</strong></span>
                                </div>
                                @endif
                                @if($h->latitude && $h->longitude)
                                <div class="text-[10px] text-gray-400" data-lat="{{ $h->latitude }}" data-lng="{{ $h->longitude }}">{{ number_format($h->latitude, 4) }}, {{ number_format($h->longitude, 4) }}</div>
                                @endif
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 text-center py-2">{{ __('Belum ada homestay yang ditetapkan.') }}</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Kegiatan yang Muncul -->
                    <div onclick="toggleSection(this)" class="collapse-header flex items-center justify-between cursor-pointer p-3 bg-[#053d2c]/5 rounded-xl hover:bg-[#053d2c]/10 transition border border-[#053d2c]/10 mt-6 border-t border-neutral-100 pt-6 lg:mt-0 lg:border-t-0 lg:pt-0">
                        <h3 class="font-heading font-semibold text-[#053d2c] mb-0 flex items-center gap-2">
                            {{ __('Kegiatan yang Muncul') }}
                            <i class="bx bx-chevron-down text-xs transform transition-transform duration-200"></i>
                        </h3>
                    </div>
                    <div class="collapse-content hidden overflow-hidden pt-3 pb-3 bg-[#f8fdfb] border border-neutral-100 p-6 rounded-[2rem]">
                        <p class="text-sm text-neutral-600 mb-4">{{ __('Hanya kegiatan berikut yang ditampilkan untuk event ini:') }}</p>
                        <input type="text" class="map-list-search" placeholder="Cari kegiatan..." onkeyup="filterList(this, 'kegiatan-list-show')">
                        <div id="kegiatan-empty-show" class="search-empty text-center py-4">{{ __('Tidak ditemukan.') }}</div>
                        <ul id="kegiatan-list-show" class="space-y-2">
                            @forelse($activities as $a)
                            <li class="flex items-start gap-3 p-3 border border-neutral-100 rounded-xl bg-white cursor-pointer" onclick="flyToMarker({{ $a->latitude }}, {{ $a->longitude }})" data-name="{{ $a->title_id }}">
                                <div class="bg-emerald-100 text-emerald-700 rounded-lg p-2 shrink-0"><i class="fas fa-running text-sm"></i></div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm" data-lat="{{ $a->latitude }}" data-lng="{{ $a->longitude }}">{{ $a->title_id }}</div>
                                    <div class="text-xs text-gray-500">{{ $a->duration_id }}</div>
                                </div>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 text-center py-2">{{ __('Belum ada kegiatan yang ditetapkan.') }}</li>
                            @endforelse
                        </ul>
                    </div>

                </div>

                <script>
                function toggleSection(header) {
                    const content = header.nextElementSibling;
                    const icon = header.querySelector('.bx-chevron-down');
                    const isHidden = content.classList.contains('hidden');
                    content.classList.toggle('hidden');
                    if (icon) {
                        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                }

                function filterList(input, listId) {
                    const search = input.value.toLowerCase();
                    const list = document.getElementById(listId);
                    const empty = document.getElementById(listId.replace('-list-', '-empty-'));
                    const items = list.getElementsByTagName('li');
                    let visible = 0;

                    for (let item of items) {
                        if (item.classList.contains('text-gray-500')) continue;
                        const name = item.getAttribute('data-name') || item.textContent;
                        if (name.toLowerCase().includes(search)) {
                            item.style.display = '';
                            visible++;
                        } else {
                            item.style.display = 'none';
                        }
                    }

                    if (empty) {
                        empty.style.display = visible === 0 ? 'block' : 'none';
                    }
                }
                </script>
            </div>
        </div>
    </section>
@endsection

