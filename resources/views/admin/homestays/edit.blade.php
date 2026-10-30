@extends('layouts.app')

@section('title', 'Edit Homestay - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Homestay') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui data homestay {{ $homestay->name_id }}</p>
        </div>
        <a href="{{ route('admin.homestays.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.homestays.update', $homestay) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('put')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="name_id" class="admin-form-label">{{ __('Name') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="name_id" name="name_id" value="{{ old('name_id', $homestay->name_id) }}" class="admin-form-input @error('name_id') error @enderror" required>
                        @error('name_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="name_en" class="admin-form-label">{{ __('Name') }} (English)</label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $homestay->name_en) }}" class="admin-form-input @error('name_en') error @enderror">
                        @error('name_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="address_id" class="admin-form-label">{{ __('Address') }} (Indonesia)</label>
                        <input type="text" id="address_id" name="address_id" value="{{ old('address_id', $homestay->address_id) }}" class="admin-form-input @error('address_id') error @enderror">
                        @error('address_id')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="address_en" class="admin-form-label">{{ __('Address') }} (English)</label>
                        <input type="text" id="address_en" name="address_en" value="{{ old('address_en', $homestay->address_en) }}" class="admin-form-input @error('address_en') error @enderror">
                        @error('address_en')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="latitude" class="admin-form-label">{{ __('Latitude') }}</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude', $homestay->latitude) }}" step="any" class="admin-form-input @error('latitude') error @enderror">
                        @error('latitude')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="longitude" class="admin-form-label">{{ __('Longitude') }}</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude', $homestay->longitude) }}" step="any" class="admin-form-input @error('longitude') error @enderror">
                        @error('longitude')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="capacity" class="admin-form-label">{{ __('Capacity') }}</label>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $homestay->capacity) }}" min="0" class="admin-form-input @error('capacity') error @enderror">
                        @error('capacity')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Image') }}</label>
                        <input type="file" id="image" name="image" accept="image/*" class="admin-form-input @error('image') error @enderror p-2">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP. Max 2MB.</p>
                        @error('image')<p class="admin-form-error">{{ $message }}</p>@enderror
                        <div id="image-preview" class="mt-2 {{ $homestay->image ? '' : 'hidden' }}">
                            <img src="{{ $homestay->image ? asset('storage/' . $homestay->image) : '' }}" alt="Preview" class="w-60 h-45 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="order" class="admin-form-label">{{ __('Order') }}</label>
                        <input type="number" id="order" name="order" value="{{ old('order', $homestay->order) }}" min="0" class="admin-form-input @error('order') error @enderror">
                        @error('order')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="admin-form-input w-5 h-5" {{ $homestay->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="admin-form-label ml-2">{{ __('Is Active') }}</label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Save') }}</button>
                    <a href="{{ route('admin.homestays.index') }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

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
            };
            reader.readAsDataURL(file);
        }
    });
    </script>
    @endpush
@endsection

