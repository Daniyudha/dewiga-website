@extends('layouts.app')

@section('title', 'Edit Template: ' . $rundownTemplate->name)

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #eff6ff !important;
        border-style: dashed !important;
    }
    .sortable-drag {
        opacity: 0.9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .drag-handle {
        cursor: grab;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .day-items:empty::after {
        content: "Seret kegiatan ke sini";
        display: block;
        text-align: center;
        font-size: 0.75rem;
        color: #9ca3af;
        padding: 0.75rem;
        border: 1px dashed #e5e7eb;
        border-radius: 0.5rem;
    }
</style>
@endpush

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
                    <button type="button" onclick="addDay()" class="admin-btn-secondary admin-btn-sm">
                        <i class="fas fa-plus"></i> Tambah Hari
                    </button>
                </div>
                <div class="admin-card-body">
                    <p class="text-sm text-gray-500 mb-4">Kegiatan dikelompokkan per hari. Seret (drag) kartu kegiatan untuk mengatur urutan atau memindahkannya ke hari lain.</p>
                    <div id="daysContainer" class="space-y-4">
                        @php $grouped = $rundownTemplate->items->groupBy('day_number'); @endphp
                        @forelse($grouped as $day => $items)
                        <div class="day-card border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm" data-day="{{ $day }}">
                            <div class="flex items-center justify-between bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-semibold text-gray-800 text-sm">
                                    <i class="fas fa-calendar-day text-primary-600 mr-2"></i>HARI KE-<span class="day-label">{{ $day }}</span>
                                </h4>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="addItem({{ $day }})" class="admin-btn-primary admin-btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Kegiatan
                                    </button>
                                    <button type="button" onclick="deleteDay(this)" class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-300 transition" title="Hapus hari">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4 space-y-3 day-items min-h-[56px]">
                                @foreach($items as $item)
                                    @include('admin.rundown-templates._item', ['item' => $item, 'index' => 'existing_' . $item->id, 'day' => $day, 'number' => $loop->iteration])
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-gray-400 text-center py-6" id="emptyState">Belum ada kegiatan. Klik "Tambah Hari" untuk mulai.</div>
                        @endforelse
                    </div>
                    @error('items') <p class="admin-form-error mt-2">{{ $message }}</p> @enderror
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
                        <span>Klik "Tambah Hari" atau "Tambah Kegiatan" untuk menambah hari/kegiatan</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 text-xs flex items-center justify-center shrink-0 mt-0.5">3</span>
                        <span>Seret (drag) kegiatan untuk mengatur urutan atau memindahkannya ke hari lain</span>
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let itemCounter = 0;
const sortables = new Map();

function uid() {
    itemCounter++;
    return 'item_' + itemCounter + '_' + Math.random().toString(36).substr(2, 5);
}

function dayCardTemplate(day) {
    return `
    <div class="day-card border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm" data-day="${day}">
        <div class="flex items-center justify-between bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h4 class="font-semibold text-gray-800 text-sm">
                <i class="fas fa-calendar-day text-primary-600 mr-2"></i>HARI KE-<span class="day-label">${day}</span>
            </h4>
            <div class="flex items-center gap-2">
                <button type="button" onclick="addItem(${day})" class="admin-btn-primary admin-btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kegiatan
                </button>
                <button type="button" onclick="deleteDay(this)" class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-300 transition" title="Hapus hari">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
        <div class="p-4 space-y-3 day-items min-h-[56px]"></div>
    </div>`;
}

function itemTemplate(idx, data, day) {
    return `
    <div class="item-entry border border-gray-200 rounded-xl p-4 bg-white shadow-sm" data-index="${idx}">
        <input type="hidden" name="items[${idx}][day_number]" value="${day}" class="item-day">
        <input type="hidden" name="items[${idx}][sort_order]" value="1" class="item-sort">
        <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 px-1 select-none" title="Seret untuk memindahkan"><i class="fas fa-grip-vertical"></i></span>
                <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">#<span class="item-number">1</span></span>
            </div>
            <button type="button" onclick="deleteItem(this)" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-red-50 transition">
                <i class="fas fa-trash text-xs"></i> Hapus
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai</label>
                <input type="time" name="items[${idx}][start_time]" value="${data?.start_time || ''}" class="admin-form-input text-sm">
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Jam Selesai</label>
                <input type="time" name="items[${idx}][end_time]" value="${data?.end_time || ''}" class="admin-form-input text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" name="items[${idx}][activity_name]" value="${data?.activity_name || ''}" class="admin-form-input text-sm" required>
            </div>
            <div class="md:col-span-2">
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
    </div>`;
}

function addItem(day, data = null) {
    const card = document.querySelector(`.day-card[data-day="${day}"]`);
    if (!card) return;
    card.querySelector('.day-items').insertAdjacentHTML('beforeend', itemTemplate(uid(), data, day));
    renumberDays();
    initSortables();
}

function addDay() {
    const empty = document.getElementById('emptyState');
    if (empty) empty.remove();
    const days = [...document.querySelectorAll('.day-card')].map(c => parseInt(c.dataset.day, 10));
    const next = days.length ? Math.max(...days) + 1 : 1;
    document.getElementById('daysContainer').insertAdjacentHTML('beforeend', dayCardTemplate(next));
    renumberDays();
    initSortables();
}
function deleteItem(btn) {
    btn.closest('.item-entry').remove();
    renumberDays();
    initSortables();
}

function deleteDay(btn) {
    const card = btn.closest('.day-card');
    const count = card.querySelectorAll('.item-entry').length;
    if (count > 0 && !confirm('Hari ini masih berisi ' + count + ' kegiatan. Hapus beserta seluruh kegiatannya?')) return;
    card.remove();
    renumberDays();
    initSortables();
}

function renumberDays() {
    document.querySelectorAll('.day-card').forEach(card => {
        const day = card.dataset.day;
        card.querySelectorAll('.item-entry').forEach((entry, i) => {
            entry.querySelector('.item-day').value = day;
            entry.querySelector('.item-sort').value = i + 1;
            entry.querySelector('.item-number').textContent = i + 1;
        });
    });
}

function initSortables() {
    document.querySelectorAll('.day-items').forEach(el => {
        if (sortables.has(el)) {
            sortables.get(el).destroy();
            sortables.delete(el);
        }
        const s = new Sortable(el, {
            group: 'rundown-items',
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: renumberDays,
        });
        sortables.set(el, s);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initSortables();
});
</script>
@endpush