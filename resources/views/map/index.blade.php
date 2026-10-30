@extends('layouts.frontend')

@section('title', __('Peta Lokasi Lengkap') . ' - ' . config('app.name'))
@section('meta_description', __('Lihat peta lokasi semua homestay dan kegiatan yang aktif di Desa Wisata Gabugan.'))

@push('style-alt')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 600px; width: 100%; border-radius: 1rem; border: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] mb-3">{{ __('Peta Lokasi Lengkap') }}</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">{{ __('Semua homestay dan kegiatan ditampilkan pada peta berikut.') }}</p>
            </div>
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Peta: desktop di kiri (order-1), mobile/tablet di bawah (order-2) -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    @include('partials._map-canvas', [
                        'markersJson' => json_encode($markers),
                        'centerLat' => -7.75,
                        'centerLng' => 110.23,
                        'zoom' => 13,
                    ])
                </div>

                <!-- Daftar: mobile/tablet di atas (order-1), desktop di kanan (order-2) -->
                <div class="lg:col-span-1 order-1 lg:order-2 sticky top-24 lg:sticky-top-24">
                    <div class="bg-[#f8fdfb] border border-neutral-100 p-6 rounded-[2rem]">

                        <!-- Legend -->
                        <div onclick="toggleSection(this)" class="collapse-header flex items-center justify-between cursor-pointer p-3 bg-[#053d2c]/5 rounded-xl hover:bg-[#053d2c]/10 transition border border-[#053d2c]/10 mb-0">
                            <h3 class="font-heading font-semibold text-[#053d2c] mb-0 flex items-center gap-2">
                                {{ __('Legenda') }}
                                <i class="bx bx-chevron-down text-xs transform transition-transform duration-200"></i>
                            </h3>
                        </div>
                        <div class="collapse-content hidden overflow-hidden pt-3 pb-3">
                            <div class="flex items-center gap-3 mb-4">
                                <div style="background:#00a877;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:18px;border:2px solid #fff;"><i class="bx bx-home-alt"></i></div>
                                <span class="text-sm font-medium text-gray-700">{{ __('Homestay') }}</span>
                            </div>
                            <div class="flex items-center gap-3 mb-4">
                                <div style="background:#e8a838;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:18px;border:2px solid #fff;"><i class="bx bx-run"></i></div>
                                <span class="text-sm font-medium text-gray-700">{{ __('Kegiatan / Aktivitas') }}</span>
                            </div>
                        </div>

                        <!-- Daftar Homestay -->
                        <div onclick="toggleSection(this)" class="collapse-header flex items-center justify-between cursor-pointer p-3 bg-[#053d2c]/5 rounded-xl hover:bg-[#053d2c]/10 transition border border-[#053d2c]/10 mt-6 border-t border-neutral-100 pt-6 lg:mt-0 lg:border-t-0 lg:pt-0">
                            <h3 class="font-heading font-semibold text-[#053d2c] mb-0 flex items-center gap-2">
                                {{ __('Daftar Homestay') }}
                                <i class="bx bx-chevron-down text-xs transform transition-transform duration-200"></i>
                            </h3>
                        </div>
                        <div class="collapse-content hidden overflow-hidden pt-3 pb-3">
                            <ul class="space-y-2">
                                @forelse($homestays as $h)
                                <li class="text-sm text-gray-700 cursor-pointer hover:bg-gray-100 p-2" onclick="flyToMarker({{ $h->latitude }}, {{ $h->longitude }})">
                                    {{ $h->name_id }}
                                    @if($h->latitude && $h->longitude)
                                        <span class="text-[10px] text-gray-400 ml-2" data-lat="{{ $h->latitude }}" data-lng="{{ $h->longitude }}">({{ number_format($h->latitude, 4) }}, {{ number_format($h->longitude, 4) }})</span>
                                    @endif
                                </li>
                                @empty
                                <li class="text-sm text-gray-500">{{ __('Tidak ada data.') }}</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Daftar Kegiatan -->
                        <div onclick="toggleSection(this)" class="collapse-header flex items-center justify-between cursor-pointer p-3 bg-[#053d2c]/5 rounded-xl hover:bg-[#053d2c]/10 transition border border-[#053d2c]/10 mt-6 border-t border-neutral-100 pt-6 lg:mt-0 lg:border-t-0 lg:pt-0">
                            <h3 class="font-heading font-semibold text-[#053d2c] mb-0 flex items-center gap-2">
                                {{ __('Daftar Kegiatan') }}
                                <i class="bx bx-chevron-down text-xs transform transition-transform duration-200"></i>
                            </h3>
                        </div>
                        <div class="collapse-content hidden overflow-hidden pt-3 pb-3">
                            <ul class="space-y-2">
                                @forelse($activities as $a)
                                <li class="text-sm text-gray-700 cursor-pointer hover:bg-gray-100 p-2" onclick="flyToMarker({{ $a->latitude }}, {{ $a->longitude }})">
                                    {{ $a->title_id }}
                                    @if($a->latitude && $a->longitude)
                                        <span class="text-[10px] text-gray-400 ml-2" data-lat="{{ $a->latitude }}" data-lng="{{ $a->longitude }}">({{ number_format($a->latitude, 4) }}, {{ number_format($a->longitude, 4) }})</span>
                                    @endif
                                </li>
                                @empty
                                <li class="text-sm text-gray-500">{{ __('Tidak ada data.') }}</li>
                                @endforelse
                            </ul>
                        </div>
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
                </script>
            </div>
        </div>
    </section>
@endsection

