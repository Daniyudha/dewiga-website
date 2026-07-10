@extends('layouts.app')

@section('title', 'Edit Activity - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Activity') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $activity->title_id }}</p>
        </div>
        <a href="{{ route('admin.activities.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.activities.update', [$activity]) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('put')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="title_id" class="admin-form-label">{{ __('Title') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="title_id" name="title_id" value="{{ old('title_id', $activity->title_id) }}" class="admin-form-input @error('title_id') error @enderror" required>
                        @error('title_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="title_en" class="admin-form-label">{{ __('Title') }} (English)</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $activity->title_en) }}" class="admin-form-input @error('title_en') error @enderror">
                        @error('title_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Image') }} <span class="text-red-500">*</span></label>
                        <input type="file" id="image" name="image" accept="image/*" class="admin-form-input @error('image') error @enderror p-2">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image. Recommended: JPG, PNG, WebP. Max 2MB.</p>
                        @error('image')<p class="admin-form-error">{{ $message }}</p>@enderror
                        <div id="image-preview" class="mt-2 {{ $activity->image ? '' : 'hidden' }}">
                            <img src="{{ $activity->image ? asset('storage/' . $activity->image) : '' }}" alt="Preview" class="w-32 h-24 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label for="min_pax" class="admin-form-label">{{ __('Min Pax') }}</label>
                        <input type="text" id="min_pax" name="min_pax" value="{{ old('min_pax', $activity->min_pax) }}" class="admin-form-input">
                        @error('min_pax')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="duration_id" class="admin-form-label">{{ __('Duration') }} (Indonesia)</label>
                        <input type="text" id="duration_id" name="duration_id" value="{{ old('duration_id', $activity->duration_id) }}" class="admin-form-input">
                        @error('duration_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="duration_en" class="admin-form-label">{{ __('Duration') }} (English)</label>
                        <input type="text" id="duration_en" name="duration_en" value="{{ old('duration_en', $activity->duration_en) }}" class="admin-form-input">
                        @error('duration_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="includes_id" class="admin-form-label">{{ __('Includes') }} (Indonesia)</label>
                        <input type="text" id="includes_id" name="includes_id" value="{{ old('includes_id', $activity->includes_id) }}" class="admin-form-input">
                        @error('includes_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="includes_en" class="admin-form-label">{{ __('Includes') }} (English)</label>
                        <input type="text" id="includes_en" name="includes_en" value="{{ old('includes_en', $activity->includes_en) }}" class="admin-form-input">
                        @error('includes_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="description_id" class="admin-form-label">{{ __('Description') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <textarea id="description_id" name="description_id" rows="4" class="admin-form-textarea @error('description_id') error @enderror" required>{{ old('description_id', $activity->description_id) }}</textarea>
                        @error('description_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="description_en" class="admin-form-label">{{ __('Description') }} (English)</label>
                        <textarea id="description_en" name="description_en" rows="4" class="admin-form-textarea @error('description_en') error @enderror">{{ old('description_en', $activity->description_en) }}</textarea>
                        @error('description_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <p class="text-sm text-gray-500 mb-1">{{ __('Order') }}: <strong>{{ $activity->order }}</strong> <span class="text-gray-400">({{ __('auto') }})</span></p>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $activity->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 rounded border-gray-300">
                            {{ __('Featured') }}
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Update') }}</button>
                    <a href="{{ route('admin.activities.index') }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Gallery Management Section --}}
    <div class="admin-card mt-6">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Gallery Images') }}</h2>
        </div>
        <div class="admin-card-body">
            @if($activity->galleries && $activity->galleries->count() > 0)
                <div class="overflow-x-auto mb-4">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }} (ID)</th>
                                <th>{{ __('Name') }} (EN)</th>
                                <th>{{ __('Image') }}</th>
                                <th class="!text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activity->galleries as $gallery)
                                <tr>
                                    <td class="font-medium">{{ $gallery->name_id ?? '-' }}</td>
                                    <td class="font-medium">{{ $gallery->name_en ?? '-' }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name_id }}" class="admin-thumb">
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-4">
                                            <a href="{{ route('admin.activities.galleries.edit', [$activity, $gallery]) }}"
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.activities.galleries.destroy', [$activity, $gallery]) }}" class="inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" onclick="showDeleteModal(this.closest('form'))" class="text-red-600 hover:text-red-800 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 text-gray-500">
                    <i class="fas fa-images text-3xl text-gray-300 block mb-2"></i>
                    <p>{{ __('No gallery images yet.') }}</p>
                </div>
            @endif

            {{-- Upload Form --}}
            <form method="post" action="{{ route('admin.activities.galleries.store', [$activity]) }}" enctype="multipart/form-data" class="border-t border-gray-100 pt-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="admin-form-group">
                        <label for="name_id" class="admin-form-label">{{ __('Image Name') }} (Indonesia)</label>
                        <input type="text" id="name_id" name="name_id" value="{{ old('name_id') }}"
                               class="admin-form-input" placeholder="e.g. Aktivitas Seru">
                        @error('name_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="name_en" class="admin-form-label">{{ __('Image Name') }} (English)</label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}"
                               class="admin-form-input" placeholder="e.g. Fun Activity">
                        @error('name_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Upload Image') }}</label>
                        <input type="file" id="gallery_image" name="image"
                               class="admin-form-input @error('image') error @enderror">
                        @error('image')<p class="admin-form-error">{{ $message }}</p>@enderror
                        <div id="gallery-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-32 h-20 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                </div>
                <button type="submit" class="admin-btn-primary mt-4">
                    <i class="fas fa-upload"></i>
                    {{ __('Upload') }}
                </button>
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
