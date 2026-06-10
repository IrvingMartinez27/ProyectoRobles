<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\product;
use App\Models\inventory;
use Illuminate\Http\Request;

class CatalogoPublicoController extends Controller
{
    public function index($tenant_id)
    {
        // Buscar tenant
        $tenant = Tenant::find($tenant_id);
        if (!$tenant) abort(404);

        // Buscar dueño
        $owner = User::where('tenant_id', $tenant_id)
            ->where('role', 'admin')
            ->first();
        if (!$owner) abort(404);

        // Inicializar tenant para acceder a su BD
        tenancy()->initialize($tenant);

        $productos = product::where('estado', true)
            ->with(['category', 'inventories' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->get()
            ->map(function ($p) use ($tenant_id) {
                $tallas = $p->inventories->pluck('stock', 'talla')->toArray();
                $stockTotal = array_sum($tallas);
                return [
                    'id'          => $p->id,
                    'nombre'      => $p->name,
                    'precio'      => $p->precio,
                    'categoria'   => strtolower($p->category->name ?? 'general'),
                    'imagen'      => $p->imagen
                        ? asset('storage/' . $p->imagen)
                        : null,
                    'tallas'      => $tallas,
                    'stock_total' => $stockTotal,
                ];
            })
            ->filter(fn($p) => $p['stock_total'] > 0)
            ->values();

        tenancy()->end();

        return view('catalogo_publico', [
            'productos'   => $productos,
            'store_name'  => $tenant->store_name ?? $owner->store_name,
            'whatsapp'    => $owner->whatsapp,
            'tenant_id'   => $tenant_id,
        ]);
    }
}