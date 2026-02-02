<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainerSidebarAccess
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware(['trainer-sidebar:my_programs'])
     */
    public function handle(Request $request, Closure $next, ?string $requiredItem = null): Response
    {
        $user = $request->user();

        // No user or no required item — continue
        if (! $user || ! $requiredItem) {
            return $next($request);
        }

        // Admins bypass trainer-specific checks
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Only enforce for trainers; other roles bypass
        if ($user->role !== 'trainer') {
            return $next($request);
        }

        // Check if trainer has access to this sidebar item
        if ($user->trainer && $user->trainer->hasAccessTo($requiredItem)) {
            return $next($request);
        }

        // Access denied — return 403 Forbidden (no redirect)
        abort(403, 'You do not have permission to access this page.');
    }
}
