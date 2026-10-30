@extends('layouts.app')

@section('title', 'Events - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Events') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola event dan tentukan homestay/kegiatan yang aktif untuk tiap event.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add New') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>{{ __('Name') }}</th>
                        <th class="!text-center">{{ __('Date') }}</th>
                        <th class="!text-center">{{ __('Participants') }}</th>
                        <th class="!text-center">{{ __('Active') }}</th>
                        <th class="!text-center w-32">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="max-w-[220px] truncate font-medium">{{ $event->name_id }}</td>
                        <td class="!text-center text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                        </td>
                        <td class="!text-center">{{ $event->participant_count }}</td>
                        <td class="!text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold {{ $event->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $event->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="!text-center">
                            <a href="{{ route('admin.events.edit', $event) }}" class="admin-btn-sm admin-btn-outline"><i class="fas fa-pen"></i></a>
                            <a href="{{ route('admin.events.assign-homestays', $event) }}" class="admin-btn-sm admin-btn-outline" title="{{ __('Assign Homestays') }}"><i class="fas fa-home"></i></a>
                            <a href="{{ route('admin.events.assign-activities', $event) }}" class="admin-btn-sm admin-btn-outline" title="{{ __('Assign Activities') }}"><i class="fas fa-running"></i></a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('delete')
                                <button type="submit" class="admin-btn-sm admin-btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">{{ __('No data found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
@endsection
