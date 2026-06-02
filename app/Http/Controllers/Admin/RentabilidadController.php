<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\GastoOperativo;
use App\Models\sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentabilidadController extends Controller
{
    public function index(Request $request)
    {
        $mes       = $request->mes ?? now()->format('Y-m');
        $almacenId = $request->almacen_id;
        $anio      = substr($mes, 0, 4);
        $mesNum    = substr($mes, 5, 2);

        // Ventas del período
        $ventas = sale::with('details.product')
            ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mesNum)
            ->get();

        $ingresosBrutos = $ventas->sum('total');

        // Costo de proveedor
        $costoProveedor = $ventas->flatMap->details->sum(function($d) {
            return ($d->product->costo ?? 0) * $d->cantidad;
        });

        // Gastos operativos
        $gastos = GastoOperativo::with('almacen')
            ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mesNum)
            ->get();

        $totalGastos   = $gastos->sum('monto');
        $utilidadBruta = $ingresosBrutos - $costoProveedor;
        $utilidadNeta  = $utilidadBruta - $totalGastos;
        $margen        = $ingresosBrutos > 0
            ? round(($utilidadNeta / $ingresosBrutos) * 100, 1)
            : 0;

        // Top productos por rentabilidad
        $topProductos = $ventas->flatMap->details
            ->groupBy('product_id')
            ->map(function($detalles) {
                $producto = $detalles->first()->product;
                $ingreso  = $detalles->sum('subtotal');
                $costo    = $detalles->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
                return [
                    'nombre'   => $producto->name ?? '—',
                    'unidades' => $detalles->sum('cantidad'),
                    'ingreso'  => $ingreso,
                    'costo'    => $costo,
                    'ganancia' => $ingreso - $costo,
                    'margen'   => $ingreso > 0 ? round((($ingreso - $costo) / $ingreso) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('ganancia')
            ->take(5)
            ->values();

        // Gastos por categoría
        $gastosPorCategoria = $gastos->groupBy('categoria')->map->sum('monto');

        // Almacenes para filtro
        $almacenes = Almacen::where('activo', true)->get();

        $plan      = Auth::user()->plan ?? 'gratis';
        $esBusiness = $plan === 'business';

        return view('rentabilidad', compact(
            'ingresosBrutos', 'costoProveedor', 'totalGastos',
            'utilidadBruta', 'utilidadNeta', 'margen',
            'topProductos', 'gastosPorCategoria', 'gastos',
            'almacenes', 'mes', 'almacenId', 'esBusiness'
        ));
    }
}