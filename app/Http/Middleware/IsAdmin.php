<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login route
        if ($request->routeIs('auth.login')) {
            return $next($request);
        }

        // Redirect unauthenticated users
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Please log in.');
        }

        // Redirect if user is inactive
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('auth.login')->with('error', 'Account disabled. Contact support.');
        }

        // Allow admins or proceed for all others
        if (in_array(Auth::user()->role, ['admin', 'super admin'])) {
            return $next($request);
        }

        // Default redirect for unauthorized roles
        return redirect()->route('user.dashboard')->with('error', 'Unauthorized access.');
    }
}
