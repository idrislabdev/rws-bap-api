<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsMSO
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
        if (Auth::user()->role === 'ROOT' || Auth::user()->role === 'ADMIN' || Auth::user()->role === 'RWS' || Auth::user()->role === 'MSO') {
            return $next($request);
        } else {
            return response()->json(['error' => 'Unaothorized'], 403);
        }
    }
}
