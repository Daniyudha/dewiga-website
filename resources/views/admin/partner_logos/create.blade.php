@extends('layouts.app')

@section('title', 'Add Partner Logo - Admin Dewiga')

@push('styles')
<style>
    #dropzone {
        transition: all 0.2s ease;
    }
    #dropzone.drag-over {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.partner_logos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-arrow-left"></i> Back to Partner Logos
        </a>
    </div>

    {{-- Form Card --}}
    <div class="max-w-2xl">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-semibold text-gray-800">Partner Logo</h3>
                        <p class="text-xs text-gray-500">Upload a new client/partner logo. Drag & drop or click to browse.</p>
                    </div>
                </div>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.partner_logos.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Name --}}
                    <div class="admin-form-group mb-5">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="admin-form-input @error('name') error @enderror"
                               placeholder="e.g. Ministry of Tourism" required>
                        @error('name')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Dropzone --}}
                    <div class="admin-form-group mb-5">
                        <label class="admin-form-label">{{ __('Logo Image') }} <span class="text-red-500">*</span></label>

                        {{-- Dropzone --}}
                        <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-600 font-medium">Drop image here or click to browse</p>
                            <p class="text-xs text-gray-400 mt-1">Supported: PNG, SVG. Max 2MB.</p>
                            <input type="file" id="image" name="image" accept="image/*" class="hidden">
                        </div>

                        {{-- Preview after selecting file --}}
                        <div id="previewArea" class="hidden mt-4">
                            <div class="relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <div class="overflow-hidden flex items-center justify-center">
                                    <img id="previewImage" src="" alt="Preview" class="w-full h-full object-contain">
                                </div>
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                                    <button type="button" id="removeFileBtn"
                                            class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <p id="fileSelectedText" class="text-sm text-gray-500 mt-2">1 image selected</p>
                        </div>

                        @error('image')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- URL --}}
                    <div class="admin-form-group mb-5">
                        <label for="url" class="admin-form-label">{{ __('Website URL') }}</label>
                        <input type="url" id="url" name="url" value="{{ old('url') }}"
                               class="admin-form-input @error('url') error @enderror"
                               placeholder="https://example.com">
                        @error('url')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        {{-- Order --}}
                        <div class="admin-form-group">
                            <label for="order" class="admin-form-label">{{ __('Order') }}</label>
                            <input type="number" id="order" name="order" value="{{ old('order') }}"
                                   class="admin-form-input @error('order') error @enderror"
                                   placeholder="Auto" min="0">
                            @error('order')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active --}}
                        <div class="admin-form-group">
                            <label class="admin-form-label">{{ __('Status') }}</label>
                            <div class="flex items-center gap-3 mt-2">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" checked
                                           class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="admin-btn-primary">
                            <i class="fas fa-save"></i>
                            {{ __('Save Logo') }}
                        </button>
                        <a href="{{ route('admin.partner_logos.index') }}" class="admin-btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('image');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const removeFileBtn = document.getElementById('removeFileBtn');

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            dropzone.classList.add('hidden');
            previewArea.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetDropzone() {
        fileInput.value = '';
        previewArea.classList.add('hidden');
        dropzone.classList.remove('hidden');
    }

    // Click to browse
    dropzone.addEventListener('click', () => fileInput.click());

    // Drag over
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('drag-over');
    });

    // Drag leave
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('drag-over');
    });

    // Drop
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            showPreview(fileInput.files[0]);
        }
    });

    // Remove file
    removeFileBtn.addEventListener('click', resetDropzone);
});
</script>
@endpush