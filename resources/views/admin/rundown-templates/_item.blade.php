<div class="item-entry border border-gray-200 rounded-xl p-4 bg-white shadow-sm" data-index="{{ $index }}">
    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
    <input type="hidden" name="items[{{ $index }}][day_number]" value="{{ $day }}" class="item-day">
    <input type="hidden" name="items[{{ $index }}][sort_order]" value="{{ $number }}" class="item-sort">
    <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 px-1 select-none" title="Seret untuk memindahkan">
                <i class="fas fa-grip-vertical"></i>
            </span>
            <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">#<span class="item-number">{{ $number }}</span></span>
        </div>
        <button type="button" onclick="deleteItem(this)" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-red-50 transition">
            <i class="fas fa-trash text-xs"></i> Hapus
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <div class="md:col-span-1">
            <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai</label>
            <input type="time" name="items[{{ $index }}][start_time]" value="{{ $item->start_time ? substr($item->start_time, 0, 5) : '' }}" class="admin-form-input text-sm">
        </div>
        <div class="md:col-span-1">
            <label class="block text-xs font-medium text-gray-600 mb-1">Jam Selesai</label>
            <input type="time" name="items[{{ $index }}][end_time]" value="{{ $item->end_time ? substr($item->end_time, 0, 5) : '' }}" class="admin-form-input text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
            <input type="text" name="items[{{ $index }}][activity_name]" value="{{ $item->activity_name ?? '' }}" class="admin-form-input text-sm" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Lokasi</label>
            <input type="text" name="items[{{ $index }}][location]" value="{{ $item->location ?? '' }}" class="admin-form-input text-sm">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Penanggung Jawab</label>
            <input type="text" name="items[{{ $index }}][person_in_charge]" value="{{ $item->person_in_charge ?? '' }}" class="admin-form-input text-sm">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
            <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description ?? '' }}" class="admin-form-input text-sm">
        </div>
    </div>
</div>