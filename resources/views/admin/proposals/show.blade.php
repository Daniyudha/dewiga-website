@extends('layouts.app')

@section('title', $proposal->proposal_title ?? 'Proposal')

@section('content')
@php
    $waNum = preg_replace('/[^0-9]/', '', $proposal->whatsapp);
    if (substr($waNum, 0, 1) === '0') $waNum = '62' . substr($waNum, 1);
@endphp
@php
    $selectedTemplate = $proposal->rundown_template_id ? \App\Models\RundownTemplate::with('items')->find($proposal->rundown_template_id) : null;
@endphp
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ $proposal->proposal_title ?? 'Proposal' }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            <span class="font-mono">{{ $proposal->proposal_number ?? $proposal->estimation_number }}</span>
            &middot; {{ $proposal->institution_name }}
            <span class="inline-block ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\Enums\ProposalStatus::badgeClass($proposal->proposal_status) }}">
                {{ \App\Enums\ProposalStatus::label($proposal->proposal_status) }}
            </span>
            @if($proposal->proposal_version > 1)
                <span class="ml-2 text-xs bg-gray-100 px-2 py-0.5 rounded">v{{ $proposal->proposal_version }}</span>
            @endif
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        @if($isConverted && $convertedSchedule)
            <a href="{{ route('admin.schedules.show', $convertedSchedule) }}" class="admin-btn-sm admin-btn-success">
                <i class="fas fa-calendar-check mr-1"></i> Lihat Schedule
            </a>
        @endif
        @php
            $publicProposalUrl = url('proposal/' . $proposal->estimation_number);
            $waShareNum = preg_replace('/[^0-9]/', '', $proposal->whatsapp);
            if (substr($waShareNum, 0, 1) === '0') $waShareNum = '62' . substr($waShareNum, 1);
            $waShareText = rawurlencode("Halo Bapak/Ibu,\n\nBerikut proposal program kegiatan untuk {$proposal->institution_name}.\n\nNomor: " . ($proposal->proposal_number ?? $proposal->estimation_number) . "\nProgram: {$proposal->proposal_title}\nPeserta: {$proposal->service_participant_count} orang\nTotal: " . formatPrice($proposal->quotation_total) . "\n\nLihat Proposal: {$publicProposalUrl}\n\nSalam,\nDesa Wisata Gabugan");
        @endphp
        <a href="{{ $publicProposalUrl }}" class="admin-btn-sm admin-btn-info" target="_blank" title="Buka Link Publik Proposal">
            <i class="fas fa-link mr-1"></i> Link Publik
        </a>
        <a href="https://wa.me/{{ $waShareNum }}?text={{ $waShareText }}" target="_blank" class="admin-btn-sm admin-btn-success" title="Kirim WhatsApp Proposal">
            <i class="fab fa-whatsapp mr-1"></i> Kirim WA
        </a>
        <button type="button" class="admin-btn-sm admin-btn-secondary" title="Salin Link Publik" onclick="navigator.clipboard.writeText('{{ $publicProposalUrl }}'); alert('Link proposal berhasil disalin: {{ $publicProposalUrl }}');">
            <i class="fas fa-copy mr-1"></i> Salin
        </button>
        <a href="{{ route('admin.proposals.pdf-view', $proposal) }}" target="_blank" class="admin-btn-sm admin-btn-info">
            <i class="fas fa-file-pdf mr-1"></i> View PDF
        </a>
        <a href="{{ route('admin.proposals.pdf-download', $proposal) }}" class="admin-btn-sm admin-btn-warning">
            <i class="fas fa-download mr-1"></i> Download PDF
        </a>
        @if($proposal->proposal_status !== \App\Enums\ProposalStatus::CONVERTED)
            <a href="{{ route('admin.proposals.edit', $proposal) }}" class="admin-btn-sm admin-btn-primary">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        @endif
        <a href="{{ route('admin.proposals.index') }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

{{-- Status Actions --}}
<div class="flex gap-2 mb-4 flex-wrap">
    @php $transitions = \App\Enums\ProposalStatus::validTransitions()[$proposal->proposal_status] ?? []; @endphp
    @foreach($transitions as $trans)
        <form method="POST" action="{{ route('admin.proposals.update-status', $proposal) }}" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $trans }}">
            <button type="submit" class="admin-btn-xs admin-btn-{{ $trans === 'cancelled' ? 'danger' : ($trans === 'approved' ? 'success' : 'secondary') }}"
                onclick="return confirm('Ubah status menjadi {{ \App\Enums\ProposalStatus::label($trans) }}?')">
                <i class="fas fa-arrow-right mr-1"></i> {{ \App\Enums\ProposalStatus::label($trans) }}
            </button>
        </form>
    @endforeach
    @if($proposal->proposal_status === \App\Enums\ProposalStatus::APPROVED)
        <button type="button" onclick="showConvertModal()" class="admin-btn-xs admin-btn-primary">
            <i class="fas fa-calendar-plus mr-1"></i> Convert ke Schedule
        </button>
    @endif
</div>

{{-- Rundown Section --}}
@if($selectedTemplate)
<div class="admin-card shadow-md mb-6">
    <div class="admin-card-header flex items-center justify-between">
        <h3 class="font-heading font-semibold text-gray-800">
            <i class="fas fa-clipboard-list text-primary-600 mr-2"></i>
            Template Rundown
        </h3>
        <span class="text-sm text-gray-500">
            {{ $selectedTemplate->name }} ({{ $selectedTemplate->duration_days }} Hari, {{ $selectedTemplate->items->count() }} Kegiatan)
        </span>
    </div>
    <div class="admin-card-body p-0">
        @php $groupedItems = $selectedTemplate->items->groupBy('day_number'); @endphp
        <div class="overflow-x-auto">
            @foreach($groupedItems as $day => $items)
            <div class="border-b border-gray-200 last:border-0">
                <div class="bg-gray-50 px-4 py-2 font-medium text-sm text-gray-700">
                    <i class="fas fa-calendar-day text-primary-500 mr-1"></i> HARI KE-{{ $day }}
                    <span class="text-gray-400 ml-2">
                        {{ $proposal->arrival_date->copy()->addDays($day - 1)->translatedFormat('l, d/m/Y') }}
                    </span>
                </div>
                <table class="admin-table">
                    <thead><tr><th class="w-20">Waktu</th><th>Kegiatan</th><th>Lokasi</th><th>Penanggung Jawab</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        @foreach($items->sortBy('sort_order') as $item)
                        <tr>
                            <td class="whitespace-nowrap text-sm font-mono">
                                @if($item->start_time){{ substr($item->start_time,0,5) }}@if($item->end_time)–{{ substr($item->end_time,0,5) }}@endif @else - @endif
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
            @endforeach
        </div>
    </div>
    <div class="admin-card-body border-t border-gray-100">
        <label class="form-label text-xs">Ganti Template Rundown</label>
        <form method="POST" action="{{ route('admin.proposals.update-program', $proposal) }}" class="flex gap-3 items-center justify-start max-w-md">
            @csrf
            <div class="flex-1">
                <select name="rundown_template_id" class="admin-input w-full shadow-md rounded-md border border-gray-300">
                    <option value="">-- Hapus Template --</option>
                    @foreach($templates as $t)
                        <option value="{{ $t->id }}" {{ $selectedTemplate->id == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->duration_days }}H)</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="admin-btn-md admin-btn-secondary text-sm">
                <i class="fas fa-sync mr-1"></i> Ganti
            </button>
        </form>
    </div>
</div>
@else
<div class="admin-card shadow-md mb-6">
    <div class="admin-card-header">
        <h3 class="font-heading font-semibold text-gray-800">
            <i class="fas fa-clipboard-list text-primary-600 mr-2"></i>
            Template Rundown
        </h3>
    </div>
    <div class="admin-card-body">
        <div class="text-center py-4">
            <div class="text-gray-300 mb-2"><i class="fas fa-clipboard-list text-4xl"></i></div>
            <p class="text-sm text-gray-500 mb-3">Belum ada template rundown yang dipilih.</p>
            <form method="POST" action="{{ route('admin.proposals.update-program', $proposal) }}" class="flex gap-3 items-center justify-center max-w-md mx-auto">
                @csrf
                <div class="flex-1">
                    <select name="rundown_template_id" class="admin-input w-full shadow-md rounded-md border border-gray-300">
                        <option value="">-- Pilih Template --</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->duration_days }}H, {{ $t->items_count ?? $t->items->count() }} kegiatan)</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-btn-md admin-btn-primary text-sm">
                    <i class="fas fa-check mr-1"></i> Pilih
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header"><h3 class="font-heading font-semibold text-gray-800"><i class="fas fa-school text-primary-600 mr-2"></i>Informasi Rombongan</h3></div>
            <div class="admin-card-body">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><span class="text-gray-500">Instansi</span><p class="font-medium">{{ $proposal->institution_name }}</p></div>
                    <div><span class="text-gray-500">PIC</span><p class="font-medium">{{ $proposal->contact_person }}</p></div>
                    <div><span class="text-gray-500">WA</span><p class="font-medium">{{ $proposal->whatsapp }}</p></div>
                    <div><span class="text-gray-500">Kedatangan</span><p class="font-medium">{{ $proposal->arrival_date->format('d/m/Y') }}</p></div>
                    <div><span class="text-gray-500">Kepulangan</span><p class="font-medium">{{ $proposal->departure_date->format('d/m/Y') }}</p></div>
                    <div><span class="text-gray-500">Durasi</span><p class="font-medium">{{ $proposal->arrival_date->diffInDays($proposal->departure_date) + 1 }} Hari</p></div>
                    <div><span class="text-gray-500">Siswa</span><p class="font-medium">{{ $proposal->student_count }}</p></div>
                    <div><span class="text-gray-500">Pendamping</span><p class="font-medium">{{ $proposal->companion_count }}</p></div>
                    <div><span class="text-gray-500">Total Peserta</span><p class="font-medium">{{ $proposal->service_participant_count }}</p></div>
                    @if($proposal->notes)<div class="col-span-full"><span class="text-gray-500">Catatan</span><p class="font-medium">{{ $proposal->notes }}</p></div>@endif
                </div>
            </div>
        </div>

        <div class="admin-card shadow-md">
            <div class="admin-card-header"><h3 class="font-heading font-semibold text-gray-800"><i class="fas fa-file-invoice text-primary-600 mr-2"></i>Rincian Biaya</h3></div>
            <div class="admin-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead><tr><th>Komponen</th><th>Qty</th><th>Frekuensi</th><th>Harga Satuan</th><th>Harga/Orang</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($proposal->items as $item)
                            <tr>
                                <td class="font-medium">{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->frequency }} {{ $item->unit }}</td>
                                <td class="font-mono">{{ formatPrice($item->unit_price) }}</td>
                                <td class="font-mono">{{ formatPrice($item->price_per_person) }}</td>
                                <td class="font-mono font-semibold">{{ formatPrice($item->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header"><h3 class="font-heading font-semibold text-gray-800"><i class="fas fa-calculator text-primary-600 mr-2"></i>Ringkasan</h3></div>
            <div class="admin-card-body space-y-3">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Grand Total:</span><span class="font-bold font-mono">{{ formatPrice($proposal->subtotal) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Harga/Orang:</span><span class="font-bold font-mono text-primary-600">{{ formatPrice($proposal->rounded_price_per_person) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Total Quotation:</span><span class="font-bold font-mono text-primary-600 text-lg">{{ formatPrice($proposal->quotation_total) }}</span></div>
            </div>
        </div>
        <div class="admin-card shadow-md">
            <div class="admin-card-header"><h3 class="font-heading font-semibold text-gray-800"><i class="fas fa-tag text-primary-600 mr-2"></i>Info</h3></div>
            <div class="admin-card-body space-y-2 text-sm">
                <div><span class="text-gray-500">No. Proposal</span><p class="font-mono font-medium">{{ $proposal->proposal_number ?? $proposal->estimation_number }}</p></div>
                @if($proposal->proposal_sent_at)<div><span class="text-gray-500">Terkirim</span><p>{{ \Carbon\Carbon::parse($proposal->proposal_sent_at)->format('d/m/Y') }}</p></div>@endif
                @if($proposal->approved_at)<div><span class="text-gray-500">Disetujui</span><p>{{ \Carbon\Carbon::parse($proposal->approved_at)->format('d/m/Y') }}</p></div>@endif
                <div><span class="text-gray-500">Dibuat</span><p>{{ $proposal->created_at->format('d/m/Y H:i') }}</p></div>
            </div>
        </div>
    </div>
</div>

{{-- Convert Modal --}}
@if($proposal->proposal_status === \App\Enums\ProposalStatus::APPROVED)
<div id="convertModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
        <h3 class="text-lg font-heading font-semibold text-gray-900 mb-4"><i class="fas fa-calendar-plus mr-2 text-primary-600"></i>Konversi ke Jadwal</h3>
        <form method="POST" action="{{ route('admin.proposals.convert-to-schedule', $proposal) }}">
            @csrf
            <p class="text-sm text-gray-600 mb-4">Proposal akan dikonversi menjadi jadwal. Data peserta dan harga akan dibawa ke schedule.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('convertModal').classList.add('hidden')" class="admin-btn-sm admin-btn-secondary">Batal</button>
                <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-calendar-plus mr-1"></i> Konversi</button>
            </div>
        </form>
    </div>
</div>
<script>
function showConvertModal() { document.getElementById('convertModal').classList.remove('hidden'); document.getElementById('convertModal').classList.add('flex'); }
</script>
@endif
@endsection