@extends('layouts.app')

@section('title', 'Template Rundown')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Template Rundown</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola template kegiatan berdasarkan durasi paket</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.rundown-templates.create') }}" class="admin-btn-sm admin-btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Template
        </a>
    </div>
</div>

{{-- Search --}}
<div class="admin-card mb-6">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.rundown-templates.index') }}">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari template..." class="form-input flex-1">
                <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.rundown-templates.index') }}" class="admin-btn-sm admin-btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Template List --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($templates as $template)
        <div class="admin-card hover:shadow-lg transition-shadow duration-200 {{ $template->is_active ? '' : 'opacity-75' }}">
            <div class="admin-card-body">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-heading font-semibold text-gray-900">
                            <a href="{{ route('admin.rundown-templates.show', $template) }}" class="hover:text-primary-600">
                                {{ $template->name }}
                            </a>
                        </h3>
                        @if($template->code)
                            <span class="text-xs font-mono text-gray-500">{{ $template->code }}</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                @if($template->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $template->description }}</p>
                @endif

                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                    <span><i class="far fa-calendar mr-1"></i>{{ $template->duration_days }} Hari</span>
                    @if($template->duration_nights > 0)
                        <span><i class="fas fa-moon mr-1"></i>{{ $template->duration_nights }} Malam</span>
                    @endif
                    <span><i class="far fa-list-alt mr-1"></i>{{ $template->items_count }} Kegiatan</span>
                </div>

                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.rundown-templates.show', $template) }}" class="text-xs text-primary-600 hover:underline">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                    <a href="{{ route('admin.rundown-templates.edit', $template) }}" class="text-xs text-amber-600 hover:underline">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.rundown-templates.duplicate', $template) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline">
                            <i class="fas fa-copy mr-1"></i> Duplikasi
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.rundown-templates.toggle-active', $template) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs {{ $template->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                            <i class="fas {{ $template->is_active ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                            {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-clipboard-list text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-600 mb-1">Belum ada template rundown</h3>
            <p class="text-sm text-gray-400 mb-4">Buat template rundown pertama untuk memudahkan pembuatan jadwal kegiatan.</p>
            <a href="{{ route('admin.rundown-templates.create') }}" class="admin-btn-sm admin-btn-primary">
                <i class="fas fa-plus mr-1"></i> Buat Template
            </a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $templates->links() }}
</div>
@endsection