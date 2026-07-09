@extends('layouts.frontend')

@section('title', $travel_package->title ?? $travel_package->type . ' - ' . __('messages.seo.packages_title'))
@section('meta_description', strip_tags($travel_package->description))
@section('og_title', $travel_package->title ?? $travel_package->type)
@section('og_description', strip_tags($travel_package->description))
@section('twitter_title', $travel_package->title ?? $travel_package->type)
@section('twitter_description', strip_tags($travel_package->description))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-end pt-24">
        <div class="absolute inset-0 z-0">
            @php
                $heroImg = $travel_package->galleries->count() > 0 && $travel_package->galleries->first()->images
                    ? asset('storage/' . $travel_package->galleries->first()->images)
                    : asset('frontend/assets/img/package-top.jpg');
            @endphp
            <img src="{{ $heroImg }}" alt="{{ $travel_package->type }}" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/40 to-black/30 z-10"></div>
        </div>
        <div class="relative z-10 container mx-auto px-6 pb-16">
            <div class="max-w-4xl">
                <span class="inline-flex items-center gap-2 bg-[#00a877] text-white px-5 py-2 rounded-full text-sm font-semibold mb-6">{{ $travel_package->type }}</span>
                <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-white">{{ $travel_package->title ?? $travel_package->type }}</h1>
                <p class="text-neutral-200 text-base md:text-lg font-medium max-w-2xl leading-relaxed font-light">{{ $travel_package->location }}</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b border-neutral-100">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <a href="{{ route('homepage') }}" class="hover:text-[#00a877]">@lang('messages.nav.home')</a>
                <i class="bx bx-chevron-right"></i>
                <a href="{{ route('travel_package.index') }}" class="hover:text-[#00a877]">@lang('messages.nav.packages')</a>
                <i class="bx bx-chevron-right"></i>
                <span class="text-[#053d2c] font-medium truncate">{{ $travel_package->title ?? $travel_package->type }}</span>
            </div>
        </div>
    </section>

    {{-- DETAIL --}}
    <section class="py-24 bg-[#f8fdfb]">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-[1fr_380px] gap-12">
                {{-- LEFT: Content --}}
                <div>
                    <div class="bg-white rounded-[2rem] shadow-xl p-8 md:p-10">
                        <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.popular.subtitle')</span>
                        <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#053d2c] mb-6">{{ $travel_package->title ?? $travel_package->type }}</h2>
                        <div class="prose max-w-none text-neutral-600 leading-relaxed">
                            {!! $travel_package->description !!}
                        </div>
                    </div>

                    {{-- Gallery --}}
                    @if($travel_package->galleries->count() > 1)
                    <div class="mt-8">
                        <h3 class="font-serif text-2xl font-bold text-[#053d2c] mb-6">Galeri</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($travel_package->galleries as $gallery)
                                <div class="aspect-[4/3] rounded-2xl overflow-hidden">
                                    <img src="{{ Storage::url($gallery->image) }}" alt="" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- RIGHT: Sidebar --}}
                <div class="space-y-6 lg:sticky lg:top-24 self-start">
                    <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-neutral-100">
                        <span class="text-[10px] uppercase text-neutral-400 block tracking-wider mb-1">@lang('messages.popular.start_from')</span>
                        <p class="font-serif text-4xl font-bold text-[#00a877] mb-6">{{ formatPrice($travel_package->price) }} <span class="text-sm text-neutral-500 font-sans font-normal">/orang</span></p>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-10 h-10 rounded-xl bg-[#e8fbf3] flex items-center justify-center text-[#00a877]"><i class="bx bx-map"></i></div>
                                <div>
                                    <p class="text-neutral-500 text-xs">Lokasi</p>
                                    <p class="font-semibold text-[#053d2c]">{{ $travel_package->location }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-10 h-10 rounded-xl bg-[#e8fbf3] flex items-center justify-center text-[#00a877]"><i class="bx bx-tag"></i></div>
                                <div>
                                    <p class="text-neutral-500 text-xs">Tipe</p>
                                    <p class="font-semibold text-[#053d2c]">{{ $travel_package->type }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank"
                           class="w-full inline-flex items-center justify-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300">
                            <i class="bx bxl-whatsapp text-xl"></i>
                            @lang('messages.whatsapp.cta')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pb-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-[#053d2c] to-[#043424] rounded-[2rem] p-12 md:p-16 text-center text-white">
                <h2 class="font-serif text-white text-3xl md:text-4xl font-bold mb-4">Tertarik dengan paket ini?</h2>
                <p class="text-neutral-300 max-w-2xl mx-auto mb-8 font-light">Hubungi kami sekarang untuk konsultasi dan pemesanan paket wisata.</p>
                <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank"
                   class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300">
                    <i class="bx bxl-whatsapp text-xl"></i>
                    @lang('messages.whatsapp.cta')
                </a>
            </div>
        </div>
    </section>
@endsection