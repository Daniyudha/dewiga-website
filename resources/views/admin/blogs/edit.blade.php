@extends('layouts.app')

@section('title', 'Edit Blog - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Blog') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $blog->title }}</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.blogs.update', [$blog]) }}" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf
                @method('put')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title (Indonesia) --}}
                    <div class="admin-form-group">
                        <label for="title_id" class="admin-form-label">
                            {{ __('Title') }} (Indonesia) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title_id" name="title_id" value="{{ old('title_id', $blog->title_id) }}"
                               class="admin-form-input @error('title_id') error @enderror" required>
                        @error('title_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title (English) --}}
                    <div class="admin-form-group">
                        <label for="title_en" class="admin-form-label">
                            {{ __('Title') }} (English) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $blog->title_en) }}"
                               class="admin-form-input @error('title_en') error @enderror" required>
                        @error('title_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Category --}}
                    <div class="admin-form-group">
                        <label for="category_id" class="admin-form-label">{{ __('Category') }} <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" class="admin-form-select @error('category_id') error @enderror" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($blog->category_id ?? old('category_id')) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Image') }}</label>
                        @if($blog->image && file_exists(public_path('storage/' . $blog->image)))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-24 h-16 rounded-lg object-cover border border-gray-200">
                            </div>
                        @endif
                        <input type="file" id="image" name="image" class="admin-form-input @error('image') error @enderror">
                        <p class="text-xs text-gray-400 mt-1">{{ __('Leave empty to keep current image') }}</p>
                        @error('image')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Excerpt (Indonesia) --}}
                    <div class="admin-form-group">
                        <label for="excerpt_id" class="admin-form-label">
                            {{ __('Excerpt') }} (Indonesia) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="excerpt_id" name="excerpt_id" value="{{ old('excerpt_id', $blog->excerpt_id) }}"
                               class="admin-form-input @error('excerpt_id') error @enderror" maxlength="160" required>
                        <p class="text-xs text-gray-400 mt-1">Maximum 160 characters</p>
                        @error('excerpt_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Excerpt (English) --}}
                    <div class="admin-form-group">
                        <label for="excerpt_en" class="admin-form-label">
                            {{ __('Excerpt') }} (English) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="excerpt_en" name="excerpt_en" value="{{ old('excerpt_en', $blog->excerpt_en) }}"
                               class="admin-form-input @error('excerpt_en') error @enderror" maxlength="160" required>
                        <p class="text-xs text-gray-400 mt-1">Maximum 160 characters</p>
                        @error('excerpt_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description (Indonesia) --}}
                <div class="admin-form-group">
                    <label for="description_id" class="admin-form-label">
                        {{ __('Description') }} (Indonesia) <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description_id" name="description_id" class="admin-form-textarea @error('description_id') error @enderror" required>{{ old('description_id', $blog->description_id) }}</textarea>
                    @error('description_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (English) --}}
                <div class="admin-form-group">
                    <label for="description_en" class="admin-form-label">
                        {{ __('Description') }} (English) <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description_en" name="description_en" class="admin-form-textarea @error('description_en') error @enderror" required>{{ old('description_en', $blog->description_en) }}</textarea>
                    @error('description_en')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Update') }}
                    </button>
                    <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
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

    // Ensure CKEditor content is synced before form submits
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
