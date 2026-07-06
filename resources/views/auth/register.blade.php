@extends('layouts.guest')

@section('title', 'Register - Admin Dewiga')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-heading font-bold text-gray-800">{{ __('Create Account') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Register a new admin account') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div class="admin-form-group">
                <label for="name" class="admin-form-label">{{ __('Name') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" id="name" name="name"
                           class="admin-form-input pl-10 @error('name') error @enderror"
                           placeholder="Your name" required autocomplete="name" autofocus>
                </div>
                @error('name')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="admin-form-group">
                <label for="email" class="admin-form-label">{{ __('Email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email"
                           class="admin-form-input pl-10 @error('email') error @enderror"
                           placeholder="your@email.com" required autocomplete="email">
                </div>
                @error('email')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="admin-form-group">
                <label for="password" class="admin-form-label">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password"
                           class="admin-form-input pl-10 @error('password') error @enderror"
                           placeholder="Create a password" required autocomplete="new-password">
                </div>
                @error('password')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="admin-form-group">
                <label for="password_confirmation" class="admin-form-label">{{ __('Confirm Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="admin-form-input pl-10"
                           placeholder="Confirm your password" required autocomplete="new-password">
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="admin-btn-primary w-full">
                <i class="fas fa-user-plus"></i>
                {{ __('Register') }}
            </button>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-500">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 hover:underline font-medium">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </div>
@endsection
