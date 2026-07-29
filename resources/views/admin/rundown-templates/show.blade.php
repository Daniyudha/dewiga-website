@extends('layouts.app')

@section('title', $rundownTemplate->name)

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ $rundownTemplate->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            @if($rundownTemplate->code)<span class="font-mono">{{ $rundownTemplate->code }}</span> &middot; @endif
            {{ $rundownTemplate->duration_days }} Hari @if($rundownTemplate->duration_nights > 0) / {{ $rundownTemplate->duration_nights }} Malam @endif
            &middot; {{ $rundownTemplate->items->count() }} Kegiatan
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.rundown-templates.edit', $rundownTemplate) }}" class="admin-btn-sm admin-btn-primary">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.rundown-templates.duplicate', $rundownTemplate) }}" class="inline">
            @csrf
            <button type="submit" class="admin-btn-sm admin-btn-info">
                <i class="fas fa-copy mr-1"></i> Duplikasi
            </button>
        </form>
        <form method="POST" action="{{ route('admin.rundown-templates.toggle-active', $rundownTemplate) }}" class="inline">
            @csrf @method('PATCH')
            <button type="submit" class="admin-btn-sm {{ $rundownTemplate->is_active ? 'admin-btn-warning' : 'admin-btn-success' }}">
                <i class="fas {{ $rundownTemplate->is_active ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                {{ $rundownTemplate->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
        <a href="{{ route('admin.rundown-templates.index') }}" class="admin-btn-sm admin-btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        {{-- Description --}}
        @if($rundownTemplate->description)
        <div class="admin-card shadow-md">
            <div class="admin-card-body">
                <p class="text-gray-600">{{ $rundownTemplate->description }}</p>
            </div>
        </div>
        @endif

        {{-- Items by Day --}}
        @foreach($groupedItems as $day => $items)
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calendar-day text-primary-600 mr-2"></i>
                    HARI KE-{{ $day }}
                </h3>
            </div>
            <div class="admin-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-20">Waktu</th>
                                <th>Kegiatan</th>
                                <th>Lokasi</th>
                                <th>Penanggung Jawab</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td class="whitespace-nowrap text-sm">
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
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i> Informasi
                </h3>
            </div>
            <div class="admin-card-body space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-medium {{ $rundownTemplate->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $rundownTemplate->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Durasi</span>
                    <span class="font-medium">{{ $rundownTemplate->duration_days }} Hari</span>
                </div>
                @if($rundownTemplate->duration_nights > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Malam</span>
                    <span class="font-medium">{{ $rundownTemplate->duration_nights }} Malam</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Kegiatan</span>
                    <span class="font-medium">{{ $rundownTemplate->items->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibuat oleh</span>
                    <span class="font-medium">{{ $rundownTemplate->creator?->name ?? 'System' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-medium">{{ $rundownTemplate->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        @if($rundownTemplate->scheduleRundowns()->count() > 0)
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-link text-primary-600 mr-2"></i> Digunakan di
                </h3>
            </div>
            <div class="admin-card-body text-sm">
                <p class="text-gray-500">{{ $rundownTemplate->scheduleRundowns()->count() }} jadwal</p>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.rundown-templates.destroy', $rundownTemplate) }}" 
              onclick="showDeleteModal(this.closest('form'))">
            @csrf @method('DELETE')
            <button type="submit" class="w-full admin-btn-sm admin-btn-danger">
                <i class="fas fa-trash mr-1"></i> Hapus Template
            </button>
        </form>
    </div>
</div>
@endsection