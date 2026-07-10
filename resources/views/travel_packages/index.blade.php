@extends('layouts.frontend')

@section('title', __('messages.seo.packages_title'))
@section('meta_description', __('messages.seo.packages_desc'))
@section('og_title', __('messages.seo.packages_title'))
@section('og_description', __('messages.seo.packages_desc'))
@section('twitter_title', __('messages.seo.packages_title'))
@section('twitter_description', __('messages.seo.packages_desc'))
@section('og_image', asset('frontend/assets/img/package-top.jpg'))
@section('twitter_image', asset('frontend/assets/img/package-top.jpg'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[65vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="@lang('messages.nav.packages')" class="w-full h-full object-cover opacity-30">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="@lang('messages.nav.packages')" class="w-full h-full object-cover opacity-30">
            @else
                <img src="{{ asset('frontend/assets/img/package-top.jpg') }}" alt="@lang('messages.nav.packages')" class="w-full h-full object-cover opacity-30">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white">
            <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-5 mx-auto">
                <i class="bx bx-map"></i>
                @lang('messages.packages.hero_subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.nav.packages')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.packages.hero_desc')
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-5 rounded-2xl">
                    <h4 class="text-white text-2xl font-bold">{{ $travel_packages->total() }}+</h4>
                    <span class="text-neutral-300 text-sm">@lang('messages.nav.packages')</span>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-5 rounded-2xl">
                    <h4 class="text-white text-2xl font-bold">20+</h4>
                    <span class="text-neutral-300 text-sm">@lang('messages.packages.activities')</span>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- PACKAGES GRID --}}
    <section class="py-24 bg-[#f8fdfb]" id="popular">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 mb-10">
                <div>
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.packages.subtitle')</span>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#053d2c]">@lang('messages.packages.title')</h2>
                </div>
                <div class="text-sm text-neutral-500">@lang('messages.packages.showing', ['count' => $travel_packages->total()])</div>
            </div>

            @if ($travel_packages->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($travel_packages as $travel_package)
                        <article class="group bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                            <a href="{{ route('travel_package.show', $travel_package->slug) }}">
                                <div class="relative h-72 overflow-hidden">
                                    <img src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}" alt="{{ $travel_package->location }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                    <div class="absolute top-5 left-5">
                                        <span class="bg-[#00a877] text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg">{{ $travel_package->type }}</span>
                                    </div>
                                    <div class="absolute bottom-5 left-5 right-5">
                                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-2">
                                            <div class="text-neutral-300 text-xs font-medium">@lang('messages.popular.start_from')</div>
                                            <div class="text-xl font-bold text-white">{{ formatPrice($travel_package->price) }} /pax</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-[#053d2c] mb-3 group-hover:text-[#00a877] transition">{{ $travel_package->location }}</h3>
                                    <p class="text-sm text-neutral-500 group-hover:text-neutral-600 line-clamp-2 min-h-[44px] mt-2">{{ Str::limit(strip_tags($travel_package->description), 120) }}</p>
                                    <div class="mt-6 flex items-center justify-between">
                                        <span class="text-[#00a877] font-semibold">@lang('messages.popular.view_detail')</span>
                                        <div class="w-10 h-10 rounded-full bg-[#00a877] text-white flex items-center justify-center group-hover:translate-x-1 transition"><i class="bx bx-right-arrow-alt"></i></div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if ($travel_packages->hasPages())
                <div class="mt-14 flex justify-center">
                    <nav class="flex items-center gap-2" role="navigation">
                        @if ($travel_packages->onFirstPage())
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-neutral-300 border border-neutral-200 cursor-not-allowed"><i class="bx bx-chevron-left"></i></span>
                        @else
                            <a href="{{ $travel_packages->previousPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition"><i class="bx bx-chevron-left"></i></a>
                        @endif
                        @foreach ($travel_packages->getUrlRange(1, $travel_packages->lastPage()) as $page => $url)
                            @if ($page == $travel_packages->currentPage())
                                <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-[#00a877] text-white border border-[#00a877]">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($travel_packages->hasMorePages())
                            <a href="{{ $travel_packages->nextPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition"><i class="bx bx-chevron-right"></i></a>
                        @else
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-neutral-300 border border-neutral-200 cursor-not-allowed"><i class="bx bx-chevron-right"></i></span>
                        @endif
                    </nav>
                </div>
                @endif
            @else
                <div class="text-center py-12"><p class="text-neutral-500">@lang('messages.packages.empty')</p></div>
            @endif
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#053d2c] to-[#043424] p-12 md:p-16 text-center">
                <div class="absolute inset-0 bg-[url('/frontend/assets/img/pattern.png')] opacity-10"></div>
                <div class="relative z-10 max-w-3xl mx-auto">
                    <span class="inline-block bg-[#00a877]/20 text-[#00c887] border border-[#00a877]/30 px-4 py-2 rounded-full text-xs font-semibold tracking-wider uppercase mb-5">
                        @lang('messages.packages.cta_badge')
                    </span>
                    <h2 class="font-serif text-white text-3xl md:text-5xl font-bold leading-tight mb-5">@lang('messages.packages.cta_hero_title')</h2>
                    <p class="text-neutral-300 text-lg mb-8 font-light">@lang('messages.packages.cta_hero_desc')</p>
                    <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank"
                       class="inline-flex items-center gap-3 bg-[#00a877] hover:bg-[#009065] text-white font-semibold px-8 py-4 rounded-full hover:shadow-lg transition duration-300">
                        <i class="bx bxl-whatsapp text-xl"></i>
                        @lang('messages.whatsapp.cta')
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection