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

        // Check if user is marked as admin OR has any role assigned
        $isAdmin = $user->is_admin ?? false;
        $hasRole = $user->roles()->exists();

        if (!$isAdmin && !$hasRole) {
            abort(403, 'Forbidden. You do not have access to this area.');
        }

        return $next($request);
    }
}
