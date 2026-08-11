<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Services\DashboardStatisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    protected DashboardStatisticService $dashboardService;

    public function __construct(DashboardStatisticService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Show simple dashboard for non-super_admin users (limited access)
        if (!$user->isSuperAdmin()) {
            return view('admin.dashboard-simple');
        }

        [$startDate, $endDate] = $this->resolvePeriod($request);

        // Check if analytics table exists
        if (!Schema::hasTable('analytics')) {
            return $this->renderEmptyDashboard($startDate, $endDate);
        }

        $totalVisits = Analytics::whereBetween('visited_at', [$startDate, $endDate])->count();

        // Real-time stats (not filtered by period)
        $todayVisits = Analytics::today()->count();
        $weekVisits = Analytics::thisWeek()->count();
        $monthVisits = Analytics::thisMonth()->count();

        $topPages = Analytics::select('url')
            ->selectRaw('count(*) as visits')
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('url')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();

        $deviceStats = [
            'desktop' => Analytics::where('device_type', 'desktop')->whereBetween('visited_at', [$startDate, $endDate])->count(),
            'mobile' => Analytics::where('device_type', 'mobile')->whereBetween('visited_at', [$startDate, $endDate])->count(),
            'tablet' => Analytics::where('device_type', 'tablet')->whereBetween('visited_at', [$startDate, $endDate])->count(),
        ];

        $dailyVisits = Analytics::selectRaw('DATE(visited_at) as date, count(*) as visits')
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $operationalStats = $this->dashboardService->getOperationalStats($startDate, $endDate);
        $upcomingReservations = $this->dashboardService->getUpcomingReservations();
        $quotationsNeedFollowUp = $this->dashboardService->getQuotationsNeedFollowUp();
        $openTripsNearlyFull = $this->dashboardService->getOpenTripsNearlyFull();

        return view('admin.dashboard', compact(
            'totalVisits',
            'todayVisits',
            'weekVisits',
            'monthVisits',
            'topPages',
            'deviceStats',
            'dailyVisits',
            'operationalStats',
            'upcomingReservations',
            'quotationsNeedFollowUp',
            'openTripsNearlyFull',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Resolve the filter period from the request.
     * Defaults to the last 3 months.
     *
     * @return array{0: \Carbon\Carbon, 1: \Carbon\Carbon} [startDate, endDate]
     */
    private function resolvePeriod(Request $request): array
    {
        $startDate = $request->filled('start_date')
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay()
            : now()->subMonths(3)->startOfDay();

        $endDate = $request->filled('end_date')
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        return [$startDate, $endDate];
    }

    /**
     * Render dashboard with empty analytics data.
     */
    private function renderEmptyDashboard($startDate, $endDate)
    {
        return view('admin.dashboard', [
            'totalVisits' => 0,
            'todayVisits' => 0,
            'weekVisits' => 0,
            'monthVisits' => 0,
            'topPages' => collect(),
            'deviceStats' => ['desktop' => 0, 'mobile' => 0, 'tablet' => 0],
            'dailyVisits' => collect(),
            'operationalStats' => $this->dashboardService->getOperationalStats($startDate, $endDate),
            'upcomingReservations' => collect(),
            'quotationsNeedFollowUp' => collect(),
            'openTripsNearlyFull' => collect(),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
