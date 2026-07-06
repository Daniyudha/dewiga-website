@extends('layouts.frontend')

@section('title', __('messages.seo.home_title') . ' - Community Impact')
@section('meta_description', __('messages.impact.hero_desc'))

@section('content')

    {{-- HERO --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">

        <img
            src="{{ asset('frontend/assets/img/community-top.jpg') }}"
            alt="Community Impact"
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
                        @lang('messages.nav.impact')
                    </span>

                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">

                    <i class="bx bx-globe"></i>

                    @lang('messages.impact.hero_subtitle')

                </span>

                {{-- Title --}}
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">
                    @lang('messages.nav.impact')
                </h1>

                {{-- Description --}}
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl leading-relaxed">
                    @lang('messages.impact.hero_desc')
                </p>

            </div>

        </div>

    </section>

    {{-- STATS --}}
    <section class="relative -mt-16 z-20">
        <div class="container-custom">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white rounded-3xl p-6 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">
                        @lang('messages.impact.stats.families')
                    </h3>
                    <p class="text-primary-500 mt-2 text-sm">
                        @lang('messages.impact.stats.families_label')
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">
                        @lang('messages.impact.stats.income')
                    </h3>
                    <p class="text-primary-500 mt-2 text-sm">
                        @lang('messages.impact.stats.income_label')
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">
                        @lang('messages.impact.stats.traditions')
                    </h3>
                    <p class="text-primary-500 mt-2 text-sm">
                        @lang('messages.impact.stats.traditions_label')
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">
                        @lang('messages.impact.stats.visitors')
                    </h3>
                    <p class="text-primary-500 mt-2 text-sm">
                        @lang('messages.impact.stats.visitors_label')
                    </p>
                </div>

            </div>

        </div>
    </section>

    {{-- SECTION 1: Economic Impact --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="relative order-2 lg:order-1">

                    <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[40px] bg-secondary/20"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/impact.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

                <div class="order-1 lg:order-2">

                    <span class="section-subtitle">
                        @lang('messages.impact.section1_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.impact.section1_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.impact.section1_desc')
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- SECTION 2: Socio-Cultural Impact --}}
    <section class="section-padding bg-primary-50/40">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="relative">

                    <div class="absolute -top-6 -left-6 w-full h-full rounded-[40px] bg-primary/10"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/budaya.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

                <div>

                    <span class="section-subtitle">
                        @lang('messages.impact.section2_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.impact.section2_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.impact.section2_desc')
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- SECTION 3: Environmental Impact --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="relative order-2 lg:order-1">

                    <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[40px] bg-secondary/20"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/kelestarian.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

                <div class="order-1 lg:order-2">

                    <span class="section-subtitle">
                        @lang('messages.impact.section3_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.impact.section3_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.impact.section3_desc')
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="pb-24">

        <div class="container-custom">

            <div class="bg-gradient-to-r from-primary to-primary-800 rounded-[40px] p-12 text-center text-white">

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    @lang('messages.impact.cta_title')
                </h2>

                <p class="text-white/80 max-w-2xl mx-auto mb-8">
                    @lang('messages.impact.cta_desc')
                </p>

                <div class="flex flex-wrap justify-center gap-4">

                    <a
                        href="{{ route('travel_package.index') }}"
                        class="button button-gold"
                    >
                        @lang('messages.popular.see_all')
                    </a>

                    <a
                        href="{{ route('contact') }}"
                        class="px-6 py-3 rounded-xl border border-white/30 hover:bg-white/10 transition"
                    >
                        @lang('messages.nav.contact')
                    </a>

                </div>

            </div>

        </div>

    </section>

@endsection
