@extends('layouts.app')

@section('title', 'Edit Rundown: ' . $rundown->title)

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Edit Rundown</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $rundown->title }} @if($rundown->rundown_number) &middot; {{ $rundown->rundown_number }} @endif</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.schedules.show', $schedule) }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Jadwal
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        {{-- Metadata Form --}}
        <form method="POST" action="{{ route('admin.schedules.rundown.update', [$schedule, $rundown]) }}" class="admin-card shadow-md">
            @csrf @method('PUT')
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i> Informasi Rundown
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Judul Rundown</label>
                        <input type="text" name="title" value="{{ $rundown->title }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Template</label>
                        <p class="form-input bg-gray-50">{{ $rundown->template?->name ?? 'Kustom (tanpa template)' }}</p>
                    </div>
                </div>
                <div>
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="2" class="form-input">{{ $rundown->notes }}</textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="admin-btn-sm admin-btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Informasi
                    </button>
                </div>
            </div>
        </form>

        {{-- Items Management --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header flex items-center justify-between">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-list text-primary-600 mr-2"></i> Kegiatan Rundown
                </h3>
                <button type="button" onclick="showAddItemModal()" class="admin-btn-sm admin-btn-primary">
                    <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
                </button>
            </div>
            <div class="admin-card-body p-0">
                @php $groupedItems = $rundown->items->groupBy('day_number'); @endphp
                @foreach($groupedItems as $day => $items)
                <div class="border-b border-gray-200 last:border-0">
                    <div class="bg-gray-50 px-4 py-2 font-medium text-sm text-gray-700 border-b">
                        <i class="fas fa-calendar-day text-primary-500 mr-1"></i> HARI KE-{{ $day }}
                        @if($firstItem = $items->first())
                            @if($firstItem->activity_date)
                                <span class="text-gray-400 ml-2">{{ \Carbon\Carbon::parse($firstItem->activity_date)->translatedFormat('l, d/m/Y') }}</span>
                            @endif
                        @endif
                    </div>
                    <table class="admin-table">
                        <tbody>
                            @foreach($items->sortBy('sort_order') as $item)
                            <tr>
                                <td class="w-24 text-sm font-mono">
                                    @if($item->start_time) {{ substr($item->start_time, 0, 5) }} @else - @endif
                                    @if($item->end_time) – {{ substr($item->end_time, 0, 5) }} @endif
                                </td>
                                <td class="font-medium">{{ $item->activity_name }}</td>
                                <td class="text-sm">{{ $item->location ?? '-' }}</td>
                                <td class="text-sm">{{ $item->person_in_charge ?? '-' }}</td>
                                <td class="whitespace-nowrap">
                                    <a href="#" onclick="showEditItemModal({{ $item->id }})" class="text-amber-600 hover:underline text-xs mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.schedules.rundown.delete-item', [$schedule, $rundown, $item]) }}" class="inline" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.schedules.rundown.duplicate-item', [$schedule, $rundown, $item]) }}" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline text-xs"><i class="fas fa-copy"></i></button>
                                    </form>
                                </td>
                            </tr>
                            
                            {{-- Inline edit form (hidden) --}}
                            <tr id="editItemForm-{{ $item->id }}" class="hidden">
                                <td colspan="5" class="p-3 bg-amber-50">
                                    <form method="POST" action="{{ route('admin.schedules.rundown.update-item', [$schedule, $rundown, $item]) }}" class="grid grid-cols-1 md:grid-cols-6 gap-2">
                                        @csrf @method('PUT')
                                        <input type="number" name="day_number" value="{{ $item->day_number }}" class="form-input text-sm" min="1">
                                        <input type="date" name="activity_date" value="{{ $item->activity_date?->format('Y-m-d') }}" class="form-input text-sm">
                                        <input type="time" name="start_time" value="{{ $item->start_time }}" class="form-input text-sm">
                                        <input type="time" name="end_time" value="{{ $item->end_time }}" class="form-input text-sm">
                                        <input type="text" name="activity_name" value="{{ $item->activity_name }}" class="form-input text-sm" required>
                                        <input type="text" name="location" value="{{ $item->location }}" class="form-input text-sm" placeholder="Lokasi">
                                        <input type="text" name="person_in_charge" value="{{ $item->person_in_charge }}" class="form-input text-sm" placeholder="PJ">
                                        <input type="text" name="description" value="{{ $item->description }}" class="form-input text-sm" placeholder="Keterangan">
                                        <div class="md:col-span-6 flex gap-2">
                                            <button type="submit" class="admin-btn-xs admin-btn-primary">Simpan</button>
                                            <button type="button" onclick="hideEditItemForm({{ $item->id }})" class="admin-btn-xs admin-btn-secondary">Batal</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i> Info
                </h3>
            </div>
            <div class="admin-card-body space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Kegiatan</span>
                    <span class="font-medium">{{ $rundown->items->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Hari</span>
                    <span class="font-medium">{{ $groupedItems->count() }}</span>
                </div>
            </div>
        </div>

        {{-- View/Download PDF --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-file-pdf text-primary-600 mr-2"></i> PDF
                </h3>
            </div>
            <div class="admin-card-body space-y-2">
                <a href="{{ route('admin.schedules.rundown.pdf-view', [$schedule, $rundown]) }}" target="_blank" class="block w-full text-center admin-btn-sm admin-btn-info">
                    <i class="fas fa-eye mr-1"></i> View PDF
                </a>
                <a href="{{ route('admin.schedules.rundown.pdf-download', [$schedule, $rundown]) }}" class="block w-full text-center admin-btn-sm admin-btn-primary">
                    <i class="fas fa-download mr-1"></i> Download PDF
                </a>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="admin-card border border-red-200 shadow-md">
            <div class="admin-card-header bg-red-50">
                <h3 class="font-heading font-semibold text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone
                </h3>
            </div>
            <div class="admin-card-body space-y-2">
                <form method="POST" action="{{ route('admin.schedules.rundown.destroy', [$schedule, $rundown]) }}"
                      onsubmit="return confirm('Hapus rundown ini beserta semua itemnya? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-center admin-btn-sm admin-btn-danger">
                        <i class="fas fa-trash mr-1"></i> Hapus Rundown
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Item Modal --}}
<div id="addItemModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-heading font-semibold text-gray-900">Tambah Kegiatan</h3>
                <button type="button" onclick="closeModal('addItemModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.schedules.rundown.add-item', [$schedule, $rundown]) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Hari ke- <span class="text-red-500">*</span></label>
                        <input type="number" name="day_number" value="{{ $groupedItems->count() }}" class="form-input" min="1" required>
                    </div>
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="activity_date" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Nama Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="activity_name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" name="person_in_charge" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="form-input"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeModal('addItemModal')" class="admin-btn-sm admin-btn-secondary">Batal</button>
                    <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-plus mr-1"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showAddItemModal() {
    document.getElementById('addItemModal').classList.remove('hidden');
    document.getElementById('addItemModal').classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

function showEditItemModal(itemId) {
    document.getElementById('editItemForm-' + itemId).classList.remove('hidden');
}

function hideEditItemForm(itemId) {
    document.getElementById('editItemForm-' + itemId).classList.add('hidden');
}
</script>
@endpush