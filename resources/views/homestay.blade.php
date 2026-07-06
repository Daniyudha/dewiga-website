@extends('layouts.frontend')

@section('title', __('messages.seo.home_title') . ' - Homestay')
@section('meta_description', __('messages.homestay.hero_desc'))

@section('content')

    {{-- HERO --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">

        <img
            src="{{ asset('frontend/assets/img/homestay-top.jpg') }}"
            alt="Homestay"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        <div class="container-custom relative z-10">

            <div class="max-w-4xl">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-white/70 text-sm mb-6">

                    <a
                        href="{{ route('homepage') }}"
                        class="hover:text-white transition"
                    >
                        @lang('messages.nav.home')
                    </a>

                    <i class="bx bx-chevron-right"></i>

                    <span>
                        @lang('messages.nav.homestay')
                    </span>

                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">

                    <i class="bx bx-home-heart"></i>

                    @lang('messages.homestay.hero_subtitle')

                </span>

                {{-- Title --}}
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">
                    @lang('messages.nav.homestay')
                </h1>

                {{-- Description --}}
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl leading-relaxed">
                    @lang('messages.homestay.hero_desc')
                </p>

            </div>

        </div>

    </section>

    {{-- SECTION 1: About Homestay --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="relative">

                    <div class="absolute -top-6 -left-6 w-full h-full rounded-[40px] bg-primary/10"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/homestay-1.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

                <div>

                    <span class="section-subtitle">
                        @lang('messages.homestay.section1_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.homestay.section1_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.homestay.section1_desc')
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- SECTION 2: Facilities --}}
    <section class="section-padding bg-primary-50/40">

        <div class="container-custom">

            <div class="text-center mb-16">

                <span class="section-subtitle">
                    @lang('messages.homestay.section2_subtitle')
                </span>

                <h2 class="section-title">
                    @lang('messages.homestay.section2_title')
                </h2>

                <p class="max-w-2xl mx-auto text-primary-600">
                    @lang('messages.homestay.section2_desc')
                </p>

            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="value-card text-center">
                    <div class="value-card-icon mx-auto">
                        <i class="bx bx-bed"></i>
                    </div>
                    <h3 class="value-card-title">
                        @lang('messages.homestay.facilities.bedroom')
                    </h3>
                    <p class="value-card-desc">
                        @lang('messages.homestay.facilities.bedroom_desc')
                    </p>
                </div>

                <div class="value-card text-center">
                    <div class="value-card-icon secondary mx-auto">
                        <i class="bx bx-restaurant"></i>
                    </div>
                    <h3 class="value-card-title">
                        @lang('messages.homestay.facilities.food')
                    </h3>
                    <p class="value-card-desc">
                        @lang('messages.homestay.facilities.food_desc')
                    </p>
                </div>

                <div class="value-card text-center">
                    <div class="value-card-icon mx-auto">
                        <i class="bx bx-bath"></i>
                    </div>
                    <h3 class="value-card-title">
                        @lang('messages.homestay.facilities.bathroom')
                    </h3>
                    <p class="value-card-desc">
                        @lang('messages.homestay.facilities.bathroom_desc')
                    </p>
                </div>

                <div class="value-card text-center">
                    <div class="value-card-icon secondary mx-auto">
                        <i class="bx bx-user-voice"></i>
                    </div>
                    <h3 class="value-card-title">
                        @lang('messages.homestay.facilities.guide')
                    </h3>
                    <p class="value-card-desc">
                        @lang('messages.homestay.facilities.guide_desc')
                    </p>
                </div>

            </div>

        </div>

    </section>

    {{-- SECTION 3: Activities --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="text-center mb-16">

                <span class="section-subtitle">
                    @lang('messages.homestay.section3_subtitle')
                </span>

                <h2 class="section-title">
                    @lang('messages.homestay.section3_title')
                </h2>

            </div>

            <div class="grid md:grid-cols-2 gap-8">

                {{-- Activity 1 --}}
                <div class="flex items-start gap-5 bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">

                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-2xl shrink-0">
                        <i class="bx bx-leaf"></i>
                    </div>

                    <div>
                        <h3 class="font-semibold text-primary-900 text-lg mb-1">
                            @lang('messages.homestay.activities.farming')
                        </h3>
                        <p class="text-primary-500 text-sm leading-relaxed">
                            @lang('messages.homestay.activities.farming_desc')
                        </p>
                    </div>

                </div>

                {{-- Activity 2 --}}
                <div class="flex items-start gap-5 bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">

                    <div class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary text-2xl shrink-0">
                        <i class="bx bx-dish"></i>
                    </div>

                    <div>
                        <h3 class="font-semibold text-primary-900 text-lg mb-1">
                            @lang('messages.homestay.activities.cooking')
                        </h3>
                        <p class="text-primary-500 text-sm leading-relaxed">
                            @lang('messages.homestay.activities.cooking_desc')
                        </p>
                    </div>

                </div>

                {{-- Activity 3 --}}
                <div class="flex items-start gap-5 bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">

                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-2xl shrink-0">
                        <i class="bx bx-paint"></i>
                    </div>

                    <div>
                        <h3 class="font-semibold text-primary-900 text-lg mb-1">
                            @lang('messages.homestay.activities.craft')
                        </h3>
                        <p class="text-primary-500 text-sm leading-relaxed">
                            @lang('messages.homestay.activities.craft_desc')
                        </p>
                    </div>

                </div>

                {{-- Activity 4 --}}
                <div class="flex items-start gap-5 bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">

                    <div class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary text-2xl shrink-0">
                        <i class="bx bx-music"></i>
                    </div>

                    <div>
                        <h3 class="font-semibold text-primary-900 text-lg mb-1">
                            @lang('messages.homestay.activities.evening')
                        </h3>
                        <p class="text-primary-500 text-sm leading-relaxed">
                            @lang('messages.homestay.activities.evening_desc')
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="pb-24">

        <div class="container-custom">

            <div class="bg-gradient-to-r from-primary to-primary-800 rounded-[40px] p-12 text-center text-white">

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    @lang('messages.homestay.cta_title')
                </h2>

                <p class="text-white/80 max-w-2xl mx-auto mb-8">
                    @lang('messages.homestay.cta_desc')
                </p>

                <div class="flex flex-wrap justify-center gap-4">

                    <a
                        href="{{ route('travel_package.index') }}"
                        class="button button-gold"
                    >
                        @lang('messages.packages.book_now')
                    </a>

                    <a
                        href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                        target="_blank"
                        class="px-6 py-3 rounded-xl border border-white/30 hover:bg-white/10 transition"
                    >
                        <i class="bx bxl-whatsapp mr-2"></i>
                        @lang('messages.whatsapp.cta')
                    </a>

                </div>

            </div>

        </div>

    </section>

@endsection
