<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteExport;

class ReporteController extends Controller
{
    // ── HELPERS COMUNES ───────────────────────────────────────
    private function buildQuery(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);
        $query = sale::with(['client', 'details.product'])->orderBy('created_at', 'desc');

        if (!$esPro) {
            $query->whereDate('created_at', now()->toDateString());
        } else {
            $periodo = $request->input('periodo', '');
            if ($periodo === 'hoy') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($periodo === 'semana') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($periodo === 'mes') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($periodo === 'mes_pasado') {
                $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            } elseif ($request->filled('desde') || $request->filled('hasta')) {
                if ($request->filled('desde')) $query->whereDate('created_at', '>=', $request->desde);
                if ($request->filled('hasta')) $query->whereDate('created_at', '<=', $request->hasta);
            }
        }

        return $query;
    }

    private function buildMetrics($query, $esPro)
    {
        $ventasAll    = (clone $query)->get();
        $totalPeriodo = $ventasAll->sum('total');
        $totalReg     = $ventasAll->count();
        $ticketProm   = $totalReg > 0 ? $totalPeriodo / $totalReg : 0;

        // Top productos
        $topProductos = $ventasAll->flatMap->details
            ->groupBy('product_id')
            ->map(fn($g) => [
                'nombre'   => $g->first()->product->name ?? '—',
                'unidades' => $g->sum('cantidad'),
                'monto'    => $g->sum('subtotal'),
            ])
            ->sortByDesc('monto')
            ->take(10)
            ->values();

        // Por método de pago
        $porMetodo = [];
        if ($esPro) {
            $metodos = (clone $query)->selectRaw('metodo_pago, COUNT(*) as cantidad, SUM(total) as monto')
                ->groupBy('metodo_pago')
                ->orderBy('metodo_pago')
                ->get();
            foreach ($metodos as $m) {
                $porMetodo[$m->metodo_pago ?? 'efectivo'] = [
                    'cantidad' => $m->cantidad,
                    'monto'    => $m->monto,
                ];
            }
        }

        return compact('totalPeriodo', 'totalReg', 'ticketProm', 'topProductos', 'porMetodo', 'ventasAll');
    }

    // ── INDEX ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);

        $query   = $this->buildQuery($request);
        $metrics = $this->buildMetrics($query, $esPro);

        $porTipo = collect();
        if ($esPro) {
            $porTipo = (clone $query)->selectRaw('tipo_venta, COUNT(*) as cantidad, SUM(total) as monto')
                ->groupBy('tipo_venta')
                ->orderBy('tipo_venta')
                ->get()
                ->mapWithKeys(fn($t) => [$t->tipo_venta ?? 'menudeo' => [
                    'cantidad' => $t->cantidad,
                    'monto'    => number_format($t->monto, 2),
                ]]);
        }

        $ventas = (clone $query)->paginate(15)->through(fn($v) => [
            'id'            => $v->id,
            'cliente'       => $v->client->name ?? 'Sin cliente',
            'num_productos' => $v->details->sum('cantidad'),
            'fecha'         => $v->created_at->format('d/m/Y H:i'),
            'metodo_pago'   => $v->metodo_pago ?? 'efectivo',
            'tipo_venta'    => $v->tipo_venta ?? 'menudeo',
            'estado'        => 'completada',
            'total'         => number_format($v->total, 2),
        ]);

        return view('reporte', [
            'ventas'         => $ventas,
            'totalPeriodo'   => number_format($metrics['totalPeriodo'], 2),
            'totalRegistros' => $metrics['totalReg'],
            'ticketPromedio' => number_format($metrics['ticketProm'], 2),
            'topProductos'   => $metrics['topProductos'],
            'porMetodo'      => $metrics['porMetodo'],
            'porTipo'        => $porTipo,
            'plan'           => $plan,
            'esPro'          => $esPro,
        ]);
    }

    // ── EXPORT PDF ────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);
        if (!$esPro) abort(403);

        $query   = $this->buildQuery($request);
        $metrics = $this->buildMetrics($query, $esPro);
        $user    = Auth::user();
        $periodo = $this->labelPeriodo($request);

        $porTipoRaw = (clone $query)->selectRaw('tipo_venta, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('tipo_venta')
            ->orderBy('tipo_venta')
            ->get()
            ->mapWithKeys(fn($t) => [$t->tipo_venta ?? 'menudeo' => [
                'cantidad' => $t->cantidad,
                'monto'    => $t->monto,
            ]])->toArray();
        $metrics['porTipo'] = $porTipoRaw;

        $pdf = Pdf::loadView('exports.reporte_pdf', [
            'metrics'    => $metrics,
            'user'       => $user,
            'periodo'    => $periodo,
            'plan'       => $plan,
            'generadoEn' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('reporte-quivex-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── EXPORT EXCEL ──────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);
        if (!$esPro) abort(403);

        $query   = $this->buildQuery($request);
        $metrics = $this->buildMetrics($query, $esPro);
        $periodo = $this->labelPeriodo($request);

        return Excel::download(
            new ReporteExport($metrics, $periodo, Auth::user(), $plan),
            'reporte-quivex-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private function labelPeriodo(Request $request): string
    {
        $p = $request->input('periodo', '');
        return match($p) {
            'hoy'        => 'Hoy — ' . now()->format('d/m/Y'),
            'semana'     => 'Esta semana',
            'mes'        => now()->translatedFormat('F Y'),
            'mes_pasado' => now()->subMonth()->translatedFormat('F Y'),
            default      => $request->filled('desde')
                ? ($request->desde . ' al ' . ($request->hasta ?? now()->format('Y-m-d')))
                : 'Todo el tiempo',
        };
    }
}