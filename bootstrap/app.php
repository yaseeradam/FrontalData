<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'confirm.setup' => \App\Http\Middleware\ConfirmSetup::class,
            'auth.sessions' => \App\Http\Middleware\AuthenticateSession::class,
            'callback_ips' => \App\Http\Middleware\CallbackRegister::class,
            'require_pin' => \App\Http\Middleware\RequirePin::class,
            'auth.bearer' => \App\Http\Middleware\BearerToken::class,
        ]);
         $middleware->validateCsrfTokens(except: [
        'callback/*',
        'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
   