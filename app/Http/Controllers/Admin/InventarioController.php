<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\inventory;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventarioController extends Controller
{
    // Límite de productos para el plan gratis
    const LIMITE_PLAN_GRATIS = 20;

    public function index()
    {
        $productos = product::where('estado', true)
            ->with(['category', 'inventories'])
            ->get()
            ->map(function ($producto) {
                $tallas = $producto->inventories
                    ->pluck('stock', 'talla')
                    ->toArray();

                return [
                    'id'          => $producto->id,
                    'nombre'      => $producto->name,
                    'categoria'   => strtolower($producto->category->name ?? 'sin categoría'),
                    'precio'      => $producto->precio,
                    'imagen'      => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
                    'tallas'      => $tallas,
                    'stock_total' => array_sum($tallas),
                ];
            });

        $totalProductos  = $productos->count();
        $limiteAlcanzado = $totalProductos >= self::LIMITE_PLAN_GRATIS;
        $plan            = Auth::user()->plan ?? 'gratis';

        return view('inventario', compact('productos', 'totalProductos', 'limiteAlcanzado', 'plan'));
    }

    public function store(Request $request)
    {
        // Verificar límite del plan gratis
        $plan           = Auth::user()->plan ?? 'gratis';
        $totalProductos = product::where('estado', true)->count();

        if ($plan === 'gratis' && $totalProductos >= self::LIMITE_PLAN_GRATIS) {
            return redirect()->route('inventario')
                ->with('limite_alcanzado', true)
                ->with('error', 'Alcanzaste el límite de ' . self::LIMITE_PLAN_GRATIS . ' productos del plan Gratis. Actualiza a Pro para productos ilimitados.');
        }

        $request->validate([
            'nombre'     => 'required|string',
            'precio'     => 'required|numeric|min:0',
            'categoria'  => 'required|string',
            'tallas'     => 'required|array|min:1',
            'cantidades' => 'required|array|min:1',
            'imagen'     => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ], [
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            'imagen.max'   => 'La imagen no puede pesar más de 2MB.',
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

        // Guardar imagen si se subió
        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $path = $request->file('imagen')->store('productos', 'public');
            product::where('id', $producto->id)->update(['imagen' => $path]);
        }

        // Guardar o sumar cada talla
        foreach ($request->tallas as $index => $talla) {
            $cantidad = $request->cantidades[$index] ?? 0;

            if (empty(trim($talla))) continue;

            $inventario = inventory::where('product_id', $producto->id)
                ->where('talla', $talla)
                ->first();

            if ($inventario) {
                $inventario->increment('stock', (int) $cantidad);
            } else {
                inventory::create([
                    'product_id'     => $producto->id,
                    'talla'          => $talla,
                    'stock'          => (int) $cantidad,
                    'precio_decimal' => $request->precio,
                ]);
            }
        }

        return redirect()->route('inventario')->with('success', 'Producto guardado correctamente.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:products,id',
            'tallas'      => 'required|array',
        ]);

        foreach ($request->tallas as $talla => $cantidad) {
            inventory::where('product_id', $request->producto_id)
                ->where('talla', $talla)
                ->update(['stock' => (int) $cantidad]);
        }

        return redirect()->route('inventario')->with('success', 'Stock actualizado correctamente.');
    }
}