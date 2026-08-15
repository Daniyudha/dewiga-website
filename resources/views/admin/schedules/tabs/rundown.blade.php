<div class="space-y-6">
    @if($schedule->scheduleRundown)
        {{-- Rundown exists --}}
        @php $rundown = $schedule->scheduleRundown; @endphp

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-clipboard-list text-primary-600 mr-2"></i>
                    {{ $rundown->title }}
                </h3>
                @if($rundown->rundown_number)
                    <p class="text-xs text-gray-500 font-mono">{{ $rundown->rundown_number }}</p>
                @endif
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.schedules.rundown.pdf-view', [$schedule, $rundown]) }}" target="_blank" class="admin-btn-xs admin-btn-info">
                    <i class="fas fa-eye mr-1"></i> View PDF
                </a>
                <a href="{{ route('admin.schedules.rundown.pdf-download', [$schedule, $rundown]) }}" class="admin-btn-xs admin-btn-primary">
                    <i class="fas fa-download mr-1"></i> Download PDF
                </a>
                <a href="{{ route('admin.schedules.rundown.edit', [$schedule, $rundown]) }}" class="admin-btn-xs admin-btn-secondary">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.schedules.rundown.destroy', [$schedule, $rundown]) }}" 
                      onclick="showDeleteModal(this.closest('form'))" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="admin-btn-xs admin-btn-danger">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        @if($rundown->notes)
            <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded">
                <i class="fas fa-sticky-note mr-1 text-gray-400"></i> {{ $rundown->notes }}
            </div>
        @endif

        {{-- Items by Day --}}
        @php $groupedItems = $rundown->items->groupBy('day_number'); @endphp

        @foreach($groupedItems as $day => $items)
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h4 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calendar-day text-primary-600 mr-2"></i>
                    HARI KE-{{ $day }}
                    @php $firstItem = $items->first(); @endphp
                    @if($firstItem && $firstItem->activity_date)
                        <span class="text-sm text-gray-500 ml-2">
                            {{ \Carbon\Carbon::parse($firstItem->activity_date)->translatedFormat('l, d F Y') }}
                        </span>
                    @endif
                </h4>
            </div>
            <div class="admin-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-24">Waktu</th>
                                <th>Kegiatan</th>
                                <th>Lokasi</th>
                                <th>Penanggung Jawab</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items->sortBy('sort_order') as $item)
                            <tr>
                                <td class="whitespace-nowrap text-sm font-mono">
                                    @if($item->start_time)
                                        {{ substr($item->start_time, 0, 5) }}
                                        @if($item->end_time) – {{ substr($item->end_time, 0, 5) }} @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="font-medium">{{ $item->activity_name }}</td>
                                <td class="text-sm">{{ $item->location ?? '-' }}</td>
                                <td class="text-sm">{{ $item->person_in_charge ?? '-' }}</td>
                                <td class="text-sm text-gray-500">{{ $item->description ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Reset from Template --}}
        <div class="admin-card">
            <div class="admin-card-body">
                <details class="group">
                    <summary class="text-sm text-amber-600 cursor-pointer hover:text-amber-700">
                        <i class="fas fa-redo mr-1"></i> Reset dari Template
                    </summary>
                    <div class="mt-3 p-3 bg-amber-50 rounded border border-amber-200">
                        <p class="text-sm text-amber-800 mb-3">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Tindakan ini akan mengganti semua item rundown yang sudah diedit. Lanjutkan?
                        </p>
                        <form method="POST" action="{{ route('admin.schedules.rundown.reset-from-template', [$schedule, $rundown]) }}" class="flex gap-3 items-end">
                            @csrf
                            <select name="rundown_template_id" class="admin-input w-full shadow-md rounded-md border border-gray-300 text-sm flex-1" required>
                                <option value="">Pilih Template</option>
                                @foreach($templates as $t)
                                    <option value="{{ $t->id }}" {{ $rundown->rundown_template_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="admin-btn-sm admin-btn-warning" onclick="return confirm('Yakin akan mereset rundown dari template? Semua perubahan akan hilang.')">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>

    @else
        {{-- No rundown yet --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-body text-center py-8">
                <div class="text-gray-300 mb-4">
                    <i class="fas fa-clipboard-list text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Belum ada rundown untuk jadwal ini</h3>
                <p class="text-sm text-gray-400 mb-6">Buat rundown dari template atau buat rundown kosong untuk memulai.</p>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="showCreateFromTemplateModal()" class="admin-btn-sm admin-btn-primary">
                        <i class="fas fa-copy mr-1"></i> Buat dari Template
                    </button>
                    <button type="button" onclick="showCreateEmptyModal()" class="admin-btn-sm admin-btn-secondary">
                        <i class="fas fa-plus mr-1"></i> Buat Rundown Kosong
                    </button>
                </div>
            </div>
        </div>

        {{-- Create from Template Modal --}}
        <div id="createFromTemplateModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-heading font-semibold text-gray-900">Buat Rundown dari Template</h3>
                        <button type="button" onclick="closeModal('createFromTemplateModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.schedules.rundown.create-from-template', $schedule) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="admin-label">Pilih Template <span class="text-red-500">*</span></label>
                                <select name="rundown_template_id" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                                    <option value="">-- Pilih Template --</option>
                                    @foreach($templates as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->duration_days }} Hari)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="admin-label">Judul Rundown</label>
                                <input type="text" name="title" class="admin-input w-full shadow-md rounded-md border border-gray-300" 
                                       value="Rundown {{ $schedule->visitor_name ?? 'Jadwal #'.$schedule->id }}" placeholder="Otomatis dari template">
                            </div>
                            <div>
                                <label class="admin-label">Catatan</label>
                                <textarea name="notes" rows="2" class="admin-input w-full shadow-md rounded-md border border-gray-300" placeholder="Opsional"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                            <button type="button" onclick="closeModal('createFromTemplateModal')" class="admin-btn-sm admin-btn-secondary">Batal</button>
                            <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-copy mr-1"></i> Buat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Create Empty Modal --}}
        <div id="createEmptyModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-heading font-semibold text-gray-900">Buat Rundown Kosong</h3>
                        <button type="button" onclick="closeModal('createEmptyModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.schedules.rundown.create-empty', $schedule) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="admin-label">Judul Rundown</label>
                                <input type="text" name="title" class="admin-input w-full shadow-md rounded-md border border-gray-300" 
                                       value="Rundown {{ $schedule->visitor_name ?? 'Jadwal #'.$schedule->id }}">
                            </div>
                            <div>
                                <label class="admin-label">Catatan</label>
                                <textarea name="notes" rows="2" class="admin-input w-full shadow-md rounded-md border border-gray-300" placeholder="Opsional"></textarea>
                            </div>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Rundown kosong akan dibuat tanpa template. Anda dapat menambahkan kegiatan secara manual nanti.
                            </p>
                        </div>
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                            <button type="button" onclick="closeModal('createEmptyModal')" class="admin-btn-sm admin-btn-secondary">Batal</button>
                            <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-plus mr-1"></i> Buat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function showCreateFromTemplateModal() {
    document.getElementById('createFromTemplateModal').classList.remove('hidden');
    document.getElementById('createFromTemplateModal').classList.add('flex');
}

function showCreateEmptyModal() {
    document.getElementById('createEmptyModal').classList.remove('hidden');
    document.getElementById('createEmptyModal').classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}
</script>
@endpush