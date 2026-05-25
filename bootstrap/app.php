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
        // Registra el middleware de rol admin con un alias corto
        $middleware->alias([
            'solo.admin' => \App\Http\Middleware\SoloAdmin::class,
        ]);

        // Evita el 419 en desarrollo — extiende la sesión a 8 horas
        $middleware->validateCsrfTokens(except: [
            // Si necesitas excluir rutas del CSRF en el futuro, agrégalas aquí
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();