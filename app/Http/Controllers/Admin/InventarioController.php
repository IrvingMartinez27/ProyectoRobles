<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\inventory;
use App\Models\category;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        // Traemos todos los productos activos con su categoría e inventarios
        $productos = product::where('estado', true)
            ->with(['category', 'inventories'])
            ->get()
            ->map(function ($producto) {

                // Construimos el array de tallas => cantidad
                $tallas = $producto->inventories
                    ->pluck('stock', 'talla')
                    ->toArray();

                return [
                    'id'          => $producto->id,
                    'nombre'      => $producto->name,
                    'categoria'   => strtolower($producto->category->name ?? 'sin categoría'),
                    'precio'      => $producto->precio,
                    'tallas'      => $tallas,
                    'stock_total' => array_sum($tallas),
                ];
            });

        return view('inventario', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'categoria'   => 'required|string',
            'tallas'      => 'required|array|min:1',
            'cantidades'  => 'required|array|min:1',
        ]);

        // Buscar o crear la categoría
        $categoria = category::firstOrCreate(
            ['name' => ucfirst($request->categoria)]
        );

        // Si el producto ya existe (mismo nombre), solo sumamos stock
        $producto = product::whereRaw('LOWER(name) = ?', [strtolower($request->nombre)])->first();

        if (!$producto) {
            $producto = product::create([
                'name'        => $request->nombre,
                'descripcion' => '',
                'precio'      => $request->precio,
                'category_id' => $categoria->id,
                'estado'      => true,
            ]);
        }

        // Guardar o sumar cada talla
        foreach ($request->tallas as $index => $talla) {
            $cantidad = $request->cantidades[$index] ?? 0;

            if (empty(trim($talla))) continue;

            $inventario = inventory::where('product_id', $producto->id)
                ->where('talla', $talla)
                ->first();

            if ($inventario) {
                // Si ya existe esa talla, sumamos las piezas
                $inventario->increment('stock', (int) $cantidad);
            } else {
                // Si no existe, la creamos
                inventory::create([
                    'product_id'     => $producto->id,
                    'talla'          => $talla,
                    'stock'          => (int) $cantidad,
                    'precio_decimal' => $request->precio,
                ]);
            }
        }

        return redirect()->route('inventario');
    }

    public function update(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:products,id',
            'tallas'      => 'required|array',
        ]);

        // Actualizamos el stock de cada talla
        foreach ($request->tallas as $talla => $cantidad) {
            Inventory::where('product_id', $request->producto_id)
                ->where('talla', $talla)
                ->update(['stock' => (int) $cantidad]);
        }

        return redirect()->route('inventario');
    }
}
