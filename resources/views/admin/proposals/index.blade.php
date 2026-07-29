@extends('layouts.app')

@section('title', 'Proposal Program')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Proposal Program</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar proposal program Desa Wisata Gabugan</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.proposals.settings') }}" class="admin-btn-sm admin-btn-info">
            <i class="fas fa-cog mr-1"></i> Pengaturan Harga
        </a>
        <a href="{{ route('admin.proposal-settings.index') }}" class="admin-btn-sm admin-btn-info">
            <i class="fas fa-sliders-h mr-1"></i> Profil Desa
        </a>
        <a href="{{ route('admin.proposals.create') }}" class="admin-btn-sm admin-btn-primary">
            <i class="fas fa-plus mr-1"></i> Buat Proposal
        </a>
    </div>
</div>

<div class="admin-card mb-6">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.proposals.index') }}">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proposal..." class="form-input flex-1">
                <select name="status" class="form-input w-48">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\ProposalStatus::labels() as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn-sm admin-btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.proposals.index') }}" class="admin-btn-sm admin-btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 gap-4">
    @forelse($proposals as $proposal)
    <div class="admin-card hover:shadow-lg transition-shadow duration-200">
        <div class="admin-card-body">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-heading font-semibold text-gray-900">
                            <a href="{{ route('admin.proposals.show', $proposal) }}" class="hover:text-primary-600">
                                {{ $proposal->proposal_title ?? 'Proposal ' . $proposal->institution_name }}
                            </a>
                        </h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ \App\Enums\ProposalStatus::badgeClass($proposal->proposal_status) }}">
                            {{ \App\Enums\ProposalStatus::label($proposal->proposal_status) }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-500">
                        <span><i class="fas fa-school mr-1"></i>{{ $proposal->institution_name }}</span>
                        <span><i class="fas fa-hashtag mr-1"></i>{{ $proposal->proposal_number ?? $proposal->estimation_number }}</span>
                        <span><i class="fas fa-users mr-1"></i>{{ $proposal->service_participant_count }} peserta</span>
                        <span><i class="fas fa-calendar mr-1"></i>{{ $proposal->arrival_date->format('d/m/Y') }} - {{ $proposal->departure_date->format('d/m/Y') }}</span>
                        <span><i class="fas fa-tag mr-1"></i>{{ formatPrice($proposal->rounded_price_per_person) }}/org</span>
                        <span><i class="fas fa-file-invoice mr-1"></i>{{ formatPrice($proposal->quotation_total) }}</span>
                    </div>
                </div>
                <div class="flex gap-2 ml-4">
                    <a href="{{ route('admin.proposals.show', $proposal) }}" class="admin-btn-xs admin-btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.proposals.pdf-view', $proposal) }}" target="_blank" class="admin-btn-xs admin-btn-info">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <div class="text-gray-300 mb-4"><i class="fas fa-file-invoice text-6xl"></i></div>
        <h3 class="text-lg font-medium text-gray-600 mb-2">Belum ada proposal</h3>
        <p class="text-sm text-gray-400 mb-4">Buat proposal program pertama untuk dikirim ke calon peserta.</p>
        <a href="{{ route('admin.proposals.create') }}" class="admin-btn-sm admin-btn-primary"><i class="fas fa-plus mr-1"></i> Buat Proposal</a>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $proposals->links() }}</div>
@endsection