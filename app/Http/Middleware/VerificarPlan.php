<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarPlan
{
    public function handle(Request $request, Closure $next, string $planRequerido = 'pro')
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        $planActual = $user->plan ?? 'gratis';

        // Si el tenant tiene plan diferente al usuario, usar el del tenant
        if ($user->tenant) {
            $planActual = $user->tenant->plan ?? $planActual;
        }

        $niveles = ['gratis' => 0, 'pro' => 1, 'business' => 2];

        if (($niveles[$planActual] ?? 0) < ($niveles[$planRequerido] ?? 0)) {
            return redirect('/pago/planes')
                ->with('error', 'Necesitas el plan Pro para acceder a esta función.');
        }

        return $next($request);
    }
}