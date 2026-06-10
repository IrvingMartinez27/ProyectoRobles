<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\inventory;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes    = Almacen::where('activo', true)->get();
        $plan         = Auth::user()->plan ?? 'gratis';
        $esBusiness   = $plan === 'business';
        $maxAlmacenes = 3;

        return view('almacenes', compact('almacenes', 'esBusiness', 'maxAlmacenes'));
    }

    public function show($id)
    {
        $almacen   = Almacen::findOrFail($id);
        $almacenes = Almacen::where('activo', true)->get();
        $local     = $almacenes->where('tipo', 'fisico')->first();
        $virtual   = $almacenes->where('tipo', 'virtual')->first();

        if ($almacen->tipo === 'virtual') {
            // Mostrar productos en tránsito
            $enTransito = inventory::with('product')
                ->where('en_transito', '>', 0)
                ->whereHas('product', fn($q) => $q->where('estado', true))
                ->get();
        } else {
            // Físico → mostrar inventario asignado a este almacén
            $enTransito = inventory::with('product')
                ->where('almacen_id', $almacen->id)
                ->where('stock', '>', 0)
                ->whereHas('product', fn($q) => $q->where('estado', true))
                ->get();
        }

        return view('para_entrega', compact(
            'enTransito', 'almacen', 'local', 'virtual', 'almacenes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo'   => 'required|in:fisico,virtual',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'tipo.required'   => 'El tipo es obligatorio.',
        ]);

        $total = Almacen::where('activo', true)->count();
        if ($total >= 3) {
            return back()->with('error', 'Máximo 3 almacenes/sucursales en el plan Business.');
        }

        Almacen::create([
            'nombre' => $request->nombre,
            'tipo'   => $request->tipo,
            'activo' => true,
        ]);

        return back()->with('success', 'Almacén creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo'   => 'required|in:fisico,virtual',
        ]);

        $almacen = Almacen::findOrFail($id);
        $almacen->update([
            'nombre' => $request->nombre,
            'tipo'   => $request->tipo,
        ]);

        return back()->with('success', 'Almacén actualizado.');
    }

    public function destroy($id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->update(['activo' => false]);

        return back()->with('success', 'Almacén desactivado.');
    }
}