<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
        {
            // Log the user out
            Auth::logout();
    
            // Invalidate the session
            $request->session()->invalidate();
    
            // Regenerate the session token to prevent CSRF attacks
            $request->session()->regenerateToken();
    
            // Redirect to the login page or any other page after logout
            return redirect()->route('login'); // Adjust this route if necessary
        }
}