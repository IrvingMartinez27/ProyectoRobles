<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $periodo = request('periodo', 'dia');
        $plan    = Auth::user()->plan ?? 'gratis';

        if ($plan === 'gratis') {
            $periodo = 'dia';
        }

        // ── GRÁFICA ───────────────────────────────────────────────
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
            // ── GRÁFICA DEL DÍA — cada venta en su hora:minuto exacto ──
            $ventas = sale::whereDate('created_at', today())
                ->selectRaw('DATE_FORMAT(created_at, "%H:%i") as momento, total')
                ->orderBy('created_at')
                ->get();

            if ($ventas->isEmpty()) {
                $labels  = ['8:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];
                $valores = [0, 0, 0, 0, 0, 0, 0];
            } else {
                $labels  = $ventas->pluck('momento')->toArray();
                $valores = $ventas->pluck('total')->map(fn($v) => (float) $v)->toArray();
            }
        }

        // ── KPIs ──────────────────────────────────────────────────
        $ventasHoy      = sale::whereDate('created_at', today())->sum('total') ?? 0;
        $numVentasHoy   = sale::whereDate('created_at', today())->count();
        $ticketPromedio = $numVentasHoy > 0 ? round($ventasHoy / $numVentasHoy, 2) : 0;

        // ── TOP PRODUCTOS ─────────────────────────────────────────
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
                'imagen' => $d->product->imagen ? asset('storage/' . $d->product->imagen) : null,
            ]);

        // ── LOW STOCK ─────────────────────────────────────────────
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

    // ── ENDPOINT IA ───────────────────────────────────────────────
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
                'imagen' => $d->product->imagen ? asset('storage/' . $d->product->imagen) : null,
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
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 500,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $body    = $response->body();
            $content = json_decode($body, true)['content'][0]['text'] ?? '{}';
            $data    = json_decode($content, true);

            if (!$data) {
                throw new \Exception('Respuesta inválida');
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'alertas'       => $stockBajo->take(3)->map(fn($s) => "Stock bajo: {$s['producto']} talla {$s['talla']} ({$s['stock']} pzs)")->values()->toArray(),
                'tendencias'    => $topProductos->take(2)->map(fn($p) => "{$p['nombre']} es tu producto más vendido")->values()->toArray(),
                'prediccion'    => 'No se pudo conectar con la IA. Revisa tu API key.',
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