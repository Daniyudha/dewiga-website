@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
@php
    $isConverted = $schedule->source_type === 'price_estimation';
    $estimation = $schedule->priceEstimation;
    $paymentStatus = $paymentSummary['payment_status'] ?? 'unpaid';

    // WhatsApp URLs
    $waService = app(\App\Services\WhatsAppMessageService::class);
    $waPending = $waService->getWhatsAppUrl($schedule, 'pending');
    $waConfirmed = $waService->getWhatsAppUrl($schedule, 'confirmed');
    $waPayment = $waService->getWhatsAppUrl($schedule, 'reminder_payment');
    $waUpcoming = $waService->getWhatsAppUrl($schedule, 'upcoming_visit');
@endphp

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Detail Jadwal</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $schedule->visitor_name ?? 'Jadwal #'.$schedule->id }}
            <span class="inline-block mx-2">&middot;</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->status_badge }}">
                {{ $schedule->status_label }}
            </span>
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        @if($isConverted && $estimation)
            <a href="{{ route('admin.price-calculator.show', $estimation) }}" class="admin-btn-sm admin-btn-info">
                <i class="fas fa-calculator mr-1"></i>
                Lihat Estimasi
            </a>
        @endif
        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="admin-btn-sm admin-btn-primary">
            <i class="fas fa-edit mr-1"></i>
            Edit
        </a>
        <a href="{{ route('admin.schedules.index') }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
</div>

{{-- WhatsApp Quick Actions --}}
@if($waPending || $waConfirmed)
<div class="flex gap-2 mb-4 flex-wrap">
    <span class="text-sm text-gray-500 font-medium mr-1 self-center">Kirim WhatsApp:</span>
    @if($waPending)
    <a href="{{ $waPending }}" target="_blank" class="admin-btn-xs admin-btn-success"><i class="fab fa-whatsapp mr-1"></i>Pending</a>
    @endif
    @if($waConfirmed)
    <a href="{{ $waConfirmed }}" target="_blank" class="admin-btn-xs admin-btn-success"><i class="fab fa-whatsapp mr-1"></i>Confirmed</a>
    @endif
    @if($waPayment)
    <a href="{{ $waPayment }}" target="_blank" class="admin-btn-xs admin-btn-success"><i class="fab fa-whatsapp mr-1"></i>Pengingat Bayar</a>
    @endif
    @if($waUpcoming)
    <a href="{{ $waUpcoming }}" target="_blank" class="admin-btn-xs admin-btn-success"><i class="fab fa-whatsapp mr-1"></i>Menjelang Kunjungan</a>
    @endif
</div>
@endif

{{-- Tab Navigation --}}
<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-6 overflow-x-auto" id="scheduleTabs">
        <button onclick="switchTab('ringkasan')" class="tab-link active" data-tab="ringkasan" type="button">
            <i class="fas fa-info-circle mr-1"></i> Ringkasan
        </button>
        <button onclick="switchTab('pembayaran')" class="tab-link" data-tab="pembayaran" type="button">
            <i class="fas fa-credit-card mr-1"></i> Pembayaran
            @if($paymentStatus === 'unpaid')
                <span class="ml-1 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
            @endif
        </button>
        <button onclick="switchTab('riwayat')" class="tab-link" data-tab="riwayat" type="button">
            <i class="fas fa-history mr-1"></i> Riwayat
        </button>
        <button onclick="switchTab('rundown')" class="tab-link" data-tab="rundown" type="button">
            <i class="fas fa-clipboard-list mr-1"></i> Rundown
        </button>
    </nav>
</div>

{{-- TAB: Ringkasan --}}
<div id="tab-ringkasan" class="tab-content">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Informasi Umum --}}
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        Informasi Umum
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Nama Rombongan</span>
                            <p class="font-medium">{{ $schedule->visitor_name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Status</span>
                            <p><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->status_badge }}">{{ $schedule->status_label }}</span></p>
                        </div>
                        <div>
                            <span class="text-gray-500">Sumber</span>
                            <p class="font-medium">{{ $schedule->source_label ?? 'Input Manual' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal Mulai</span>
                            <p class="font-medium">{{ $schedule->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal Selesai</span>
                            <p class="font-medium">{{ $schedule->end_date?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Durasi</span>
                            <p class="font-medium">{{ $schedule->end_date ? $schedule->start_date->diffInDays($schedule->end_date) + 1 . ' hari' : '1 hari' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Paket Wisata</span>
                            <p class="font-medium">{{ $schedule->travelPackage?->type ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Kuota</span>
                            <p class="font-medium">{{ $schedule->booked }} / {{ $schedule->quota }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($isConverted && $estimation)
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-file-invoice-dollar text-primary-600 mr-2"></i>
                        Informasi Quotation (dari Estimasi)
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Nomor Estimasi</span>
                            <p class="font-mono font-medium">{{ $estimation->estimation_number }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Instansi</span>
                            <p class="font-medium">{{ $estimation->institution_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Total Quotation</span>
                            <p class="font-mono font-bold">{{ formatPrice($estimation->quotation_total) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Harga per Orang</span>
                            <p class="font-mono">{{ formatPrice($estimation->rounded_price_per_person) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah Siswa</span>
                            <p class="font-medium">{{ $estimation->student_count }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah Pendamping</span>
                            <p class="font-medium">{{ $estimation->companion_count }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Peserta Layanan Utama</span>
                            <p class="font-medium">{{ $estimation->service_participant_count }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Peserta Kegiatan</span>
                            <p class="font-medium">{{ $estimation->activity_participant_count }}</p>
                        </div>
                    </div>
                    @if($estimation->notes)
                    <div class="mt-3 pt-3 border-t text-sm">
                        <span class="text-gray-500">Catatan:</span>
                        <p class="mt-1">{{ $estimation->notes }}</p>
                    </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('admin.price-calculator.show', $estimation) }}" class="text-sm text-primary-600 hover:underline">
                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Detail Estimasi
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Ringkasan Pembayaran (Sidebar) --}}
        <div class="space-y-6">
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                        Ringkasan Pembayaran
                    </h3>
                </div>
                <div class="admin-card-body space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Tagihan</span>
                        <span class="font-mono font-bold">{{ formatPrice($paymentSummary['total_tagihan']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Dibayar</span>
                        <span class="font-mono font-medium text-green-600">{{ formatPrice($paymentSummary['total_dibayar']) }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sisa Pembayaran</span>
                        <span class="font-mono font-bold {{ $paymentSummary['sisa_pembayaran'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ formatPrice($paymentSummary['sisa_pembayaran']) }}
                        </span>
                    </div>
                    @if($paymentSummary['kelebihan'] > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kelebihan</span>
                        <span class="font-mono text-amber-600">{{ formatPrice($paymentSummary['kelebihan']) }}</span>
                    </div>
                    @endif
                    <div class="text-center pt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\Enums\PaymentStatus::badgeClass($paymentStatus) }}">
                            {{ \App\Enums\PaymentStatus::label($paymentStatus) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-bolt text-primary-600 mr-2"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="admin-card-body space-y-2">
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="block w-full text-center admin-btn-sm admin-btn-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Jadwal
                    </a>
                    @if($isConverted && $estimation)
                    <a href="{{ route('admin.price-calculator.show', $estimation) }}" class="block w-full text-center admin-btn-sm admin-btn-info">
                        <i class="fas fa-calculator mr-1"></i> Lihat Estimasi
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB: Pembayaran --}}
<div id="tab-pembayaran" class="tab-content hidden">
    <div class="admin-card shadow-md">
        <div class="admin-card-header flex items-center justify-between">
            <h3 class="font-heading font-semibold text-gray-800">
                <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                Riwayat Pembayaran
            </h3>
            <button type="button" onclick="showPaymentModal()" class="admin-btn-sm admin-btn-primary">
                <i class="fas fa-plus mr-1"></i> Tambah Pembayaran
            </button>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No. Pembayaran</th>
                            <th>Jenis</th>
                            <th>Metode</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Referensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedule->payments as $payment)
                            <tr>
                                <td class="font-mono text-sm">{{ $payment->payment_number ?? '-' }}</td>
                                <td>{{ $payment->type_label }}</td>
                                <td>{{ $payment->method_label }}</td>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td class="font-mono font-medium">{{ formatPrice($payment->amount) }}</td>
                                <td><span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">{{ ucfirst($payment->status) }}</span></td>
                                <td class="text-sm">{{ $payment->reference_number ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">Belum ada pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- TAB: Rundown --}}
<div id="tab-rundown" class="tab-content hidden">
    @include('admin.schedules.tabs.rundown')
</div>

{{-- TAB: Riwayat Status --}}
<div id="tab-riwayat" class="tab-content hidden">
    <div class="admin-card shadow-md">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">
                <i class="fas fa-history text-primary-600 mr-2"></i>
                Riwayat Perubahan Status
            </h3>
        </div>
        <div class="admin-card-body">
            @forelse($schedule->statusHistories->sortByDesc('created_at') as $history)
                <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                    <div class="w-2 h-2 mt-2 rounded-full bg-primary-500 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-xs rounded-full {{ \App\Enums\ScheduleStatus::badgeClass($history->new_status) }}">
                                {{ \App\Enums\ScheduleStatus::label($history->new_status) }}
                            </span>
                            @if($history->old_status)
                                <span class="text-xs text-gray-400">
                                    (dari {{ \App\Enums\ScheduleStatus::label($history->old_status) }})
                                </span>
                            @endif
                        </div>
                        @if($history->notes)
                            <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $history->created_at->format('d/m/Y H:i') }}
                            @if($history->changedBy)
                                oleh {{ $history->changedBy->name }}
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center py-4 text-gray-500">Belum ada riwayat perubahan status</p>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
.tab-link { @apply px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 hover:border-gray-300 cursor-pointer; }
.tab-link.active { @apply text-primary-600 border-primary-500; }
</style>
@endpush

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    // Show target tab
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    // Update active link
    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
    document.querySelector(`.tab-link[data-tab="${tabName}"]`).classList.add('active');
}

function showPaymentModal() {
    alert('Form tambah pembayaran akan ditambahkan di FASE selanjutnya.');
}
</script>
@endpush