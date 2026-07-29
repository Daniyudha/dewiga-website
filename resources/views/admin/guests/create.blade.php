@extends('layouts.app')

@section('title', 'Tambah Tamu - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Tambah Tamu Manual</h1>
            <p class="text-sm text-gray-500 mt-1">Input data tamu secara manual ke database</p>
        </div>
        <a href="{{ route('admin.guests.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('message'))
        <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium
            @if(session('alert-type') == 'success') bg-green-100 text-green-800 border border-green-200
            @else bg-red-100 text-red-800 border border-red-200 @endif">
            {{ session('message') }}
        </div>
    @endif

    <div class="admin-card max-w-2xl">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.guests.store') }}">
                @csrf

                {{-- Nama PIC --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name"
                           class="admin-input w-full rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Asal/Instansi --}}
                <div class="mb-4">
                    <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Asal / Instansi</label>
                    <input type="text" id="institution" name="institution"
                           class="admin-input w-full rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('institution') border-red-500 @enderror"
                           value="{{ old('institution') }}">
                    @error('institution')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. Telepon --}}
                <div class="mb-4">
                    <label for="number_phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" id="number_phone" name="number_phone"
                           class="admin-input w-full rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('number_phone') border-red-500 @enderror"
                           value="{{ old('number_phone') }}" placeholder="08xxxxxxxxxx">
                    @error('number_phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email"
                           class="admin-input w-full rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="admin-input w-full rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.guests.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection