<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\client;
use App\Models\product;
use App\Models\inventory;
use App\Models\box;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $ventas = sale::with(['client', 'details.product', 'ticket'])
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->map(function ($venta) {
                return [
                    'id'               => $venta->id,
                    'cliente'          => $venta->client->name ?? 'Sin cliente',
                    'telefono'         => $venta->client->telefono ?? null,
                    'puntos_cliente'   => $venta->client->puntos ?? 0,
                    'total'            => $venta->total,
                    'estado'           => 'completada',
                    'fecha'            => $venta->created_at->format('d/m/Y H:i'),
                    'folio'            => $venta->ticket->folio ?? '—',
                    'metodo_pago'      => $venta->metodo_pago ?? 'efectivo',
                    'tipo_venta'       => $venta->tipo_venta ?? 'menudeo',
                    'puntos_ganados'   => $venta->puntos_ganados ?? 0,
                    'puntos_canjeados' => $venta->puntos_canjeados ?? 0,
                    'descuento_puntos' => $venta->descuento_puntos ?? 0,
                    'productos'        => $venta->details->map(fn($d) => [
                        'nombre'  => $d->product->name ?? '—',
                        'detalle' => 'x' . $d->cantidad . ($d->talla ? ' · Talla ' . $d->talla : ''),
                        'precio'  => $d->subtotal,
                    ])->toArray(),
                ];
            });

        $clientes = client::orderBy('name')->get();

        $productos = product::where('estado', true)
            ->with(['category', 'inventories' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'nombre'    => $p->name,
                'precio'    => $p->precio,
                'categoria' => strtolower($p->category->name ?? ''),
                'tallas'    => $p->inventories->map(fn($i) => [
                    'talla' => $i->talla,
                    'stock' => $i->stock,
                ])->toArray(),
            ]);

        $plan          = Auth::user()->plan ?? 'gratis';
        $tenant        = Tenant::find(Auth::user()->tenant_id);
        $lealtadActivo = in_array($plan, ['pro', 'business']) && ($tenant->lealtad_activo ?? false);

        return view('sales', compact('ventas', 'clientes', 'productos', 'plan', 'lealtadActivo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'productos'  => 'required|array|min:1',
            'cantidades' => 'required|array|min:1',
            'precios'    => 'required|array|min:1',
        ], [
            'productos.required' => 'Agrega al menos un producto.',
            'productos.min'      => 'Agrega al menos un producto.',
        ]);

        // ── VALIDAR STOCK ──────────────────────────────────────
        foreach ($request->productos as $i => $productoId) {
            $cantidad = $request->cantidades[$i] ?? 1;
            $talla    = $request->tallas[$i] ?? null;
            if ($talla) {
                $inv      = inventory::where('product_id', $productoId)->where('talla', $talla)->first();
                $producto = product::find($productoId);
                $nombre   = $producto->name ?? 'Producto';
                if (!$inv || $inv->stock < $cantidad) {
                    $stockDisponible = $inv->stock ?? 0;
                    return back()
                        ->with('error', "Solo hay {$stockDisponible} piezas disponibles de \"{$nombre}\" en talla {$talla}.")
                        ->withInput();
                }
            }
        }

        $plan          = Auth::user()->plan ?? 'gratis';
        $esPro         = in_array($plan, ['pro', 'business']);
        $tenant        = Tenant::find(Auth::user()->tenant_id);
        $lealtadActivo = $esPro && ($tenant->lealtad_activo ?? false);
        $puntosCanjear = $lealtadActivo ? (int) ($request->puntos_canjear ?? 0) : 0;

        // ── VALIDAR PUNTOS ─────────────────────────────────────
        if ($puntosCanjear > 0) {
            $clienteCheck = client::find($request->cliente_id);
            if ($clienteCheck && $clienteCheck->puntos < $puntosCanjear) {
                return back()
                    ->with('error', "El cliente solo tiene {$clienteCheck->puntos} puntos disponibles.")
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request, $puntosCanjear, $lealtadActivo) {

            $clienteId = $request->cliente_id;
            if (empty($clienteId) || !is_numeric($clienteId)) {
                $nombreCliente = $request->cliente_nombre ?? 'Cliente general';
                $clienteObj = client::firstOrCreate(
                    ['name' => trim($nombreCliente)],
                    ['telefono' => null, 'direccion' => null, 'puntos' => 0]
                );
                $clienteId = $clienteObj->id;
            }

            $caja = box::where('estado', true)->latest()->first();
            if (!$caja) {
                $caja = box::create([
                    'user_id'        => Auth::id(),
                    'fecha_apertura' => today(),
                    'fecha_cierre'   => today(),
                    'monto_apertura' => 0,
                    'monto_final'    => 0,
                    'estado'         => true,
                ]);
            }

            $subtotal        = 0;
            foreach ($request->productos as $i => $productoId) {
                $subtotal += ($request->cantidades[$i] ?? 1) * ($request->precios[$i] ?? 0);
            }

            $descuentoPuntos = $lealtadActivo ? $puntosCanjear : 0;
            $total           = max(0, $subtotal - $descuentoPuntos);
            $puntosGanados   = $lealtadActivo ? (int) floor($total / 50) : 0;

            $venta = sale::create([
                'client_id'        => $clienteId,
                'user_id'          => Auth::id(),
                'box_id'           => $caja->id,
                'total'            => $total,
                'metodo_pago'      => $request->metodo_pago ?? 'efectivo',
                'tipo_venta'       => $request->tipo_venta ?? 'menudeo',
                'puntos_ganados'   => $puntosGanados,
                'puntos_canjeados' => $puntosCanjear,
                'descuento_puntos' => $descuentoPuntos,
            ]);

            foreach ($request->productos as $i => $productoId) {
                $cantidad = $request->cantidades[$i] ?? 1;
                $precio   = $request->precios[$i] ?? 0;
                $talla    = $request->tallas[$i] ?? null;
                $producto = product::find($productoId);
                if ($producto) {
                    detail_sale::create([
                        'sale_id'         => $venta->id,
                        'product_id'      => $producto->id,
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal'        => $cantidad * $precio,
                        'talla'           => $talla,
                    ]);
                    if ($talla) {
                        inventory::where('product_id', $producto->id)
                            ->where('talla', $talla)
                            ->decrement('stock', $cantidad);
                    }
                }
            }

            // Actualizar puntos solo si lealtad está activo
            if ($lealtadActivo) {
                $clienteObj = client::find($clienteId);
                if ($clienteObj) {
                    $nuevosPuntos = max(0, $clienteObj->puntos - $puntosCanjear + $puntosGanados);
                    $clienteObj->update(['puntos' => $nuevosPuntos]);
                }
            }

            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('sales')
            ->with('success', 'Venta registrada correctamente.')
            ->with('ticket_metodo', $request->metodo_pago ?? 'efectivo')
            ->with('ticket_tipo', $request->tipo_venta ?? 'menudeo')
            ->with('ticket_recibido', $request->recibido ?? 0)
            ->with('ticket_cambio', $request->cambio ?? 0);
    }
}