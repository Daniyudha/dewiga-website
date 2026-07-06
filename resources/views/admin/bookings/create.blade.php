@extends('layouts.app')

@section('title', 'Create Booking - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Create Booking') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new booking manually</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.bookings.store') }}" class="space-y-6">
                @csrf

                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="admin-form-input @error('name') error @enderror"
                           placeholder="Customer name" required>
                    @error('name')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
