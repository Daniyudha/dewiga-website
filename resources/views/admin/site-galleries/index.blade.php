@extends('layouts.app')

@section('title', 'Site Gallery - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Site Gallery') }}</h1>
        <p class="text-sm text-gray-500 mt-1">Manage gallery images displayed on the homepage</p>
    </div>
</div>

{{-- Upload Area --}}
<div class="admin-card mb-6">
    <div class="admin-card-body">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                <i class="fas fa-cloud-upload-alt text-lg"></i>
            </div>
            <div>
                <h3 class="font-heading font-semibold text-gray-800">Upload Images</h3>
                <p class="text-xs text-gray-500">Select multiple images to upload (JPG, PNG, WebP. Max 5MB each)</p>
            </div>
        </div>

        <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer">
            <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
            <p class="text-sm text-gray-600 font-medium">Drop images here or click to browse</p>
            <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, WebP</p>
            <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="hidden">
        </div>

        {{-- Upload Progress --}}
        <div id="uploadProgress" class="hidden mt-4">
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <span id="progressText" class="text-sm text-gray-600 shrink-0">0%</span>
            </div>
        </div>
    </div>
</div>

{{-- Gallery Grid --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="font-heading font-semibold text-gray-800">Exciting Images</h3>
        <span class="text-xs text-gray-500">{{ $galleries->count() }} images</span>
    </div>
    <div class="admin-card-body">
        @if($galleries->count() > 0)
        <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($galleries as $gallery)
            <div class="gallery-item group relative bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition" data-id="{{ $gallery->id }}">
                {{-- Image --}}
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title ?? 'Gallery' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>

                {{-- Overlay Actions --}}
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <form method="POST" action="{{ route('admin.site-galleries.destroy', [$gallery]) }}" class="inline">
                        @csrf @method('delete')
                        <button type="button" onclick="showDeleteModal(this.closest('form'))" class="w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition shadow">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>

                {{-- Title Input --}}
                <div class="p-3 bg-white">
                    <div class="flex items-center gap-2">
                        <input type="text" 
                               class="gallery-title-input w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400/30"
                               value="{{ $gallery->title }}"
                               placeholder="Add title..."
                               data-id="{{ $gallery->id }}"
                               data-url="{{ route('admin.site-galleries.update-title', [$gallery]) }}">
                        <button class="save-title-btn text-primary-600 hover:text-primary-700 shrink-0 px-1.5 py-1 rounded-lg hover:bg-primary-50 transition hidden" data-id="{{ $gallery->id }}">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-images text-4xl text-gray-200 block mb-3"></i>
            <p class="text-gray-500 text-sm">No images yet. Upload some above!</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    // Dropzone click
    dropzone.addEventListener('click', () => fileInput.click());

    // Drag events
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-primary-500', 'bg-primary-50/50');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-primary-500', 'bg-primary-50/50');
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-primary-500', 'bg-primary-50/50');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            uploadFiles();
        }
    });

    // File select
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) uploadFiles();
    });

    function uploadFiles() {
        const files = fileInput.files;
        if (files.length === 0) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        uploadProgress.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("admin.site-galleries.upload") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = pct + '%';
                progressText.textContent = pct + '%';
            }
        };

        xhr.onload = function() {
            setTimeout(() => {
                uploadProgress.classList.add('hidden');
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
                fileInput.value = '';
                location.reload();
            }, 500);
        };

        xhr.onerror = function() {
            uploadProgress.classList.add('hidden');
            alert('Upload failed. Please try again.');
        };

        xhr.send(formData);
    }

    // ---- Title Save ----
    document.querySelectorAll('.gallery-title-input').forEach(input => {
        const saveBtn = input.closest('.flex').querySelector('.save-title-btn');
        
        input.addEventListener('input', function() {
            saveBtn.classList.remove('hidden');
        });

        saveBtn.addEventListener('click', function() {
            const id = input.dataset.id;
            const url = input.dataset.url;
            const title = input.value;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ title: title })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    saveBtn.classList.add('hidden');
                    // Brief success feedback
                    input.style.borderColor = '#00a877';
                    setTimeout(() => { input.style.borderColor = ''; }, 1000);
                }
            })
            .catch(err => console.error('Error saving title:', err));
        });

        // Save on Enter key
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveBtn.click();
            }
        });
    });
});
</script>
@endpush
</write_to_file>