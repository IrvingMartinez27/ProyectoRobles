<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;

class TenancyServiceProvider extends ServiceProvider
{
    // Sin namespace por defecto para soportar la sintaxis de array callable
    public static string $controllerNamespace = '';

    public function events()
    {
        return [
            // ── Eventos del Tenant ─────────────────────────────────────────
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,   // Crea la BD del tenant
                    Jobs\MigrateDatabase::class,  // Corre las migraciones del tenant
                    // Jobs\SeedDatabase::class,  // Opcional: llenar con datos iniciales
                ])->send(function (Events\TenantCreated $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // Cambiar a true en producción para mejor rendimiento
            ],
            Events\SavingTenant::class   => [],
            Events\TenantSaved::class    => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class  => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class  => [
                JobPipeline::make([
                    Jobs\DeleteDatabase::class,   // Elimina la BD cuando se borra el tenant
                ])->send(function (Events\TenantDeleted $event) {
                    return $event->tenant;
                })->shouldBeQueued(false),
            ],

            // ── Eventos de Dominio ─────────────────────────────────────────
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class  => [],
            Events\SavingDomain::class   => [],
            Events\DomainSaved::class    => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class  => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class  => [],

            // ── Eventos de Base de Datos ───────────────────────────────────
            Events\DatabaseCreated::class    => [],
            Events\DatabaseMigrated::class   => [],
            Events\DatabaseSeeded::class     => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class    => [],

            // ── Eventos de Tenancy ─────────────────────────────────────────
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class  => [
                Listeners\BootstrapTenancy::class, // Activa el contexto del tenant
            ],

            Events\EndingTenancy::class  => [],
            Events\TenancyEnded::class   => [
                Listeners\RevertToCentralContext::class, // Vuelve al contexto central
            ],

            Events\BootstrappingTenancy::class    => [],
            Events\TenancyBootstrapped::class     => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class  => [],

            // ── Sincronización de Recursos ─────────────────────────────────
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Se dispara solo cuando un recurso sincronizado cambia en una BD diferente
            // a la de origen (para evitar bucles infinitos)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();
        $this->makeTenancyMiddlewareHighestPriority();
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }
                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes()
    {
        // Registra las rutas del tenant si el archivo existe
        $this->app->booted(function () {
            if (file_exists(base_path('routes/tenant.php'))) {
                Route::namespace(static::$controllerNamespace)
                    ->group(base_path('routes/tenant.php'));
            }
        });
    }

    protected function makeTenancyMiddlewareHighestPriority()
    {
        // Estos middlewares se registran con la más alta prioridad
        // para que se ejecuten antes que cualquier otro middleware
        $tenancyMiddleware = [
            Middleware\PreventAccessFromCentralDomains::class,
            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}