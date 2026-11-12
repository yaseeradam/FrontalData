<?php

use App\Http\Controllers\CoreController;

Route::prefix('docmentation')->middleware('auth', 'auth.session', 'confirm.setup', 'require_pin')->group(function () {
        Route::get('home', [CoreController::class, 'devUser'])->name('dev.user');
        Route::get('airtime', [CoreController::class, 'devAirtime'])->name('dev.airtime');
        Route::get('data', [CoreController::class, 'devData'])->name('dev.data');
        Route::get('cabletv', [CoreController::class, 'devTv'])->name('dev.tv');
        Route::get('electricity', [CoreController::class, 'devElectricity'])->name('dev.electricity');
        
        // VER
        Route::prefix('validations')->group(function () {
            Route::get('iuc', [CoreController::class, 'devIuc'])->name('dev.iuc');
            Route::get('meter', [CoreController::class, 'devMeter'])->name('dev.meter');
        });
});