<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\detail_sale;
use Illuminate\Http\Request;

class ResumenController extends Controller
{
    public function index(Request $request)
    {
        // Fecha seleccionada, por defecto hoy
        $fecha = $request->get('fecha', now()->toDateString());

        // Ventas del día seleccionado
        $ventasDelDia = sale::with(['details.product'])
            ->whereDate('created_at', $fecha)
            ->get();

        // Métricas
        $totalDia      = number_format($ventasDelDia->sum('total'), 2);
        $numVentas     = $ventasDelDia->count();
        $ticketPromedio = $numVentas > 0
            ? number_format($ventasDelDia->sum('total') / $numVentas, 2)
            : '0.00';

        // Productos vendidos en el día agrupados
        $productosDelDia = detail_sale::with('product')
            ->whereHas('sale', fn($q) => $q->whereDate('created_at', $fecha))
            ->get()
            ->groupBy('product_id')
            ->map(function ($detalles) {
                $primero = $detalles->first();
                return [
                    'nombre'   => $primero->product->name ?? '—',
                    'cantidad' => $detalles->sum('cantidad'),
                    'subtotal' => number_format($detalles->sum('subtotal'), 2),
                ];
            })
            ->sortByDesc('cantidad')
            ->values();

        return view('resumen', compact(
            'totalDia',
            'numVentas',
            'ticketPromedio',
            'productosDelDia'
        ));
    }
}