@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $schedule->travelPackage->type }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $schedule->start_date->format('d M Y') }} @if($schedule->end_date) - {{ $schedule->end_date->format('d M Y') }} @endif
                | {{ $schedule->travelPackage->location ?? '-' }}
                | Quota: {{ $schedule->booked }}/{{ $schedule->quota }}
            </p>
        </div>
        <div class="flex space-x-2">
            <form method="POST" action="{{ route('admin.open-trip-registrations.recalculate', $schedule) }}" onsubmit="return confirm('Recalculate booked count for this event?');" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-sync"></i> Recalculate Quota
                </button>
            </form>
            <a href="{{ route('admin.open-trip-registrations.export', $schedule) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                <i class="fas fa-file-excel"></i> Export .xls
            </a>
            <a href="{{ route('admin.open-trip-registrations.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2">
                &larr; Back to Events
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-500">Total Registrations</p>
            <p class="text-2xl font-bold text-gray-800">{{ $registrations->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-500">Confirmed</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $registrations->where('status', 'confirmed')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $registrations->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-500">Cancelled</p>
            <p class="text-2xl font-bold text-red-600">{{ $registrations->where('status', 'cancelled')->count() }}</p>
        </div>
    </div>

    {{-- Registrations Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">People</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registrations as $index => $registration)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $registration->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->number_phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->people_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $registration->status_badge }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                            @if($registration->participants->count() > 0)
                                <div class="space-y-1">
                                    @foreach($registration->participants as $participant)
                                        <div class="flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            <span>{{ $participant->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.open-trip-registrations.show', $registration) }}" 
                               class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('admin.open-trip-registrations.edit', $registration) }}" 
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form method="POST" action="{{ route('admin.open-trip-registrations.destroy', $registration) }}" 
                                  onsubmit="return confirm('Delete this registration?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No registrations for this event yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
