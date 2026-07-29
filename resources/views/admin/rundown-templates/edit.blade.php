@extends('layouts.app')

@section('title', 'Edit Template: ' . $rundownTemplate->name)

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Edit Template</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $rundownTemplate->name }}</p>
    </div>
    <a href="{{ route('admin.rundown-templates.show', $rundownTemplate) }}" class="admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.rundown-templates.update', $rundownTemplate) }}" id="templateForm">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i> Informasi Template
                    </h3>
                </div>
                <div class="admin-card-body space-y-4">
                    <div>
                        <label class="admin-form-label">Nama Template <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $rundownTemplate->name) }}" class="admin-form-input" required>
                    </div>
                    <div>
                        <label class="admin-form-label">Kode</label>
                        <input type="text" name="code" value="{{ old('code', $rundownTemplate->code) }}" class="admin-form-input">
                    </div>
                    <div>
                        <label class="admin-form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="admin-form-input">{{ old('description', $rundownTemplate->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="admin-form-label">Durasi Hari <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', $rundownTemplate->duration_days) }}" class="admin-form-input" min="1" required>
                        </div>
                        <div>
                            <label class="admin-form-label">Durasi Malam</label>
                            <input type="number" name="duration_nights" value="{{ old('duration_nights', $rundownTemplate->duration_nights) }}" class="admin-form-input" min="0">
                        </div>
                        <div>
                            <label class="admin-form-label">Status</label>
                            <select name="is_active" class="admin-form-input">
                                <option value="1" {{ $rundownTemplate->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$rundownTemplate->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Template Items --}}
            <div class="admin-card">
                <div class="admin-card-header flex items-center justify-between">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-list text-primary-600 mr-2"></i> Daftar Kegiatan
                    </h3>
                    <button type="button" onclick="addItem()" class="admin-btn-primary admin-btn-sm">
                        <i class="fas fa-plus"></i> Tambah Kegiatan
                    </button>
                </div>
                <div class="admin-card-body">
                    <p class="text-sm text-gray-500 mb-4">Tambahkan kegiatan untuk setiap hari. Urutan dapat diatur dengan tombol naik/turun.</p>
                    <div id="itemsContainer" class="space-y-4">
                        @foreach($rundownTemplate->items as $item)
                            @include('admin.rundown-templates._item', ['item' => $item, 'index' => 'existing_' . $item->id])
                        @endforeach
                    </div>
                </div>
                <div class="admin-card-footer flex justify-end gap-3">
                    <a href="{{ route('admin.rundown-templates.show', $rundownTemplate) }}" class="admin-btn-secondary">Batal</a>
                    <button type="submit" class="admin-btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">Petunjuk</h3>
                </div>
                <div class="admin-card-body text-sm text-gray-600 space-y-3">
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 text-xs flex items-center justify-center shrink-0 mt-0.5">1</span>
                        <span>Edit informasi template di kolom kiri</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 text-xs flex items-center justify-center shrink-0 mt-0.5">2</span>
                        <span>Klik "Tambah Kegiatan" untuk menambahkan item baru</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 text-xs flex items-center justify-center shrink-0 mt-0.5">3</span>
                        <span>Atur urutan kegiatan dengan tombol ↑ ↓</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 text-xs flex items-center justify-center shrink-0 mt-0.5">4</span>
                        <span>Klik "Simpan Perubahan" untuk menyimpan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function uid() {
    return 'item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
}

let existingCount = {{ $rundownTemplate->items->count() }};

function addItem(data = null) {
    existingCount++;
    const dayNum = data?.day_number || 1;
    const idx = uid();
    
    const html = `
        <div class="item-entry border border-gray-200 rounded-xl p-4 bg-white shadow-sm" data-index="${idx}">
            <input type="hidden" name="items[${idx}][sort_order]" value="${existingCount}" class="sort-order">
            <input type="hidden" name="items[${idx}][id]" value="">
            <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="moveItem(this, -1)" class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:border-gray-300 transition" title="Naik">
                        <i class="fas fa-chevron-up text-xs"></i>
                    </button>
                    <button type="button" onclick="moveItem(this, 1)" class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:border-gray-300 transition" title="Turun">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">#<span class="item-number">${idx}</span></span>
                </div>
                <button type="button" onclick="this.closest('.item-entry').remove(); renumberItems();" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-trash text-xs"></i> Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Hari ke-</label>
                    <input type="number" name="items[${idx}][day_number]" value="${dayNum}" class="admin-form-input text-sm" min="1" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai</label>
                    <input type="time" name="items[${idx}][start_time]" value="${data?.start_time || ''}" class="admin-form-input text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jam Selesai</label>
                    <input type="time" name="items[${idx}][end_time]" value="${data?.end_time || ''}" class="admin-form-input text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="items[${idx}][activity_name]" value="${data?.activity_name || ''}" class="admin-form-input text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Lokasi</label>
                    <input type="text" name="items[${idx}][location]" value="${data?.location || ''}" class="admin-form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Penanggung Jawab</label>
                    <input type="text" name="items[${idx}][person_in_charge]" value="${data?.person_in_charge || ''}" class="admin-form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                    <input type="text" name="items[${idx}][description]" value="${data?.description || ''}" class="admin-form-input text-sm">
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
</script>
@endpush