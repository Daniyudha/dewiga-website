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
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">
        <img
            src="{{ asset('frontend/assets/img/package-top.jpg') }}"
            alt="@lang('messages.nav.packages')"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        <div class="container-custom relative z-10">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 text-white/70 text-sm mb-6">
                    <a href="{{ route('homepage') }}" class="hover:text-white">
                        @lang('messages.nav.home')
                    </a>
                    <i class="bx bx-chevron-right"></i>
                    <span>@lang('messages.nav.packages')</span>
                </div>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">
                    <i class="bx bx-map"></i>
                    @lang('messages.packages.hero_subtitle')
                </span>
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">
                    @lang('messages.nav.packages')
                </h1>
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl">
                    @lang('messages.packages.hero_desc')
                </p>
                <div class="flex flex-wrap gap-4 mt-8">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            {{ $travel_packages->total() }}+
                        </h4>
                        <span class="text-white/70 text-sm">
                            @lang('messages.nav.packages')
                        </span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            20+
                        </h4>
                        <span class="text-white/70 text-sm">
                            @lang('messages.packages.activities')
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- PACKAGES GRID --}}
    <section class="section-padding" id="popular">
        <div class="container-custom">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 mb-10">
                <div>
                    <span class="section-subtitle">
                        @lang('messages.packages.subtitle')
                    </span>
                    <h2 class="section-title mb-0">
                        @lang('messages.packages.title')
                    </h2>
                </div>
                <div class="text-sm text-primary-500">
                    @lang('messages.packages.showing', ['count' => $travel_packages->total()])
                </div>
            </div>

            @if ($travel_packages->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($travel_packages as $travel_package)
                        <article
                            class="group bg-white rounded-[28px] overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
                        >
                            <a href="{{ route('travel_package.show', $travel_package->slug) }}">
                                <div class="relative h-72 overflow-hidden">
                                    <img
                                        src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}"
                                        alt="{{ $travel_package->location }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                    <div class="absolute top-5 left-5">
                                        <span class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg">
                                            {{ $travel_package->type }}
                                        </span>
                                    </div>
                                    <div class="absolute bottom-5 left-5 right-5">
                                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-2">
                                            <div class="text-gray-300 text-xs font-medium">
                                                @lang('messages.popular.start_from')
                                            </div>
                                            <div class="text-xl font-bold text-white">
                                                {{ formatPrice($travel_package->price) }} /pax
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-primary-900 mb-3 group-hover:text-primary transition">
                                        {{ $travel_package->location }}
                                    </h3>
                                    <p class="text-sm text-gray-500 group-hover:text-primary-500 line-clamp-2 min-h-[44px] mt-2">
                                        {{
                                            Str::limit(
                                                strip_tags($travel_package->description),
                                                120
                                            )
                                        }}
                                    </p>
                                    <div class="mt-6 flex items-center justify-between">
                                        <span class="text-gray-500 group-hover:text-primary font-semibold">
                                            @lang('messages.popular.view_detail')
                                        </span>
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center group-hover:translate-x-1 transition"
                                        >
                                            <i class="bx bx-right-arrow-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($travel_packages->hasPages())
                <div class="mt-14 flex justify-center">
                    <nav class="pagination" role="navigation" aria-label="Pagination">
                        {{-- Previous --}}
                        @if ($travel_packages->onFirstPage())
                            <span class="pagination-link pagination-disabled" aria-disabled="true">
                                <i class="bx bx-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $travel_packages->previousPageUrl() }}" class="pagination-link" rel="prev">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($travel_packages->getUrlRange(1, $travel_packages->lastPage()) as $page => $url)
                            @if ($page == $travel_packages->currentPage())
                                <span class="pagination-link pagination-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($travel_packages->hasMorePages())
                            <a href="{{ $travel_packages->nextPageUrl() }}" class="pagination-link" rel="next">
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-link pagination-disabled" aria-disabled="true">
                                <i class="bx bx-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <p class="text-primary-500">@lang('messages.packages.empty')</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="py-24">
        <div class="container-custom">
            <div
                class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-primary to-primary-700 p-10 md:p-16 text-center"
            >
                <div class="absolute inset-0 bg-[url('/frontend/assets/img/pattern.png')] opacity-10"></div>
                <div class="relative z-10 max-w-3xl mx-auto">
                    <span class="inline-block bg-white/60 px-4 py-2 rounded-full text-sm mb-5">
                        @lang('messages.packages.cta_badge')
                    </span>
                    <h2 class="text-white text-3xl md:text-5xl font-bold leading-tight mb-5">
                        @lang('messages.packages.cta_hero_title')
                    </h2>
                    <p class="text-white/80 text-lg mb-8">
                        @lang('messages.packages.cta_hero_desc')
                    </p>
                    <a
                        href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                        target="_blank"
                        class="inline-flex items-center gap-3 bg-white text-primary font-semibold px-8 py-4 rounded-2xl hover:scale-105 hover:bg-white/80 hover:shadow-lg transition"
                    >
                        <i class="bx bxl-whatsapp text-xl"></i>
                        @lang('messages.whatsapp.cta')
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
