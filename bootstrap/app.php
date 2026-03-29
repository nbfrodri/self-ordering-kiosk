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
        // Ensure the API middleware group includes throttle and CORS.
        // Laravel 11+ includes HandleCors in the global middleware by default,
        // but we explicitly add the throttle limiter for the API group here.
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
