<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GastoOperativo;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoOperativoController extends Controller
{
    public function index(Request $request)
    {
        $mes       = $request->mes ?? now()->format('Y-m');
        $almacenId = $request->almacen_id;

        $gastos = GastoOperativo::with('almacen')
            ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
            ->whereYear('fecha', substr($mes, 0, 4))
            ->whereMonth('fecha', substr($mes, 5, 2))
            ->latest()
            ->get();

        $almacenes  = Almacen::where('activo', true)->get();
        $totalGastos = $gastos->sum('monto');

        $porCategoria = $gastos->groupBy('categoria')->map->sum('monto');

        return view('gastos', compact('gastos', 'almacenes', 'mes', 'almacenId', 'totalGastos', 'porCategoria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'concepto'   => 'required|string|max:100',
            'categoria'  => 'required|in:fijo,variable,hormiga',
            'monto'      => 'required|numeric|min:0',
            'fecha'      => 'required|date',
            'almacen_id' => 'nullable|exists:almacenes,id',
        ], [
            'concepto.required'  => 'El concepto es obligatorio.',
            'categoria.required' => 'La categoría es obligatoria.',
            'monto.required'     => 'El monto es obligatorio.',
            'fecha.required'     => 'La fecha es obligatoria.',
        ]);

        GastoOperativo::create([
            'concepto'   => $request->concepto,
            'categoria'  => $request->categoria,
            'monto'      => $request->monto,
            'fecha'      => $request->fecha,
            'almacen_id' => $request->almacen_id,
        ]);

        return back()->with('success', 'Gasto registrado correctamente.');
    }

    public function destroy($id)
    {
        GastoOperativo::findOrFail($id)->delete();
        return back()->with('success', 'Gasto eliminado.');
    }
}