<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\inventory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $periodo = request('periodo', 'dia');
        $plan    = Auth::user()->plan ?? 'gratis';

        // Plan gratis siempre ve el día
        if ($plan === 'gratis') {
            $periodo = 'dia';
        }

        // ── DATOS REALES PARA LA GRÁFICA ──────────────────────────

        if ($periodo === 'semana') {
            $labels  = [];
            $valores = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha     = Carbon::now()->subDays($i);
                $labels[]  = $fecha->locale('es')->isoFormat('ddd D');
                $valores[] = sale::whereDate('created_at', $fecha->toDateString())->sum('total') ?? 0;
            }

        } elseif ($periodo === 'mes') {
            $labels  = [];
            $valores = [];
            for ($i = 3; $i >= 0; $i--) {
                $inicio    = Carbon::now()->subWeeks($i + 1)->startOfWeek();
                $fin       = Carbon::now()->subWeeks($i)->endOfWeek();
                $labels[]  = 'Sem ' . $inicio->format('d/M');
                $valores[] = sale::whereBetween('created_at', [$inicio, $fin])->sum('total') ?? 0;
            }

        } else {
            // ── GRÁFICA DEL DÍA CON HORAS REALES ──────────────────
            // Trae todas las ventas de hoy agrupadas por hora real
            $ventasPorHora = sale::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as hora, SUM(total) as total')
                ->groupBy('hora')
                ->orderBy('hora')
                ->get()
                ->keyBy('hora');

            if ($ventasPorHora->isEmpty()) {
                // Sin ventas — mostrar horas vacías del día laboral
                $labels  = ['8:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
                $valores = [0, 0, 0, 0, 0, 0, 0];
            } else {
                // Mostrar desde la primera hasta la última hora con ventas
                $horaMin = $ventasPorHora->keys()->min();
                $horaMax = $ventasPorHora->keys()->max();

                $labels  = [];
                $valores = [];

                for ($h = $horaMin; $h <= $horaMax; $h++) {
                    $labels[]  = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                    $valores[] = $ventasPorHora->has($h) ? (float) $ventasPorHora[$h]->total : 0;
                }
            }
        }

        // ── KPIs DEL DÍA ──────────────────────────────────────────
        $ventasHoy      = sale::whereDate('created_at', today())->sum('total') ?? 0;
        $numVentasHoy   = sale::whereDate('created_at', today())->count();
        $ticketPromedio = $numVentasHoy > 0 ? round($ventasHoy / $numVentasHoy, 2) : 0;

        // ── TOP PRODUCTOS ──────────────────────────────────────────
        $totalVendido = detail_sale::sum('cantidad') ?: 1;

        $topProductos = detail_sale::selectRaw('product_id, SUM(cantidad) as total_vendido')
            ->groupBy('product_id')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->with('product')
            ->get()
            ->map(fn($d) => [
                'id'         => $d->product_id,
                'nombre'     => $d->product->name ?? '—',
                'ventas'     => $d->total_vendido,
                'porcentaje' => round(($d->total_vendido / $totalVendido) * 100),
                'imagen'     => null,
            ]);

        // ── LOW STOCK POR TALLAS ───────────────────────────────────
        $lowStock = inventory::with('product')
            ->where('stock', '<', 5)
            ->where('stock', '>=', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->orderBy('stock')
            ->get()
            ->map(fn($inv) => [
                'id'     => $inv->product->id ?? null,
                'nombre' => $inv->product->name ?? '—',
                'talla'  => $inv->talla,
                'stock'  => $inv->stock,
                'imagen' => null,
            ])
            ->filter(fn($p) => $p['id'] !== null)
            ->values();

        return view('dashboard', [
            'labels'         => $labels,
            'valores'        => $valores,
            'periodo'        => $periodo,
            'ventasHoy'      => $ventasHoy,
            'numVentasHoy'   => $numVentasHoy,
            'ticketPromedio' => $ticketPromedio,
            'topProductos'   => $topProductos,
            'lowStock'       => $lowStock,
            'plan'           => $plan,
        ]);
    }

    public function reponer(Request $request)
    {
        return redirect()->route('inventario')
            ->with('reponer_producto', $request->producto);
    }
}