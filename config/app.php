<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la Aplicación
    |--------------------------------------------------------------------------
    | Este valor es el nombre de la aplicación, usado en notificaciones
    | y otros elementos de la interfaz donde se necesite mostrar el nombre.
    */

    'name' => env('APP_NAME', 'Quivex'),

    /*
    |--------------------------------------------------------------------------
    | Entorno de la Aplicación
    |--------------------------------------------------------------------------
    | Determina el entorno actual de la aplicación (local, production, etc).
    | Se configura desde el archivo .env.
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Modo Debug
    |--------------------------------------------------------------------------
    | En modo debug se muestran errores detallados con stack trace.
    | En producción debe estar en false para mostrar solo una página genérica.
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL de la Aplicación
    |--------------------------------------------------------------------------
    | URL raíz de la aplicación, usada por Artisan para generar URLs correctas
    | en comandos de consola.
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona Horaria
    |--------------------------------------------------------------------------
    | Zona horaria por defecto para toda la aplicación.
    | Se usa en funciones de fecha y hora de PHP.
    | Cada tenant puede sobreescribir esto desde el middleware InicializarTenant.
    */

    'timezone' => 'America/Mexico_City',

    /*
    |--------------------------------------------------------------------------
    | Configuración de Idioma
    |--------------------------------------------------------------------------
    | El idioma predeterminado de la aplicación para traducciones y localización.
    */

    'locale' => env('APP_LOCALE', 'es'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'es_MX'),

    /*
    |--------------------------------------------------------------------------
    | Clave de Cifrado
    |--------------------------------------------------------------------------
    | Clave usada por los servicios de cifrado de Laravel.
    | Debe ser una cadena aleatoria de 32 caracteres.
    | Nunca expongas esta clave públicamente.
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Driver de Modo Mantenimiento
    |--------------------------------------------------------------------------
    | Opciones para el driver que gestiona el modo mantenimiento de Laravel.
    | Drivers disponibles: "file", "cache"
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];