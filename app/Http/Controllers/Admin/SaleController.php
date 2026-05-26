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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        // Ventas del día con cliente y detalles
        $ventas = sale::with(['client', 'details.product', 'ticket'])
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->map(function ($venta) {
                return [
                    'id'        => $venta->id,
                    'cliente'   => $venta->client->name ?? 'Sin cliente',
                    'total'     => $venta->total,
                    'estado'    => 'completada',
                    'fecha'     => $venta->created_at->format('d/m/Y H:i'),
                    'folio'     => $venta->ticket->folio ?? '—',
                    'productos' => $venta->details->map(fn($d) => [
                        'nombre'  => $d->product->name ?? '—',
                        'detalle' => 'x' . $d->cantidad . ($d->talla ? ' · Talla ' . $d->talla : ''),
                        'precio'  => $d->subtotal,
                    ])->toArray(),
                ];
            });

        // Clientes para el select
        $clientes = client::orderBy('name')->get();

        // Productos con tallas disponibles
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

        return view('sales', compact('ventas', 'clientes', 'productos'));
    }

    public function store(Request $request)
    {
        // Validar que venga al menos el nombre del cliente o su id
        $request->validate([
            'productos'  => 'required|array|min:1',
            'cantidades' => 'required|array|min:1',
            'precios'    => 'required|array|min:1',
        ], [
            'productos.required'  => 'Agrega al menos un producto.',
            'productos.min'       => 'Agrega al menos un producto.',
        ]);

        DB::transaction(function () use ($request) {

            // Resolver cliente — usar id si existe, si no crear por nombre
            $clienteId = $request->cliente_id;

            if (empty($clienteId) || !is_numeric($clienteId)) {
                $nombreCliente = $request->cliente_nombre ?? 'Cliente general';
                $cliente = client::firstOrCreate(
                    ['name' => trim($nombreCliente)],
                    ['telefono' => null, 'direccion' => null]
                );
                $clienteId = $cliente->id;
            }

            // Buscar caja abierta, si no hay crear una temporal
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

            // Calcular total
            $total = 0;
            foreach ($request->productos as $i => $productoId) {
                $cantidad = $request->cantidades[$i] ?? 1;
                $precio   = $request->precios[$i] ?? 0;
                $total   += $cantidad * $precio;
            }

            // Crear la venta
            $venta = sale::create([
                'client_id'   => $clienteId,
                'user_id'     => Auth::id(),
                'box_id'      => $caja->id,
                'total'       => $total,
                'metodo_pago' => $request->metodo_pago ?? 'efectivo',
            ]);

            // Crear el detalle por cada producto
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

                    // Descontar stock si hay talla seleccionada
                    if ($talla) {
                        inventory::where('product_id', $producto->id)
                            ->where('talla', $talla)
                            ->decrement('stock', $cantidad);
                    }
                }
            }

            // Generar ticket automáticamente
            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('sales')->with('success', 'Venta registrada correctamente.');
    }
}