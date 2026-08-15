@extends('layouts.app')

@section('title', 'Database Tamu - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Database Tamu</h1>
            <p class="text-sm text-gray-500 mt-1">Menampung semua data tamu dari booking, open trip, dan input manual</p>
        </div>
        <a href="{{ route('admin.guests.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Tamu Manual
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="admin-card mb-6">
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.guests.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" placeholder="Cari nama, instansi, kontak..."
                           class="admin-input w-full"
                           value="{{ request('search') }}">
                </div>

                <label class="text-sm font-medium text-gray-700">Sumber:</label>
                <select name="source" class="admin-input w-auto min-w-[150px]" onchange="this.form.submit()">
                    <option value="">Semua Sumber</option>
                    <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="booking" {{ request('source') == 'booking' ? 'selected' : '' }}>Booking</option>
                    <option value="open_trip" {{ request('source') == 'open_trip' ? 'selected' : '' }}>Open Trip</option>
                </select>

                <button type="submit" class="admin-btn-sm admin-btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>

                @if(request('search') || request('source'))
                    <a href="{{ route('admin.guests.index') }}" class="admin-btn-sm admin-btn-secondary text-red-600">
                        <i class="fas fa-times"></i> Hapus Filter
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Guests Table --}}
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
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($guests as $guest)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $guests->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $guest->name }}</div>
                                    @if($guest->email)
                                        <div class="text-xs text-gray-500">{{ $guest->email }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $guest->institution ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($guest->number_phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guest->number_phone) }}" target="_blank"
                                           class="text-green-600 hover:text-green-800">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $guest->number_phone }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $guest->source_badge }}">
                                        {{ $guest->source_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $guest->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.guests.edit', $guest) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.guests.destroy', $guest) }}"
                                              onclick="showDeleteModal(this.closest('form'))" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-users text-3xl text-gray-300"></i>
                                        <p class="text-sm">Belum ada data tamu.</p>
                                        <a href="{{ route('admin.guests.create') }}" class="text-sm text-blue-600 hover:underline">
                                            Tambah tamu manual
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $guests->links() }}
    </div>
@endsection