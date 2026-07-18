<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function track(Request $request)
    {
        Analytics::create([
            'url' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'country' => $this->getCountry($request->ip()),
            'device_type' => $this->getDeviceType($request->userAgent()),
            'browser' => $this->getBrowser($request->userAgent()),
            'visited_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function dashboard()
    {
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

        return view('admin.dashboard', compact(
            'totalVisits',
            'todayVisits',
            'weekVisits',
            'monthVisits',
            'topPages',
            'deviceStats',
            'dailyVisits'
        ));
    }

    private function getCountry($ip)
    {
        // Simple implementation - can be enhanced with geoip package
        return 'ID'; // Default to Indonesia
    }

    private function getDeviceType($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false || strpos($userAgent, 'iphone') !== false) {
            return 'mobile';
        }
        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function getBrowser($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false) {
            return 'Chrome';
        }
        if (strpos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        }
        if (strpos($userAgent, 'safari') !== false) {
            return 'Safari';
        }
        if (strpos($userAgent, 'edge') !== false) {
            return 'Edge';
        }
        return 'Other';
    }
}