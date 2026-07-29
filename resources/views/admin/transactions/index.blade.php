@extends('layouts.app')

@section('title', 'Data Keuangan - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Data Keuangan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pemasukan, pengeluaran, dan saldo keuangan</p>
    </div>
    <a href="{{ route('admin.transactions.create') }}" class="admin-btn-primary">
        <i class="fas fa-plus mr-1"></i> Tambah Transaksi
    </a>
</div>

@if(session('message'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium
        @if(session('alert-type') == 'success') bg-green-100 text-green-800 border border-green-200
        @else bg-red-100 text-red-800 border border-red-200 @endif">
        {{ session('message') }}
    </div>
@endif

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-green-600">Rp {{ number_format($totalDebit, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Pemasukan (Debit)</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-red-600">Rp {{ number_format($totalCredit, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Pengeluaran (Kredit)</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-blue-600">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Saldo Filter</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-body text-center">
            <div class="text-3xl font-bold text-indigo-600">Rp {{ number_format($saldoBerjalan, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Saldo Keseluruhan</div>
        </div>
    </div>
</div>

{{-- Filter 1 baris --}}
<div class="admin-card mb-6">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="form-input flex-1">
            <select name="source" class="form-input w-48">
                <option value="">Semua Sumber Dana</option>
                @foreach(\App\Models\Transaction::sources() as $val => $label)
                    <option value="{{ $val }}" {{ request('source') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="category" class="form-input w-40">
                <option value="">Semua Kategori</option>
                @foreach(\App\Models\Transaction::categories() as $val => $label)
                    <option value="{{ $val }}" {{ request('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-36" placeholder="Dari">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-36" placeholder="Sampai">
            <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-search mr-1"></i> Filter</button>
            @if(request('search') || request('source') || request('category') || request('date_from') || request('date_to'))
                <a href="{{ route('admin.transactions.index') }}" class="admin-btn-sm admin-btn-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber Dana</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Debit (Rp)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Kredit (Rp)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo (Rp)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $transactions->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $transaction->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->description }}</div>
                                @if($transaction->notes)
                                    <div class="text-xs text-gray-500">{{ $transaction->notes }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ \App\Models\Transaction::categories()[$transaction->category] ?? $transaction->category ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ \App\Models\Transaction::sources()[$transaction->source] ?? $transaction->source ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-green-600">
                                {{ $transaction->debit > 0 ? number_format($transaction->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-red-600">
                                {{ $transaction->credit > 0 ? number_format($transaction->credit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-gray-800">
                                {{ number_format($transaction->balance, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.transactions.edit', $transaction) }}" class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}"
                                          onclick="showDeleteModal(this.closest('form'))" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-coins text-3xl text-gray-300"></i>
                                    <p class="text-sm">Belum ada data transaksi.</p>
                                    <a href="{{ route('admin.transactions.create') }}" class="text-sm text-blue-600 hover:underline">Tambah transaksi</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection