<?php

namespace App\Plugins\MyPlugin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for handling MyPlugin's core functionality.
 */
class MyPluginController extends Controller
{
    /**
     * Display the main plugin index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('plugins.myplugin::index', compact("user"));
    }
    
    /**
     * Display the plugin's admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function pluginAdmin()
    {
        return view('plugins.myplugin::admin');
    }
}
