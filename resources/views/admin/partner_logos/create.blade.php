@extends('layouts.app')

@section('title', 'Add Partner Logo - Admin Dewiga')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.partner_logos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-arrow-left"></i> Back to Partner Logos
        </a>
    </div>

    <form method="POST" action="{{ route('admin.partner_logos.store') }}" enctype="multipart/form-data" id="partnerLogoForm">
        @csrf
        
        <div class="max-w-2xl">
            {{-- Image Upload --}}
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Logo Image <span class="text-red-500">*</span>
                    </label>

                    {{-- Dropzone --}}
                    <div id="dropzoneSingle" class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-600 font-medium">Drop image here or click to browse</p>
                        <p class="text-xs text-gray-400 mt-1">Supported: PNG, SVG. Max 2MB.</p>
                        <input type="file" id="fileInputSingle" name="image" accept="image/*" class="hidden">
                    </div>

                    {{-- Preview after selecting file --}}
                    <div id="previewAreaSingle" class="hidden">
                        <div class="relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img id="previewImageSingle" src="" alt="Preview" class="w-full h-full object-contain">
                            </div>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                                <button type="button" id="removeFileSingleBtn" 
                                        class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <p id="fileSelectedTextSingle" class="text-sm text-gray-500 mt-2">1 image selected</p>
                    </div>

                    {{-- Upload Progress --}}
                    <div id="uploadProgressSingle" class="hidden mt-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div id="progressBarSingle" class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="progressTextSingle" class="text-sm text-gray-600 shrink-0">0%</span>
                        </div>
                    </div>

                    @error('image')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Name --}}
            <div class="admin-card mt-6">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">Partner Information</h3>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-group mb-5">
                        <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="admin-form-input @error('name') error @enderror"
                               placeholder="e.g. Ministry of Tourism" required>
                        @error('name')
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
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="admin-btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    {{ __('Save Logo') }}
                </button>
                <a href="{{ route('admin.partner_logos.index') }}" class="admin-btn-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzoneSingle = document.getElementById('dropzoneSingle');
    const fileInputSingle = document.getElementById('fileInputSingle');
    const previewAreaSingle = document.getElementById('previewAreaSingle');
    const previewImageSingle = document.getElementById('previewImageSingle');
    const removeFileSingleBtn = document.getElementById('removeFileSingleBtn');
    const uploadProgressSingle = document.getElementById('uploadProgressSingle');
    const progressBarSingle = document.getElementById('progressBarSingle');
    const progressTextSingle = document.getElementById('progressTextSingle');
    const fileSelectedTextSingle = document.getElementById('fileSelectedTextSingle');
    const form = document.getElementById('partnerLogoForm');

    function showPreviewSingle(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImageSingle.src = e.target.result;
            dropzoneSingle.classList.add('hidden');
            previewAreaSingle.classList.remove('hidden');
            fileSelectedTextSingle.textContent = '1 image selected';
        };
        reader.readAsDataURL(file);
    }

    function resetDropzoneSingle() {
        fileInputSingle.value = '';
        previewAreaSingle.classList.add('hidden');
        dropzoneSingle.classList.remove('hidden');
        uploadProgressSingle.classList.add('hidden');
        progressBarSingle.style.width = '0%';
        progressTextSingle.textContent = '0%';
    }

    dropzoneSingle.addEventListener('click', () => fileInputSingle.click());
    dropzoneSingle.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzoneSingle.classList.add('border-primary-500', 'bg-primary-50/50');
    });
    dropzoneSingle.addEventListener('dragleave', () => {
        dropzoneSingle.classList.remove('border-primary-500', 'bg-primary-50/50');
    });
    dropzoneSingle.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzoneSingle.classList.remove('border-primary-500', 'bg-primary-50/50');
        if (e.dataTransfer.files.length > 0) showPreviewSingle(e.dataTransfer.files[0]);
    });
    fileInputSingle.addEventListener('change', () => {
        if (fileInputSingle.files.length > 0) showPreviewSingle(fileInputSingle.files[0]);
    });
    removeFileSingleBtn.addEventListener('click', resetDropzoneSingle);
});
</script>
@endpush