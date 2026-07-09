@extends('layouts.app')

@section('title', 'Activities - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Activities') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage activity listings</p>
        </div>
        <a href="{{ route('admin.activities.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add New') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th>{{ __('Min Pax') }}</th>
                        <th>{{ __('Featured') }}</th>
                        <th>{{ __('Order') }}</th>
                        <th class="!text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
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
                        <td>{{ $activity->min_pax }}</td>
                        <td>
                            @if($activity->is_featured)
                                <span class="admin-badge-green">Featured</span>
                            @else
                                <span class="admin-badge-gray">No</span>
                            @endif
                        </td>
                        <td>{{ $activity->order }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('admin.activities.edit', [$activity]) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.activities.destroy', [$activity]) }}" class="inline">
                                    @csrf @method('delete')
                                    <button type="button" onclick="showDeleteModal(this.closest('form'))" class="text-red-600 hover:text-red-800 transition-colors mt-4">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                            {{ __('No activities found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
</write_to_file>