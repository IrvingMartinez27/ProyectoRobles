<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\inventory;
use App\Models\category;
use App\Models\Almacen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventarioController extends Controller
{
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
                    'costo'       => $producto->costo ?? 0,
                    'imagen'      => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
                    'tallas'      => $tallas,
                    'stock_total' => array_sum($tallas),
                ];
            });

        $plan            = Auth::user()->plan ?? 'gratis';
        $esBusiness      = $plan === 'business';
        $totalProductos  = $productos->count();

        $productosCreados = Auth::user()->productos_creados_totales ?? 0;
        $limiteAlcanzado  = $plan === 'gratis' && $productosCreados >= self::LIMITE_PLAN_GRATIS;

        return view('inventario', compact(
            'productos', 'totalProductos', 'limiteAlcanzado',
            'plan', 'esBusiness', 'productosCreados'
        ));
    }

    public function store(Request $request)
    {
        $plan       = Auth::user()->plan ?? 'gratis';
        $esBusiness = $plan === 'business';
        $user       = Auth::user();

        // ── LÍMITE PLAN GRATIS ────────────────────────────────
        if ($plan === 'gratis') {
            $productosCreados = $user->productos_creados_totales ?? 0;
            if ($productosCreados >= self::LIMITE_PLAN_GRATIS) {
                return redirect()->route('inventario')
                    ->with('error', 'Alcanzaste el límite de ' . self::LIMITE_PLAN_GRATIS . ' productos del Plan Gratis. Actualiza a Pro para productos ilimitados.');
            }
        }

        $request->validate([
            'nombre'     => 'required|string',
            'precio'     => 'required|numeric|min:0',
            'costo'      => 'nullable|numeric|min:0',
            'categoria'  => 'required|string',
            'tallas'     => 'required|array|min:1',
            'cantidades' => 'required|array|min:1',
            // 51200 KB = 50MB. Soporta fotos directas de celular sin necesidad de comprimir.
            'imagen'     => 'nullable|image|mimes:jpeg,png,webp|max:51200',
        ], [
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'Solo se permiten imágenes JPG, PNG o WEBP.',
            'imagen.max'   => 'La imagen no puede pesar más de 50MB.',
        ]);

        // ── ALMACÉN LOCAL POR DEFECTO ─────────────────────────
        $almacenId = null;
        if ($esBusiness) {
            $local = Almacen::where('tipo', 'fisico')->where('activo', true)->first();
            $almacenId = $local?->id;
        }

        $categoria = category::firstOrCreate(
            ['name' => ucfirst($request->categoria)]
        );

        $esProductoNuevo = false;
        $producto = product::whereRaw('LOWER(name) = ?', [strtolower($request->nombre)])->first();

        if (!$producto) {
            $esProductoNuevo = true;
            $producto = product::create([
                'name'        => $request->nombre,
                'descripcion' => '',
                'precio'      => $request->precio,
                'costo'       => $esBusiness ? ($request->costo ?? 0) : 0,
                'category_id' => $categoria->id,
                'estado'      => true,
            ]);
        } else {
            if ($esBusiness && $request->filled('costo')) {
                $producto->update(['costo' => $request->costo]);
            }
        }

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $path = $request->file('imagen')->store('productos', 'public');
            product::where('id', $producto->id)->update(['imagen' => $path]);
        }

        foreach ($request->tallas as $index => $talla) {
            $cantidad = $request->cantidades[$index] ?? 0;
            if (empty(trim($talla))) continue;

            $inventario = inventory::where('product_id', $producto->id)
                ->where('talla', $talla)
                ->first();

            if ($inventario) {
                $inventario->increment('stock', (int) $cantidad);
                // Si no tiene almacén asignado, asignarlo al local
                if (!$inventario->almacen_id && $almacenId) {
                    $inventario->update(['almacen_id' => $almacenId]);
                }
            } else {
                inventory::create([
                    'product_id'     => $producto->id,
                    'talla'          => $talla,
                    'stock'          => (int) $cantidad,
                    'precio_decimal' => $request->precio,
                    'almacen_id'     => $almacenId,
                    'en_transito'    => 0,
                ]);
            }
        }

        // ── CONTADOR HISTÓRICO ────────────────────────────────
        if ($plan === 'gratis' && $esProductoNuevo) {
            \DB::connection('mysql')->table('users')
                ->where('id', $user->id)
                ->increment('productos_creados_totales');
        }

        return redirect()->route('inventario')->with('success', 'Producto guardado correctamente.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:products,id',
            'tallas'      => 'required|array',
        ]);

        $plan       = Auth::user()->plan ?? 'gratis';
        $esBusiness = $plan === 'business';

        if ($esBusiness && $request->filled('costo')) {
            product::where('id', $request->producto_id)
                ->update(['costo' => $request->costo]);
        }

        foreach ($request->tallas as $talla => $cantidad) {
            inventory::where('product_id', $request->producto_id)
                ->where('talla', $talla)
                ->update(['stock' => (int) $cantidad]);
        }

        return redirect()->route('inventario')->with('success', 'Stock actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = product::findOrFail($id);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        inventory::where('product_id', $id)->delete();
        $producto->update(['estado' => false]);

        return redirect()->route('inventario')->with('success', 'Producto eliminado correctamente.');
    }
}