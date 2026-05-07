<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // muestra todos los productos en el inventario
   public function index()
{
    // aqui Irving trae todos los productos con sus tallas y stock
    $productos = [];
    return view('inventario', compact('productos'));
}

    // guarda un nuevo producto o suma piezas si ya existe
    // recibe: nombre, precio, categoria, tallas[], cantidades[], producto_existente
    public function store(Request $request)
    {
        // calcula el stock total sumando todas las cantidades
        $stockTotal = array_sum($request->cantidades ?? []);

        // calcula el estado automaticamente segun el stock total
        // 11+ piezas = disponible, 1-10 = poco, 0 = agotado
        if ($stockTotal >= 11) {
            $estado = 'disponible';
        } elseif ($stockTotal >= 1) {
            $estado = 'poco';
        } else {
            $estado = 'agotado';
        }

        // si producto_existente = 1 Irving suma las piezas al stock actual
        // si producto_existente = 0 Irving crea el producto nuevo

        return redirect()->route('inventario')->with('success', 'Producto guardado correctamente');
    }

    // muestra el detalle de un producto especifico
    public function show($id)
    {
        // aqui Irving trae el producto con sus tallas y stock
        return redirect()->route('inventario');
    }

    // actualiza el stock de un producto existente
    // recibe las tallas y cantidades actualizadas
    public function update(Request $request, $id)
    {
        // recalcula el stock total con las nuevas cantidades
        $stockTotal = array_sum($request->cantidades ?? []);

        // recalcula el estado automaticamente
        if ($stockTotal >= 11) {
            $estado = 'disponible';
        } elseif ($stockTotal >= 1) {
            $estado = 'poco';
        } else {
            $estado = 'agotado';
        }

        // aqui Irving actualiza el producto en la bd

        return redirect()->route('inventario')->with('success', 'Producto actualizado correctamente');
    }

    // elimina un producto del inventario
    public function destroy($id)
    {
        // aqui Irving elimina el producto y sus tallas de la bd
        return redirect()->route('inventario')->with('success', 'Producto eliminado correctamente');
    }
}