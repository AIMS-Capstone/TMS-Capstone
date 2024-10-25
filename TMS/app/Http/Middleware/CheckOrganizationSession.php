<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOrganizationSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if organization_id is present in the session
        if (!$request->session()->has('organization_id')) {
            // Redirect to org-setup route if not found
            return redirect()->route('org-setup'); // Adjust this to your actual route name
        }

        return $next($request);
    }
}
