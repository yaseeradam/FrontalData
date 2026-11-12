<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RequirePin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && session('requires_pin', false)) {
            return redirect()->route('core.auth');
        }

        return $next($request);
    }
}