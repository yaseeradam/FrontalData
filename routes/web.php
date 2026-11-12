<?php

use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;
try {
    $status = jannah_opt("homepage_status");
} catch (\Throwable $e) {
    // DB not ready (fresh install, etc.) — fall through to the dashboard route.
    $status = null;
}
Route::redirect('/login', '/auth/login');
Route::redirect('/register', '/auth/register');
Route::get('/complete-setup', [CoreController::class, 'completeSetup'])->middleware(['auth', 'verified', 'auth.session'])->name('complete.setup');
Route::post('/complete-setup', [CoreController::class, 'saveSetup'])->middleware(['auth', 'verified', 'auth.session'])->name('setup.save');
if($status == "enabled")
{
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return app(CoreController::class)->welcome();
});

Route::get('/dashboard', [CoreController::class, 'load'])->middleware(['auth', 'verified', 'auth.session', 'confirm.setup'])->name('dashboard');
} else {
    Route::get('/', [CoreController::class, 'load'])->middleware(['auth', 'verified', 'auth.session', 'confirm.setup'])->name('dashboard');
}
Route::middleware('auth', 'auth.session', 'confirm.setup', 'require_pin')->group(function () {
    Route::get('/profile', [CoreController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [CoreController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [CoreController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/clear-cache', function () {
    if (app()->environment('local')) {
      Artisan::call('route:clear');
      Artisan::call('view:clear');
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('event:clear');
    
    return "All caches have been cleared!";
    }
    return "Unauthorized.";
});
Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
});


require __DIR__.'/auth.php';
require __DIR__.'/dev.php';
require __DIR__.'/api.php';
require __DIR__.'/admin.php';
require __DIR__.'/core.php';
require __DIR__.'/callback.php';
