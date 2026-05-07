<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class VentaController extends Controller
{
    // muestra todas las ventas del dia en la vista de sales
    public function index()
    {
        // aqui Irving conecta la query para traer las ventas del dia
        $ventas = [];
        $clientes = Client::all();

        $productos = [];

    return view('sales', compact('ventas', 'clientes', 'productos'));
}

    // guarda una nueva venta en la bd
    // recibe el cliente, los productos del carrito y el total
    public function store(Request $request)
    {
        // busca el cliente por nombre, si no existe lo crea automaticamente
        $cliente = Client::firstOrCreate(
            ['name' => $request->cliente_nombre],
            ['telefono' => null, 'direccion' => null]
        );

        // aqui Irving guarda la venta con sus productos
        // $request->productos_carrito → JSON con los productos
        // $request->total_carrito → total de la venta
        // $cliente->id → id del cliente nuevo o existente

        return redirect()->route('sales')->with('success', 'Venta registrada correctamente');
    }

    // muestra el detalle de una venta especifica
    public function show($id)
    {
        // aqui Irving trae la venta con sus productos y cliente
        return redirect()->route('sales');
    }

    // actualiza el estado de una venta
    public function update(Request $request, $id)
    {
        // aqui Irving actualiza el estado de la venta
        return redirect()->route('sales')->with('success', 'Venta actualizada');
    }

    // elimina una venta
    public function destroy($id)
    {
        // aqui Irving elimina la venta de la bd
        return redirect()->route('sales')->with('success', 'Venta eliminada');
    }
}