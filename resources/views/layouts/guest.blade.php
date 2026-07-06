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
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/brand-logo.png') }}" alt="Dewiga"
                         class="w-10 h-10 brightness-0 invert">
                    <span class="font-heading text-2xl font-bold text-white">DEWIGA</span>
                </a>
                <p class="text-primary-100 text-sm mt-1">Admin Panel</p>
            </div>

            {{-- Content --}}
            <div class="auth-card-body">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-100 px-6 py-4 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Desa Wisata Gabugan. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
    @yield('scripts')
</body>
</html>
