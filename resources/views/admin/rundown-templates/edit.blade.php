@extends('layouts.app')

@section('title', 'Edit Template: ' . $rundownTemplate->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-heading font-bold text-gray-900">Edit Template: {{ $rundownTemplate->name }}</h1>
    <a href="{{ route('admin.rundown-templates.show', $rundownTemplate) }}" class="admin-btn-sm admin-btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.rundown-templates.update', $rundownTemplate) }}" id="templateForm">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
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
                            <input type="text" name="name" value="{{ old('name', $rundownTemplate->name) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" value="{{ old('code', $rundownTemplate->code) }}" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="form-input">{{ old('description', $rundownTemplate->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Durasi Hari <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', $rundownTemplate->duration_days) }}" class="form-input" min="1" required>
                        </div>
                        <div>
                            <label class="form-label">Durasi Malam</label>
                            <input type="number" name="duration_nights" value="{{ old('duration_nights', $rundownTemplate->duration_nights) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-input">
                                <option value="1" {{ $rundownTemplate->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$rundownTemplate->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

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
                    <div id="itemsContainer" class="space-y-4">
                        @foreach($rundownTemplate->items->sortBy('sort_order') as $item)
                        <div class="item-entry border border-gray-200 rounded-lg p-4 bg-white">
                            <input type="hidden" name="items[{{ $item->id }}][sort_order]" value="{{ $loop->index + 1 }}" class="sort-order">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="moveItem(this, -1)" class="text-gray-400 hover:text-gray-600"><i class="fas fa-chevron-up"></i></button>
                                    <button type="button" onclick="moveItem(this, 1)" class="text-gray-400 hover:text-gray-600"><i class="fas fa-chevron-down"></i></button>
                                    <span class="text-sm font-medium text-gray-700">Item #<span class="item-number">{{ $loop->index + 1 }}</span></span>
                                </div>
                                <button type="button" onclick="this.closest('.item-entry').remove(); renumberItems();" class="text-red-500 hover:text-red-700 text-sm"><i class="fas fa-trash"></i> Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                                <div>
                                    <label class="form-label text-xs">Hari ke-</label>
                                    <input type="number" name="items[{{ $item->id }}][day_number]" value="{{ $item->day_number }}" class="form-input text-sm" min="1" required>
                                </div>
                                <div>
                                    <label class="form-label text-xs">Jam Mulai</label>
                                    <input type="time" name="items[{{ $item->id }}][start_time]" value="{{ $item->start_time }}" class="form-input text-sm">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Jam Selesai</label>
                                    <input type="time" name="items[{{ $item->id }}][end_time]" value="{{ $item->end_time }}" class="form-input text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label text-xs">Nama Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="items[{{ $item->id }}][activity_name]" value="{{ $item->activity_name }}" class="form-input text-sm" required>
                                </div>
                                <div>
                                    <label class="form-label text-xs">Lokasi</label>
                                    <input type="text" name="items[{{ $item->id }}][location]" value="{{ $item->location }}" class="form-input text-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="form-label text-xs">Penanggung Jawab</label>
                                    <input type="text" name="items[{{ $item->id }}][person_in_charge]" value="{{ $item->person_in_charge }}" class="form-input text-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="form-label text-xs">Deskripsi</label>
                                    <input type="text" name="items[{{ $item->id }}][description]" value="{{ $item->description }}" class="form-input text-sm">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card shadow-md">
                <div class="admin-card-body space-y-3">
                    <button type="submit" class="w-full admin-btn-sm admin-btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.rundown-templates.show', $rundownTemplate) }}" class="block w-full text-center admin-btn-sm admin-btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let itemCount = {{ $rundownTemplate->items->count() }};
let currentDay = 1;

function addItem(data = null) {
    itemCount++;
    const dayNum = data?.day_number || currentDay;
    const idx = itemCount;
    
    const html = `
        <div class="item-entry border border-gray-200 rounded-lg p-4 bg-white" data-index="${idx}">
            <input type="hidden" name="items[new_${idx}][sort_order]" value="${idx}" class="sort-order">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="moveItem(this, -1)" class="text-gray-400 hover:text-gray-600"><i class="fas fa-chevron-up"></i></button>
                    <button type="button" onclick="moveItem(this, 1)" class="text-gray-400 hover:text-gray-600"><i class="fas fa-chevron-down"></i></button>
                    <span class="text-sm font-medium text-gray-700">Item #<span class="item-number">${idx}</span></span>
                </div>
                <button type="button" onclick="this.closest('.item-entry').remove(); renumberItems();" class="text-red-500 hover:text-red-700 text-sm"><i class="fas fa-trash"></i> Hapus</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div>
                    <label class="form-label text-xs">Hari ke-</label>
                    <input type="number" name="items[new_${idx}][day_number]" value="${dayNum}" class="form-input text-sm" min="1" required>
                </div>
                <div>
                    <label class="form-label text-xs">Jam Mulai</label>
                    <input type="time" name="items[new_${idx}][start_time]" value="" class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label text-xs">Jam Selesai</label>
                    <input type="time" name="items[new_${idx}][end_time]" value="" class="form-input text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label text-xs">Nama Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="items[new_${idx}][activity_name]" value="" class="form-input text-sm" required>
                </div>
                <div>
                    <label class="form-label text-xs">Lokasi</label>
                    <input type="text" name="items[new_${idx}][location]" value="" class="form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="form-label text-xs">Penanggung Jawab</label>
                    <input type="text" name="items[new_${idx}][person_in_charge]" value="" class="form-input text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="form-label text-xs">Deskripsi</label>
                    <input type="text" name="items[new_${idx}][description]" value="" class="form-input text-sm">
                </div>
            </div>
        </div>
    `;
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);
    renumberItems();
}

function renumberItems() {
    document.querySelectorAll('.item-entry').forEach((item, index) => {
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
    if (direction < 0) parent.insertBefore(entry, items[newIdx]);
    else parent.insertBefore(entry, items[newIdx].nextSibling);
    renumberItems();
}
</script>
@endpush