<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Bypass checks for login route
        if ($request->routeIs('auth.login')) {
            return $next($request);
        }

        // Redirect unauthenticated users
        if (! Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Please log in.');
        }

        // Check account status
        if (! Auth::user()->is_active) {
            Auth::logout();

            return redirect()->route('auth.login')
                ->with('error', 'Account disabled. Contact support.');
        }

        return $next($request);
    }
}
