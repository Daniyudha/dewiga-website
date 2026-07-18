@extends('layouts.app')

@section('title', 'Dashboard - Admin Dewiga')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/Chart.min.css">
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Dashboard') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Welcome back, :name!', ['name' => Auth::user()->name]) }}</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Total Visits') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalVisits ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Today') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($todayVisits ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                        <i class="fas fa-calendar-week text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('This Week') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($weekVisits ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('This Month') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($monthVisits ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Daily Visits Chart --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">{{ __('Daily Visits (Last 30 Days)') }}</h3>
            </div>
            <div class="admin-card-body">
                <canvas id="dailyVisitsChart" height="200"></canvas>
            </div>
        </div>

        {{-- Device Stats Chart --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">{{ __('Device Distribution') }}</h3>
            </div>
            <div class="admin-card-body">
                <canvas id="deviceChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Pages --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">{{ __('Top Pages') }}</h3>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Page URL') }}</th>
                            <th>{{ __('Visits') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPages ?? [] as $index => $page)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-mono text-sm">{{ $page->url }}</td>
                                <td><span class="admin-badge-primary">{{ $page->visits }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">{{ __('No data yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Visits Chart
    const dailyVisitsCtx = document.getElementById('dailyVisitsChart');
    if (dailyVisitsCtx) {
        const dailyVisitsData = @json($dailyVisits ?? []);
        const labels = dailyVisitsData.map(item => item.date);
        const data = dailyVisitsData.map(item => item.visits);

        new Chart(dailyVisitsCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ __('Visits') }}',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // Device Stats Chart
    const deviceCtx = document.getElementById('deviceChart');
    if (deviceCtx) {
        const deviceStats = @json($deviceStats ?? ['desktop' => 0, 'mobile' => 0, 'tablet' => 0]);
        
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: ['{{ __('Desktop') }}', '{{ __('Mobile') }}', '{{ __('Tablet') }}'],
                datasets: [{
                    data: [deviceStats.desktop, deviceStats.mobile, deviceStats.tablet],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
