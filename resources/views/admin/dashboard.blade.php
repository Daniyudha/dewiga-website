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


{{-- Operational Dashboard Section --}}
@if(isset($operationalStats))
<div class="mt-8 pt-8 border-t border-gray-200">
    <h2 class="text-xl font-heading font-bold text-gray-900 mb-4">Reservasi & Operasional</h2>

    {{-- Operational Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 max-w-full">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 flex-shrink-0">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Reservasi Pending</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $operationalStats['pending_reservations'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Reservasi Confirmed</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $operationalStats['confirmed_reservations'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Kunjungan Bulan Ini</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $operationalStats['visits_this_month'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 flex-shrink-0">
                        <i class="fas fa-file-invoice-dollar text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Nilai Quotation</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ formatPrice($operationalStats['quotation_value']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Pembayaran Diterima</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ formatPrice($operationalStats['payments_received']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                        <i class="fas fa-globe text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Open Trip Aktif</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $operationalStats['active_open_trips'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <i class="fas fa-calculator text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Estimasi Tindak Lanjut</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $operationalStats['unconverted_quotations'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">Sisa Tagihan</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ formatPrice($operationalStats['sisa_tagihan']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Reservations --}}
    <div class="admin-card mb-6">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">Reservasi Terdekat</h3>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Rombongan</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingReservations ?? [] as $res)
                            <tr>
                                <td class="whitespace-nowrap">{{ $res['start_date']->format('d/m/Y') }}</td>
                                <td class="font-medium">{{ $res['visitor_name'] ?? '-' }}</td>
                                <td>{{ $res['participants'] }}</td>
                                <td><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $res['status_badge'] }}">{{ $res['status_label'] }}</span></td>
                                <td><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{\App\Enums\PaymentStatus::badgeClass($res['payment_status'])}}">{{ $res['payment_status_label'] }}</span></td>
                                <td><a href="{{ route('admin.schedules.show', $res['id']) }}" class="text-primary-600 hover:underline text-sm">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-gray-500">Belum ada reservasi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quotations Need Follow Up --}}
    <div class="admin-card mb-6">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">Quotation Perlu Tindak Lanjut</h3>
        </div>
        <div class="admin-card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No. Estimasi</th>
                            <th>Instansi</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotationsNeedFollowUp ?? [] as $q)
                            <tr>
                                <td class="font-mono text-sm">{{ $q->estimation_number }}</td>
                                <td>{{ $q->institution_name }}</td>
                                <td class="font-mono">{{ formatPrice($q->quotation_total) }}</td>
                                <td>{{ $q->created_at->format('d/m/Y') }}</td>
                                <td><a href="{{ route('admin.price-calculator.show', $q) }}" class="text-primary-600 hover:underline text-sm">Lihat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">Semua estimasi sudah ditindaklanjuti</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
</div>{{-- .container end --}}

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
