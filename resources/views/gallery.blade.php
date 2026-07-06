@extends('layouts.frontend')

@section('title', __('messages.seo.gallery_title'))
@section('meta_description', __('messages.seo.gallery_desc'))
@section('og_title', __('messages.seo.gallery_title'))
@section('og_description', __('messages.seo.gallery_desc'))

@section('content')
    {{-- HERO --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">

        <img
            src="{{ asset('frontend/assets/img/gallery-top.jpg') }}"
            alt="@lang('messages.nav.gallery')"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        <div class="container-custom relative z-10">

            <div class="max-w-3xl">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-white/70 text-sm mb-6">
                    <a href="{{ route('homepage') }}" class="hover:text-white transition">
                        @lang('messages.nav.home')
                    </a>

                    <i class="bx bx-chevron-right"></i>

                    <span>
                        @lang('messages.nav.gallery')
                    </span>
                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">
                    <i class="bx bx-camera"></i>
                    @lang('messages.gallery.hero_subtitle')
                </span>

                {{-- Title --}}
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">
                    @lang('messages.nav.gallery')
                </h1>

                {{-- Description --}}
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl">
                    @lang('messages.gallery.hero_desc')
                </p>

                {{-- Stats --}}
                {{-- <div class="flex flex-wrap gap-4 mt-8">

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            500+
                        </h4>
                        <span class="text-white/70 text-sm">
                            Dokumentasi Wisata
                        </span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            20+
                        </h4>
                        <span class="text-white/70 text-sm">
                            Aktivitas Wisata
                        </span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            2004
                        </h4>
                        <span class="text-white/70 text-sm">
                            Berdiri Sejak
                        </span>
                    </div>

                </div> --}}

            </div>

        </div>

    </section>

    {{-- INTRO --}}
    <section class="section-padding pb-0">

        <div class="container-custom text-center">

            <span class="section-subtitle">
                @lang('messages.gallery.subtitle')
            </span>

            <h2 class="section-title">
                @lang('messages.gallery.title')
            </h2>

            <p class="max-w-3xl mx-auto text-primary-600 leading-relaxed mt-4">
                @lang('messages.gallery.description')
            </p>

        </div>

    </section>

    {{-- GALLERY FEED --}}
    <section class="section-padding bg-primary-50/40">

        <div class="container-custom">

            <div class="bg-white rounded-[32px] shadow-xl overflow-hidden">

                {{-- Header Feed --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-8 border-b border-stone-100">

                    <div>

                        <h3 class="text-2xl font-bold text-primary-900">
                            @lang('messages.gallery.instagram_feed')
                        </h3>

                        <p class="text-primary-500 mt-1">
                            @lang('messages.gallery.instagram_desc')
                        </p>

                    </div>

                    <a
                        href="https://www.instagram.com/desawisatagabugan"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition"
                    >
                        <i class="bx bxl-instagram text-xl"></i>
                        @lang('messages.gallery.instagram_handle')
                    </a>

                </div>

                {{-- Instagram Feed --}}
                <div class="p-6 md:p-8">

                    <script
                        src="https://static.elfsight.com/platform/platform.js"
                        data-use-service-core
                        defer
                    ></script>

                    <div class="instagram-feed-wrapper">
    <div
        class="elfsight-app-c9115b0c-b45f-4b8b-a17e-d4e24a1a0c0d"
        data-elfsight-app-lazy
    ></div>
</div>

                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="pb-20">

        <div class="container-custom">

            <div class="bg-gradient-to-r from-primary to-primary-800 rounded-[32px] p-10 text-center text-white">

                <span class="text-secondary uppercase tracking-[2px] text-sm font-semibold">
                    @lang('messages.gallery.cta_badge')
                </span>

                <h2 class="text-3xl md:text-4xl font-bold mt-3 mb-4">
                    @lang('messages.gallery.cta_title')
                </h2>

                <p class="max-w-2xl mx-auto text-primary-100 mb-8">
                    @lang('messages.gallery.cta_desc')
                </p>

                <a
                    href="https://api.whatsapp.com/send?phone=6281328856252"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-secondary text-primary-900 px-8 py-4 rounded-full font-semibold hover:scale-105 transition"
                >
                    <i class="bx bxl-whatsapp text-xl"></i>
                    @lang('messages.gallery.cta_consult')
                </a>

            </div>

        </div>

    </section>

    <style>
        .eapps-instagram-feed-title {
            display: none !important;
        }
        .eapps-instagram-feed-posts-grid-load-more {
            background: linear-gradient(
                135deg,
                #14532d 0%,
                #1f7a44 100%
            ) !important;
            color: white !important;
            border-radius: 999px !important;
            font-weight: 700 !important;
            letter-spacing: .3px !important;
        }

        .eapps-instagram-feed-posts-grid-load-more:hover {
            transform: translateY(-4px) !important;
        }

        .instagram-feed-wrapper {
            position: relative;
            overflow: hidden;
        }

        .instagram-feed-wrapper::after {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            bottom: 0;
            height: 55px;
            background: white;
            z-index: 99999;
            pointer-events: none;
        }
    </style>
@endsection
