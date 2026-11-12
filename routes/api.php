<?php

use App\Http\Controllers\CoreController;

Route::prefix('api')->middleware('auth.bearer')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [CoreController::class, 'login']);
        Route::post('register', [CoreController::class, 'register']);
        Route::post('complete', [CoreController::class, 'saveSetup']);
        Route::post('reset-password', [CoreController::class, 'passwordReset']);
    });

    Route::prefix('product')->group(function () {
        Route::get('airtime', [CoreController::class, 'airtime']);
        Route::get('data', [CoreController::class, 'data']);
        Route::get('cabletv', [CoreController::class, 'tv']);
        Route::get('electricity', [CoreController::class, 'electricity']);
        Route::get('epins', [CoreController::class, 'epin']);
        Route::get('recharge_pins', [CoreController::class, 'recharge']);
        Route::get('betting', [CoreController::class, 'betting']);
        Route::get('data_pins', [CoreController::class, 'datacard']);
    });

    Route::prefix('purchase')->group(function () {
        Route::post('airtime', [CoreController::class, 'buyAirtime']);
        Route::post('data', [CoreController::class, 'buyData']);
        Route::post('cabletv', [CoreController::class, 'subTv']);
        Route::post('electricity', [CoreController::class, 'payElectricity']);
        Route::post('epins', [CoreController::class, 'buyEpins']);
    });

    Route::prefix('verify')->group(function () {
        Route::post('iuc', [CoreController::class, 'verifyIUC']);
        Route::post('meter', [CoreController::class, 'verifyMeter']);
        Route::post('token', [CoreController::class, 'ApiVerifyToken']);
        
    });

    Route::any('/', function () {
        return response()->json(['status' => 'success', 'message' => 'Welcome to ' . config('app.name') . ' API']);
    });
    
    Route::any('/app/update', function () {
        return response()->json([
                                 'status' => 'success',
                                 "version" => "1.0.0",
                                 'update_type' => "required",
                                 "download_url" => "https://demo.al-manarah.com.ng/release/app/",
                                 "description" => "We have exciting new features and improvements!"

]);
    });
    
    ##@ GENERAL 
    Route::prefix('user')->group(function () {
        Route::get('info', [CoreController::class, 'userInfo']);
        Route::get('transactions', [CoreController::class, 'userTransactions']);
        Route::post('edit', [CoreController::class, 'userUpdate']);
        Route::post('password', [CoreController::class, 'userPasswordUpdate']);
        Route::post('pin', [CoreController::class, 'userPinUpdate']);
        Route::post('kyc', [CoreController::class, 'kycVerify']);
        Route::post('upgrade', [CoreController::class, 'userUpgradeRole']);
       
        
    });
    
    Route::get('/notifications', [CoreController::class, 'notifications']);
    Route::get('/options', [CoreController::class, 'options']);
    Route::get('/users', [CoreController::class, 'users']);
    Route::get('/transaction/{session_id}', [CoreController::class, 'transaction'])
    ->where('session_id', '[0-9]+');
});