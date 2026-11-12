<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SingleSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();

            $session = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $sessionId)
                ->first();

            if ($session) {
                DB::table('sessions')->where('id', $session->id)->delete();
            }

            DB::table('users')->where('id', $user->id)->update(['session_id' => $sessionId]);
        }

        return $next($request);
    }
}