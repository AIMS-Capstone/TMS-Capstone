<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuth
{
    public function handle($request, Closure $next)
    {
        if (!auth()->guard('client')->check()) {
            return redirect()->route('client.login');
        }
        return $next($request);
    }

}

