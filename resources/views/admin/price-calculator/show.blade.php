@extends('layouts.app')

@section('title', 'Rincian Estimasi - ' . $estimation->estimation_number)

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Rincian Estimasi</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $estimation->estimation_number }} - {{ $estimation->institution_name }}</p>
    </div>
    @php
        $waNum = preg_replace('/[^0-9]/', '', $estimation->whatsapp);
        if (substr($waNum, 0, 1) === '0') $waNum = '62' . substr($waNum, 1);
        $waText = rawurlencode("Halo Bapak/Ibu,\n\nBerikut estimasi kunjungan ke Desa Wisata Gabugan.\n\nNomor Estimasi: {$estimation->estimation_number}\nInstansi: {$estimation->institution_name}\nPeserta: {$estimation->service_participant_count} orang\nEstimasi: " . formatPrice($estimation->rounded_price_per_person) . "/orang\nTotal: " . formatPrice($estimation->quotation_total) . "\n\nSalam,\nDesa Wisata Gabugan");
    @endphp
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.price-calculator.pdf-view', $estimation) }}" class="admin-btn-sm admin-btn-info" target="_blank">
            <i class="fas fa-file-pdf mr-1"></i>
            View PDF
        </a>
        <a href="{{ route('admin.price-calculator.pdf-download', $estimation) }}" class="admin-btn-sm admin-btn-warning" target="_blank">
            <i class="fas fa-download mr-1"></i>
            Download PDF
        </a>
        <a href="https://wa.me/{{ $waNum }}?text={{ $waText }}" class="admin-btn-sm admin-btn-success" target="_blank">
            <i class="fab fa-whatsapp mr-1"></i>
            WhatsApp
        </a>
        <a href="{{ route('admin.price-calculator.edit', $estimation) }}" class="admin-btn-sm admin-btn-primary">
            <i class="fas fa-edit mr-1"></i>
            Edit
        </a>
        <a href="{{ route('admin.price-calculator.duplicate', $estimation) }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-copy mr-1"></i>
            Duplikasi
        </a>
        <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Informasi Rombongan --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-school text-primary-600 mr-2"></i>
                    Informasi Rombongan
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Sekolah / Instansi</span>
                        <p class="font-medium">{{ $estimation->institution_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Penanggung Jawab</span>
                        <p class="font-medium">{{ $estimation->contact_person }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">WhatsApp</span>
                        <p class="font-medium">{{ $estimation->whatsapp }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal Kedatangan</span>
                        <p class="font-medium">{{ $estimation->arrival_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal Kepulangan</span>
                        <p class="font-medium">{{ $estimation->departure_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Dibuat Oleh</span>
                        <p class="font-medium">{{ $estimation->createdBy?->name ?? '-' }}</p>
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
                    @if($estimation->notes)
                    <div class="col-span-full">
                        <span class="text-gray-500">Catatan</span>
                        <p class="font-medium">{{ $estimation->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-file-invoice text-primary-600 mr-2"></i>
                    Rincian Biaya
                </h3>
            </div>
            <div class="admin-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Qty</th>
                                <th>Frekuensi</th>
                                <th>Harga Satuan</th>
                                <th>Harga/Orang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estimation->items as $item)
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

    {{-- Summary --}}
    <div class="space-y-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calculator text-primary-600 mr-2"></i>
                    Ringkasan
                </h3>
            </div>
            <div class="admin-card-body space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Grand Total Biaya:</span>
                    <span class="font-bold font-mono">{{ formatPrice($estimation->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga Aktual per Orang:</span>
                    <span class="font-mono">{{ formatPrice($estimation->actual_price_per_person) }}</span>
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pembulatan:</span>
                    <span class="font-medium">
                        @switch($estimation->rounding_type)
                            @case('up_1000') Ke atas Rp1.000 @break
                            @case('up_5000') Ke atas Rp5.000 @break
                            @case('up_10000') Ke atas Rp10.000 @break
                            @default Tanpa Pembulatan
                        @endswitch
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga per Orang (Setelah Pembulatan):</span>
                    <span class="font-bold font-mono text-primary-600">{{ formatPrice($estimation->rounded_price_per_person) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Quotation:</span>
                    <span class="font-bold font-mono text-primary-600 text-lg">{{ formatPrice($estimation->quotation_total) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Selisih Pembulatan:</span>
                    <span class="font-mono {{ $estimation->difference_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $estimation->difference_amount >= 0 ? '+' : '' }}{{ formatPrice($estimation->difference_amount) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-tag text-primary-600 mr-2"></i>
                    Info
                </h3>
            </div>
            <div class="admin-card-body space-y-2 text-sm">
                <div>
                    <span class="text-gray-500">Nomor Estimasi</span>
                    <p class="font-mono font-medium">{{ $estimation->estimation_number }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Dibuat</span>
                    <p>{{ $estimation->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection