<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmSetup
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->first_name || !$user->last_name || !$user->phone) {
            return redirect()->route('complete.setup');
        }

        return $next($request);
    }
}