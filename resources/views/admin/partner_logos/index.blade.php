@extends('layouts.app')

@section('title', 'Partner Logos - Admin Dewiga')

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #f0fdf4 !important;
    }
    .sortable-chosen {
        background: #f0fdf4;
    }
    .sortable-drag {
        opacity: 0.8;
        background: #fff !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .sortable-handle {
        cursor: grab;
        color: #9ca3af;
        transition: color 0.2s;
    }
    .sortable-handle:hover {
        color: #374151;
    }
    .sortable-handle:active {
        cursor: grabbing;
    }
    #savingIndicator {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }
    #savingIndicator.show {
        display: flex;
    }
</style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Partner Logos') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage client / partner logos displayed on homepage. Drag rows to reorder.</p>
        </div>
        <a href="{{ route('admin.partner_logos.create') }}" class="admin-btn-primary admin-btn-sm">
            <i class="fas fa-plus"></i>
            {{ __('Add Logo') }}
        </a>
    </div>

    {{-- Table Card --}}
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table" id="logosTable">
                <thead>
                    <tr>
                        <th class="w-10"></th>
                        <th>#</th>
                        <th>{{ __('Logo') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('URL') }}</th>
                        <th>{{ __('Order') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody id="logosSortable">
                    @forelse($partnerLogos as $logo)
                        <tr data-id="{{ $logo->id }}" class="sortable-row transition-colors duration-150 hover:bg-primary-50/50">
                            <td class="sortable-handle text-center">
                                <i class="fas fa-grip-vertical text-gray-300 hover:text-gray-500"></i>
                            </td>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $logo->image) }}"
                                     alt="{{ $logo->name }}"
                                     class="w-16 h-auto max-h-12 object-contain border border-gray-200 rounded-lg p-1 bg-white">
                            </td>
                            <td>
                                <span class="font-medium">{{ $logo->name }}</span>
                            </td>
                            <td>
                                @if($logo->url)
                                    <a href="{{ $logo->url }}" target="_blank" class="text-primary-600 hover:underline text-sm">
                                        {{ Str::limit($logo->url, 40) }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm order-badge">{{ $logo->order }}</span>
                            </td>
                            <td>
                                @if($logo->is_active)
                                    <span class="admin-badge-green">{{ __('Active') }}</span>
                                @else
                                    <span class="admin-badge-gray">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.partner_logos.edit', [$logo]) }}"
                                       class="admin-btn-sm admin-btn-secondary">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </a>

                                    <form method="POST" action="{{ route('admin.partner_logos.destroy', [$logo]) }}" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="button" onclick="showDeleteModal(this.closest('form'))" class="admin-btn-danger admin-btn-sm">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                <i class="fas fa-images text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No partner logos yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($partnerLogos->hasPages())
    <div class="mt-6">
        {{ $partnerLogos->links() }}
    </div>
    @endif

    {{-- Saving Indicator --}}
    <div id="savingIndicator" class="items-center gap-2 bg-primary-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
        <i class="fas fa-spinner fa-spin"></i>
        <span>{{ __('Saving order...') }}</span>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('logosSortable');
        if (!tbody) return;

        let reorderTimeout = null;
        const savingIndicator = document.getElementById('savingIndicator');

        new Sortable(tbody, {
            handle: '.sortable-handle',
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
            onEnd: function() {
                // Clear any pending save
                if (reorderTimeout) clearTimeout(reorderTimeout);

                // Debounce: wait 500ms after last drag before saving
                reorderTimeout = setTimeout(saveOrder, 500);
            }
        });

        function saveOrder() {
            const rows = document.querySelectorAll('#logosSortable .sortable-row');
            const ids = Array.from(rows).map(row => parseInt(row.dataset.id));

            if (ids.length === 0) return;

            // Show saving indicator
            savingIndicator.classList.add('show');

            // Update visual order numbers immediately
            rows.forEach((row, index) => {
                const orderBadge = row.querySelector('.order-badge');
                if (orderBadge) orderBadge.textContent = index;
                const numCell = row.querySelector('td:nth-child(2)');
                if (numCell) numCell.textContent = index + 1;
            });

            fetch('{{ route('admin.partner_logos.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show brief success feedback
                    savingIndicator.classList.remove('show');
                    savingIndicator.classList.add('show');
                    savingIndicator.innerHTML = '<i class="fas fa-check"></i> <span>{{ __('Order saved!') }}</span>';
                    setTimeout(() => {
                        savingIndicator.classList.remove('show');
                        savingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>{{ __('Saving order...') }}</span>';
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Reorder failed:', error);
                savingIndicator.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>{{ __('Failed to save order') }}</span>';
                setTimeout(() => {
                    savingIndicator.classList.remove('show');
                    savingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>{{ __('Saving order...') }}</span>';
                }, 3000);
            });
        }
    });
</script>
@endpush