@extends('layouts.app')

@section('title', 'Create Booking - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Add Booking') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new booking manually</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Booking Information') }}</h2>
        </div>
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.bookings.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="admin-form-group">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="admin-form-input @error('name') error @enderror"
                               placeholder="Customer name" required>
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
                               placeholder="customer@example.com" required>
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

                    {{-- Start Date --}}
                    <div class="admin-form-group">
                        <label for="start_date" class="admin-form-label">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', old('date')) }}"
                               class="admin-form-input @error('start_date') error @enderror" required>
                        @error('start_date')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="admin-form-group">
                        <label for="end_date" class="admin-form-label">{{ __('End Date') }}</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                               class="admin-form-input @error('end_date') error @enderror">
                        @error('end_date')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="admin-form-group">
                        <label for="amount" class="admin-form-label">{{ __('Amount (Rp)') }}</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}"
                               class="admin-form-input @error('amount') error @enderror"
                               placeholder="0" min="0" step="1000">
                        @error('amount')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- People Count --}}
                    <div class="admin-form-group">
                        <label for="people_count" class="admin-form-label">{{ __('Number of People') }}</label>
                        <input type="number" id="people_count" name="people_count" value="{{ old('people_count', 1) }}"
                               class="admin-form-input @error('people_count') error @enderror"
                               placeholder="1" min="1">
                        @error('people_count')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Travel Package --}}
                    <div class="admin-form-group">
                        <label for="travel_package_id" class="admin-form-label">{{ __('Travel Package') }} <span class="text-red-500">*</span></label>
                        <select id="travel_package_id" name="travel_package_id" class="admin-form-input @error('travel_package_id') error @enderror" required>
                            <option value="">{{ __('Select Package') }}</option>
                            @foreach($travel_packages as $package)
                                <option value="{{ $package->id }}" {{ old('travel_package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->type }} - {{ $package->location }}
                                </option>
                            @endforeach
                        </select>
                        @error('travel_package_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="admin-form-group">
                        <label for="schedule_id" class="admin-form-label">{{ __('Schedule (Optional)') }}</label>
                        <select id="schedule_id" name="schedule_id" class="admin-form-input @error('schedule_id') error @enderror">
                            <option value="">{{ __('No Schedule') }}</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->start_date->format('d M Y') }} - {{ $schedule->travelPackage->type }}
                                    @if($schedule->visitor_name) ({{ $schedule->visitor_name }}) @endif
                                    [{{ $schedule->remainingQuota() }} spots]
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="admin-form-group">
                        <label for="status" class="admin-form-label">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="admin-form-input @error('status') error @enderror" required>
                            @foreach(\App\Models\Booking::statuses() as $val => $label)
                                <option value="{{ $val }}" {{ old('status', 'pending') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="admin-form-group md:col-span-2">
                        <label for="notes" class="admin-form-label">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" rows="3" class="admin-form-input @error('notes') error @enderror"
                                  placeholder="Optional notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="admin-form-group md:col-span-2">
                        <label for="description" class="admin-form-label">{{ __('Description') }}</label>
                        <textarea id="description" name="description" rows="3" class="admin-form-input @error('description') error @enderror"
                                  placeholder="Additional description...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Save Booking') }}
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection