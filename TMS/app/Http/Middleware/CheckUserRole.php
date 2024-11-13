<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Get the authenticated user's role
        $userRole = Auth::user()->role;

        // Define restricted routes for the Accountant role
        $restrictedRoutes = [
            'recycle-bin*', // Matches /recycle-bin and all sub-routes
            'user-management*', // Matches /user-management and all sub-routes
        ];

        // Check if the user's role is "Accountant" and if they’re trying to access restricted routes
        if ($userRole === $role && $this->isRestrictedRoute($request, $restrictedRoutes)) {
            return redirect()->route('dashboard')->with('error', 'Access Denied: Restricted Area');
        }

        return $next($request);
    }

    /**
     * Check if the current route is restricted.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $restrictedRoutes
     * @return bool
     */
    protected function isRestrictedRoute($request, array $restrictedRoutes)
    {
        foreach ($restrictedRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }
        return false;
    }
}
