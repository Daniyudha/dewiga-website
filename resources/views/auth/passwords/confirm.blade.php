@extends('layouts.guest')

@section('title', 'Confirm Password - Admin Dewiga')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-heading font-bold text-gray-800">{{ __('Confirm Password') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Please confirm your password before continuing.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div class="admin-form-group">
                <label for="password" class="admin-form-label">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password"
                           class="admin-form-input pl-10 @error('password') error @enderror"
                           placeholder="Enter your password" required autocomplete="current-password">
                </div>
                @error('password')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="admin-btn-primary w-full">
                <i class="fas fa-check-circle"></i>
                {{ __('Confirm Password') }}
            </button>
        </form>

        @if (Route::has('password.request'))
            <div class="text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 hover:underline">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    </div>
@endsection
