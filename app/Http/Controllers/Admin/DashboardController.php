<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\inventory;
use App\Models\GastoOperativo;
use App\Models\RestockDescarte;
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
                    $ventas    = $query->with('details.product')->get();
                    $ingresos  = $ventas->sum('total');
                    $costos    = $ventas->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
                    $gastos    = GastoOperativo::whereDate('fecha', $fecha->toDateString())
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->sum('monto');
                    $valores[] = max(0, $ingresos - $costos - $gastos);
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
                    $gastosDia     = GastoOperativo::whereDate('fecha', today())
                        ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                        ->sum('monto');
                    $gastoPorVenta = $ventasConDetalles->count() > 0 ? $gastosDia / $ventasConDetalles->count() : 0;

                    $labels  = $ventasConDetalles->map(fn($v) => Carbon::parse($v->created_at)->format('H:i'))->toArray();
                    $valores = $ventasConDetalles->map(function ($v) use ($gastoPorVenta) {
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
        $ingresoBruto = 0;
        $costosGastos = 0;
        $utilidadReal = 0;

        if ($esBusiness) {
            $mes    = now()->format('Y-m');
            $anio   = substr($mes, 0, 4);
            $mesNum = substr($mes, 5, 2);

            $ventasMes    = sale::with('details.product')
                ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                ->whereYear('created_at', $anio)
                ->whereMonth('created_at', $mesNum)
                ->get();

            $ingresoBruto = $ventasMes->sum('total');
            $costosMes    = $ventasMes->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
            $gastosMes    = GastoOperativo::whereYear('fecha', $anio)->whereMonth('fecha', $mesNum)
                ->when($almacenId, fn($q) => $q->where('almacen_id', $almacenId))
                ->sum('monto');
            $costosGastos = $costosMes + $gastosMes;
            $utilidadReal = $ingresoBruto - $costosGastos;
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

        // ── RESTOCK SEMÁFORO ──────────────────────────────────
        $userId = Auth::id();

        $descartados = RestockDescarte::where('user_id', $userId)
            ->where('descartado_hasta', '>', now())
            ->get()
            ->map(fn($d) => $d->product_id . '_' . $d->talla)
            ->toArray();

        $lowStock = inventory::with('product')
            ->where('stock', '>=', 0)
            ->where('stock', '<=', 8)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->orderBy('stock')
            ->get()
            ->map(function ($inv) use ($descartados) {
                $key = $inv->product_id . '_' . $inv->talla;
                if (in_array($key, $descartados)) return null;

                if ($inv->stock === 0) {
                    $mensaje  = '¡Sin stock!';
                    $urgencia = 'critico';
                } elseif ($inv->stock <= 3) {
                    $mensaje  = "Solo {$inv->stock} pieza(s) — se está agotando";
                    $urgencia = 'critico';
                } elseif ($inv->stock <= 6) {
                    $mensaje  = "Pocas piezas — {$inv->stock} restantes";
                    $urgencia = 'advertencia';
                } else {
                    $mensaje  = "Stock bajo — {$inv->stock} piezas";
                    $urgencia = 'bajo';
                }

                return [
                    'id'       => $inv->product->id ?? null,
                    'nombre'   => $inv->product->name ?? '—',
                    'talla'    => $inv->talla,
                    'stock'    => $inv->stock,
                    'mensaje'  => $mensaje,
                    'urgencia' => $urgencia,
                    'imagen'   => $inv->product->imagen ? asset('storage/' . $inv->product->imagen) : null,
                ];
            })
            ->filter(fn($p) => $p !== null && $p['id'] !== null)
            ->sortBy('stock')
            ->values();

        // ── DATOS PRO/BUSINESS EXCLUSIVOS ────────────────────
        $ventasSemana       = 0;
        $ventasSemanaPasada = 0;
        $mejorDiaSemana     = ['dia' => '—', 'total' => 0];
        $clientesSemana     = 0;
        $clientesHoy        = 0;
        $clientesNuevosHoy  = 0;
        $productoEstrella   = null;
        $ventasRecientes    = collect();
        $actividadReciente  = [];

        if ($plan === 'pro' || $plan === 'business') {
            $inicioSemana    = Carbon::now()->startOfWeek();
            $finSemana       = Carbon::now()->endOfWeek();
            $inicioSemPasada = Carbon::now()->subWeek()->startOfWeek();
            $finSemPasada    = Carbon::now()->subWeek()->endOfWeek();

            $ventasSemana       = (float) sale::whereBetween('created_at', [$inicioSemana, $finSemana])->sum('total');
            $ventasSemanaPasada = (float) sale::whereBetween('created_at', [$inicioSemPasada, $finSemPasada])->sum('total');

            $mejorVenta = sale::whereBetween('created_at', [$inicioSemana, $finSemana])
                ->selectRaw('DATE(created_at) as fecha, SUM(total) as total_dia')
                ->groupBy('fecha')
                ->orderByDesc('total_dia')
                ->first();
            if ($mejorVenta) {
                $mejorDiaSemana = [
                    'dia'   => Carbon::parse($mejorVenta->fecha)->locale('es')->isoFormat('dddd D/MM'),
                    'total' => (float) $mejorVenta->total_dia,
                ];
            }

            $clientesSemana = sale::whereBetween('created_at', [$inicioSemana, now()])
                ->whereNotNull('client_id')
                ->distinct('client_id')
                ->count('client_id');

            $clientesHoy = sale::whereDate('created_at', today())
                ->whereNotNull('client_id')
                ->distinct('client_id')
                ->count('client_id');

            $clientesNuevosHoy = \App\Models\Client::whereDate('created_at', today())->count();

            $mesIni = Carbon::now()->startOfMonth();
            $mesFin = Carbon::now()->endOfMonth();

            $estrella = detail_sale::selectRaw('product_id, SUM(cantidad) as total_vendido, SUM(subtotal) as total_monto')
                ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$mesIni, $mesFin]))
                ->groupBy('product_id')
                ->orderByDesc('total_vendido')
                ->with('product')
                ->first();

            $totalMes = detail_sale::whereHas('sale', fn($q) => $q->whereBetween('created_at', [$mesIni, $mesFin]))->sum('cantidad') ?: 1;

            if ($estrella && $estrella->product) {
                $productoEstrella = [
                    'nombre'     => $estrella->product->name,
                    'ventas'     => (int) $estrella->total_vendido,
                    'porcentaje' => round(($estrella->total_vendido / $totalMes) * 100),
                    'imagen'     => $estrella->product->imagen ? asset('storage/' . $estrella->product->imagen) : null,
                    'monto'      => (float) $estrella->total_monto,
                ];
            }

            $ventasRecientes = sale::whereDate('created_at', today())
                ->with('client')
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(fn($v) => [
                    'id'            => $v->id,
                    'cliente'       => $v->client->name ?? 'Cliente general',
                    'total'         => (float) $v->total,
                    'metodo'        => $v->metodo_pago ?? 'efectivo',
                    'hora'          => Carbon::parse($v->created_at)->format('H:i'),
                    'num_productos' => $v->details()->count(),
                ]);

            $actividadReciente = sale::whereDate('created_at', today())
                ->with('client')
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(fn($v) => [
                    'cliente'       => $v->client->name ?? 'Cliente general',
                    'total'         => (float) $v->total,
                    'metodo_pago'   => $v->metodo_pago ?? 'efectivo',
                    'tipo'          => $v->tipo_venta ?? 'menudeo',
                    'num_productos' => $v->details()->count(),
                    'hora'          => Carbon::parse($v->created_at)->format('H:i'),
                ])
                ->toArray();
        }

        // ── ALMACENES PARA FILTRO ─────────────────────────────
        $almacenes = $esBusiness ? \App\Models\Almacen::where('activo', true)->get() : collect();

        return view('dashboard', [
            'labels'             => $labels,
            'valores'            => $valores,
            'periodo'            => $periodo,
            'ventasHoy'          => $ventasHoy,
            'numVentasHoy'       => $numVentasHoy,
            'ticketPromedio'     => $ticketPromedio,
            'topProductos'       => $topProductos,
            'lowStock'           => $lowStock,
            'plan'               => $plan,
            'esBusiness'         => $esBusiness,
            'almacenes'          => $almacenes,
            'almacenId'          => $almacenId,
            'ingresoBruto'       => $ingresoBruto,
            'costosGastos'       => $costosGastos,
            'utilidadReal'       => $utilidadReal,
            'ventasSemana'       => $ventasSemana,
            'ventasSemanaPasada' => $ventasSemanaPasada,
            'mejorDiaSemana'     => $mejorDiaSemana,
            'clientesHoy'        => $clientesHoy,
            'clientesNuevosHoy'  => $clientesNuevosHoy,
            'clientesSemana'     => $clientesSemana,
            'productoEstrella'   => $productoEstrella,
            'ventasRecientes'    => $ventasRecientes,
            'actividadReciente'  => $actividadReciente,
        ]);
    }

    // ── DESCARTAR ALERTA DE RESTOCK ───────────────────────────
    public function descartarRestock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'talla'      => 'required|string',
            'dias'       => 'sometimes|integer|min:1|max:30',
        ]);

        $dias = $request->input('dias', 7);

        RestockDescarte::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'talla'      => $request->talla,
            ],
            [
                'descartado_hasta' => now()->addDays($dias),
            ]
        );

        return response()->json(['ok' => true]);
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
            $fecha            = Carbon::now()->subDays($i);
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
                'cantidad' => (int) $d->total_vendido,
                'monto'    => (float) $d->total_monto,
            ]);

        $stockBajo = inventory::with('product')
            ->where('stock', '<=', 8)
            ->where('stock', '>=', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->orderBy('stock')
            ->get()
            ->map(fn($inv) => [
                'producto' => $inv->product->name ?? '—',
                'talla'    => $inv->talla,
                'stock'    => $inv->stock,
            ]);

        $totalVentas7dias = collect($ventasUltimos7)->sum('total');
        $promedioVentas7  = $totalVentas7dias > 0 ? round($totalVentas7dias / 7, 0) : 0;
        $diasSinVentas    = collect($ventasUltimos7)->where('total', 0)->count();
        $mejorDia         = collect($ventasUltimos7)->sortByDesc('total')->first();

        $mesActual = Carbon::now()->locale('es')->isoFormat('MMMM');
        $storeName = Auth::user()->store_name ?? 'la tienda';

        $prompt = "Eres el asistente IA de Quivex, un sistema POS para tiendas de moda y streetwear mexicanas.

CONTEXTO: Estás analizando los datos reales de '{$storeName}' para dar recomendaciones útiles y accionables DENTRO del sistema Quivex.

LO QUE EL DUEÑO PUEDE HACER EN QUIVEX (recomienda solo esto):
- Reponer stock de productos específicos desde el módulo Inventario
- Registrar ventas por voz o manualmente
- Activar o usar el programa de lealtad para retener clientes frecuentes
- Revisar el reporte semanal o mensual para detectar patrones
- Hacer ventas de mayoreo con precios especiales para mover stock parado
- Consultar el historial de sus clientes más frecuentes

NUNCA menciones: pre-pedidos, email marketing, redes sociales, publicidad, apps externas, sistemas ajenos a Quivex.

MÉTRICAS RESUMIDAS:
- Promedio diario últimos 7 días: $" . number_format($promedioVentas7, 0) . "
- Días sin ventas en los últimos 7: {$diasSinVentas}
- Mejor día: " . ($mejorDia ? $mejorDia['fecha'] . " con $" . number_format($mejorDia['total'], 0) : 'N/A') . "

DATOS COMPLETOS:
- Mes: {$mesActual}
- Ventas día a día (últimos 7): " . json_encode($ventasUltimos7) . "
- Top 5 productos más vendidos: " . json_encode($topProductos) . "
- Productos con stock bajo (8 o menos piezas): " . json_encode($stockBajo) . "

FORMATO DE RESPUESTA — responde ÚNICAMENTE con este JSON, sin texto extra ni backticks:
{
  \"alertas\": [\"máximo 3 alertas urgentes y específicas con nombres de productos y números\"],
  \"tendencias\": [\"máximo 3 patrones reales detectados en los datos, no obviedades\"],
  \"prediccion\": \"qué esperar los próximos 2-3 días con números específicos basados en los datos\",
  \"recomendacion\": \"UNA acción concreta que puede hacer HOY en Quivex, mencionando el módulo específico\"
}";

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
                    'max_tokens' => 700,
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
            $alertasFallback = $stockBajo->take(3)->map(fn($s) =>
                "Stock bajo: {$s['producto']} talla {$s['talla']} — solo {$s['stock']} pieza(s). Ve a Inventario y reponlo."
            )->values()->toArray();

            $tendenciasFallback = $topProductos->take(2)->map(fn($p) =>
                "{$p['nombre']} lidera ventas con {$p['cantidad']} unidades vendidas"
            )->values()->toArray();

            return response()->json([
                'alertas'       => $alertasFallback,
                'tendencias'    => $tendenciasFallback,
                'prediccion'    => "Promedio diario de los últimos 7 días: $" . number_format($promedioVentas7, 0) . ". " . ($diasSinVentas > 0 ? "{$diasSinVentas} día(s) sin ventas registradas." : "Sin días sin ventas."),
                'recomendacion' => $stockBajo->count() > 0
                    ? "Ve a Inventario y repón el stock de {$stockBajo->first()['producto']} talla {$stockBajo->first()['talla']} — quedan solo {$stockBajo->first()['stock']} pieza(s)."
                    : 'Revisa el reporte semanal para identificar tus mejores días de venta.',
            ]);
        }
    }

    public function reponer(Request $request)
    {
        return redirect()->route('inventario')
            ->with('reponer_producto', $request->producto);
    }
}