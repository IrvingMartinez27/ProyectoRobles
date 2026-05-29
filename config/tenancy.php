<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Tenant;

return [
    /*
    |--------------------------------------------------------------------------
    | Modelo del Tenant
    |--------------------------------------------------------------------------
    | Clase que representa cada tienda/tenant en el sistema.
    */
    'tenant_model' => Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    /*
    |--------------------------------------------------------------------------
    | Modelo de Dominio
    |--------------------------------------------------------------------------
    | Clase que representa los dominios/slugs de cada tenant.
    */
    'domain_model' => Domain::class,

    /*
    |--------------------------------------------------------------------------
    | Dominios Centrales
    |--------------------------------------------------------------------------
    | Lista de dominios donde vive la app central (login, registro, landing).
    | Solo relevante si usas identificación por dominio o subdominio.
    */
    'central_domains' => [
        '127.0.0.1',
        'localhost',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bootstrappers de Tenancy
    |--------------------------------------------------------------------------
    | Se ejecutan cuando se inicializa el tenant.
    | Su responsabilidad es hacer que Laravel sea consciente del tenant activo.
    */
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class, // Requiere phpredis
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Base de Datos
    |--------------------------------------------------------------------------
    | Usada por DatabaseTenancyBootstrapper.
    | Cada tenant tendrá su propia base de datos llamada: prefix + tenant_id
    */
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'central'),

        // Conexión usada como plantilla para crear la conexión dinámica del tenant.
        // No la llames "tenant", ese nombre está reservado por el paquete.
        'template_tenant_connection' => null,

        // Las BDs de cada tenant se crean así: prefix + tenant_id + suffix
        'prefix' => 'tenant_',
        'suffix' => '',

        // Managers que crean y eliminan las BDs de cada tenant
        'managers' => [
            'sqlite'   => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql'    => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'mariadb'  => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql'    => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Caché
    |--------------------------------------------------------------------------
    | Usada por CacheTenancyBootstrapper.
    | Cada clave en caché tendrá un tag con el tenant_id para aislar los datos.
    */
    'cache' => [
        'tag_base' => 'tenant',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Archivos
    |--------------------------------------------------------------------------
    | Usada por FilesystemTenancyBootstrapper.
    | Cada disco tendrá una carpeta separada por tenant.
    */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'local',
            // 's3',
        ],

        // Rutas raíz para discos locales
        'root_override' => [
            'local'  => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],

        // Si se deshabilita esto probablemente se rompa el disco local.
        // Solo deshabilitar si usas almacenamiento externo como S3.
        'suffix_storage_path' => true,

        // Por defecto, asset() también es multi-tenant.
        // Usa global_asset() para assets globales no relacionados al tenant.
        'asset_helper_tenancy' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Redis
    |--------------------------------------------------------------------------
    | Usada por RedisTenancyBootstrapper. Requiere phpredis.
    | No necesitas esto si Redis solo se usa para caché.
    */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [
            // 'default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Funcionalidades Adicionales
    |--------------------------------------------------------------------------
    | Clases que agregan funcionalidad extra al sistema de tenancy.
    | Se ejecutan sin importar si el tenant está inicializado o no.
    */
    'features' => [
        // Stancl\Tenancy\Features\UserImpersonation::class,
        // Stancl\Tenancy\Features\TelescopeTags::class,
        // Stancl\Tenancy\Features\UniversalRoutes::class,
        // Stancl\Tenancy\Features\TenantConfig::class,
        // Stancl\Tenancy\Features\CrossDomainRedirect::class,
        // Stancl\Tenancy\Features\ViteBundler::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rutas de Tenancy
    |--------------------------------------------------------------------------
    | Incluye la ruta de assets del tenant. Puedes deshabilitarla
    | si usas almacenamiento externo como S3.
    */
    'routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Parámetros de Migración
    |--------------------------------------------------------------------------
    | Usados por el comando tenants:migrate para correr las migraciones
    | de cada tenant desde la carpeta database/migrations/tenant/
    */
    'migration_parameters' => [
        '--force'    => true,
        '--path'     => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Parámetros de Seeder
    |--------------------------------------------------------------------------
    | Usados por el comando tenants:seed.
    */
    'seeder_parameters' => [
        '--class' => 'DatabaseSeeder',
        // '--force' => true,
    ],
];