<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // Tambahkan baris ini untuk mengecualikan callback Midtrans dari CSRF
        $middleware->validateCsrfTokens(except: [
            'api/checkout/callback',
        ]);

        $middleware->alias([
            'is.admin' => \App\Http\Middleware\IsAdmin::class,
            'is.seller'=> \App\Http\Middleware\IsSeller::class,
            'seller'=> \App\Http\Middleware\CheckSeller::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();