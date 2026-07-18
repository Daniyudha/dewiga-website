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

    {{-- GLightbox CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

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
        <nav class="container mx-auto px-6 xl:px-0 flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a
                href="{{ route('homepage') }}"
                class="flex items-center gap-3 shrink-0"
            >
                {{-- Logo putih untuk hero (default) --}}
                <img
                    id="logo-white"
                    src="{{ asset('frontend/assets/img/brand-logo-white.png') }}"
                    alt="Desa Wisata Gabugan"
                    class="h-8 lg:h-10 w-auto block"
                >
                {{-- Logo gelap untuk scroll --}}
                <img
                    id="logo-dark"
                    src="{{ asset('frontend/assets/img/brand-logo.png') }}"
                    alt="Desa Wisata Gabugan"
                    class="h-8 lg:h-10 w-auto hidden"
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

                <a href="{{ route('schedule.index') }}"
                class="nav-link-desktop {{ request()->routeIs('schedule.*') ? 'active-nav' : '' }}">
                    @lang('messages.nav.schedules')
                </a>

                <a href="{{ route('blog.index') }}"
                class="nav-link-desktop {{ request()->routeIs('blog.*') ? 'active-nav' : '' }}">
                    @lang('messages.nav.blog')
                </a>

                <a href="{{ route('activities.index') }}"
                class="nav-link-desktop {{ request()->routeIs('activities.*') ? 'active-nav' : '' }}">
                    @lang('messages.nav.activities')
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
                    class="hidden xl:flex items-center gap-1 bg-[#00a877] hover:bg-[#008c6a] text-white px-3 py-1.5 rounded-xl text-sm font-medium transition"
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
        {{-- <div
            id="nav-overlay"
            class="fixed inset-0 bg-black/50 z-[998] is-hidden lg:hidden"
        ></div> --}}

        {{-- Mobile Menu --}}
        <aside
            id="nav-menu"
            class="fixed top-0 right-0 w-[300px] max-w-[85vw] h-screen bg-white z-[999] shadow-2xl translate-x-full transition-transform duration-300 lg:hidden"
        >

            {{-- Header Mobile --}}
            <div class="flex items-center justify-between px-6 h-16 border-b">

                <h3 class="font-semibold text-lg">
                    @lang('messages.footer.menu')
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
                        <a href="{{ route('schedule.index') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-calendar-event"></i>
                            @lang('messages.nav.schedules')
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('activities.index') }}"
                        class="mobile-nav-link">
                            <i class="bx bx-run"></i>
                            @lang('messages.nav.activities')
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

    {{-- SECTION 9: FOOTER --}}
    <footer class="bg-[#032419] text-neutral-300 pt-24 pb-8 border-t border-white/5">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
                
                <div class="md:col-span-3 lg:col-span-3 xl:col-span-3">
                    <div class="flex items-center gap-3 mb-6">
                        <a href="{{ route('homepage') }}" class="inline-block">
                            <img class="max-w-[130px] h-auto"
                                 src="{{ asset('frontend/assets/img/brand-logo-outline.png') }}"
                                 alt="Desa Wisata Gabugan">
                        </a>
                        <div>
                            <div class="block lg:hidden xl:block logo-text transition-all duration-300">
                                @if(App::isLocale('id'))
                                    <span class="text-md block">@lang('messages.logo.small')</span>
                                @endif
                                <h1 class="font-semibold text-2xl leading-none">GABUGAN</h1>
                                @if(App::isLocale('en'))
                                    <span class="text-md block">@lang('messages.logo.small')</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="text-neutral-400 text-sm leading-relaxed font-light">
                        Destinasi wisata budaya, edukasi, dan agrowisata Salak Pondoh premium terkemuka di lereng Gunung Merapi, Sleman, Yogyakarta. Menyuguhkan kearifan lokal yang tulus bagi setiap peziarah budaya.
                    </p>
                </div>

                <div class="md:col-span-3 lg:col-span-3 xl:col-span-3">
                    <h4 class="font-serif text-white font-semibold text-lg mb-6">@lang('messages.footer.quick_links')</h4>
                    <ul class="space-y-3.5 text-sm">
                        <li><a href="{{ route('homepage') }}" class="hover:text-[#00c887] transition">@lang('messages.footer.home')</a></li>
                        <li><a href="{{ route('about-us') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.about_us')</a></li>
                        <li><a href="{{ route('impact') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.impact')</a></li>
                        <li><a href="{{ route('homestay') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.homestay')</a></li>
                        <li><a href="{{ route('travel_package.index') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.packages')</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-[#00c887] transition">@lang('messages.footer.news_blog')</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.gallery')</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-[#00c887] transition">@lang('messages.nav.contact')</a></li>
                        <li><a href="{{ route('testimonials.create') }}" class="hover:text-[#00c887] transition">@lang('messages.testimonials.footer_link')</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 lg:col-span-3 xl:col-span-3">
                    <h4 class="font-serif text-white font-semibold text-lg mb-6">@lang('messages.footer.secretariat')</h4>
                    <ul class="space-y-4 text-sm font-light">
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Dusun Gabugan, Desa Donokerto, Kecamatan Turi, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55551</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>+62 813 2885 6252 (Sekretariat Desa)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>edpdewiga@gmail.com</span>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-3 lg:col-span-3 xl:col-span-3">
                    <h4 class="font-serif text-white font-semibold text-lg mb-6">@lang('messages.footer.operational_hours')</h4>
                    <div class="flex items-start gap-3 text-sm font-light mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <span class="font-semibold text-white block">@lang('messages.footer.every_day')</span>
                            <span>@lang('messages.footer.hours')</span>
                            <span class="text-[10px] text-[#00c887] block mt-1">@lang('messages.footer.homestay_24h')</span>
                        </div>
                    </div>

                    <h4 class="font-serif text-white font-semibold text-sm mb-4">@lang('messages.footer.follow_social')</h4>
                    <a href="https://instagram.com/desawisatagabugan" target="_blank" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-xl text-xs font-semibold transition mb-2">
                        <i class="bx bxl-instagram text-[#00c887]"></i>
                        @desawisatagabugan
                    </a>
                    <a href="https://facebook.com/desa.gabugan" target="_blank" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-xl text-xs font-semibold transition mb-2">
                        <i class="bx bxl-facebook-circle text-[#00c887]"></i>
                        @desa.gabugan
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-xl text-xs font-semibold transition mb-2">
                        <i class="bx bxl-whatsapp text-[#00c887]"></i>
                        @Jatmiko
                    </a>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-neutral-500">
                <p>@lang('messages.footer.copyright_full', ['year' => date('Y')])</p>
                <p class="flex items-center gap-1">
                    @lang('messages.footer.powered_by') <a href="https://www.gegacreative.com" target="_blank" class="text-[#00a7c8] hover:text-[#00a7c8]/80 transition">PT Gega Creative Ideas</a> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                </p>
            </div>
        </div>
    </footer>

    {{-- Scroll Up --}}
    <a href="#" class="fixed -bottom-[20%] right-8 w-10 h-10 bg-primary text-white rounded-lg shadow-card flex items-center justify-center text-xl z-[50] opacity-0 invisible transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover" id="scroll-up" aria-label="Scroll to top">
        <i class="bx bx-chevrons-up"></i>
    </a>

    {{-- AI Chat Assistant (3 partials: data, html, scripts) --}}
    @include('partials.ai-chat-data')
    @include('partials.ai-chat')
    @include('partials.ai-chat-scripts')

    {{-- Scripts --}}
    {{-- GLightbox --}}
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = GLightbox({
                selector: '.glightbox-item',
                touchNavigation: true,
                loop: true,
                zoomable: true,
                draggable: true,
                openEffect: 'fade',
                closeEffect: 'fade',
                slideEffect: 'slide',
                width: '90vw',
                height: '90vh',
                moreLength: 0,
                descPosition: 'bottom',
                slideAutoplay: false,
                svg: {
                    next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>',
                    prev: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>',
                }
            });
        });
    </script>

    <script src="{{ asset('frontend/assets/libraries/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libraries/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    {{-- Hero Slider Script --}}
    @include('partials.scripts-hero-rotator')

    {{-- Contact Form AJAX --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

        // ========================
        // Mobile Menu
        // ========================

        $('#nav-toggle').on('click', function() {
            $('#nav-menu').removeClass('translate-x-full');
            $('#nav-overlay').removeClass('is-hidden');
            $('body').addClass('overflow-hidden');
        });

        $('#nav-close, #nav-overlay').on('click', function() {
            $('#nav-menu').addClass('translate-x-full');
            $('#nav-overlay').addClass('is-hidden');
            $('body').removeClass('overflow-hidden');
        });

        const header = document.getElementById('header');
        const logoWhite = document.getElementById('logo-white');
        const logoDark = document.getElementById('logo-dark');

        let lastState = false;

        window.addEventListener('scroll', () => {

            const isScrolled = window.scrollY > 150;

            if (isScrolled !== lastState) {

                header.classList.toggle(
                    'scroll-header',
                    isScrolled
                );

                // Toggle logo
                if (isScrolled) {
                    logoWhite?.classList.add('hidden');
                    logoDark?.classList.remove('hidden');
                } else {
                    logoWhite?.classList.remove('hidden');
                    logoDark?.classList.add('hidden');
                }

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

