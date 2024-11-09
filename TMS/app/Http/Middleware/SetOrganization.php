<?php

namespace App\Http\Middleware;

use App\Models\OrgSetup;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetOrganization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            $organization = OrgSetup::find($client->org_setup_id);

            if ($organization) {
                session(['organization' => $organization]);
            } else {
                // Optionally handle if no organization is found (e.g., redirect or error)
                return redirect()->route('client.login')->withErrors(['org_error' => 'Organization not found.']);
            }
        }

        return $next($request);
    }
}
