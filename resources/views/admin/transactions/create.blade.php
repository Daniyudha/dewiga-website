@extends('layouts.app')

@section('title', 'Tambah Transaksi - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Tambah Transaksi</h1>
        <p class="text-sm text-gray-500 mt-1">Input data pemasukan atau pengeluaran baru</p>
    </div>
    <a href="{{ route('admin.transactions.index') }}" class="admin-btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
</div>

<div class="admin-card max-w-2xl">
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin.transactions.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="admin-form-group">
                    <label class="admin-form-label">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="admin-form-input @error('date') error @enderror" required>
                    @error('date')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Kategori</label>
                    <select name="category" class="admin-form-input @error('category') error @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach(\App\Models\Transaction::categories() as $val => $label)
                            <option value="{{ $val }}" {{ old('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group md:col-span-2">
                    <label class="admin-form-label">Deskripsi <span class="text-red-500">*</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" class="admin-form-input @error('description') error @enderror" placeholder="Keterangan transaksi" required>
                    @error('description')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Sumber Dana</label>
                    <select name="source" class="admin-form-input @error('source') error @enderror">
                        <option value="">Pilih Sumber</option>
                        @foreach(\App\Models\Transaction::sources() as $val => $label)
                            <option value="{{ $val }}" {{ old('source') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('source')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Jenis</label>
                    <select id="type" class="admin-form-input" onchange="toggleType()">
                        <option value="debit" {{ old('debit') ? 'selected' : '' }}>Pemasukan (Debit)</option>
                        <option value="credit" {{ old('credit') ? 'selected' : '' }}>Pengeluaran (Kredit)</option>
                    </select>
                </div>

                <div class="admin-form-group" id="debit_field">
                    <label class="admin-form-label">Jumlah Debit (Rp)</label>
                    <input type="number" name="debit" value="{{ old('debit') }}" class="admin-form-input @error('debit') error @enderror" min="0" step="1000" placeholder="0">
                    @error('debit')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group" id="credit_field" style="display:none">
                    <label class="admin-form-label">Jumlah Kredit (Rp)</label>
                    <input type="number" name="credit" value="{{ old('credit') }}" class="admin-form-input @error('credit') error @enderror" min="0" step="1000" placeholder="0">
                    @error('credit')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="admin-form-group md:col-span-2">
                    <label class="admin-form-label">Catatan</label>
                    <textarea name="notes" rows="3" class="admin-form-input @error('notes') error @enderror" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-4">
                <button type="submit" class="admin-btn-success"><i class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                <a href="{{ route('admin.transactions.index') }}" class="admin-btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleType() {
    const type = document.getElementById('type').value;
    document.getElementById('debit_field').style.display = type === 'debit' ? '' : 'none';
    document.getElementById('credit_field').style.display = type === 'credit' ? '' : 'none';
}
</script>
@endsection