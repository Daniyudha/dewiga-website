<?php

namespace App\Http\Middleware;

use App\Models\Analytics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Track only GET requests and not admin routes, and only if table exists
        if ($request->method() === 'GET' && !$request->is('admin/*') && Schema::hasTable('analytics')) {
            $this->trackVisit($request);
        }

        return $response;
    }

    private function trackVisit($request)
    {
        try {
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
        } catch (\Exception $e) {
            // Silently fail if there's any error
        }
    }

    private function getCountry($ip)
    {
        return 'ID';
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