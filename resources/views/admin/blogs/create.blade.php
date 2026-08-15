@extends('layouts.app')

@section('title', 'Create Blog - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Create Blog') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Write a new blog article</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form Card --}}
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title (Indonesia) --}}
                    <div class="admin-form-group">
                        <label for="title_id" class="admin-form-label">
                            {{ __('Title') }} (Indonesia) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title_id" name="title_id" value="{{ old('title_id') }}"
                               class="admin-form-input @error('title_id') error @enderror"
                               placeholder="e.g. Menjelajahi Surga Tersembunyi" required>
                        @error('title_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title (English) --}}
                    <div class="admin-form-group">
                        <label for="title_en" class="admin-form-label">
                            {{ __('Title') }} (English) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}"
                               class="admin-form-input @error('title_en') error @enderror"
                               placeholder="e.g. Exploring the Hidden Paradise" required>
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_id ?? $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="admin-form-group">
                        <label for="image" class="admin-form-label">{{ __('Image') }} <span class="text-red-500">*</span></label>
                        <input type="file" id="image" name="image"
                               class="admin-form-input @error('image') error @enderror" required>
                        @error('image')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-32 h-20 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Excerpt (Indonesia) --}}
                    <div class="admin-form-group">
                        <label for="excerpt_id" class="admin-form-label">
                            {{ __('Excerpt') }} (Indonesia) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="excerpt_id" name="excerpt_id" value="{{ old('excerpt_id') }}"
                               class="admin-form-input @error('excerpt_id') error @enderror"
                               placeholder="Brief description (ID)" maxlength="160" required>
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
                        <input type="text" id="excerpt_en" name="excerpt_en" value="{{ old('excerpt_en') }}"
                               class="admin-form-input @error('excerpt_en') error @enderror"
                               placeholder="Brief description (EN)" maxlength="160" required>
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
                    <textarea id="description_id" name="description_id" class="admin-form-textarea @error('description_id') error @enderror"
                              placeholder="Write your blog content in Indonesian..." required>{{ old('description_id') }}</textarea>
                    @error('description_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (English) --}}
                <div class="admin-form-group">
                    <label for="description_en" class="admin-form-label">
                        {{ __('Description') }} (English) <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description_en" name="description_en" class="admin-form-textarea @error('description_en') error @enderror"
                              placeholder="Write your blog content in English..." required>{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO - Meta Keywords --}}
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="text-lg font-heading font-semibold text-gray-800">{{ __('SEO') }}</h3>
                        <span class="text-xs text-gray-400 font-normal">({{ __('Keywords for search engines') }})</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Meta Keywords (Indonesia) --}}
                        <div class="admin-form-group">
                            <label for="meta_keywords_id" class="admin-form-label">
                                {{ __('Meta Keywords') }} (Indonesia)
                                <span class="text-xs text-gray-400 font-normal">— pisahkan dengan koma</span>
                            </label>
                            <input type="text" id="meta_keywords_id" name="meta_keywords_id" value="{{ old('meta_keywords_id') }}"
                                   class="admin-form-input"
                                   placeholder="desa wisata gabugan, sleman, agrowisata, salak pondoh">
                            @error('meta_keywords_id')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Meta Keywords (English) --}}
                        <div class="admin-form-group">
                            <label for="meta_keywords_en" class="admin-form-label">
                                {{ __('Meta Keywords') }} (English)
                                <span class="text-xs text-gray-400 font-normal">— separate by commas</span>
                            </label>
                            <input type="text" id="meta_keywords_en" name="meta_keywords_en" value="{{ old('meta_keywords_en') }}"
                                   class="admin-form-input"
                                   placeholder="gabugan tourism village, sleman, agro tourism">
                            @error('meta_keywords_en')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Meta Description otomatis diambil dari Excerpt. OG Image otomatis menggunakan Image blog.
                    </p>
                </div>

                    {{-- Publish Options --}}
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fas fa-paper-plane text-primary-600"></i>
                            <h3 class="text-lg font-heading font-semibold text-gray-800">{{ __('Publish Options') }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Draft --}}
                            <label class="status-option cursor-pointer relative p-4 rounded-xl border-2 border-gray-200 hover:border-yellow-400 hover:bg-yellow-50 transition-all text-left block">
                                <input type="radio" name="status" value="draft" class="hidden status-radio" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center shrink-0">
                                        <i class="fas fa-pen"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ __('Draft') }}</p>
                                        <p class="text-xs text-gray-400">Simpan sebagai konsep</p>
                                    </div>
                                </div>
                            </label>

                            {{-- Schedule --}}
                            <label class="status-option cursor-pointer relative p-4 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all text-left block">
                                <input type="radio" name="status" value="scheduled" class="hidden status-radio" {{ old('status') === 'scheduled' ? 'checked' : '' }}>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ __('Schedule') }}</p>
                                        <p class="text-xs text-gray-400">Jadwalkan tanggal terbit</p>
                                    </div>
                                </div>
                            </label>

                            {{-- Publish Now --}}
                            <label class="status-option cursor-pointer relative p-4 rounded-xl border-2 border-gray-200 hover:border-green-400 hover:bg-green-50 transition-all text-left block">
                                <input type="radio" name="status" value="published" class="hidden status-radio" {{ old('status') === 'published' ? 'checked' : '' }}>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ __('Publish Now') }}</p>
                                        <p class="text-xs text-gray-400">Langsung terbitkan</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Schedule Date --}}
                        <div class="admin-form-group mt-4">
                            <label for="published_at" class="admin-form-label">
                                <i class="fas fa-clock mr-1 text-gray-400"></i>
                                {{ __('Publish Date & Time') }}
                                <span class="text-xs text-gray-400 font-normal">({{ __('wajib diisi jika memilih Schedule') }})</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="datetime-local" id="published_at" name="published_at"
                                       value="{{ old('published_at') }}"
                                       class="admin-form-input !pl-10 @error('published_at') error @enderror"
                                       style="cursor: pointer;"
                                       {{ old('status') === 'scheduled' ? '' : 'disabled' }}>
                            </div>
                            <p id="publishedAtError" class="text-red-500 text-xs mt-1 hidden">
                                Tanggal & waktu wajib diisi jika memilih Schedule.
                            </p>
                            @error('published_at')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success">
                        <i class="fas fa-save"></i>
                        {{ __('Simpan') }}
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
<script>
// Make datetime-local input open picker when clicking anywhere on the field
document.addEventListener('DOMContentLoaded', function() {
    var dateInput = document.getElementById('published_at');
    if (dateInput) {
        dateInput.addEventListener('click', function() {
            if (!this.disabled && typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch (e) {}
            }
        });
        dateInput.addEventListener('focus', function() {
            if (!this.disabled && typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch (e) {}
            }
        });
    }
});

// Status option highlight + enable/disable date picker
document.querySelectorAll('.status-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.status-option').forEach(function(option) {
            option.classList.remove('border-yellow-400', 'bg-yellow-50', 'border-blue-400', 'bg-blue-50', 'border-green-400', 'bg-green-50');
            option.classList.add('border-gray-200');
        });
        var selected = this.closest('.status-option');
        if (this.value === 'draft') {
            selected.classList.remove('border-gray-200');
            selected.classList.add('border-yellow-400', 'bg-yellow-50');
        } else if (this.value === 'scheduled') {
            selected.classList.remove('border-gray-200');
            selected.classList.add('border-blue-400', 'bg-blue-50');
        } else if (this.value === 'published') {
            selected.classList.remove('border-gray-200');
            selected.classList.add('border-green-400', 'bg-green-50');
        }

        // Toggle date picker enabled/disabled based on selected status
        var dateInput = document.getElementById('published_at');
        var dateError = document.getElementById('publishedAtError');
        if (dateInput) {
            if (this.value === 'scheduled') {
                dateInput.disabled = false;
                dateInput.required = true;
                dateInput.classList.remove('opacity-50', 'bg-gray-100', 'cursor-not-allowed');
                if (dateError) dateError.classList.add('hidden');
            } else {
                dateInput.disabled = true;
                dateInput.required = false;
                dateInput.classList.add('opacity-50', 'bg-gray-100', 'cursor-not-allowed');
                if (dateError) dateError.classList.add('hidden');
            }
        }
    });
});

// Form validation: if scheduled is selected but date is empty, block submit
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            var selectedStatus = document.querySelector('.status-radio:checked');
            var dateInput = document.getElementById('published_at');
            var dateError = document.getElementById('publishedAtError');
            if (selectedStatus && selectedStatus.value === 'scheduled' && dateInput && !dateInput.value) {
                e.preventDefault();
                dateInput.classList.add('border-red-500');
                if (dateError) dateError.classList.remove('hidden');
                document.querySelector('.status-option').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    }
});
</script>
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
                    uploadUrl: window.location.protocol + '//' + window.location.host + '{{ route('admin.upload.image', [], false) }}',
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
