@extends('layouts.app')

@section('title', 'Kalkulator Harga - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Kalkulator Harga</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat estimasi harga paket wisata rombongan</p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.price-calculator.settings') }}" class="admin-btn-secondary admin-btn-sm">
            <i class="fas fa-cog mr-1"></i>
            Pengaturan Harga
        </a>
        <a href="{{ route('admin.price-calculator.create') }}" class="admin-btn-primary admin-btn-sm">
            <i class="fas fa-plus mr-1"></i>
            Estimasi Baru
        </a>
    </div>
</div>

{{-- Search --}}
<div class="admin-card shadow-md mb-6">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.price-calculator.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" class="admin-input" placeholder="Cari nomor estimasi, sekolah, atau PIC..." value="{{ request('search') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="admin-btn-primary">
                    <i class="fas fa-search mr-1"></i>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="admin-card shadow-md">
    <div class="admin-card-body p-0">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Estimasi</th>
                        <th>Sekolah / Instansi</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Peserta</th>
                        <th>Harga/Orang</th>
                        <th>Total Quotation</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estimations as $est)
                        <tr>
                            <td class="font-mono text-sm font-medium">{{ $est->estimation_number }}</td>
                            <td>{{ $est->institution_name }}</td>
                            <td>{{ $est->arrival_date->format('d/m/Y') }} - {{ $est->departure_date->format('d/m/Y') }}</td>
                            <td>{{ $est->service_participant_count }}</td>
                            <td class="font-mono">{{ formatPrice($est->rounded_price_per_person) }}</td>
                            <td class="font-mono">{{ formatPrice($est->quotation_total) }}</td>
                            <td>{{ $est->createdBy?->name ?? '-' }}</td>
                            <td>{{ $est->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.price-calculator.pdf-view', $est) }}" class="admin-btn-sm admin-btn-info" title="View PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('admin.price-calculator.show', $est) }}" class="admin-btn-sm admin-btn-info" title="Lihat Rincian">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.price-calculator.edit', $est) }}" class="admin-btn-sm admin-btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="window.location.href='{{ route('admin.price-calculator.duplicate', $est) }}'" class="admin-btn-sm admin-btn-secondary" title="Duplikasi">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    @php
                                        $waNum = preg_replace('/[^0-9]/', '', $est->whatsapp);
                                        if (substr($waNum, 0, 1) === '0') $waNum = '62' . substr($waNum, 1);
                                        $waText = rawurlencode("Halo Bapak/Ibu,\n\nBerikut estimasi kunjungan ke Desa Wisata Gabugan.\n\nNomor Estimasi: {$est->estimation_number}\nInstansi: {$est->institution_name}\nPeserta: {$est->service_participant_count} orang\nEstimasi: " . formatPrice($est->rounded_price_per_person) . "/orang\nTotal: " . formatPrice($est->quotation_total) . "\n\nSalam,\nDesa Wisata Gabugan");
                                    @endphp
                                    <a href="https://wa.me/{{ $waNum }}?text={{ $waText }}" class="admin-btn-sm admin-btn-success" title="Kirim WhatsApp" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="{{ route('admin.price-calculator.pdf-download', $est) }}" class="admin-btn-sm admin-btn-warning" title="Download PDF" target="_blank">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('admin.price-calculator.destroy', $est) }}" method="POST" class="inline" onsubmit="return confirm('Hapus estimasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn-sm admin-btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                <i class="fas fa-calculator text-3xl mb-2"></i>
                                <p>Belum ada estimasi tersimpan</p>
                                <a href="{{ route('admin.price-calculator.create') }}" class="admin-btn-primary admin-btn-sm mt-3 inline-block">
                                    <i class="fas fa-plus mr-1"></i>
                                    Buat Estimasi Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($estimations->hasPages())
        <div class="admin-card-footer">
            {{ $estimations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection