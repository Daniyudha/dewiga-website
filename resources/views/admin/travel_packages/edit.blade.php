@extends('layouts.app')

@section('title', 'Edit Travel Package - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Travel Package') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $travel_package->location }}</p>
        </div>
        <a href="{{ route('admin.travel_packages.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    {{-- Gallery Management Section --}}
    <div class="admin-card mb-6">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Gallery Images') }}</h2>
        </div>
        <div class="admin-card-body">
            @if($travel_package->galleries && $travel_package->galleries->count() > 0)
                <div class="overflow-x-auto mb-4">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($travel_package->galleries as $gallery)
                                <tr>
                                    <td class="font-medium">{{ $gallery->name }}</td>
                                    <td>
                                        @if($gallery->images && file_exists(public_path('storage/' . $gallery->images)))
                                            <a href="{{ asset('storage/' . $gallery->images) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $gallery->images) }}" alt="{{ $gallery->name }}" class="admin-thumb">
                                            </a>
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.travel_packages.galleries.edit', [$travel_package, $gallery]) }}"
                                               class="admin-btn-warning admin-btn-sm">
                                                <i class="fas fa-edit"></i>
                                                {{ __('Edit') }}
                                            </a>
                                            <form method="POST" action="{{ route('admin.travel_packages.galleries.destroy', [$travel_package, $gallery]) }}" class="inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" onclick="showDeleteModal(this.closest('form'))" class="admin-btn-danger admin-btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                    {{ __('Delete') }}
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
            <form method="post" action="{{ route('admin.travel_packages.galleries.store', [$travel_package]) }}" enctype="multipart/form-data" class="border-t border-gray-100 pt-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label for="name" class="admin-form-label">{{ __('Image Name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="admin-form-input" placeholder="e.g. Sunset at Kuta">
                        @error('name')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="images" class="admin-form-label">{{ __('Upload Image') }}</label>
                        <input type="file" id="images" name="images"
                               class="admin-form-input @error('images') error @enderror" required>
                        @error('images')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                        <div id="images-preview" class="mt-2 hidden">
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

    {{-- Edit Package Form --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('Package Details') }}</h2>
        </div>
        <div class="admin-card-body">
            <form method="post" action="{{ route('admin.travel_packages.update', [$travel_package]) }}" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf
                @method('put')

                {{-- Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="admin-form-group">
                        <label for="type_id" class="admin-form-label">{{ __('Type') }} (Indonesia) <span class="text-red-500">*</span></label>
                        <input type="text" id="type_id" name="type_id" value="{{ old('type_id', $travel_package->type_id) }}"
                               class="admin-form-input @error('type_id') error @enderror"
                               placeholder="e.g. Budaya" required>
                        @error('type_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="type_en" class="admin-form-label">{{ __('Type') }} (English) <span class="text-red-500">*</span></label>
                        <input type="text" id="type_en" name="type_en" value="{{ old('type_en', $travel_package->type_en) }}"
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
                        <input type="text" id="location_id" name="location_id" value="{{ old('location_id', $travel_package->location_id) }}"
                               class="admin-form-input @error('location_id') error @enderror" placeholder="e.g. Live In" required>
                        @error('location_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="location_en" class="admin-form-label">{{ __('Title') }} (English) <span class="text-red-500">*</span></label>
                        <input type="text" id="location_en" name="location_en" value="{{ old('location_en', $travel_package->location_en) }}"
                               class="admin-form-input @error('location_en') error @enderror" placeholder="e.g. Live In" required>
                        @error('location_en')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Price --}}
                <div class="admin-form-group max-w-sm">
                    <label for="price" class="admin-form-label">{{ __('Price') }} <span class="text-red-500">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price', $travel_package->price) }}"
                           class="admin-form-input @error('price') error @enderror" required>
                    @error('price')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (Indonesia) --}}
                <div class="admin-form-group">
                    <label for="description_id" class="admin-form-label">{{ __('Description') }} (Indonesia) <span class="text-red-500">*</span></label>
                    <textarea id="description_id" name="description_id" class="admin-form-textarea @error('description_id') error @enderror" required>{{ old('description_id', $travel_package->description_id) }}</textarea>
                    @error('description_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description (English) --}}
                <div class="admin-form-group">
                    <label for="description_en" class="admin-form-label">{{ __('Description') }} (English) <span class="text-red-500">*</span></label>
                    <textarea id="description_en" name="description_en" class="admin-form-textarea @error('description_en') error @enderror" required>{{ old('description_en', $travel_package->description_en) }}</textarea>
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
// Image preview for gallery upload
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('images-preview');
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
