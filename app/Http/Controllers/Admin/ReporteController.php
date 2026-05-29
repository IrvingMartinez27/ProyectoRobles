<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);

        $query = sale::with(['client', 'details'])->latest();

        // ── FILTROS POR PERIODO ────────────────────────────────
        if (!$esPro) {
            // Plan gratis: solo hoy
            $query->whereDate('created_at', now()->toDateString());
        } else {
            $periodo = $request->input('periodo', '');

            if ($periodo === 'hoy') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($periodo === 'semana') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($periodo === 'mes') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            } elseif ($periodo === 'mes_pasado') {
                $query->whereMonth('created_at', now()->subMonth()->month)
                      ->whereYear('created_at', now()->subMonth()->year);
            } elseif ($request->filled('desde') || $request->filled('hasta')) {
                if ($request->filled('desde')) {
                    $query->whereDate('created_at', '>=', $request->desde);
                }
                if ($request->filled('hasta')) {
                    $query->whereDate('created_at', '<=', $request->hasta);
                }
            }
        }

        // ── MÉTRICAS ───────────────────────────────────────────
        $ventasQuery    = clone $query;
        $totalPeriodo   = number_format($ventasQuery->sum('total'), 2);
        $totalRegistros = $ventasQuery->count();
        $ticketPromedio = $totalRegistros > 0
            ? number_format($ventasQuery->sum('total') / $totalRegistros, 2)
            : '0.00';

        // Desglose por método de pago
        $porMetodo = [];
        if ($esPro) {
            $metodos = (clone $query)->selectRaw('metodo_pago, COUNT(*) as cantidad, SUM(total) as monto')
                ->groupBy('metodo_pago')
                ->get();
            foreach ($metodos as $m) {
                $porMetodo[$m->metodo_pago ?? 'efectivo'] = [
                    'cantidad' => $m->cantidad,
                    'monto'    => number_format($m->monto, 2),
                ];
            }

            // Desglose por tipo de venta
            $porTipo = (clone $query)->selectRaw('tipo_venta, COUNT(*) as cantidad, SUM(total) as monto')
                ->groupBy('tipo_venta')
                ->get()
                ->mapWithKeys(fn($t) => [$t->tipo_venta ?? 'menudeo' => [
                    'cantidad' => $t->cantidad,
                    'monto'    => number_format($t->monto, 2),
                ]]);
        } else {
            $porTipo = collect();
        }

        $ventas = $query->paginate(15)->through(fn($venta) => [
            'id'            => $venta->id,
            'cliente'       => $venta->client->name ?? 'Sin cliente',
            'num_productos' => $venta->details->sum('cantidad'),
            'fecha'         => $venta->created_at->format('d/m/Y H:i'),
            'metodo_pago'   => $venta->metodo_pago ?? 'efectivo',
            'tipo_venta'    => $venta->tipo_venta ?? 'menudeo',
            'estado'        => 'completada',
            'total'         => number_format($venta->total, 2),
        ]);

        return view('reporte', compact(
            'ventas', 'totalPeriodo', 'totalRegistros',
            'ticketPromedio', 'porMetodo', 'porTipo', 'plan', 'esPro'
        ));
    }
}