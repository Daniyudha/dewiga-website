<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Check if user has any role assigned (admin access)
        $hasRole = $user->roles()->exists();
        
        if (!$hasRole) {
            abort(403, 'Forbidden. You do not have access to this area.');
        }
        
        return $next($request);
    }
}