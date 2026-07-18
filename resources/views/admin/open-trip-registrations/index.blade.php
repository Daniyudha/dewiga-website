@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Open Trip Events</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.open-trip-registrations.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                <i class="fas fa-plus"></i> Add Registration
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Events Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schedules as $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $schedule->travelPackage->type }}
                            <br>
                            <span class="text-xs text-gray-500">{{ $schedule->visitor_name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $schedule->start_date->format('d M Y') }}
                            @if($schedule->end_date)
                                <br><span class="text-xs">→ {{ $schedule->end_date->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $schedule->travelPackage->location ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ $schedule->booked }}/{{ $schedule->quota }}</span>
                                @php $pct = $schedule->quota > 0 ? round(($schedule->booked / $schedule->quota) * 100) : 0; @endphp
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-yellow-500' : 'bg-emerald-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="font-medium">{{ $schedule->registrations_count ?? 0 }}</span> active
                            <br>
                            <span class="text-xs text-emerald-600">{{ $schedule->confirmed_count ?? 0 }} confirmed</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.open-trip-registrations.schedule', $schedule) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-users"></i> View Registrations
                            </a>
                            <a href="{{ route('admin.open-trip-registrations.export', $schedule) }}" 
                               class="text-emerald-600 hover:text-emerald-900">
                                <i class="fas fa-file-excel"></i> Export
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No open trip events found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($schedules->hasPages())
        <div class="mt-4">
            {{ $schedules->links() }}
        </div>
    @endif
</div>
@endsection
