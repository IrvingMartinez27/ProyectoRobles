<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\client;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\box;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index()
    {
        $productos = product::where('estado', true)
            ->with(['category', 'inventories'])
            ->get()
            ->map(function ($producto) {
                $tallas      = $producto->inventories->pluck('stock', 'talla')->toArray();
                $stock_total = array_sum($tallas);

                $stock = match(true) {
                    $stock_total === 0 => 'agotado',
                    $stock_total < 10  => 'poco',
                    default            => 'disponible',
                };

                return [
                    'id'        => $producto->id,
                    'nombre'    => $producto->name,
                    'categoria' => strtolower($producto->category->name ?? 'sin categoría'),
                    'precio'    => $producto->precio,
                    'imagen'    => null,
                    'tallas'    => $tallas,
                    'stock'     => $stock,
                ];
            });

        $clientes = client::orderBy('name')->get();

        return view('catalog', compact('productos', 'clientes'));
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

        DB::transaction(function () use ($request, $productosCarrito) {

            // Buscar o crear el cliente
            $clienteId = $request->cliente_id;

            if (!$clienteId) {
                $clienteExistente = client::whereRaw('LOWER(name) = ?', [strtolower($request->cliente_nombre)])->first();

                if ($clienteExistente) {
                    $clienteId = $clienteExistente->id;
                } else {
                    // Teléfono null para evitar conflicto de índice único
                    $nuevoCliente = client::create([
                        'name'      => $request->cliente_nombre,
                        'telefono'  => null,
                        'direccion' => null,
                    ]);
                    $clienteId = $nuevoCliente->id;
                }
            }

            // Buscar o crear caja abierta
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

            // Crear la venta
            $venta = sale::create([
                'client_id'   => $clienteId,
                'user_id'     => Auth::id(),
                'box_id'      => $caja->id,
                'total'       => $request->total_carrito,
                'metodo_pago' => 'efectivo',
            ]);

            // Detalle por cada producto
            foreach ($productosCarrito as $item) {
                $productoModel = product::find($item['id']);

                if ($productoModel) {
                    detail_sale::create([
                        'sale_id'         => $venta->id,
                        'product_id'      => $productoModel->id,
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                        'subtotal'        => $item['precio'] * $item['cantidad'],
                    ]);
                }
            }

            // Ticket
            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('catalog')->with('success', 'Venta registrada correctamente.');
    }
}