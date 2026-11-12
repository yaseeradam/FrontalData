<?php

use App\Http\Controllers\CoreController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [CoreController::class, 'registerPage'])->name('register');
        Route::post('register', [CoreController::class, 'register']);
        Route::get('login', [CoreController::class, 'loginPage'])->name('login');
        Route::post('login', [CoreController::class, 'login']);
        Route::get('forgot-password', [CoreController::class, 'password'])->name('password.request');
        Route::post('forgot-password', [CoreController::class, 'passwordReset'])->name('password.email');
        Route::get('reset-password/{token}', [CoreController::class, 'reset'])->name('password.reset');
        Route::post('reset-password', [CoreController::class, 'resetPassword'])->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('verify-email', [CoreController::class, 'emailVerificationPrompt'])->name('verification.notice');
        Route::get('verify-email/{id}/{hash}', [CoreController::class, 'emailVerification'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('email/verification-notification', [CoreController::class, 'emailVerificationNotice'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [CoreController::class, 'updatePassword'])->name('password.update');
        Route::post('logout', [CoreController::class, 'destroy'])->name('logout');
    });
});
