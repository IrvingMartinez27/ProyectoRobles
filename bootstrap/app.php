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
        App\Providers\TenancyServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'solo.admin'         => \App\Http\Middleware\SoloAdmin::class,
            'inicializar.tenant' => \App\Http\Middleware\InicializarTenant::class,
            'verificar.plan'     => \App\Http\Middleware\VerificarPlan::class,
            'role'               => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\InicializarTenant::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'pago/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();