@extends('layouts.guest')

@section('title', 'Login - Admin Dewiga')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-heading font-bold text-gray-800">{{ __('Selamat Datang') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('Masuk ke akun admin Anda') }}</p>
        </div>

        <form action="{{ route('login') }}" method="post" class="space-y-4">
            @csrf

            {{-- Email --}}
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

            {{-- Password --}}
            <div class="admin-form-group">
                <label for="password" class="admin-form-label">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password"
                           class="admin-form-input pl-10 pr-10 @error('password') error @enderror"
                           placeholder="Enter your password" required>
                    <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="admin-form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                <label for="remember" class="text-sm text-gray-600 cursor-pointer">{{ __('Remember Me') }}</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="admin-btn-primary w-full text-base py-2.5">
                <i class="fas fa-sign-in-alt"></i>
                {{ __('Login') }}
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && icon) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
                });
            }
        });
    </script>
@endsection