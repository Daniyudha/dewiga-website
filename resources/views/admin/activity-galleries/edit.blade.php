@extends('layouts.app')

@section('title', 'Edit Activity Gallery - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Gallery Image') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $gallery->name_id ?? 'Gallery Image' }}</p>
        </div>
        <a href="{{ route('admin.activities.edit', [$activity]) }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Activity') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.activities.galleries.update', [$activity, $gallery]) }}"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('put')

                @if($gallery->image && file_exists(public_path('storage/' . $gallery->image)))
                    <div class="mb-4">
                        <label class="admin-form-label mb-2">{{ __('Current Image') }}</label>
                        <div>
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name_id }}"
                                 class="w-48 h-36 rounded-lg object-cover border border-gray-200 shadow-sm">
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="admin-form-group">
                        <label for="name_id" class="admin-form-label">{{ __('Name') }} (Indonesia)</label>
                        <input type="text" id="name_id" name="name_id" value="{{ old('name_id', $gallery->name_id) }}"
                               class="admin-form-input @error('name_id') error @enderror"
                               placeholder="e.g. Aktivitas Seru">
                        @error('name_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="name_en" class="admin-form-label">{{ __('Name') }} (English)</label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $gallery->name_en) }}"
                               class="admin-form-input @error('name_en') error @enderror"
                               placeholder="e.g. Fun Activity">
                        @error('name_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('New Image') }}</label>
                        <input type="file" id="image" name="image"
                               class="admin-form-input @error('image') error @enderror">
                        <p class="text-xs text-gray-400 mt-1">{{ __('Leave empty to keep current image') }}</p>
                        @error('image')<p class="admin-form-error">{{ $message }}</p>@enderror
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-32 h-20 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i> {{ __('Update') }}
                    </button>
                    <a href="{{ route('admin.activities.edit', [$activity]) }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
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
</script>
@endpush