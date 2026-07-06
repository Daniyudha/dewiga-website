@extends('layouts.app')

@section('title', 'Verify Email - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Verify Your Email Address') }}</h1>
        </div>
    </div>

    <div class="admin-card max-w-2xl">
        <div class="admin-card-body text-center py-8">
            <div class="w-20 h-20 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope-open-text text-3xl"></i>
            </div>

            @if (session('resent'))
                <div class="admin-alert-success mb-4 text-left">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('A fresh verification link has been sent to your email address.') }}</span>
                </div>
            @endif

            <p class="text-gray-600 mb-2">
                {{ __('Before proceeding, please check your email for a verification link.') }}
            </p>
            <p class="text-gray-500 text-sm mb-6">
                {{ __('If you did not receive the email') }}
            </p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="admin-btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('Click here to request another') }}
                </button>
            </form>
        </div>
    </div>
@endsection
