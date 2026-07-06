@extends('layouts.guest')

@section('title', 'Reset Password - Admin Dewiga')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-heading font-bold text-gray-800">{{ __('Reset Password') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Enter your email to receive a reset link') }}</p>
        </div>

        @if (session('status'))
            <div class="admin-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

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

            <button type="submit" class="admin-btn-primary w-full">
                <i class="fas fa-paper-plane"></i>
                {{ __('Send Password Reset Link') }}
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i>
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
@endsection
