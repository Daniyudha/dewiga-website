@extends('layouts.app')

@section('title', 'Bookings - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Bookings') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage customer bookings and reservations</p>
        </div>
        <a href="{{ route('admin.bookings.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Booking') }}
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('message'))
        <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium
            @if(session('alert-type') == 'success') bg-green-100 text-green-800 border border-green-200
            @else bg-red-100 text-red-800 border border-red-200 @endif">
            {{ session('message') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="admin-card mb-6">
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-wrap items-center gap-3">
                <label class="text-sm font-medium text-gray-700">{{ __('Status') }}:</label>
                <select name="status" class="admin-input w-auto min-w-[150px] py-2 px-2 rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">{{ __('All Status') }}</option>
                    @foreach(\App\Models\Booking::statuses() as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <label class="text-sm font-medium text-gray-700 ml-2">{{ __('Date From') }}:</label>
                <input type="date" name="date_from" class="admin-input w-auto py-2 px-2 rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_from') }}" onchange="this.form.submit()">

                <label class="text-sm font-medium text-gray-700 ml-2">{{ __('Date To') }}:</label>
                <input type="date" name="date_to" class="admin-input w-auto py-2 px-2 rounded-lg shadow-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_to') }}" onchange="this.form.submit()">

                @if(request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.bookings.index') }}" class="text-sm text-red-600 hover:underline">
                        <i class="fas fa-times"></i> {{ __('Clear Filter') }}
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    @php
        $totalBookings = \App\Models\Booking::count();
        $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
        $confirmedCount = \App\Models\Booking::where('status', 'confirmed')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="admin-card">
            <div class="admin-card-body flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    <p class="text-sm text-gray-500">{{ __('Total Bookings') }}</p>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                    <p class="text-sm text-gray-500">{{ __('Pending') }}</p>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $confirmedCount }}</p>
                    <p class="text-sm text-gray-500">{{ __('Confirmed') }}</p>
                </div>
            </div>
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
                        <th>{{ __('Institution') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Package') }}</th>
                        <th>{{ __('Schedule') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('People') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="!text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="font-medium">{{ $booking->name }}</td>
                            <td>
                                <span class="text-xs text-gray-600">{{ $booking->institution ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="text-sm">{{ $booking->email }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->number_phone }}</div>
                            </td>
                            <td>{{ $booking->date->format('d M Y') }}</td>
                            <td>
                                @if($booking->end_date)
                                    {{ $booking->end_date->format('d M Y') }}
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->amount)
                                    Rp {{ number_format($booking->amount, 0, ',', '.') }}
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->travel_package)
                                    <span class="admin-badge-blue text-xs">{{ $booking->travel_package->type }}</span>
                                @else
                                    <span class="admin-badge-gray text-xs">{{ __('General Inquiry') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->schedule)
                                    <span class="text-xs text-gray-600">
                                        {{ $booking->schedule->start_date->format('d M') }}
                                        @if($booking->schedule->visitor_name)
                                            - {{ $booking->schedule->visitor_name }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($booking->people_count)
                                    {{ $booking->people_count }} {{ __('person') }}{{ $booking->people_count > 1 ? 's' : '' }}
                                @else
                                    <span class="text-xs text-gray-400">1</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->participants->isNotEmpty())
                                    <div class="text-xs">
                                        @foreach($booking->participants as $participant)
                                            <div class="mb-1">
                                                <span class="font-medium">{{ $participant->name }}</span>
                                                @if($participant->email)
                                                    <br><span class="text-gray-500">{{ $participant->email }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($booking->description)
                                    <span class="text-xs text-gray-600" title="{{ $booking->description }}">
                                        {{ Str::limit($booking->description, 30) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm transition-colors" title="{{ __('Confirm') }}">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm transition-colors" title="{{ __('Cancel') }}">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-blue-600 hover:text-blue-800 text-sm transition-colors" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                                        onsubmit="return confirm('{{ __('Are you sure?') }}')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm transition-colors" title="{{ __('Delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center py-8 text-gray-500">
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
                {{ $bookings->links("vendor.pagination.tailwind") }}
            </div>
        @endif
    </div>
@endsection