@extends('layouts.app')

@section('title', 'Create Activity - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Create Activity') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new activity listing</p>
        </div>
        <a href="{{ route('admin.activities.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.activities.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="title_id" class="admin-form-label">{{ __('Title') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="title_id" name="title_id" value="{{ old('title_id') }}" class="admin-form-input @error('title_id') error @enderror" placeholder="e.g. Membajak Sawah Tradisional" required>
                        @error('title_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="title_en" class="admin-form-label">{{ __('Title') }} (English)</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" class="admin-form-input @error('title_en') error @enderror" placeholder="e.g. Traditional Rice Field Plowing">
                        @error('title_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Image') }} <span class="text-red-500">*</span></label>
                        <input type="file" id="image" name="image" accept="image/*" class="admin-form-input @error('image') error @enderror p-2" required>
                        <p class="text-xs text-gray-400 mt-1">Recommended: JPG, PNG, WebP. Max 2MB.</p>
                        @error('image')<p class="admin-form-error">{{ $message }}</p>@enderror
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-60 h-45 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label for="min_pax" class="admin-form-label">{{ __('Min Pax') }}</label>
                        <input type="text" id="min_pax" name="min_pax" value="{{ old('min_pax') }}" class="admin-form-input" placeholder="e.g. 2 orang">
                        @error('min_pax')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="duration_id" class="admin-form-label">{{ __('Duration') }} (Indonesia)</label>
                        <input type="text" id="duration_id" name="duration_id" value="{{ old('duration_id') }}" class="admin-form-input" placeholder="e.g. 1-2 jam">
                        @error('duration_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="duration_en" class="admin-form-label">{{ __('Duration') }} (English)</label>
                        <input type="text" id="duration_en" name="duration_en" value="{{ old('duration_en') }}" class="admin-form-input" placeholder="e.g. 1-2 hours">
                        @error('duration_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="includes_id" class="admin-form-label">{{ __('Includes') }} (Indonesia)</label>
                        <input type="text" id="includes_id" name="includes_id" value="{{ old('includes_id') }}" class="admin-form-input" placeholder="e.g. Pemandu, alat membajak, air mineral">
                        @error('includes_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="includes_en" class="admin-form-label">{{ __('Includes') }} (English)</label>
                        <input type="text" id="includes_en" name="includes_en" value="{{ old('includes_en') }}" class="admin-form-input" placeholder="e.g. Guide, plowing tools, mineral water">
                        @error('includes_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="description_id" class="admin-form-label">{{ __('Description') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <textarea id="description_id" name="description_id" rows="4" class="admin-form-textarea @error('description_id') error @enderror" placeholder="Deskripsi aktivitas dalam Bahasa Indonesia" required>{{ old('description_id') }}</textarea>
                        @error('description_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="description_en" class="admin-form-label">{{ __('Description') }} (English)</label>
                        <textarea id="description_en" name="description_en" rows="4" class="admin-form-textarea @error('description_en') error @enderror" placeholder="Activity description in English">{{ old('description_en') }}</textarea>
                        @error('description_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label class="admin-form-label flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-primary-600 rounded border-gray-300">
                            {{ __('Featured') }}
                        </label>
                    </div>
                </div>

                {{-- Gallery Upload (optional) --}}
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-heading font-semibold text-gray-800 mb-4">{{ __('Gallery Images') }} <span class="text-sm font-normal text-gray-400">({{ __('optional') }})</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="admin-form-group">
                            <label for="gallery_name_id" class="admin-form-label">{{ __('Image Name') }} (Indonesia)</label>
                            <input type="text" id="gallery_name_id" name="gallery_name_id" value="{{ old('gallery_name_id') }}"
                                   class="admin-form-input" placeholder="e.g. Aktivitas Seru">
                            @error('gallery_name_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label for="gallery_name_en" class="admin-form-label">{{ __('Image Name') }} (English)</label>
                            <input type="text" id="gallery_name_en" name="gallery_name_en" value="{{ old('gallery_name_en') }}"
                                   class="admin-form-input" placeholder="e.g. Fun Activity">
                            @error('gallery_name_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="admin-form-group">
                            <label for="gallery_image" class="admin-form-label">{{ __('Upload Image') }}</label>
                            <input type="file" id="gallery_image" name="gallery_image"
                                   class="admin-form-input @error('gallery_image') error @enderror">
                            @error('gallery_image')<p class="admin-form-error">{{ $message }}</p>@enderror
                            <div id="gallery-preview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-32 h-20 rounded-lg object-cover border border-gray-200">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ __('You can add more images later in the edit page.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Save') }}</button>
                    <a href="{{ route('admin.activities.index') }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

document.getElementById('gallery_image').addEventListener('change', function(e) {
    const preview = document.getElementById('gallery-preview');
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
