<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\inventory;
use App\Models\GastoOperativo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $periodo    = request('periodo', 'dia');
        $almacenId  = request('almacen_id');
        $plan       = Auth::user()->plan ?? 'gratis';
        $esBusiness = $plan === 'business';

        if ($plan === 'gratis') {
            $periodo = 'dia';
        }

        // ── GRÁFICA ───────────────────────────────────────────
        if ($periodo === 'semana') {
            $labels  = [];
            $valores = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha    = Carbon::now()->subDays($i);
                $labels[] = $fecha->locale('es')->isoFormat('ddd D');

                $query = sale::whereDate('created_at', $fecha->toDateString());
                if ($esBusiness && $almacenId) $query->where('almacen_id', $almacenId);

                if ($esBusiness) {
                    $ventas         = $query->with('details.product')->get();
                    $ingresos       = $ventas->sum('total');
                    $costos         = $ventas->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
                    $gastos         = GastoOperativo::whereDate('fecha', $fecha->toDateString())
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->sum('monto');
                    $valores[]      = max(0, $ingresos - $costos - $gastos);
                } else {
                    $valores[] = (float) $query->sum('total');
                }
            }
        } elseif ($periodo === 'mes') {
            $labels  = [];
            $valores = [];
            for ($i = 3; $i >= 0; $i--) {
                $inicio   = Carbon::now()->subWeeks($i + 1)->startOfWeek();
                $fin      = Carbon::now()->subWeeks($i)->endOfWeek();
                $labels[] = 'Sem ' . $inicio->format('d/M');

                $query = sale::whereBetween('created_at', [$inicio, $fin]);
                if ($esBusiness && $almacenId) $query->where('almacen_id', $almacenId);

                if ($esBusiness) {
                    $ventas    = $query->with('details.product')->get();
                    $ingresos  = $ventas->sum('total');
                    $costos    = $ventas->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
                    $gastos    = GastoOperativo::whereBetween('fecha', [$inicio, $fin])
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->sum('monto');
                    $valores[] = max(0, $ingresos - $costos - $gastos);
                } else {
                    $valores[] = (float) $query->sum('total');
                }
            }
        } else {
            $query = sale::whereDate('created_at', today())
                ->selectRaw('DATE_FORMAT(created_at, "%H:%i") as momento, total, id')
                ->orderBy('created_at');
            if ($esBusiness && $almacenId) {
                $query = sale::whereDate('created_at', today())
                    ->where('almacen_id', $almacenId)
                    ->selectRaw('DATE_FORMAT(created_at, "%H:%i") as momento, total, id')
                    ->orderBy('created_at');
            }
            $ventasDia = $query->get();

            if ($ventasDia->isEmpty()) {
                $labels  = ['8:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
                $valores = [0, 0, 0, 0, 0, 0, 0];
            } else {
                if ($esBusiness) {
                    $ventasConDetalles = sale::whereDate('created_at', today())
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->with('details.product')
                        ->orderBy('created_at')
                        ->get();
                    $gastosDia = GastoOperativo::whereDate('fecha', today())
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->sum('monto');
                    $gastoPorVenta = $ventasConDetalles->count() > 0 ? $gastosDia / $ventasConDetalles->count() : 0;

                    $labels  = $ventasConDetalles->map(fn($v) => Carbon::parse($v->created_at)->format('H:i'))->toArray();
                    $valores = $ventasConDetalles->map(function($v) use ($gastoPorVenta) {
                        $costo = $v->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
                        return max(0, $v->total - $costo - $gastoPorVenta);
                    })->toArray();
                } else {
                    $labels  = $ventasDia->pluck('momento')->toArray();
                    $valores = $ventasDia->pluck('total')->map(fn($v) => (float) $v)->toArray();
                }
            }
        }

        // ── KPIs ──────────────────────────────────────────────
        $queryHoy = sale::whereDate('created_at', today());
        if ($esBusiness && $almacenId) $queryHoy->where('almacen_id', $almacenId);

        $ventasHoy      = $queryHoy->sum('total') ?? 0;
        $numVentasHoy   = $queryHoy->count();
        $ticketPromedio = $numVentasHoy > 0 ? round($ventasHoy / $numVentasHoy, 2) : 0;

        // ── KPIs BUSINESS ─────────────────────────────────────
        $ingresoBruto   = 0;
        $costosGastos   = 0;
        $utilidadReal   = 0;

        if ($esBusiness) {
            $mes    = now()->format('Y-m');
            $anio   = substr($mes, 0, 4);
            $mesNum = substr($mes, 5, 2);

            $ventasMes = sale::with('details.product')
                ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                ->whereYear('created_at', $anio)
                ->whereMonth('created_at', $mesNum)
                ->get();

            $ingresoBruto  = $ventasMes->sum('total');
            $costosMes     = $ventasMes->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
            $gastosMes     = GastoOperativo::whereYear('fecha', $anio)->whereMonth('fecha', $mesNum)
                ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                ->sum('monto');
            $costosGastos  = $costosMes + $gastosMes;
            $utilidadReal  = $ingresoBruto - $costosGastos;
        }

        // ── TOP PRODUCTOS ─────────────────────────────────────
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
                'imagen'     => $d->product->imagen ? asset('storage/' . $d->product->imagen) : null,
            ]);

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

        // ── ALMACENES PARA FILTRO ─────────────────────────────
        $almacenes = $esBusiness ? \App\Models\Almacen::where('activo', true)->get() : collect();

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
            'esBusiness'     => $esBusiness,
            'almacenes'      => $almacenes,
            'almacenId'      => $almacenId,
            'ingresoBruto'   => $ingresoBruto,
            'costosGastos'   => $costosGastos,
            'utilidadReal'   => $utilidadReal,
        ]);
    }

    public function ia(Request $request)
    {
        $plan  = Auth::user()->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);

        if (!$esPro) {
            return response()->json(['error' => 'Plan Pro requerido'], 403);
        }

        $ventasUltimos7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $ventasUltimos7[] = [
                'fecha' => $fecha->format('d/m'),
                'total' => (float) sale::whereDate('created_at', $fecha->toDateString())->sum('total'),
                'num'   => sale::whereDate('created_at', $fecha->toDateString())->count(),
            ];
        }

        $topProductos = detail_sale::selectRaw('product_id, SUM(cantidad) as total_vendido, SUM(subtotal) as total_monto')
            ->groupBy('product_id')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->with('product')
            ->get()
            ->map(fn($d) => [
                'nombre'   => $d->product->name ?? '—',
                'cantidad' => $d->total_vendido,
                'monto'    => $d->total_monto,
            ]);

        $stockBajo = inventory::with('product')
            ->where('stock', '<', 5)
            ->where('stock', '>=', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->get()
            ->map(fn($inv) => [
                'producto' => $inv->product->name ?? '—',
                'talla'    => $inv->talla,
                'stock'    => $inv->stock,
            ]);

        $mesActual = Carbon::now()->locale('es')->isoFormat('MMMM');
        $storeName = Auth::user()->store_name ?? 'la tienda';

        $prompt = "Eres un asistente de negocios para una tienda deportiva mexicana llamada '{$storeName}'. 
Analiza los siguientes datos y responde en JSON con exactamente esta estructura:

{
  \"alertas\": [\"alerta 1\", \"alerta 2\"],
  \"tendencias\": [\"tendencia 1\", \"tendencia 2\"],
  \"prediccion\": \"texto corto sobre predicción para los próximos días\",
  \"recomendacion\": \"una recomendación concreta de acción\"
}

DATOS:
- Mes actual: {$mesActual}
- Ventas últimos 7 días: " . json_encode($ventasUltimos7) . "
- Top productos vendidos: " . json_encode($topProductos) . "
- Productos con stock bajo (menos de 5 piezas): " . json_encode($stockBajo) . "

Responde SOLO con el JSON, sin texto adicional, sin backticks.";

        try {
            $apiKey = config('services.anthropic.key');
            if (empty($apiKey)) throw new \Exception('API key no configurada');

            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => 600,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

            if (!$response->successful()) {
                throw new \Exception('Error HTTP: ' . $response->status());
            }

            $body    = $response->json();
            $content = $body['content'][0]['text'] ?? null;
            if (!$content) throw new \Exception('Sin contenido en respuesta');

            $content = preg_replace('/```json|```/', '', $content);
            $data    = json_decode(trim($content), true);
            if (!$data) throw new \Exception('JSON inválido');

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'alertas'       => $stockBajo->take(3)->map(fn($s) => "Stock bajo: {$s['producto']} talla {$s['talla']} ({$s['stock']} pzs)")->values()->toArray(),
                'tendencias'    => $topProductos->take(2)->map(fn($p) => "{$p['nombre']} es tu producto más vendido")->values()->toArray(),
                'prediccion'    => 'Error: ' . $e->getMessage(),
                'recomendacion' => 'Verifica el inventario de productos con poco stock.',
            ]);
        }
    }

    public function reponer(Request $request)
    {
        return redirect()->route('inventario')
            ->with('reponer_producto', $request->producto);
    }
}