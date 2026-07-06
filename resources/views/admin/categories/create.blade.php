@extends('layouts.app')

@section('title', 'Create Category - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Create Category') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new blog category</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.categories.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Indonesian --}}
                    <div class="admin-form-group">
                        <label for="name_id" class="admin-form-label">
                            {{ __('Name') }} (Indonesia) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name_id" name="name_id" value="{{ old('name_id') }}"
                               class="admin-form-input @error('name_id') error @enderror"
                               placeholder="e.g. Budaya" required>
                        @error('name_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- English --}}
                    <div class="admin-form-group">
                        <label for="name_en" class="admin-form-label">
                            {{ __('Name') }} (English) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}"
                               class="admin-form-input @error('name_en') error @enderror"
                               placeholder="e.g. Culture" required>
                        @error('name_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
