@extends('layouts.guest')

@section('title', 'Reset Password - Admin Dewiga')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-heading font-bold text-gray-800">{{ __('Reset Password') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Choose a new password') }}</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="admin-form-group">
                <label for="email" class="admin-form-label">{{ __('Email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email"
                           class="admin-form-input pl-10 @error('email') error @enderror"
                           placeholder="your@email.com" required autofocus>
                </div>
                @error('email')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="password" class="admin-form-label">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password"
                           class="admin-form-input pl-10 @error('password') error @enderror"
                           placeholder="New password" required>
                </div>
                @error('password')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="password_confirmation" class="admin-form-label">{{ __('Confirm Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="admin-form-input pl-10"
                           placeholder="Confirm password" required>
                </div>
            </div>

            <button type="submit" class="admin-btn-primary w-full">
                <i class="fas fa-sync-alt"></i>
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
@endsection
