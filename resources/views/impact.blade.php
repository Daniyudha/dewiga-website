@extends('layouts.frontend')

@section('title', __('messages.seo.home_title') . ' - Community Impact')
@section('meta_description', __('messages.impact.hero_desc'))

@section('content')

    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="Community Impact" class="w-full h-full object-cover">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="Community Impact" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('frontend/assets/img/community-top.jpg') }}" alt="Community Impact" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/70 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
            <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-5 mx-auto">
                <i class="bx bx-globe"></i>
                @lang('messages.impact.hero_subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.nav.impact')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.impact.hero_desc')
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-[#e8fbf3] fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-16 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-neutral-100 text-center hover:shadow-lg transition duration-300">
                    <h3 class="font-serif text-4xl font-bold text-[#00a877]">@lang('messages.impact.stats.families')</h3>
                    <p class="text-neutral-600 mt-2 text-sm font-medium">@lang('messages.impact.stats.families_label')</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-neutral-100 text-center hover:shadow-lg transition duration-300">
                    <h3 class="font-serif text-4xl font-bold text-[#00a877]">@lang('messages.impact.stats.income')</h3>
                    <p class="text-neutral-600 mt-2 text-sm font-medium">@lang('messages.impact.stats.income_label')</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-neutral-100 text-center hover:shadow-lg transition duration-300">
                    <h3 class="font-serif text-4xl font-bold text-[#00a877]">@lang('messages.impact.stats.traditions')</h3>
                    <p class="text-neutral-600 mt-2 text-sm font-medium">@lang('messages.impact.stats.traditions_label')</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-neutral-100 text-center hover:shadow-lg transition duration-300">
                    <h3 class="font-serif text-4xl font-bold text-[#00a877]">@lang('messages.impact.stats.visitors')</h3>
                    <p class="text-neutral-600 mt-2 text-sm font-medium">@lang('messages.impact.stats.visitors_label')</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 1: Economic Impact --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative order-2 lg:order-1">
                    <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[2rem] bg-[#E8A838]/20"></div>
                    <img data-src="{{ asset('frontend/assets/img/impact.jpg') }}" alt=""
                         class="lazy_img relative z-10 rounded-[2rem] shadow-2xl w-full aspect-[4/3] object-cover">
                </div>
                <div class="order-1 lg:order-2">
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.impact.section1_subtitle')</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] leading-tight mb-6">@lang('messages.impact.section1_title')</h2>
                    <p class="text-neutral-600 leading-relaxed">@lang('messages.impact.section1_desc')</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 2: Socio-Cultural Impact --}}
    <section class="py-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-6 -left-6 w-full h-full rounded-[2rem] bg-[#00a877]/10"></div>
                    <img data-src="{{ asset('frontend/assets/img/budaya.jpg') }}" alt=""
                         class="lazy_img relative z-10 rounded-[2rem] shadow-2xl w-full aspect-[4/3] object-cover">
                </div>
                <div>
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.impact.section2_subtitle')</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] leading-tight mb-6">@lang('messages.impact.section2_title')</h2>
                    <p class="text-neutral-600 leading-relaxed">@lang('messages.impact.section2_desc')</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Environmental Impact --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative order-2 lg:order-1">
                    <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[2rem] bg-[#E8A838]/20"></div>
                    <img data-src="{{ asset('frontend/assets/img/kelestarian.jpg') }}" alt=""
                         class="lazy_img relative z-10 rounded-[2rem] shadow-2xl w-full aspect-[4/3] object-cover">
                </div>
                <div class="order-1 lg:order-2">
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.impact.section3_subtitle')</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] leading-tight mb-6">@lang('messages.impact.section3_title')</h2>
                    <p class="text-neutral-600 leading-relaxed">@lang('messages.impact.section3_desc')</p>
                </div>
            </div>
        </div>


    {{-- CTA --}}
    <section class="pb-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-[#053d2c] to-[#043424] rounded-[2rem] p-12 md:p-16 text-center text-white">
                <h2 class="font-serif text-white text-3xl md:text-4xl font-bold mb-4">@lang('messages.impact.cta_title')</h2>
                <p class="text-neutral-300 max-w-2xl mx-auto mb-8 font-light">@lang('messages.impact.cta_desc')</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('travel_package.index') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300">
                        @lang('messages.popular.see_all')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-medium transition duration-300">
                        @lang('messages.nav.contact')
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection