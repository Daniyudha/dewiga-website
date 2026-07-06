@extends('layouts.app')

@section('title', 'Dashboard - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Dashboard') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    {{-- Welcome Card --}}
    <div class="admin-card">
        <div class="admin-card-body text-center py-12">
            <div class="w-20 h-20 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tachometer-alt text-3xl"></i>
            </div>
            <h2 class="text-xl font-heading font-semibold text-gray-800 mb-2">
                {{ __('Welcome to Dashboard') }}
            </h2>
            <p class="text-gray-500 max-w-md mx-auto">
                {{ __('You are logged in as') }} <strong class="text-primary-600">{{ Auth::user()->name }}</strong>.
                {{ __('Use the sidebar to manage your content.') }}
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="admin-btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    {{ __('View Site') }}
                </a>
                <a href="{{ route('admin.profile.show') }}" class="admin-btn-primary">
                    <i class="fas fa-user"></i>
                    {{ __('My Profile') }}
                </a>
            </div>
        </div>
    </div>
@endsection
