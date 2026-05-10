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
        $middleware->alias([
            'IsAuth'   => App\Http\Middleware\IsAuth::class,
            'IsMember' => App\Http\Middleware\CheckMembership::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'movie',
            'movie/*',
            'request/'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(\Illuminate\Auth\AuthenticationException $e, $request){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        });
    })->create();