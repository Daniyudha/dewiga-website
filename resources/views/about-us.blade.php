@extends('layouts.frontend')

@section('title', __('messages.seo.about_title'))
@section('meta_description', __('messages.seo.about_desc'))
@section('og_title', __('messages.seo.about_title'))
@section('og_description', __('messages.seo.about_desc'))

@section('content')

    {{-- HERO --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">

        <img
            src="{{ asset('frontend/assets/img/about-top.jpg') }}"
            alt="@lang('messages.nav.about')"
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
                        @lang('messages.nav.about')
                    </span>

                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">

                    <i class="bx bx-landscape"></i>

                    @lang('messages.about.hero_subtitle')

                </span>

                {{-- Title --}}
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">

                    @lang('messages.nav.about')

                </h1>

                {{-- Description --}}
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl leading-relaxed">

                    @lang('messages.about.hero_desc')

                </p>

                {{-- Stats --}}
                <div class="flex flex-wrap gap-4 mt-8 lg:hidden">

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">

                        <h4 class="text-white text-xl font-bold">
                            2004
                        </h4>

                        <span class="text-white/70 text-sm">
                            @lang('messages.about.founded_since')
                        </span>

                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">

                        <h4 class="text-white text-xl font-bold">
                            20+
                        </h4>

                        <span class="text-white/70 text-sm">
                            @lang('messages.about.years_exp')
                        </span>

                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">

                        <h4 class="text-white text-xl font-bold">
                            Mandiri
                        </h4>

                        <span class="text-white/70 text-sm">
                            @lang('messages.about.tourism_village')
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- STATS --}}
    <section class="relative -mt-16 z-20 lg:block hidden">
        <div class="container-custom">

            <div class="grid md:grid-cols-3 gap-6">

                <div class="bg-white rounded-3xl p-8 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">20+</h3>
                    <p class="text-primary-500 mt-2">
                        @lang('messages.about.years_exp')
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">2004</h3>
                    <p class="text-primary-500 mt-2">
                        @lang('messages.about.founded_since')
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-xl text-center">
                    <h3 class="text-3xl font-bold text-primary">Mandiri</h3>
                    <p class="text-primary-500 mt-2">
                        @lang('messages.about.category_village')
                    </p>
                </div>

            </div>

        </div>
    </section>

    {{-- SECTION 1 --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="relative">

                    <div class="absolute -top-6 -left-6 w-full h-full rounded-[40px] bg-primary/10"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/about-1.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

                <div>

                    <span class="section-subtitle">
                        @lang('messages.about.section1_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.about.section1_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.about.section1_desc')
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- SECTION 2 --}}
    <section class="section-padding bg-primary-50/40">

        <div class="container-custom">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="order-2 lg:order-1">

                    <span class="section-subtitle">
                        @lang('messages.about.section2_subtitle')
                    </span>

                    <h2 class="section-title">
                        @lang('messages.about.section2_title')
                    </h2>

                    <p class="text-primary-600 leading-loose mt-6">
                        @lang('messages.about.section2_desc')
                    </p>

                </div>

                <div class="relative order-1 lg:order-2">

                    <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[40px] bg-secondary/20"></div>

                    <img
                        data-src="{{ asset('frontend/assets/img/about-2.jpg') }}"
                        alt=""
                        class="lazy_img relative z-10 rounded-[32px] shadow-2xl w-full aspect-[4/3] object-cover"
                    >

                </div>

            </div>

        </div>

    </section>

    {{-- FACILITIES --}}
    <section class="section-padding">

        <div class="container-custom">

            <div class="text-center mb-16">

                <span class="section-subtitle">
                    @lang('messages.about.section3_subtitle')
                </span>

                <h2 class="section-title">
                    @lang('messages.about.section3_title')
                </h2>

                <p class="max-w-2xl mx-auto text-primary-600">
                    @lang('messages.about.section3_desc')
                </p>

            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl p-8 shadow-lg text-center">
                    <i class="bx bx-home text-5xl text-primary mb-4"></i>
                    <h3 class="font-semibold text-primary-900">
                        @lang('messages.about.facilities.homestay')
                    </h3>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-lg text-center">
                    <i class="bx bx-restaurant text-5xl text-primary mb-4"></i>
                    <h3 class="font-semibold text-primary-900">
                        @lang('messages.about.facilities.local_cuisine')
                    </h3>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-lg text-center">
                    <i class="bx bx-bus text-5xl text-primary mb-4"></i>
                    <h3 class="font-semibold text-primary-900">
                        @lang('messages.about.facilities.parking_area')
                    </h3>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-lg text-center">
                    <i class="bx bx-map text-5xl text-primary mb-4"></i>
                    <h3 class="font-semibold text-primary-900">
                        @lang('messages.about.facilities.tour_package')
                    </h3>
                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="pb-24">

        <div class="container-custom">

            <div class="bg-gradient-to-r from-primary to-primary-800 rounded-[40px] p-12 text-center text-white">

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    @lang('messages.about.cta_title')
                </h2>

                <p class="text-white/80 max-w-2xl mx-auto mb-8">
                    @lang('messages.about.cta_desc')
                </p>

                <div class="flex flex-wrap justify-center gap-4">

                    <a
                        href="{{ route('travel_package.index') }}"
                        class="button button-gold"
                    >
                        @lang('messages.about.cta_packages')
                    </a>

                    <a
                        href="{{ route('contact') }}"
                        class="px-6 py-3 rounded-xl border border-white/30 hover:bg-white/10 transition"
                    >
                        @lang('messages.about.cta_contact')
                    </a>

                </div>

            </div>

        </div>

    </section>

@endsection