@extends('layouts.app')

@section('title', 'Schedules - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<style>
.fc { font-family: inherit; }
.fc .fc-toolbar { margin-bottom: 1.5rem; }
.fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; color: #1f2937; }
.fc .fc-button { background: #059669 !important; border: none !important; text-transform: capitalize !important; }
.fc .fc-button:hover { background: #047857 !important; }
.fc .fc-daygrid-day { transition: background-color 0.2s; cursor: pointer; }
.fc .fc-daygrid-day:hover { background: #f9fafb; }
.fc .fc-daygrid-day.fc-day-today { background: #f0fdf4; }
</style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Schedules') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Manage travel package schedules and availability') }}</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Schedule') }}
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="admin-card mb-6">
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, paket..."
                       class="admin-input flex-1 min-w-[200px]">
                <label class="text-sm font-medium text-gray-700">{{ __('Type') }}:</label>
                <select name="type" class="admin-input w-auto min-w-[150px]">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach(\App\Models\Schedule::types() as $val => $label)
                        <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn-md admin-btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('admin.schedules.index') }}" class="admin-btn-md admin-btn-secondary text-red-600">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Calendar Card (Collapsible) --}}
    <div class="admin-card mb-6">
        <div class="admin-card-header cursor-pointer select-none" onclick="toggleCalendar()">
            <div class="w-full flex items-center justify-between">
                <h2 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                    {{ __('Schedule Calendar') }}
                </h2>
                <div class="flex items-center gap-2">
                    <span id="calendarToggleIcon" class="text-gray-400 text-sm">
                        <i class="fas fa-chevron-up"></i>
                    </span>
                </div>
            </div>
        </div>
        <div id="calendarBody" class="admin-card-body">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- Schedule List Table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="font-heading font-semibold text-gray-800">{{ __('All Schedules') }}</h2>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Package') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Visitor') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Start Date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('End Date') }}</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quota') }}</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Booked') }}</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Remaining') }}</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-900 whitespace-nowrap">
                                    {{ $schedule->schedule_code ?? 'SCH' . str_pad($schedule->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->travelPackage->type ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $schedule->travelPackage->location ?? '' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $typeColors = [
                                            'open_trip' => 'bg-blue-100 text-blue-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $typeColor = $typeColors[$schedule->type] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColor }}">
                                        {{ $schedule->type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $schedule->visitor_name ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $schedule->start_date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $schedule->end_date ? $schedule->end_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-center font-medium">{{ $schedule->quota }}</td>
                                <td class="px-4 py-3 text-sm text-center">{{ $schedule->booked }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    @if($schedule->remainingQuota() > 0)
                                        <span class="text-green-600 font-medium">{{ $schedule->remainingQuota() }}</span>
                                    @else
                                        <span class="text-red-600 font-medium">{{ __('Full') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form method="POST" action="{{ route('admin.schedules.toggle-active', $schedule) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $schedule->is_active ? 0 : 1 }}">
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                            {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $schedule->is_active ? __('Active') : __('Inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.schedules.show', $schedule) }}"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium transition-colors" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                                            onsubmit="return confirm('{{ __('Are you sure?') }}')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                                    {{ __('No schedules found.') }}
                                    <a href="{{ route('admin.schedules.create') }}" class="text-emerald-600 hover:underline ml-1">
                                        {{ __('Add one now') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($schedules->hasPages())
            <div class="admin-card-footer px-4 py-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('calendarBody').style.display = 'none';
});

var calendarInitialized = false;
var calendarInstance = null;

function toggleCalendar() {
    const body = document.getElementById('calendarBody');
    const icon = document.getElementById('calendarToggleIcon');
    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.innerHTML = '<i class="fas fa-chevron-up"></i>';
        if (!calendarInitialized) {
            calendarInitialized = true;
            const calendarEl = document.getElementById('calendar');
            const events = @json($calendarEvents);
            calendarInstance = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: events,
                eventClick: function(info) {
                    window.location.href = '/admin/schedules/' + info.event.id + '/edit';
                },
                eventDidMount: function(info) {
                    const props = info.event.extendedProps;
                    let tooltip = '<b>' + props.package_name + '</b>';
                    if (props.visitor_name) tooltip += '<br>Visitor: ' + props.visitor_name;
                    tooltip += '<br>Quota: ' + props.quota + ' | Booked: ' + props.booked + ' | Remaining: ' + props.remaining;
                    if (props.end_date) tooltip += '<br>' + props.start_date + ' → ' + props.end_date;
                    info.el.setAttribute('title', tooltip.replace(/<[^>]*>/g, ''));
                }
            });
            calendarInstance.render();
        }
    } else {
        body.style.display = 'none';
        icon.innerHTML = '<i class="fas fa-chevron-down"></i>';
    }
}
</script>
@endpush
