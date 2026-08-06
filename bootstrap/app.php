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
            'es_admin' => \App\Http\Middleware\EsAdmin::class,
        ]);

        // Si el cliente no tiene sesión, enviarlo a Google Login
        $middleware->redirectGuestsTo('/auth/google');

        // Confiar en todos los proxies (Railway, Cloudflare, etc.)
        $middleware->trustProxies(at: '*');

        // Capturar referidos
        $middleware->web(append: [
            \App\Http\Middleware\CapturarReferido::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();