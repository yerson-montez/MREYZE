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
        // Aquí registras tus middlewares personalizados
        $middleware->alias([
            'es_admin' => \App\Http\Middleware\EsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->withMiddleware(function (Middleware $middleware) {
        // ... tu middleware existente ...
        $middleware->web(append: [
            \App\Http\Middleware\CapturarReferido::class,
        ]);
    })
    
    ->create();