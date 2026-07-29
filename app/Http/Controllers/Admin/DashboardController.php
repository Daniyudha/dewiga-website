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

    public function index()
    {
        $user = auth()->user();

        // Show simple dashboard for non-super_admin users (limited access)
        if (!$user->isSuperAdmin()) {
            return view('admin.dashboard-simple');
        }

        // Check if analytics table exists
        if (!Schema::hasTable('analytics')) {
            return view('admin.dashboard', [
                'totalVisits' => 0,
                'todayVisits' => 0,
                'weekVisits' => 0,
                'monthVisits' => 0,
                'topPages' => collect(),
                'deviceStats' => ['desktop' => 0, 'mobile' => 0, 'tablet' => 0],
                'dailyVisits' => collect(),
                'operationalStats' => $this->dashboardService->getOperationalStats(),
                'upcomingReservations' => collect(),
                'quotationsNeedFollowUp' => collect(),
                'openTripsNearlyFull' => collect(),
            ]);
        }

        $totalVisits = Analytics::count();
        $todayVisits = Analytics::today()->count();
        $weekVisits = Analytics::thisWeek()->count();
        $monthVisits = Analytics::thisMonth()->count();

        $topPages = Analytics::select('url')
            ->selectRaw('count(*) as visits')
            ->groupBy('url')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();

        $deviceStats = [
            'desktop' => Analytics::where('device_type', 'desktop')->count(),
            'mobile' => Analytics::where('device_type', 'mobile')->count(),
            'tablet' => Analytics::where('device_type', 'tablet')->count(),
        ];

        $dailyVisits = Analytics::selectRaw('DATE(visited_at) as date, count(*) as visits')
            ->where('visited_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Operational statistics
        $operationalStats = $this->dashboardService->getOperationalStats();
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
            'openTripsNearlyFull'
        ));
    }
}
