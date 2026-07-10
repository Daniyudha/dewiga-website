@extends('layouts.app')

@section('title', 'Activities - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Activities') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Drag & drop to reorder. Max 6 featured.</p>
        </div>
        <a href="{{ route('admin.activities.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add New') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table" id="sortable-table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th>{{ __('Featured') }}</th>
                        <th class="!text-center w-24">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    @forelse($activities as $activity)
                    <tr data-id="{{ $activity->id }}">
                        <td class="font-medium text-gray-900 cursor-grab handle">
                            <i class="fas fa-grip-vertical text-gray-400"></i>
                        </td>
                        <td class="max-w-[200px] truncate font-medium">{{ $activity->title_id }}</td>
                        <td>
                            @if($activity->image && file_exists(public_path('storage/' . $activity->image)))
                                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title_id }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $activity->duration_id }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.activities.update', [$activity]) }}" class="inline featured-form">
                                @csrf @method('put')
                                <input type="hidden" name="title_id" value="{{ $activity->title_id }}">
                                <input type="hidden" name="title_en" value="{{ $activity->title_en }}">
                                <input type="hidden" name="duration_id" value="{{ $activity->duration_id }}">
                                <input type="hidden" name="duration_en" value="{{ $activity->duration_en }}">
                                <input type="hidden" name="min_pax" value="{{ $activity->min_pax }}">
                                <input type="hidden" name="includes_id" value="{{ $activity->includes_id }}">
                                <input type="hidden" name="includes_en" value="{{ $activity->includes_en }}">
                                <input type="hidden" name="description_id" value="{{ $activity->description_id }}">
                                <input type="hidden" name="description_en" value="{{ $activity->description_en }}">
                                <input type="hidden" name="is_featured" value="{{ $activity->is_featured ? '0' : '1' }}">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 {{ $activity->is_featured ? 'bg-amber-100 text-amber-700 border border-amber-200 hover:bg-amber-200' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}">
                                    <i class="fas fa-star text-xs"></i>
                                    {{ $activity->is_featured ? __('Featured') : __('Set Featured') }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('admin.activities.edit', [$activity]) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.activities.destroy', [$activity]) }}" class="inline">
                                    @csrf @method('delete')
                                    <button type="button" onclick="showDeleteModal(this.closest('form'))" class="text-red-600 hover:text-red-800 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                            {{ __('No activities found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Featured Limit Modal --}}
    <div id="featuredModal" class="modal-overlay" onclick="if(event.target===this) closeFeaturedModal()">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>{{ __('Featured Limit Reached') }}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('Maksimal 6 aktivitas dapat difeatured. Hapus featured dari aktivitas lain terlebih dahulu.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeFeaturedModal()" class="admin-btn-primary admin-btn-sm">
                    <i class="fas fa-check"></i>
                    {{ __('OK, Mengerti') }}
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableEl = document.getElementById('sortable');
    if (!sortableEl) return;

    new Sortable(sortableEl, {
        animation: 150,
        handle: '.handle',
        onEnd: function() {
            const ids = [];
            sortableEl.querySelectorAll('tr[data-id]').forEach(function(tr) {
                ids.push(tr.dataset.id);
            });
            fetch('{{ route("admin.activities.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: ids })
            }).then(function(res) {
                if (!res.ok) console.error('Reorder failed');
            }).catch(function(err) {
                console.error('Reorder error:', err);
            });
        }
    });

    // Featured limit modal
    let featuredFormToSubmit = null;

    window.confirmFeaturedForm = function() {
        if (featuredFormToSubmit) {
            featuredFormToSubmit.submit();
            featuredFormToSubmit = null;
        }
        closeFeaturedModal();
    };

    window.closeFeaturedModal = function() {
        document.getElementById('featuredModal').classList.remove('open');
        document.body.style.overflow = '';
        featuredFormToSubmit = null;
    };

    document.querySelectorAll('.featured-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const featuredCount = {{ $activities->where('is_featured', true)->count() }};
            const isFeatured = this.querySelector('input[name="is_featured"]').value === '1';
            if (isFeatured && featuredCount >= 6) {
                e.preventDefault();
                featuredFormToSubmit = this;
                document.getElementById('featuredModal').classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });
    });
});
</script>
@endpush