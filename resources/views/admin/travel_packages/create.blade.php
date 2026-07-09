@extends('layouts.app')

@section('title', 'Create Travel Package - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Create Travel Package') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new travel package</p>
        </div>
        <a href="{{ route('admin.travel_packages.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.travel_packages.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf

                {{-- Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="type_id" class="admin-form-label">{{ __('Type') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="type_id" name="type_id" value="{{ old('type_id') }}"
                               class="admin-form-input @error('type_id') error @enderror"
                               placeholder="e.g. Budaya" required>
                        @error('type_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="type_en" class="admin-form-label">{{ __('Type') }} (English) <span class="text-red-500">*</span></label>
                        <input type="text" id="type_en" name="type_en" value="{{ old('type_en') }}"
                               class="admin-form-input @error('type_en') error @enderror"
                               placeholder="e.g. Culture" required>
                        @error('type_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Title (Location) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="location_id" class="admin-form-label">{{ __('Title') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="location_id" name="location_id" value="{{ old('location_id') }}"
                               class="admin-form-input @error('location_id') error @enderror"
                               placeholder="e.g. Live In" required>
                        @error('location_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="location_en" class="admin-form-label">{{ __('Title') }} (English) <span class="text-red-500">*</span></label>
                        <input type="text" id="location_en" name="location_en" value="{{ old('location_en') }}"
                               class="admin-form-input @error('location_en') error @enderror"
                               placeholder="e.g. Live In" required>
                        @error('location_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Price --}}
                <div class="admin-form-group max-w-sm">
                    <label for="price" class="admin-form-label">{{ __('Price') }} <span class="text-red-500">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}"
                           class="admin-form-input @error('price') error @enderror"
                           placeholder="e.g. 500000" required>
                    @error('price')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (Indonesia) --}}
                <div class="admin-form-group">
                    <label for="description_id" class="admin-form-label">{{ __('Description') }} (Indonesia) <span class="text-red-500">*</span></label>
                    <textarea id="description_id" name="description_id" class="admin-form-textarea @error('description_id') error @enderror"
                              placeholder="Describe the travel package in Indonesian..." required>{{ old('description_id') }}</textarea>
                    @error('description_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (English) --}}
                <div class="admin-form-group">
                    <label for="description_en" class="admin-form-label">{{ __('Description') }} (English) <span class="text-red-500">*</span></label>
                    <textarea id="description_en" name="description_en" class="admin-form-textarea @error('description_en') error @enderror"
                              placeholder="Describe the travel package in English..." required>{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gallery Image Upload --}}
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-heading font-semibold text-gray-800 mb-4">{{ __('Gallery Image') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="admin-form-group">
                            <label for="image_name" class="admin-form-label">{{ __('Image Name') }}</label>
                            <input type="text" id="image_name" name="image_name" value="{{ old('image_name') }}"
                                   class="admin-form-input" placeholder="e.g. Sunset at Kuta">
                            @error('image_name')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="admin-form-group">
                            <label for="image" class="admin-form-label">{{ __('Upload Image') }}</label>
                            <input type="file" id="image" name="image"
                                   class="admin-form-input @error('image') error @enderror">
                            @error('image')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-32 h-20 rounded-lg object-cover border border-gray-200">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.travel_packages.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Image preview
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
<script src="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.umd.js"></script>
<script>
(function() {
    'use strict';

    var ckEditors = [];

    function getPlugin(name) {
        try {
            return CKEDITOR[name];
        } catch (e) {
            return undefined;
        }
    }

    function initEditor(elementId) {
        try {
            if (typeof CKEDITOR === 'undefined') {
                console.warn('CKEditor not available');
                return;
            }
            var EditorClass = CKEDITOR.ClassicEditor;
            if (!EditorClass) {
                console.warn('ClassicEditor not available');
                return;
            }
            var el = document.querySelector('#' + elementId);
            if (!el) {
                console.warn('Element not found:', elementId);
                return;
            }
            EditorClass.create(el, {
                plugins: [
                    'Essentials', 'Bold', 'Italic', 'Paragraph', 'Heading',
                    'List', 'Link', 'BlockQuote',
                    'Image', 'ImageUpload', 'ImageToolbar', 'ImageStyle',
                    'SimpleUploadAdapter'
                ].map(getPlugin).filter(Boolean),
                toolbar: [ 'undo', 'redo', '|', 'bold', 'italic', '|', 'heading', '|', 'bulletedList', 'numberedList', '|', 'link', 'blockQuote', '|', 'imageUpload' ],
                image: {
                    toolbar: [ 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight' ]
                },
                simpleUpload: {
                    uploadUrl: '{{ route('admin.upload.image') }}',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }
            }).then(function(instance) {
                ckEditors.push(instance);
            }).catch(function(err) {
                console.error('CKEditor init error for', elementId, ':', err);
            });
        } catch (err) {
            console.error('CKEditor setup error for', elementId, ':', err);
        }
    }

    initEditor('description_id');
    initEditor('description_en');

    setTimeout(function() {
        var form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                ckEditors.forEach(function(instance) {
                    try {
                        instance.updateSourceElement();
                    } catch (e) {
                        console.error('CKEditor sync error:', e);
                    }
                });
            });
        }
    }, 500);
})();
</script>
@endpush
