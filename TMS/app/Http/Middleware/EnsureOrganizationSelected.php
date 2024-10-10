<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        if (!$request->session()->has('organization')) {
            // Redirect to organization selection page or show an error
            return redirect()->route('org-setup')->with('error', 'Please select an organization.');
        }

        return $next($request);
    }
}
