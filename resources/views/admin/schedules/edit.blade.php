@extends('layouts.app')

@section('title', 'Edit Schedule - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Schedule') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Update travel package schedule') }}</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Schedules') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Schedule Information') }}</h2>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Travel Package --}}
                    <div>
                        <label for="travel_package_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Travel Package') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="travel_package_id" id="travel_package_id"
                            class="admin-input w-full @error('travel_package_id') border-red-500 @enderror" required>
                            <option value="">{{ __('Select Package') }}</option>
                            @foreach($travel_packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('travel_package_id', $schedule->travel_package_id) == $package->id ? 'selected' : '' }}>
                                    {{ $package->type }} - {{ $package->location }}
                                </option>
                            @endforeach
                        </select>
                        @error('travel_package_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Schedule Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" class="admin-input w-full @error('type') border-red-500 @enderror" required>
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($types as $val => $label)
                                <option value="{{ $val }}" {{ old('type', $schedule->type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Visitor Name --}}
                    <div>
                        <label for="visitor_name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Visitor / Group Name') }}
                        </label>
                        <input type="text" name="visitor_name" id="visitor_name"
                            class="admin-input w-full @error('visitor_name') border-red-500 @enderror"
                            value="{{ old('visitor_name', $schedule->visitor_name) }}"
                            placeholder="{{ __('e.g. SMA Diponegoro Jakarta') }}">
                        @error('visitor_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Start Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            class="admin-input w-full @error('start_date') border-red-500 @enderror"
                            value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('End Date') }}
                        </label>
                        <input type="date" name="end_date" id="end_date"
                            class="admin-input w-full @error('end_date') border-red-500 @enderror"
                            value="{{ old('end_date', $schedule->end_date?->format('Y-m-d')) }}">
                        <p class="text-xs text-gray-400 mt-1">{{ __('Leave empty for single-day schedule') }}</p>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quota --}}
                    <div>
                        <label for="quota" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Quota (Max Participants)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quota" id="quota"
                            class="admin-input w-full @error('quota') border-red-500 @enderror"
                            min="1" value="{{ old('quota', $schedule->quota) }}" required>
                        @error('quota')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Booked Count (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Currently Booked') }}
                        </label>
                        <input type="number" class="admin-input w-full bg-gray-50 text-gray-500"
                            value="{{ $schedule->booked }}" readonly disabled>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Booked count is managed automatically') }}</p>
                    </div>

                    {{-- Active Status --}}
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.schedules.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="admin-btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Update Schedule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-alt')
<script>
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
</script>
@endpush
