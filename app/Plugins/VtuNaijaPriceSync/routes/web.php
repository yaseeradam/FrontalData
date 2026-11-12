<?php

use App\Plugins\VtuNaijaPriceSync\Controllers\VtuNaijaPriceSyncController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')
    ->prefix('admin/vtunaija-price-sync')
    ->name('admin.vtunaija-price-sync.')
    ->group(function () {
        Route::get('/', [VtuNaijaPriceSyncController::class, 'index'])->name('index');
        Route::get('/setup', [VtuNaijaPriceSyncController::class, 'setup'])->name('setup');
        Route::post('/setup', [VtuNaijaPriceSyncController::class, 'storeSetup'])->name('setup.store');
        Route::post('/preview', [VtuNaijaPriceSyncController::class, 'preview'])->name('preview');
        Route::post('/apply', [VtuNaijaPriceSyncController::class, 'apply'])->name('apply');
        Route::post('/rules', [VtuNaijaPriceSyncController::class, 'storeRule'])->name('rules.store');
        Route::delete('/rules/{id}', [VtuNaijaPriceSyncController::class, 'deleteRule'])->name('rules.delete');
        Route::get('/runs/{id}', [VtuNaijaPriceSyncController::class, 'run'])->name('runs.show');
    });
