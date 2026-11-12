<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($request->session()->getId() !== $user->current_session_id) {
                Auth::logout();
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}