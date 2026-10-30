@extends('layouts.app')

@section('title', 'Edit Event - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Event') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui event {{ $event->name_id }}</p>
        </div>
        <a href="{{ route('admin.schedules.index', ['tab' => 'peta-event']) }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.events.update', $event) }}" class="space-y-6">
                @csrf @method('put')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="name_id" class="admin-form-label">{{ __('Name') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="name_id" name="name_id" value="{{ old('name_id', $event->name_id) }}" class="admin-form-input @error('name_id') error @enderror" required>
                        @error('name_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="name_en" class="admin-form-label">{{ __('Name') }} (English)</label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $event->name_en) }}" class="admin-form-input @error('name_en') error @enderror">
                        @error('name_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="admin-form-group">
                    <label for="description_id" class="admin-form-label">{{ __('Description') }} (Indonesia)</label>
                    <textarea id="description_id" name="description_id" rows="3" class="admin-form-input @error('description_id') error @enderror">{{ old('description_id', $event->description_id) }}</textarea>
                    @error('description_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group">
                    <label for="description_en" class="admin-form-label">{{ __('Description') }} (English)</label>
                    <textarea id="description_en" name="description_en" rows="3" class="admin-form-input @error('description_en') error @enderror">{{ old('description_en', $event->description_en) }}</textarea>
                    @error('description_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="start_date" class="admin-form-label">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date) }}" class="admin-form-input @error('start_date') error @enderror" required>
                        @error('start_date')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="end_date" class="admin-form-label">{{ __('End Date') }} <span class="text-red-500">*</span></label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date) }}" class="admin-form-input @error('end_date') error @enderror" required>
                        @error('end_date')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="participant_count" class="admin-form-label">{{ __('Participants') }}</label>
                        <input type="number" id="participant_count" name="participant_count" value="{{ old('participant_count', $event->participant_count) }}" min="0" class="admin-form-input @error('participant_count') error @enderror">
                        @error('participant_count')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="admin-form-input w-5 h-5" {{ $event->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="admin-form-label ml-2">{{ __('Is Active') }}</label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Save') }}</button>
                    <a href="{{ route('admin.schedules.index', ['tab' => 'peta-event']) }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection

