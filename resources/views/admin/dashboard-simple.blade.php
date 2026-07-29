@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Dashboard') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Welcome back, :name!', ['name' => Auth::user()->name]) }}</p>
    </div>
</div>

{{-- Welcome Card --}}
<div class="admin-card mb-6">
    <div class="admin-card-body text-center py-10">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center text-primary-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>
        <h2 class="text-xl font-heading font-semibold text-gray-900 mb-2">Selamat datang, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-4">
            Anda memiliki akses terbatas. Silakan gunakan menu di sidebar untuk mengelola konten sesuai dengan role Anda.
        </p>
        @if(Auth::user()->roles->count() > 0)
        <div class="flex flex-wrap justify-center gap-2">
            @foreach(Auth::user()->roles as $role)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ $role->name }}
            </span>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Menu Cepat --}}
@if(Auth::user()->roles->count() > 0)
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="font-heading font-semibold text-gray-800">Menu Cepat</h3>
    </div>
    <div class="admin-card-body">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @if(Auth::user()->hasPermission('blogs.manage'))
            <a href="{{ route('admin.blogs.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-green-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 12.75h7.5m-7.5 3h7.5M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Kelola Blog</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('schedules.view'))
            <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-green-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Lihat Jadwal</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('bookings.view'))
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-blue-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Lihat Booking</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('transactions.view'))
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-yellow-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 0 4.5 6h.75m14.25 0h.75a.75.75 0 0 0 .75-.75v-.75M9.75 18.75h4.5M12 6.75v6m0 0-3-3m3 3 3-3" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Data Keuangan</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('packages.view'))
            <a href="{{ route('admin.travel_packages.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-indigo-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Paket Wisata</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('hero.manage'))
            <a href="{{ route('admin.hero-settings.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-pink-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Hero Settings</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('testimonials.manage'))
            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Testimoni</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('galleries.manage'))
            <a href="{{ route('admin.site-galleries.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-purple-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Zm16.5-13.5h.008v.008h-.008V7.5Zm.008 2.25h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Galeri</span>
            </a>
            @endif
            @if(Auth::user()->hasPermission('guests.view'))
            <a href="{{ route('admin.guests.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center justify-center text-cyan-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Database Tamu</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endif
@endsection