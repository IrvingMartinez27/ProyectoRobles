<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\client;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\box;
use App\Models\inventory;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $productos = product::where('estado', true)
            ->with(['category', 'inventories'])
            ->get()
            ->map(function ($producto) use ($tenantId) {
                $tallas      = $producto->inventories->pluck('stock', 'talla')->toArray();
                $stock_total = array_sum($tallas);
                $stock = match(true) {
                    $stock_total === 0 => 'agotado',
                    $stock_total < 10  => 'poco',
                    default            => 'disponible',
                };
                $imagenUrl = null;
                if ($producto->imagen) {
                    $imagenUrl = asset('storage/' . $tenantId . '/' . $producto->imagen);
                }
                return [
                    'id'        => $producto->id,
                    'nombre'    => $producto->name,
                    'categoria' => strtolower($producto->category->name ?? 'sin categoría'),
                    'precio'    => $producto->precio,
                    'imagen'    => $imagenUrl,
                    'tallas'    => $tallas,
                    'stock'     => $stock,
                ];
            });

        $clientes      = client::orderBy('name')->get();
        $plan          = Auth::user()->plan ?? 'gratis';
        $tenant        = Tenant::find($tenantId);
        $lealtadActivo = in_array($plan, ['pro', 'business']) && ($tenant->lealtad_activo ?? false);

        return view('catalog', compact('productos', 'clientes', 'plan', 'lealtadActivo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre'    => 'required|string',
            'productos_carrito' => 'required|string',
            'total_carrito'     => 'required|numeric|min:0',
        ]);

        $productosCarrito = json_decode($request->productos_carrito, true);
        if (empty($productosCarrito)) {
            return back()->withErrors(['productos_carrito' => 'El carrito está vacío.']);
        }

        // ── VALIDAR STOCK ──────────────────────────────────────
        foreach ($productosCarrito as $item) {
            $talla    = $item['talla'] ?? null;
            $cantidad = $item['cantidad'] ?? 1;
            if ($talla) {
                $inv = inventory::where('product_id', $item['id'])->where('talla', $talla)->first();
                if (!$inv || $inv->stock < $cantidad) {
                    $stockDisponible = $inv->stock ?? 0;
                    return back()
                        ->with('error', "Solo hay {$stockDisponible} piezas disponibles de \"{$item['nombre']}\" en talla {$talla}.")
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

        DB::transaction(function () use ($request, $productosCarrito, $puntosCanjear, $lealtadActivo) {

            $clienteId = $request->cliente_id;
            if (!$clienteId) {
                $clienteExistente = client::whereRaw('LOWER(name) = ?', [strtolower($request->cliente_nombre)])->first();
                if ($clienteExistente) {
                    $clienteId = $clienteExistente->id;
                } else {
                    $nuevoCliente = client::create([
                        'name'      => $request->cliente_nombre,
                        'telefono'  => null,
                        'direccion' => null,
                        'puntos'    => 0,
                    ]);
                    $clienteId = $nuevoCliente->id;
                }
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

            $subtotal        = (float) $request->total_carrito;
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

            foreach ($productosCarrito as $item) {
                $productoModel = product::find($item['id']);
                $talla         = $item['talla'] ?? null;
                $precio        = $item['precio_mayoreo'] ?? $item['precio'];
                if ($productoModel) {
                    detail_sale::create([
                        'sale_id'         => $venta->id,
                        'product_id'      => $productoModel->id,
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $precio,
                        'subtotal'        => $precio * $item['cantidad'],
                        'talla'           => $talla,
                    ]);
                    if ($talla) {
                        inventory::where('product_id', $productoModel->id)
                            ->where('talla', $talla)
                            ->decrement('stock', $item['cantidad']);
                    }
                }
            }

            if ($lealtadActivo) {
                $clienteObj = client::find($clienteId);
                if ($clienteObj) {
                    $nuevosPuntos = max(0, $clienteObj->puntos - $puntosCanjear + $puntosGanados);
                    $clienteObj->update(['puntos' => $nuevosPuntos]);
                }
            }

            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('catalog')
            ->with('success', 'Venta registrada correctamente.')
            ->with('ticket_metodo', $request->metodo_pago ?? 'efectivo')
            ->with('ticket_tipo', $request->tipo_venta ?? 'menudeo')
            ->with('ticket_recibido', $request->recibido ?? 0)
            ->with('ticket_cambio', $request->cambio ?? 0);
    }
}