<?php

use App\Http\Controllers\CoreController;

Route::prefix('callback')->group(function () {
    Route::post('/monnify', [CoreController::class, 'callback_monnify']);
    Route::post('/payvessel', [CoreController::class, 'callback_payvessel']);
    Route::post('/billstack', [CoreController::class, 'callback_billstack']);
    Route::post('/ncwallet', [CoreController::class, 'callback_ncwallet']);
})->middleware('callback.ips');