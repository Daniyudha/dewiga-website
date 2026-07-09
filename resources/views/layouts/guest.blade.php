<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Desa Wisata Gabugan - Admin Login')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/assets/favicon/site.webmanifest') }}">

    {{-- Google Fonts (same as frontend) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

    @vite('resources/css/admin.css')

    @yield('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            {{-- Header with brand --}}
            <div class="auth-card-header">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2">
                    <img src="{{ asset('frontend/assets/img/brand-logo-white.png') }}" alt="Dewiga"
                         class="w-14 h-auto">
                    <div>
                        <span class="font-heading text-2xl font-bold text-white">DEWIGA</span>
                        <p class="text-[#00c887] text-xs mt-0.5 font-medium tracking-wide">Admin Panel</p>
                    </div>
                </a>
            </div>

            {{-- Content --}}
            <div class="auth-card-body">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-100 px-6 py-4 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} <a href="{{ url('/') }}" class="text-primary-600 hover:text-primary-700">Desa Wisata Gabugan</a>. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    {{-- Back to site link --}}
    <div class="fixed bottom-4 left-4 z-10">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white text-xs rounded-lg transition border border-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Website
        </a>
    </div>

    @vite('resources/js/app.js')
    @yield('scripts')
</body>
</html>