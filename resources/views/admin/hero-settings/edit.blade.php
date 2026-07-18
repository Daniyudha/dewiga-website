@extends('layouts.app')

@section('title', 'Edit Hero - ' . ucfirst($heroSetting->page) . ' - Admin Dewiga')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.hero-settings.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fas fa-arrow-left"></i> Back to Hero Settings
    </a>
</div>

<div class="max-w-3xl">
    {{-- Slides / Images --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-800 capitalize">{{ $heroSetting->page }} Page Hero</h3>
                    <p class="text-xs text-gray-500">
                        @if($heroSetting->page === 'home')
                            Upload up to 5 images for the rotating hero slider. Drag & drop or click to browse.
                        @else
                            Upload 1 background image for this page hero. Drag & drop or click to browse.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="admin-card-body">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Image <span class="text-red-500">*</span>
            </label>

            {{-- For non-homepage: show existing image or dropzone --}}
            @if($heroSetting->page !== 'home')
                {{-- Existing Image Preview (if any) --}}
                @if($heroSetting->slides->count() > 0)
                    @php $slide = $heroSetting->slides->first(); @endphp
                    <div id="existingImageWrapper">
                        <div class="relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            {{-- Overlay Hapus --}}
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-200 flex items-center justify-center">
                                <button type="button" class="delete-slide-btn w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100"
                                        data-url="{{ route('admin.hero-settings.slides.destroy', [$heroSetting, $slide]) }}">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">1 image (hover for delete)</p>
                    </div>
                @endif

                {{-- Dropzone (shown when no existing image) --}}
                <div id="singleUploadArea" class="{{ $heroSetting->slides->count() > 0 ? 'hidden' : '' }}">
                    <div id="dropzoneSingle" class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-600 font-medium">Drop image here or click to browse</p>
                        <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, WebP. Max 5MB.</p>
                        <input type="file" id="fileInputSingle" name="image" accept="image/*" class="hidden">
                    </div>

                    {{-- Preview after selecting file --}}
                    <div id="previewAreaSingle" class="hidden">
                        <div class="relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img id="previewImageSingle" src="" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                                <button type="button" id="removeFileSingleBtn" 
                                        class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <p id="fileSelectedTextSingle" class="text-sm text-gray-500 mt-2">1 image selected</p>
                        <button type="button" id="uploadBtnSingle" 
                                class="mt-3 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition inline-flex items-center gap-2">
                            <i class="fas fa-upload"></i> Save Image
                        </button>
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
                </div>
            @else
                {{-- For homepage: multi-slide grid --}}
                <div id="homeUploadArea">
                    <div id="dropzoneHome" class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer mb-4">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-600 font-medium">Drop image here or click to browse</p>
                        <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, WebP. Max 5MB.</p>
                        <input type="file" id="fileInputHome" name="image" accept="image/*" class="hidden">
                    </div>

                    <div id="previewAreaHome" class="hidden mb-4">
                        <div class="relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img id="previewImageHome" src="" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                                <button type="button" id="removeFileHomeBtn" 
                                        class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <p id="fileSelectedTextHome" class="text-sm text-gray-500 mt-2">1 image selected</p>
                        <button type="button" id="uploadBtnHome" 
                                class="mt-3 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition inline-flex items-center gap-2">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </div>

                    <div id="uploadProgressHome" class="hidden mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div id="progressBarHome" class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="progressTextHome" class="text-sm text-gray-600 shrink-0">0%</span>
                        </div>
                    </div>

                    {{-- Home Slides Grid --}}
                    @if($heroSetting->slides->count() > 0)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-arrows-alt mr-1"></i> Drag & drop to reorder slides (auto-save)
                        </p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="slideGrid">
                        @foreach($heroSetting->slides as $slide)
                        <div class="slide-item relative group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition cursor-grab active:cursor-grabbing" data-id="{{ $slide->id }}">
                            <div class="absolute top-2 left-2 z-10">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-black/50 text-white text-xs font-bold rounded-full order-badge">{{ $slide->order }}</span>
                            </div>
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 pointer-events-none">
                            </div>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center gap-2">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <button type="button" class="delete-slide-btn w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow-lg"
                                            data-url="{{ route('admin.hero-settings.slides.destroy', [$heroSetting, $slide]) }}">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-2 bg-white flex items-center justify-between">
                                <p class="text-[10px] text-gray-500 truncate">{{ $slide->alt_text }}</p>
                                <i class="fas fa-grip-vertical text-gray-300 text-xs"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl">
                        <i class="fas fa-image text-3xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">No images yet. Upload above!</p>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- SortableJS for drag & drop reordering --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

{{-- Custom Confirm Modal --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6 text-center">
        <div class="w-14 h-14 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
        </div>
        <h3 class="text-lg font-heading font-semibold text-gray-800 mb-2">Delete Image</h3>
        <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this image? This action cannot be undone.</p>
        <div class="flex items-center gap-3 justify-center">
            <button id="confirmCancelBtn" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
            <button id="confirmDeleteBtn" class="px-5 py-2.5 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">Delete</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isHome = '{{ $heroSetting->page }}' === 'home';
    let pendingDeleteUrl = null;

    // Custom confirm modal
    const confirmModal = document.getElementById('confirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');

    function showConfirmModal(url) {
        pendingDeleteUrl = url;
        confirmModal.classList.remove('hidden');
    }

    function hideConfirmModal() {
        pendingDeleteUrl = null;
        confirmModal.classList.add('hidden');
    }

    confirmDeleteBtn.addEventListener('click', function() {
        if (pendingDeleteUrl) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = pendingDeleteUrl;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
        hideConfirmModal();
    });

    confirmCancelBtn.addEventListener('click', hideConfirmModal);
    confirmModal.addEventListener('click', function(e) {
        if (e.target === confirmModal) hideConfirmModal();
    });

    // Handle all delete buttons
    document.querySelectorAll('.delete-slide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            showConfirmModal(this.dataset.url);
        });
    });

    if (!isHome) {
        // === SINGLE IMAGE (non-homepage) ===
        const singleUploadArea = document.getElementById('singleUploadArea');
        const dropzoneSingle = document.getElementById('dropzoneSingle');
        const fileInputSingle = document.getElementById('fileInputSingle');
        const previewAreaSingle = document.getElementById('previewAreaSingle');
        const previewImageSingle = document.getElementById('previewImageSingle');
        const removeFileSingleBtn = document.getElementById('removeFileSingleBtn');
        const uploadBtnSingle = document.getElementById('uploadBtnSingle');
        const uploadProgressSingle = document.getElementById('uploadProgressSingle');
        const progressBarSingle = document.getElementById('progressBarSingle');
        const progressTextSingle = document.getElementById('progressTextSingle');
        const fileSelectedTextSingle = document.getElementById('fileSelectedTextSingle');

        let selectedFileSingle = null;

        function showPreviewSingle(file) {
            selectedFileSingle = file;
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
            selectedFileSingle = null;
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

        uploadBtnSingle.addEventListener('click', function() {
            if (!selectedFileSingle) return;
            const formData = new FormData();
            formData.append('image', selectedFileSingle);
            formData.append('alt_text', '{{ $heroSetting->page }} hero');

            uploadProgressSingle.classList.remove('hidden');
            progressBarSingle.style.width = '0%';
            progressTextSingle.textContent = '0%';
            uploadBtnSingle.disabled = true;
            uploadBtnSingle.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', window.location.protocol + '//' + window.location.host + '{{ route("admin.hero-settings.slides.upload", [$heroSetting], false) }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const pct = Math.round((e.loaded / e.total) * 100);
                    progressBarSingle.style.width = pct + '%';
                    progressTextSingle.textContent = pct + '%';
                }
            };
            xhr.onload = function() { setTimeout(() => location.reload(), 500); };
            xhr.onerror = function() {
                uploadProgressSingle.classList.add('hidden');
                uploadBtnSingle.disabled = false;
                uploadBtnSingle.innerHTML = '<i class="fas fa-upload"></i> Save Image';
                alert('Upload failed. Please try again.');
            };
            xhr.send(formData);
        });
    } else {
        // === HOME PAGE (multi-slide) ===
        // Initialize SortableJS for drag & drop reordering with auto-save
        const slideGrid = document.getElementById('slideGrid');
        let isSaving = false;

        function updateOrderBadges() {
            const items = document.querySelectorAll('.slide-item');
            items.forEach((item, index) => {
                const badge = item.querySelector('.order-badge');
                if (badge) badge.textContent = index + 1;
            });
        }

        function collectOrderData() {
            const items = document.querySelectorAll('.slide-item');
            const slides = [];
            items.forEach((item, index) => {
                slides.push({
                    id: parseInt(item.dataset.id),
                    order: index + 1
                });
            });
            return slides;
        }

        function saveOrder() {
            if (isSaving) return;
            isSaving = true;

            const slides = collectOrderData();

            fetch(window.location.protocol + '//' + window.location.host + '{{ route("admin.hero-settings.slides.reorder", [$heroSetting], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ slides: slides })
            })
            .then(response => response.json())
            .then(data => {
                isSaving = false;
                if (data.success) {
                    showToast('Order saved!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                isSaving = false;
                showToast('Failed to save order.', 'error');
            });
        }


        if (slideGrid) {
            new Sortable(slideGrid, {
                animation: 200,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                handle: '.slide-item',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onStart: function() {
                    document.querySelectorAll('.slide-item').forEach(el => {
                        el.style.transition = 'transform 0.2s ease';
                    });
                },
                onEnd: function() {
                    // Update order badges
                    updateOrderBadges();
                    // Auto-save after drag
                    saveOrder();
                }
            });
        }

        const dropzoneHome = document.getElementById('dropzoneHome');
        const fileInputHome = document.getElementById('fileInputHome');
        const previewAreaHome = document.getElementById('previewAreaHome');
        const previewImageHome = document.getElementById('previewImageHome');
        const removeFileHomeBtn = document.getElementById('removeFileHomeBtn');
        const uploadBtnHome = document.getElementById('uploadBtnHome');
        const uploadProgressHome = document.getElementById('uploadProgressHome');
        const progressBarHome = document.getElementById('progressBarHome');
        const progressTextHome = document.getElementById('progressTextHome');
        const fileSelectedTextHome = document.getElementById('fileSelectedTextHome');

        let selectedFileHome = null;

        function showPreviewHome(file) {
            selectedFileHome = file;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImageHome.src = e.target.result;
                dropzoneHome.classList.add('hidden');
                previewAreaHome.classList.remove('hidden');
                fileSelectedTextHome.textContent = '1 image selected';
            };
            reader.readAsDataURL(file);
        }

        function resetDropzoneHome() {
            selectedFileHome = null;
            fileInputHome.value = '';
            previewAreaHome.classList.add('hidden');
            dropzoneHome.classList.remove('hidden');
            uploadProgressHome.classList.add('hidden');
            progressBarHome.style.width = '0%';
            progressTextHome.textContent = '0%';
        }

        dropzoneHome.addEventListener('click', () => fileInputHome.click());
        dropzoneHome.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzoneHome.classList.add('border-primary-500', 'bg-primary-50/50');
        });
        dropzoneHome.addEventListener('dragleave', () => {
            dropzoneHome.classList.remove('border-primary-500', 'bg-primary-50/50');
        });
        dropzoneHome.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzoneHome.classList.remove('border-primary-500', 'bg-primary-50/50');
            if (e.dataTransfer.files.length > 0) showPreviewHome(e.dataTransfer.files[0]);
        });
        fileInputHome.addEventListener('change', () => {
            if (fileInputHome.files.length > 0) showPreviewHome(fileInputHome.files[0]);
        });
        removeFileHomeBtn.addEventListener('click', resetDropzoneHome);

        uploadBtnHome.addEventListener('click', function() {
            if (!selectedFileHome) return;
            const formData = new FormData();
            formData.append('image', selectedFileHome);
            formData.append('alt_text', '{{ $heroSetting->page }} hero');

            uploadProgressHome.classList.remove('hidden');
            progressBarHome.style.width = '0%';
            progressTextHome.textContent = '0%';
            uploadBtnHome.disabled = true;
            uploadBtnHome.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', window.location.protocol + '//' + window.location.host + '{{ route("admin.hero-settings.slides.upload", [$heroSetting], false) }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const pct = Math.round((e.loaded / e.total) * 100);
                    progressBarHome.style.width = pct + '%';
                    progressTextHome.textContent = pct + '%';
                }
            };
            xhr.onload = function() { setTimeout(() => location.reload(), 500); };
            xhr.onerror = function() {
                uploadProgressHome.classList.add('hidden');
                uploadBtnHome.disabled = false;
                uploadBtnHome.innerHTML = '<i class="fas fa-upload"></i> Upload Image';
                alert('Upload failed. Please try again.');
            };
            xhr.send(formData);
        });
    }
});
</script>
@endpush
