<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = sale::with(['client', 'details'])
            ->latest();

        // Filtro por rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        // Total del período (antes de paginar)
        $totalPeriodo  = number_format($query->sum('total'), 2);
        $totalRegistros = $query->count();

        // Paginar ventas
        $ventas = $query->paginate(15)->through(fn($venta) => [
            'id'             => $venta->id,
            'cliente'        => $venta->client->name ?? 'Sin cliente',
            'email'          => '',
            'num_productos'  => $venta->details->sum('cantidad'),
            'fecha'          => $venta->created_at->format('d/m/Y H:i'),
            'estado'         => 'completada',
            'total'          => number_format($venta->total, 2),
        ]);

        return view('reporte', compact('ventas', 'totalPeriodo', 'totalRegistros'));
    }
}