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
        $middleware->alias([
            'IsAuth'   => App\Http\Middleware\IsAuth::class,    // ✅ Urutan dirapikan
            'IsMember' => App\Http\Middleware\CheckMembership::class,
        ]);

        // ✅ Hati-hati: except: ['*'] menonaktifkan CSRF untuk SEMUA route
        // Ini oke untuk development/API, tapi jangan dipakai di production untuk web form
        $middleware->validateCsrfTokens(except: [
           'movie',
           'movie/*',
           'request/'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();