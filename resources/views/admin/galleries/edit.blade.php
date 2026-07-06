@extends('layouts.app')

@section('title', 'Edit Gallery - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Gallery Image') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $gallery->name }}</p>
        </div>
        <a href="{{ route('admin.travel_packages.edit', [$travel_package]) }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Package') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.travel_packages.galleries.update', [$travel_package, $gallery]) }}"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('put')

                {{-- Current Image --}}
                @if($gallery->images && file_exists(public_path('storage/' . $gallery->images)))
                    <div class="mb-4">
                        <label class="admin-form-label mb-2">{{ __('Current Image') }}</label>
                        <div>
                            <img src="{{ asset('storage/' . $gallery->images) }}" alt="{{ $gallery->name }}"
                                 class="w-48 h-36 rounded-lg object-cover border border-gray-200 shadow-sm">
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="admin-form-group">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $gallery->name) }}"
                               class="admin-form-input @error('name') error @enderror"
                               placeholder="e.g. Kuta Beach" required>
                        @error('name')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="admin-form-group">
                        <label for="images" class="admin-form-label">{{ __('New Image') }}</label>
                        <input type="file" id="images" name="images"
                               class="admin-form-input @error('images') error @enderror">
                        <p class="text-xs text-gray-400 mt-1">{{ __('Leave empty to keep current image') }}</p>
                        @error('images')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Update') }}
                    </button>
                    <a href="{{ route('admin.travel_packages.edit', [$travel_package]) }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
