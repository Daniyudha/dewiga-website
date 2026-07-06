@extends('layouts.app')

@section('title', 'My Profile - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('My Profile') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your account settings</p>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="max-w-2xl">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="font-heading font-semibold text-gray-800">{{ __('Account Information') }}</h2>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="admin-form-group">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="admin-form-input pl-10 @error('name') error @enderror" required>
                        </div>
                        @error('name')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="admin-form-group">
                        <label for="email" class="admin-form-label">{{ __('Email') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="admin-form-input pl-10 @error('email') error @enderror" required>
                        </div>
                        @error('email')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <hr class="border-gray-200">

                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-1">{{ __('Change Password') }}</p>
                        <p class="text-xs text-gray-400 mb-4">{{ __('Leave blank to keep current password') }}</p>
                    </div>

                    {{-- New Password --}}
                    <div class="admin-form-group">
                        <label for="password" class="admin-form-label">{{ __('New Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                   class="admin-form-input pl-10 @error('password') error @enderror"
                                   placeholder="Enter new password" autocomplete="new-password">
                        </div>
                        @error('password')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="admin-form-group">
                        <label for="password_confirmation" class="admin-form-label">{{ __('Confirm New Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="admin-form-input pl-10"
                                   placeholder="Confirm new password" autocomplete="new-password">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="admin-btn-primary">
                            <i class="fas fa-save"></i>
                            {{ __('Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if ($message = Session::get('success'))
        <script>
            // Simple notification using alert banner (handled by app layout flash messages)
            document.addEventListener('DOMContentLoaded', function() {
                // Flash message is already displayed by the layout
            });
        </script>
    @endif
@endsection
