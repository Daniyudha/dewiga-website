@extends('layouts.app')

@section('title', 'Data Kunjungan - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Data Kunjungan</h1>
        <p class="text-sm text-gray-500 mt-1">Rekap semua data kunjungan, asal, jumlah, length of stay & tipe tamu</p>
    </div>
</div>

@if(session('message'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium
        @if(session('alert-type') == 'success') bg-green-100 text-green-800 border border-green-200
        @else bg-red-100 text-red-800 border border-red-200 @endif">
        {{ session('message') }}
    </div>
@endif

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $summary->total_visits }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Kunjungan</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-green-600">{{ $summary->total_people }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Pengunjung</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-purple-600">{{ $summary->total_los_days }}</div>
            <div class="text-xs text-gray-500 mt-1">Total LOS (Hari)</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-orange-600">{{ $summary->avg_los }}</div>
            <div class="text-xs text-gray-500 mt-1">Rata-rata LOS (Hari)</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-teal-600">{{ $summary->avg_people_per_visit }}</div>
            <div class="text-xs text-gray-500 mt-1">Rata-rata Per Kunjungan</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-indigo-600">{{ $summary->total_institutions }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Instansi</div>
        </div>
    </div>
</div>

{{-- Breakdown by Type & Guest Type --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @foreach($summary->by_type as $type)
    <div class="admin-card">
        <div class="admin-card-body flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $type->type === 'booking' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                    {{ $type->label }}
                </span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-gray-800">{{ $type->count }} kunjungan</div>
                <div class="text-sm text-gray-500">{{ $type->people }} pengunjung</div>
            </div>
        </div>
    </div>
    @endforeach
    @foreach($summary->by_guest_type as $gt)
    <div class="admin-card">
        <div class="admin-card-body flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $gt->type === 'asing' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ $gt->label }}
                </span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-gray-800">{{ $gt->count }} kunjungan</div>
                <div class="text-sm text-gray-500">{{ $gt->people }} pengunjung</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter & Export 1 baris --}}
<div class="admin-card mb-6">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.visit-reports.index') }}" class="flex justify-between flex-wrap items-center gap-3">
            <div class="inline-flex items-center gap-2 flex-wrap">
                <input type="text" name="institution" value="{{ request('institution') }}" placeholder="Search institution..." class="form-input py-2 px-3 shadow-md rounded-md border border-gray-300 flex-1">
            </div>
            <div class="inline-flex items-center gap-2">
                <select name="year" class="form-input w-18 py-2 px-3 shadow-md rounded-md border border-gray-300 text-sm">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select name="visit_type" class="form-input w-36 py-2 px-3 shadow-md rounded-md border border-gray-300 text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="booking" {{ request('visit_type') == 'booking' ? 'selected' : '' }}>Booking</option>
                    <option value="open_trip" {{ request('visit_type') == 'open_trip' ? 'selected' : '' }}>Open Trip</option>
                </select>
                <select name="guest_type" class="form-input w-32 py-2 px-3 shadow-md rounded-md border border-gray-300 text-sm">
                    <option value="">Semua Tamu</option>
                    <option value="lokal" {{ request('guest_type') == 'lokal' ? 'selected' : '' }}>Lokal</option>
                    <option value="asing" {{ request('guest_type') == 'asing' ? 'selected' : '' }}>Asing</option>
                </select>
                <button type="submit" class="admin-btn-md admin-btn-primary"><i class="fas fa-search mr-1"></i> Filter</button>
                @if(request('year') || request('institution') || request('visit_type') || request('guest_type'))
                    <a href="{{ route('admin.visit-reports.index') }}" class="admin-btn-sm admin-btn-secondary">Reset</a>
                @endif
                <a href="{{ route('admin.visit-reports.export', request()->query()) }}" class="admin-btn-md admin-btn-success ml-auto">
                    <i class="fas fa-download mr-1"></i> Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Visit Table --}}
<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama PIC</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Asal/Instansi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe Tamu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Mulai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Selesai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">LOS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($visitsPaginated as $visit)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $visitsPaginated->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $visit->visitor_name }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->institution }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if($visit->number_phone && $visit->number_phone !== '-')
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $visit->number_phone) }}" target="_blank"
                                       class="text-green-600 hover:text-green-800">
                                        <i class="fab fa-whatsapp mr-1"></i>{{ $visit->number_phone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ ($visit->guest_type ?? 'lokal') === 'asing' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $visit->guest_type_label ?? 'Lokal' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">
                                {{ $visit->start_date ? date('d/m/Y', strtotime($visit->start_date)) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">
                                {{ $visit->end_date ? date('d/m/Y', strtotime($visit->end_date)) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $visit->length_of_stay >= 3 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $visit->length_of_stay }} hari
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $visit->total_people }} org</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $visit->visit_type === 'booking' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $visit->visit_type_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $visit->status === 'confirmed' || $visit->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $visit->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-chart-bar text-3xl text-gray-300"></i>
                                    <p class="text-sm">Belum ada data kunjungan untuk tahun {{ $year }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex items-center justify-between">
        @if($visitsPaginated->total() > 0)
        <div class="text-sm text-gray-500">
            Menampilkan {{ $visitsPaginated->total() }} kunjungan, total {{ $summary->total_people }} pengunjung,
            {{ $summary->total_los_days }} hari LOS (rata-rata {{ $summary->avg_los }} hari)
        </div>
        @endif
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $visitsPaginated->links() }}
        </div>
    </div>
</div>
@endsection