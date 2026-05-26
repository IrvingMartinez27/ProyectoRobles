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
    ->withProviders([
        // Registra el proveedor de Tenancy para que los eventos funcionen automáticamente
        App\Providers\TenancyServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        // Registra los middlewares con sus alias cortos
        $middleware->alias([
            'solo.admin'         => \App\Http\Middleware\SoloAdmin::class,
            'inicializar.tenant' => \App\Http\Middleware\InicializarTenant::class,
        ]);

        // Agrega el middleware de tenant a todas las rutas web autenticadas
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\InicializarTenant::class,
        ]);

        // Evita el 419 en desarrollo — extiende la sesión a 8 horas
        $middleware->validateCsrfTokens(except: [
            // Si necesitas excluir rutas del CSRF en el futuro, agrégalas aquí
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();