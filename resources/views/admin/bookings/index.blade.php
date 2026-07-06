@extends('layouts.app')

@section('title', 'Bookings - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Bookings') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage customer bookings</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Number Phone') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Travel Package') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="font-medium">{{ $booking->name }}</td>
                            <td class="text-gray-500">{{ $booking->email }}</td>
                            <td>{{ $booking->number_phone }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                            <td>
                                @if($booking->travel_package)
                                    <span class="admin-badge-blue">{{ $booking->travel_package->type }} - {{ $booking->travel_package->location }}</span>
                                @else
                                    <span class="admin-badge-gray">{{ __('General Inquiry') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <form method="POST" action="{{ route('admin.bookings.destroy', [$booking]) }}" class="inline">
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
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No bookings found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="admin-card-footer">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection
