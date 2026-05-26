<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class InicializarTenant
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user();

        if ($usuario && $usuario->tenant_id) {
            $tenant = Tenant::find($usuario->tenant_id);
            if ($tenant) {
                tenancy()->initialize($tenant);

                // Aplicar la zona horaria del tenant a toda la aplicación
                $timezone = $tenant->timezone ?? 'America/Mexico_City';
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
            }
        }

        return $next($request);
    }
}