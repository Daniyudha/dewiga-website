@extends('layouts.app')

@section('title', 'Create Open Trip Registration - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Add Open Trip Registration') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Register a participant for an open trip event</p>
        </div>
        <a href="{{ route('admin.open-trip-registrations.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Registration Information') }}</h2>
        </div>
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.open-trip-registrations.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="admin-form-group">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="admin-form-input @error('name') error @enderror"
                               placeholder="Participant name" required>
                        @error('name')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Institution --}}
                    <div class="admin-form-group">
                        <label for="institution" class="admin-form-label">{{ __('Institution/Organization') }}</label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                               class="admin-form-input @error('institution') error @enderror"
                               placeholder="School/Company/Organization name">
                        @error('institution')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="admin-form-group">
                        <label for="email" class="admin-form-label">{{ __('Email') }} <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="admin-form-input @error('email') error @enderror"
                               placeholder="participant@example.com" required>
                        @error('email')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="admin-form-group">
                        <label for="number_phone" class="admin-form-label">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="number_phone" name="number_phone" value="{{ old('number_phone') }}"
                               class="admin-form-input @error('number_phone') error @enderror"
                               placeholder="081234567890" required>
                        @error('number_phone')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="admin-form-group">
                        <label for="schedule_id" class="admin-form-label">{{ __('Schedule') }} <span class="text-red-500">*</span></label>
                        <select id="schedule_id" name="schedule_id" class="admin-form-input @error('schedule_id') error @enderror" required>
                            <option value="">{{ __('Select Open Trip Schedule') }}</option>
                            @foreach($schedules as $schedule)
                                @if($schedule->type === 'open_trip')
                                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->start_date->format('d M Y') }} - {{ $schedule->travelPackage->type }}
                                    ({{ $schedule->remainingQuota() }} spots left)
                                </option>
                                @endif
                            @endforeach
                        </select>
                        @error('schedule_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- People Count --}}
                    <div class="admin-form-group">
                        <label for="people_count" class="admin-form-label">{{ __('Number of People') }} <span class="text-red-500">*</span></label>
                        <input type="number" id="people_count" name="people_count" value="{{ old('people_count', 1) }}"
                               class="admin-form-input @error('people_count') error @enderror"
                               placeholder="1" min="1" required>
                        @error('people_count')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="admin-form-group">
                        <label for="status" class="admin-form-label">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="admin-form-input @error('status') error @enderror" required>
                            @foreach(\App\Models\OpenTripRegistration::statuses() as $val => $label)
                                <option value="{{ $val }}" {{ old('status', 'pending') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="admin-form-group">
                        <label for="notes" class="admin-form-label">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" rows="3" class="admin-form-input @error('notes') error @enderror"
                                  placeholder="Optional notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="admin-form-group md:col-span-2">
                        <label for="description" class="admin-form-label">{{ __('Description (Optional)') }}</label>
                        <textarea id="description" name="description" rows="3" class="admin-form-input @error('description') error @enderror"
                                  placeholder="Any special requests or notes...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Save Registration') }}
                    </button>
                    <a href="{{ route('admin.open-trip-registrations.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection