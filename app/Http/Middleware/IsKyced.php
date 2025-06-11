<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsKyced
{

    public function handle(Request $request, Closure $next)
    {

        $user = Auth::user();

        if ($user->kyc_status === 'Pending') {

            return redirect()->route('user.dashboard')->with('kyc_pending', true);
        }

        return $next($request);
    }
}
