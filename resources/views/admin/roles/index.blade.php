@extends('layouts.app')

@section('title', 'Role & Permission - Admin')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Role & Permission</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola jenis akun dan hak akses menu</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="admin-btn-primary">
        <i class="fas fa-plus"></i> Tambah Role
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    @foreach($roles as $role)
    <div class="admin-card hover:shadow-lg transition-shadow">
        <div class="admin-card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold"
                         style="background: {{ $role->slug === 'super_admin' ? '#dc2626' : ($role->slug === 'admin' ? '#059669' : ($role->slug === 'finance' ? '#2563eb' : '#8b5cf6')) }}">
                        {{ strtoupper(substr($role->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $role->slug }}</p>
                    </div>
                </div>
                @if($role->is_default)
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-semibold rounded-full">Default</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $role->description ?? 'Tidak ada deskripsi' }}</p>
            <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                <span><strong class="text-gray-800">{{ $role->permissions_count }}</strong> Permission</span>
                <span><strong class="text-gray-800">{{ $role->users_count }}</strong> Pengguna</span>
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if(!$role->is_default && $role->slug !== 'super_admin')
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                      onclick="showDeleteModal(this.closest('form'))" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 ml-3">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection