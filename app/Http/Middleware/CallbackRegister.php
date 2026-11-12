<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CallbackRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $excludedRoutes = [
                         '/callback/billstack',
                         '/callback/payvessel'
                         ];

        if (in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        $trustedIps = explode(',', env('CALLBACK_IPS'));
        if (!in_array($request->ip(), $trustedIps)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}