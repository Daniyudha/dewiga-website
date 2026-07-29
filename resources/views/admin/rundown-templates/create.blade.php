@extends('layouts.app')

@section('title', 'Tambah Template Rundown')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-heading font-bold text-gray-900">Tambah Template Rundown</h1>
    <a href="{{ route('admin.rundown-templates.index') }}" class="admin-btn-sm admin-btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.rundown-templates.store') }}" id="templateForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Template Info --}}
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i> Informasi Template
                    </h3>
                </div>
                <div class="admin-card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Template <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') is-invalid @enderror" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" value="{{ old('code') }}" class="form-input" placeholder="Opsional">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="form-input">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Durasi Hari <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', 1) }}" class="form-input" min="1" required>
                        </div>
                        <div>
                            <label class="form-label">Durasi Malam</label>
                            <input type="number" name="duration_nights" value="{{ old('duration_nights', 0) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-input">
                                <option value="1" {{ old('is_active') !== '0' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Template Items --}}
            <div class="admin-card shadow-md">
                <div class="admin-card-header flex items-center justify-between">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-list text-primary-600 mr-2"></i> Daftar Kegiatan
                    </h3>
                    <button type="button" onclick="addItem()" class="admin-btn-sm admin-btn-primary">
                        <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
                    </button>
                </div>
                <div class="admin-card-body">
                    <p class="text-sm text-gray-500 mb-4">Tambahkan kegiatan untuk setiap hari. Urutan dapat diatur dengan tombol naik/turun.</p>
                    <div id="itemsContainer" class="space-y-4">
                        {{-- Items will be added via JavaScript --}}
                    </div>
                    @error('items') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="admin-card shadow-md">
                <div class="admin-card-body space-y-3">
                    <button type="submit" class="w-full admin-btn-sm admin-btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Template
                    </button>
                    <a href="{{ route('admin.rundown-templates.index') }}" class="block w-full text-center admin-btn-sm admin-btn-secondary">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let itemCount = 0;
let currentDay = 1;

function addItem(data = null) {
    itemCount++;
    const dayNum = data?.day_number || currentDay;
    const idx = itemCount;
    
    const html = `
        <div class="item-entry border border-gray-200 rounded-lg p-4 bg-white" data-index="${idx}">
            <input type="hidden" name="items[${idx}][sort_order]" value="${idx}" class="sort-order">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="moveItem(this, -1)" class="text-gray-400 hover:text-gray-600" title="Naik">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveItem(this, 1)" class="text-gray-400 hover:text-gray-600" title="Turun">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Item #<span class="item-number">${idx}</span></span>
                </div>
                <button type="button" onclick="this.closest('.item-entry').remove(); renumberItems();" class="text-red-500 hover:text-red-700 text-sm">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div>
                    <label class="form-label text-xs">Hari ke-</label>
                    <input type="number" name="items[${idx}][day_number]" value="${dayNum}" class="form-input text-sm" min="1" required>
                </div>
                <div>
                    <label class="form-label text-xs">Jam Mulai</label>
                    <input type="time" name="items[${idx}][start_time]" value="${data?.start_time || ''}" class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label text-xs">Jam Selesai</label>
                    <input type="time" name="items[${idx}][end_time]" value="${data?.end_time || ''}" class="form-input text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label text-xs">Nama Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="items[${idx}][activity_name]" value="${data?.activity_name || ''}" class="form-input text-sm" required>
                </div>
                <div>
                    <label class="form-label text-xs">Lokasi</label>
                    <input type="text" name="items[${idx}][location]" value="${data?.location || ''}" class="form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="form-label text-xs">Penanggung Jawab</label>
                    <input type="text" name="items[${idx}][person_in_charge]" value="${data?.person_in_charge || ''}" class="form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="form-label text-xs">Deskripsi</label>
                    <input type="text" name="items[${idx}][description]" value="${data?.description || ''}" class="form-input text-sm">
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);
    renumberItems();
}

function renumberItems() {
    const items = document.querySelectorAll('.item-entry');
    items.forEach((item, index) => {
        item.querySelector('.item-number').textContent = index + 1;
        item.querySelector('.sort-order').value = index + 1;
    });
}

function moveItem(btn, direction) {
    const entry = btn.closest('.item-entry');
    const parent = entry.parentElement;
    const items = [...parent.querySelectorAll('.item-entry')];
    const currentIdx = items.indexOf(entry);
    const newIdx = currentIdx + direction;
    
    if (newIdx < 0 || newIdx >= items.length) return;
    
    if (direction < 0) {
        parent.insertBefore(entry, items[newIdx]);
    } else {
        parent.insertBefore(entry, items[newIdx].nextSibling);
    }
    renumberItems();
}

// Add a default first item
addItem();
</script>
@endpush