<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\client;
use App\Models\product;
use App\Models\box;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        // Ventas del día con cliente y detalles
        $ventas = Sale::with(['client', 'details.product', 'ticket'])
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->map(function ($venta) {
                return [
                    'id'       => $venta->id,
                    'cliente'  => $venta->client->name ?? 'Sin cliente',
                    'total'    => $venta->total,
                    'estado'   => 'completada',
                    'fecha'    => $venta->created_at->format('d/m/Y H:i'),
                    'folio'    => $venta->ticket->folio ?? '—',
                    'productos' => $venta->details->map(fn($d) => [
                        'nombre'  => $d->product->name ?? '—',
                        'detalle' => 'x' . $d->cantidad,
                        'precio'  => $d->subtotal,
                    ])->toArray(),
                ];
            });

        // Clientes y productos para los selects del modal
        $clientes  = client::orderBy('name')->get();
        $productos = product::where('estado', true)
            ->with(['category', 'inventories'])
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'nombre'    => $p->name,
                'precio'    => $p->precio,
                'categoria' => strtolower($p->category->name ?? ''),
            ]);

        return view('sales', compact('ventas', 'clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'  => 'required|exists:clients,id',
            'productos'   => 'required|array|min:1',
            'cantidades'  => 'required|array|min:1',
            'precios'     => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {

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
            foreach ($request->productos as $i => $nombreProducto) {
                $cantidad = $request->cantidades[$i] ?? 1;
                $precio   = $request->precios[$i] ?? 0;
                $total   += $cantidad * $precio;
            }

            // Crear la venta
            $venta = sale::create([
                'client_id'    => $request->cliente_id,
                'user_id'      => Auth::id(),
                'box_id'       => $caja->id,
                'total'        => $total,
                'metodo_pago'  => $request->metodo_pago ?? 'efectivo',
            ]);

            // Crear el detalle por cada producto
            foreach ($request->productos as $i => $nombreProducto) {
                $cantidad = $request->cantidades[$i] ?? 1;
                $precio   = $request->precios[$i] ?? 0;

                // Buscar el producto por nombre
                $producto = product::whereRaw('LOWER(name) = ?', [strtolower($nombreProducto)])->first();

                if ($producto) {
                    detail_sale::create([
                        'sale_id'         => $venta->id,
                        'product_id'      => $producto->id,
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal'        => $cantidad * $precio,
                    ]);
                }
            }

            // Generar ticket automáticamente
            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('sales')->with('success', 'Venta registrada correctamente.');
    }
}