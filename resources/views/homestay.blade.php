@extends('layouts.frontend')

@section('title', __('messages.seo.home_title') . ' - Homestay')
@section('meta_description', __('messages.homestay.hero_desc'))

@section('content')

    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="Homestay" class="w-full h-full object-cover">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="Homestay" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('frontend/assets/img/homestay-top.jpg') }}" alt="Homestay" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/70 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
            <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-5 mx-auto">
                <i class="bx bx-home-heart"></i>
                @lang('messages.homestay.hero_subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.nav.homestay')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.homestay.hero_desc')
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-[#e8fbf3] fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- SECTION 1: About Homestay --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-6 -left-6 w-full h-full rounded-[2rem] bg-[#00a877]/10"></div>
                    <img data-src="{{ asset('frontend/assets/img/homestay-1.jpg') }}" alt=""
                         class="lazy_img relative z-10 rounded-[2rem] shadow-2xl w-full aspect-[4/3] object-cover">
                </div>
                <div>
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.homestay.section1_subtitle')</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] leading-tight mb-6">@lang('messages.homestay.section1_title')</h2>
                    <p class="text-neutral-600 leading-relaxed">@lang('messages.homestay.section1_desc')</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 2: Facilities --}}
    <section class="py-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.homestay.section2_subtitle')</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] mb-4">@lang('messages.homestay.section2_title')</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">@lang('messages.homestay.section2_desc')</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white border border-neutral-100 rounded-3xl p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-[#00a877]/10 text-[#00a877] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="bx bx-bed"></i></div>
                    <h3 class="font-semibold text-[#053d2c] mb-2">@lang('messages.homestay.facilities.bedroom')</h3>
                    <p class="text-neutral-500 text-sm">@lang('messages.homestay.facilities.bedroom_desc')</p>
                </div>
                <div class="bg-white border border-neutral-100 rounded-3xl p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-[#E8A838]/10 text-[#E8A838] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="bx bx-restaurant"></i></div>
                    <h3 class="font-semibold text-[#053d2c] mb-2">@lang('messages.homestay.facilities.food')</h3>
                    <p class="text-neutral-500 text-sm">@lang('messages.homestay.facilities.food_desc')</p>
                </div>
                <div class="bg-white border border-neutral-100 rounded-3xl p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-[#00a877]/10 text-[#00a877] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="bx bx-bath"></i></div>
                    <h3 class="font-semibold text-[#053d2c] mb-2">@lang('messages.homestay.facilities.bathroom')</h3>
                    <p class="text-neutral-500 text-sm">@lang('messages.homestay.facilities.bathroom_desc')</p>
                </div>
                <div class="bg-white border border-neutral-100 rounded-3xl p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-[#E8A838]/10 text-[#E8A838] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="bx bx-user-voice"></i></div>
                    <h3 class="font-semibold text-[#053d2c] mb-2">@lang('messages.homestay.facilities.guide')</h3>
                    <p class="text-neutral-500 text-sm">@lang('messages.homestay.facilities.guide_desc')</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Activities --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.homestay.section3_subtitle')</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">@lang('messages.homestay.section3_title')</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex items-start gap-5 bg-[#f8fdfb] border border-neutral-100 rounded-2xl p-6 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-[#00a877]/10 flex items-center justify-center text-[#00a877] text-2xl shrink-0"><i class="bx bx-leaf"></i></div>
                    <div>
                        <h3 class="font-semibold text-[#053d2c] text-lg mb-1">@lang('messages.homestay.activities.farming')</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">@lang('messages.homestay.activities.farming_desc')</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 bg-[#f8fdfb] border border-neutral-100 rounded-2xl p-6 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-[#E8A838]/10 flex items-center justify-center text-[#E8A838] text-2xl shrink-0"><i class="bx bx-dish"></i></div>
                    <div>
                        <h3 class="font-semibold text-[#053d2c] text-lg mb-1">@lang('messages.homestay.activities.cooking')</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">@lang('messages.homestay.activities.cooking_desc')</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 bg-[#f8fdfb] border border-neutral-100 rounded-2xl p-6 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-[#00a877]/10 flex items-center justify-center text-[#00a877] text-2xl shrink-0"><i class="bx bx-paint"></i></div>
                    <div>
                        <h3 class="font-semibold text-[#053d2c] text-lg mb-1">@lang('messages.homestay.activities.craft')</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">@lang('messages.homestay.activities.craft_desc')</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 bg-[#f8fdfb] border border-neutral-100 rounded-2xl p-6 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-[#E8A838]/10 flex items-center justify-center text-[#E8A838] text-2xl shrink-0"><i class="bx bx-music"></i></div>
                    <div>
                        <h3 class="font-semibold text-[#053d2c] text-lg mb-1">@lang('messages.homestay.activities.evening')</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">@lang('messages.homestay.activities.evening_desc')</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pb-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-[#053d2c] to-[#043424] rounded-[2rem] p-12 md:p-16 text-center text-white">
                <h2 class="font-serif text-white text-3xl md:text-4xl font-bold mb-4">@lang('messages.homestay.cta_title')</h2>
                <p class="text-neutral-300 max-w-2xl mx-auto mb-8 font-light">@lang('messages.homestay.cta_desc')</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('travel_package.index') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300">
                        @lang('messages.packages.book_now')
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank"
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-medium transition duration-300">
                        <i class="bx bxl-whatsapp"></i>
                        @lang('messages.whatsapp.cta')
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection