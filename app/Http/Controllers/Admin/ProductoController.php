<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function show($id)
    {
        return redirect()->route('inventario');
    }

    public function update(Request $request, $id)
    {
        $stockTotal = array_sum($request->cantidades ?? []);

        if ($stockTotal >= 11) {
            $estado = 'disponible';
        } elseif ($stockTotal >= 1) {
            $estado = 'poco';
        } else {
            $estado = 'agotado';
        }

        return redirect()->route('inventario')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $producto = product::findOrFail($id);

        // Eliminar imagen del storage si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        // Eliminar inventarios relacionados
        inventory::where('product_id', $id)->delete();

        // Eliminar el producto
        $producto->delete();

        return redirect()->route('inventario')->with('success', 'Producto eliminado correctamente.');
    }
}