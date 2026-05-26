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
            $labels = [];
            $valores = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                $labels[]  = $fecha->locale('es')->isoFormat('ddd D');
                $valores[] = sale::whereDate('created_at', $fecha->toDateString())->sum('total') ?? 0;
            }

        } elseif ($periodo === 'mes') {
            $labels = [];
            $valores = [];
            for ($i = 3; $i >= 0; $i--) {
                $inicio = Carbon::now()->subWeeks($i + 1)->startOfWeek();
                $fin    = Carbon::now()->subWeeks($i)->endOfWeek();
                $labels[]  = 'Sem ' . $inicio->format('d/M');
                $valores[] = sale::whereBetween('created_at', [$inicio, $fin])->sum('total') ?? 0;
            }

        } else {
            // Hoy por hora (6am a 10pm)
            $labels  = [];
            $valores = [];
            $horas   = [6, 8, 10, 12, 14, 16, 18, 20, 22];
            foreach ($horas as $hora) {
                $labels[]  = $hora . ':00';
                $valores[] = sale::whereDate('created_at', today())
                    ->whereTime('created_at', '>=', $hora . ':00:00')
                    ->whereTime('created_at', '<', ($hora + 2) . ':00:00')
                    ->sum('total') ?? 0;
            }
        }

        // KPIs del día
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
        // Trae cada talla con stock bajo (menos de 5 piezas por talla)
        $lowStock = inventory::with('product')
            ->where('stock', '<', 5)
            ->where('stock', '>=', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->orderBy('stock')
            ->get()
            ->map(fn($inv) => [
                'id'      => $inv->product->id ?? null,
                'nombre'  => $inv->product->name ?? '—',
                'talla'   => $inv->talla,
                'stock'   => $inv->stock,
                'imagen'  => null,
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