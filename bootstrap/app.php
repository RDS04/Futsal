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
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude webhook endpoints dari CSRF protection (Midtrans notification)
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
            'api/payment-token', // API endpoint juga harus di-exclude atau gunakan token auth
        ]);

        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.role' => \App\Http\Middleware\AdminRoleMiddleware::class,
            'admin.master' => \App\Http\Middleware\AdminMasterMiddleware::class,
            'region.access' => \App\Http\Middleware\RegionAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
