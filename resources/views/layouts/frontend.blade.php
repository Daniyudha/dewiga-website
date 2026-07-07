<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic Meta Tags --}}
    <title>@yield('title', __('messages.seo.home_title'))</title>
    <meta name="description" content="@yield('meta_description', __('messages.seo.home_desc'))" />
    <meta name="keywords" content="@yield('meta_keywords', 'desa wisata gabugan, sleman, jogja, rural tourism, wisata edukasi, live in desa, Yogyakarta')" />
    <link rel="canonical" href="@yield('canonical_url', url()->current())" />

    {{-- Open Graph --}}
    <meta property="og:locale" content="{{ App::getLocale() === 'id' ? 'id_ID' : 'en_US' }}" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:title" content="@yield('og_title', __('messages.seo.home_title'))" />
    <meta property="og:description" content="@yield('og_description', __('messages.seo.home_desc'))" />
    <meta property="og:url" content="@yield('og_url', url()->current())" />
    <meta property="og:site_name" content="Desa Wisata Gabugan" />
    <meta property="og:image" content="@yield('og_image', asset('frontend/assets/img/hero1.jpg'))" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('twitter_title', __('messages.seo.home_title'))" />
    <meta name="twitter:description" content="@yield('twitter_description', __('messages.seo.home_desc'))" />
    <meta name="twitter:image" content="@yield('twitter_image', asset('frontend/assets/img/hero1.jpg'))" />

    {{-- hreflang alternates --}}
    @if (App::getLocale() !== 'id')
        <link rel="alternate" hreflang="id" href="{{ LaravelLocalization::getLocalizedURL('id', null, [], true) }}" />
    @endif
    @if (App::getLocale() !== 'en')
        <link rel="alternate" hreflang="en" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" />
    @endif
    <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getNonLocalizedURL() }}" />

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/assets/favicon/site.webmanifest') }}">

    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/libraries/swiper-bundle.min.css') }}" />

    {{-- Tailwind Frontend CSS (via Vite) --}}
    @vite('resources/css/frontend.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">

    @stack('style-alt')

    {{-- JSON-LD Structured Data (Schema.org TourismBusiness) --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TouristAttraction",
        "name": "Desa Wisata Gabugan",
        "description": "{{ __('messages.seo.home_desc') }}",
        "url": "{{ url('/') }}",
        "telephone": "+6281328856252",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Gabugan, Sumberagung, Moyudan",
            "addressLocality": "Sleman",
            "addressRegion": "Yogyakarta",
            "addressCountry": "ID"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -7.8681,
            "longitude": 110.2473
        },
        "sameAs": [
            "https://web.facebook.com/desa.gabugan",
            "https://www.instagram.com/desawisatagabugan/"
        ]
    }
    </script>
    @yield('schema')
</head>

<body class="{{ App::getLocale() === 'en' ? '' : '' }}">

    {{-- HEADER --}}
    <header
        id="header"
        class="absolute top-0 left-0 w-full z-50 transition-all duration-500"
    >
        <nav class="container-custom flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a
                href="{{ route('homepage') }}"
                class="flex items-center gap-3 shrink-0"
            >
                <img
                    src="{{ asset('frontend/assets/img/brand-logo.png') }}"
                    alt="Desa Wisata Gabugan"
                    class="w-10 lg:w-20 h-auto"
                >

                <div class="hidden sm:block logo-text transition-all duration-300">

                    @if(App::isLocale('id'))
                        <span class="text-xs block">
                            @lang('messages.logo.small')
                        </span>
                    @endif

                    <h1 class="font-semibold text-lg leading-none">
                        GABUGAN
                    </h1>

                    @if(App::isLocale('en'))
                        <span class="text-xs block">
                            @lang('messages.logo.small')
                        </span>
                    @endif

                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-1">

                <a href="{{ route('homepage') }}"
                class="nav-link-desktop {{ request()->routeIs('homepage') ? 'active-nav' : '' }}">
                    @lang('messages.nav.home')
                </a>

                {{-- About Dropdown --}}
                <div class="nav-dropdown relative">
                    <button
                        class="nav-link-desktop nav-dropdown-trigger flex items-center gap-1 {{ request()->routeIs('about-us') || request()->routeIs('homestay') || request()->routeIs('impact') ? 'active-nav' : '' }}">
                        @lang('messages.nav.about')
                        <i class="bx bx-chevron-down text-sm transition-transform duration-300"></i>
                    </button>
                    <div class="nav-dropdown-menu absolute top-full left-0 mt-1 bg-white rounded-2xl shadow-xl border border-stone-100 py-2 w-[230px] opacity-0 invisible translate-y-2 transition-all duration-300">
                        <a href="{{ route('about-us') }}"
                        class="block px-5 py-3 text-sm text-primary-700 hover:text-primary hover:bg-primary/15 transition rounded-lg mx-2 {{ request()->routeIs('about-us') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                            <i class="bx bx-info-circle mr-2"></i>
                            @lang('messages.nav.about_us')
                        </a>
                        <a href="{{ route('homestay') }}"
                        class="block px-5 py-3 text-sm text-primary-700 hover:text-primary hover:bg-primary/15 transition rounded-lg mx-2 {{ request()->routeIs('homestay') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                            <i class="bx bx-home-heart mr-2"></i>
                            @lang('messages.nav.homestay')
                        </a>
                        <a href="{{ route('impact') }}"
                        class="block px-5 py-3 text-sm text-primary-700 hover:text-primary hover:bg-primary/15 transition rounded-lg mx-2 {{ request()->routeIs('impact') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                            <i class="bx bx-globe mr-2"></i>
                            @lang('messages.nav.impact')
                        </a>
                    </div>
                </div>

                <a href="{{ route('travel_package.index') }}"
                class="nav-link-desktop {{ request()->routeIs('travel_package.*') ? 'active-nav' : '' }}">
                    @lang('messages.nav.packages')
                </a>

                <a href="{{ route('blog.index') }}"
                class="nav-link-desktop {{ request()->routeIs('blog.*') ? 'active-nav' : '' }}">
                    @lang('messages.nav.blog')
                </a>

                <a href="{{ route('gallery') }}"
                class="nav-link-desktop {{ request()->routeIs('gallery') ? 'active-nav' : '' }}">
                    @lang('messages.nav.gallery')
                </a>

                <a href="{{ route('contact') }}"
                class="nav-link-desktop {{ request()->routeIs('contact') ? 'active-nav' : '' }}">
                    @lang('messages.nav.contact')
                </a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-3">

                {{-- Desktop Language --}}
                <div class="hidden lg:flex items-center gap-1 lang-switcher">

                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)

                        @if ($localeCode === App::getLocale())

                            <span class="lang-switcher__active">
                                {{ strtoupper($localeCode) }}
                            </span>

                        @else

                            <a
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                class="lang-switcher__link"
                            >
                                {{ strtoupper($localeCode) }}
                            </a>

                        @endif

                        @if (!$loop->last)
                            <span class="lang-switcher__separator">|</span>
                        @endif

                    @endforeach

                </div>

                {{-- Theme --}}
                {{-- <button
                    class="text-white text-xl hover:text-primary transition"
                >
                    <i class="bx bx-moon" id="theme-button"></i>
                </button> --}}

                {{-- WhatsApp Desktop --}}
                <a
                    href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hidden xl:flex items-center gap-2 bg-primary hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition"
                >
                    <i class="bx bxl-whatsapp text-lg"></i>
                    <span>@lang('messages.whatsapp.cta')</span>
                </a>

                {{-- Mobile Hamburger --}}
                <button
                    id="nav-toggle"
                    class="lg:hidden text-white text-3xl"
                >
                    <i class="bx bx-menu"></i>
                </button>

            </div>

        </nav>

        {{-- Mobile Overlay --}}
        <div
            id="nav-overlay"
            class="fixed inset-0 bg-black/50 z-[998] hidden lg:hidden"
        ></div>

        {{-- Mobile Menu --}}
        <aside
            id="nav-menu"
            class="fixed top-0 right-0 w-[300px] max-w-[85vw] h-screen bg-white z-[999] shadow-2xl translate-x-full transition-transform duration-300 lg:hidden"
        >

            {{-- Header Mobile --}}
            <div class="flex items-center justify-between px-6 h-16 border-b">

                <h3 class="font-semibold text-lg">
                    Menu
                </h3>

                <button
                    id="nav-close"
                    class="text-3xl"
                >
                    <i class="bx bx-x"></i>
                </button>

            </div>

            {{-- Mobile Links --}}
            <div class="p-6">

                <ul class="space-y-2">

                    <li>
                        <a href="{{ route('homepage') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-home"></i>
                            @lang('messages.nav.home')
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('travel_package.index') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-map"></i>
                            @lang('messages.nav.packages')
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('blog.index') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-book"></i>
                            @lang('messages.nav.blog')
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('gallery') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-image"></i>
                            @lang('messages.nav.gallery')
                        </a>
                    </li>

                    <li>
                        <div class="mobile-nav-accordion">
                            <button
                                class="mobile-accordion-trigger flex items-center justify-between w-full px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-100 transition"
                                data-target="about-mobile-submenu"
                            >
                                <span class="flex items-center gap-3">
                                    <i class="bx bx-info-circle"></i>
                                    @lang('messages.nav.about')
                                </span>
                                <i class="bx bx-chevron-down text-lg transition-transform duration-300 mobile-accordion-arrow"></i>
                            </button>
                            <ul id="about-mobile-submenu" class="mobile-accordion-content ml-4 mt-1 space-y-1 border-l-2 border-primary/20 pl-3" style="{{ request()->routeIs('about-us') || request()->routeIs('homestay') || request()->routeIs('impact') ? '' : 'display: none;' }}">
                                <li>
                                    <a href="{{ route('about-us') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 transition text-sm {{ request()->routeIs('about-us') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                                        <i class="bx bx-info-circle text-primary/60"></i>
                                        @lang('messages.nav.about_us')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('homestay') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 transition text-sm {{ request()->routeIs('homestay') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                                        <i class="bx bx-home-heart text-primary/60"></i>
                                        @lang('messages.nav.homestay')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('impact') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 transition text-sm {{ request()->routeIs('impact') ? 'text-primary font-medium bg-primary-50/60' : '' }}">
                                        <i class="bx bx-globe text-primary/60"></i>
                                        @lang('messages.nav.impact')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('contact') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-phone"></i>
                            @lang('messages.nav.contact')
                        </a>
                    </li>

                </ul>

                {{-- WhatsApp --}}
                <a
                    href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                    target="_blank"
                    class="flex items-center justify-center gap-2 mt-6 bg-green-500 text-white py-3 rounded-xl font-medium"
                >
                    <i class="bx bxl-whatsapp"></i>
                    @lang('messages.whatsapp.cta')
                </a>

                {{-- Language --}}
                <div class="flex justify-center mt-8 pt-6 border-t border-slate-200">

                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100">

                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)

                            @if ($localeCode === App::getLocale())

                                <span class="font-semibold text-primary">
                                    {{ strtoupper($localeCode) }}
                                </span>

                            @else

                                <a
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    class="text-slate-500 hover:text-primary transition-colors duration-300"
                                >
                                    {{ strtoupper($localeCode) }}
                                </a>

                            @endif

                            @if (!$loop->last)
                                <span class="text-slate-300">|</span>
                            @endif

                        @endforeach

                    </div>

                </div>

            </div>

        </aside>
    </header>

    {{-- MAIN --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- Notification (AJAX responses) --}}
    <div id="notification-message" class="fixed top-28 left-1/2 -translate-x-1/2 z-[9999] bg-primary text-white px-6 py-3.5 rounded-xl shadow-2xl max-w-md w-[90%] text-center text-sm font-medium animate-slideDown hidden"></div>

    {{-- Global Session Message --}}
    @if (session()->has('message') || session()->has('success') || session()->has('error'))
        <div id="session-message"
             class="fixed top-28 left-1/2 -translate-x-1/2 z-[9999] max-w-md w-[90%] text-center text-sm font-medium rounded-xl shadow-2xl animate-slideDown px-6 py-3.5 {{ session()->has('error') ? 'bg-red-600' : 'bg-primary' }} text-white">
            <i class="bx bx-check-circle mr-2"></i>
            {{ session()->get('message') ?? session()->get('success') ?? session()->get('error') }}
            <i class="bx bx-x absolute top-2 right-3 cursor-pointer text-lg session-message__close"></i>
        </div>
    @endif

    {{-- FOOTER --}}
    <footer class="relative overflow-hidden bg-gradient-to-br from-primary-900 via-primary-900 to-primary-800 text-primary-300 pt-16 pb-8">
        <div class="absolute inset-0 opacity-[0.08]">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-secondary rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-secondary rounded-full blur-3xl"></div>
        </div>
        <div class="container-custom relative z-10">
            {{-- Main footer grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                {{-- Column 1: Logo + Description --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ route('homepage') }}" class="inline-block mb-4">
                        <img class="max-w-[130px] h-auto"
                             src="{{ asset('frontend/assets/img/brand-logo-outline.png') }}"
                             alt="Desa Wisata Gabugan">
                    </a>
                    <p class="text-sm leading-relaxed text-primary-300/80">
                        @lang('messages.footer.description')
                    </p>
                </div>

                {{-- Column 2: About --}}
                <div>
                    <h3 class="font-heading text-stone-100 font-semibold text-lg mb-4">@lang('messages.footer.about')</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about-us') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.about_us')</a></li>
                        <li><a href="{{ route('homestay') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.homestay')</a></li>
                        <li><a href="{{ route('impact') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.impact')</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.gallery')</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.footer.news_blog')</a></li>
                        <li><a href="{{ route('testimonials.create') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.testimonials.footer_link')</a></li>
                    </ul>
                </div>

                {{-- Column 3: Company --}}
                <div>
                    <h3 class="font-heading text-stone-100 font-semibold text-lg mb-4">@lang('messages.footer.company')</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('travel_package.index') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.footer.packages')</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.contact')</a></li>
                    </ul>
                </div>

                {{-- Column 4: Support + Social --}}
                <div>
                    <h3 class="font-heading text-stone-100 font-semibold text-lg mb-4">@lang('messages.footer.support')</h3>
                    <ul class="space-y-3 mb-6">
                        <li><a href="{{ route('contact') }}" class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.nav.contact')</a></li>
                        <li><a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="text-sm text-primary-300/70 hover:text-secondary transition-colors">@lang('messages.footer.whatsapp')</a></li>
                    </ul>
                    <h3 class="font-heading text-stone-100 font-semibold text-lg mb-4">@lang('messages.footer.follow_us')</h3>
                    <div class="flex items-center gap-3">
                        <a href="https://web.facebook.com/desa.gabugan" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center w-9 h-9 bg-primary-800 text-primary-300 rounded-full hover:bg-secondary hover:text-primary-900 transition-all text-lg">
                            <i class="bx bxl-facebook-circle"></i>
                        </a>
                        <a href="https://www.instagram.com/desawisatagabugan/" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center w-9 h-9 bg-primary-800 text-primary-300 rounded-full hover:bg-secondary hover:text-primary-900 transition-all text-lg">
                            <i class="bx bxl-instagram-alt"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center w-9 h-9 bg-primary-800 text-primary-300 rounded-full hover:bg-secondary hover:text-primary-900 transition-all text-lg">
                            <i class="bx bxl-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer bottom bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-12 pt-6 border-t border-primary-800 text-xs text-primary-400">
                <span>&copy; {{ date('Y') }} @lang('messages.footer.copyright')</span>
                <span>
                    Powered by
                    <a href="https://www.gegacreative.com/" target="_blank" rel="noopener noreferrer"
                   class="text-blue-400 hover:text-blue-500 transition-colors font-medium">
                    Gega Creative
                </a></span>
            </div>
        </div>
    </footer>

    {{-- Scroll Up --}}
    <a href="#" class="fixed -bottom-[20%] right-8 w-10 h-10 bg-primary text-white rounded-lg shadow-card flex items-center justify-center text-xl z-[50] opacity-0 invisible transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover" id="scroll-up" aria-label="Scroll to top">
        <i class="bx bx-chevrons-up"></i>
    </a>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
    <script>
        baguetteBox.run('.tz-gallery');
    </script>

    <script src="{{ asset('frontend/assets/libraries/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libraries/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    {{-- Contact Form AJAX --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

        // ========================
        // Mobile Menu
        // ========================

        $('#nav-toggle').on('click', function() {
            $('#nav-menu').removeClass('translate-x-full');
            $('#nav-overlay').removeClass('hidden');
            $('body').addClass('overflow-hidden');
        });

        $('#nav-close, #nav-overlay').on('click', function() {
            $('#nav-menu').addClass('translate-x-full');
            $('#nav-overlay').addClass('hidden');
            $('body').removeClass('overflow-hidden');
        });

        const header = document.getElementById('header');

        let lastState = false;

        window.addEventListener('scroll', () => {

            const isScrolled = window.scrollY > 150;

            if (isScrolled !== lastState) {

                header.classList.toggle(
                    'scroll-header',
                    isScrolled
                );

                lastState = isScrolled;
            }

        });

        // ========================
        // Mobile Accordion (About dropdown)
        // ========================

        $('.mobile-accordion-trigger').on('click', function() {
            const targetId = $(this).data('target');
            const $content = $('#' + targetId);
            const $arrow = $(this).find('.mobile-accordion-arrow');

            $content.slideToggle(300, function() {
                $(this).toggleClass('active');
            });

            $(this).toggleClass('active');
        });

        // ========================
        // Contact Form AJAX
        // ========================

        if ($('#myForm').length > 0) {

            $('#myForm').submit(function (e) {
                e.preventDefault();

                $('#submitBtn').prop('disabled', true);

                $('#submitBtn').html(
                    '<span class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2"></span> Sending...'
                );

                $.ajax({
                    type: "POST",
                    url: "{{ route('send.email') }}",
                    data: $('#myForm').serialize(),

                    success: function(response) {

                        $('#myForm')[0].reset();

                        $('#notification-message')
                            .removeClass('hidden')
                            .addClass('!block')
                            .text(response.message)
                            .fadeIn();

                        setTimeout(function() {
                            $('#notification-message').fadeOut();
                        }, 3000);
                    },

                    error: function() {

                        $('#notification-message')
                            .removeClass('hidden')
                            .addClass('!block')
                            .text('{{ __("messages.contact.send_email_error") }}')
                            .fadeIn();

                        setTimeout(function() {
                            $('#notification-message').fadeOut();
                        }, 3000);
                    },

                    complete: function() {

                        $('#submitBtn').prop('disabled', false);

                        $('#submitBtn').html(
                            '{{ __("messages.contact.form_submit") }}'
                        );
                    }
                });
            });
        }

        // ========================
        // Session Message
        // ========================

        const $sessionMsg = $('#session-message');

        if ($sessionMsg.length) {

            $sessionMsg.find('.session-message__close').on('click', function() {
                $sessionMsg.fadeOut(300);
            });

            setTimeout(function() {
                $sessionMsg.fadeOut(500);
            }, 5000);
        }

    });
    </script>

    @stack('script-alt')
</body>
</html>
