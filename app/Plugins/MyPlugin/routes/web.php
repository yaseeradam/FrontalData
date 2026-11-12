<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\MyPlugin\Controllers\MyPluginController;

/**
 * Define routes for MyPlugin.
 */

// Route accessible without authentication
Route::get('/plugin-test-unauthenticated', [MyPluginController::class, 'sampleU'])->name('plugin.unauthenticated');

// Routes requiring user authentication
Route::middleware(['auth', 'auth.session', 'confirm.setup', 'require_pin'])->group(function () {
    Route::get('/plugin-test', [MyPluginController::class, 'index'])->name('plugin.index');
});

// Routes accessible only by admins
Route::middleware('admin')->group(function () {
    Route::get('/admin/plugin-test', [MyPluginController::class, 'pluginAdmin'])->name('plugin.admin');
});
