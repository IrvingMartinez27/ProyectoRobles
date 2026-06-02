<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\client;
use App\Models\product;
use App\Models\inventory;
use App\Models\box;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    const LIMITE_VOZ_MENSUAL = 150;

    public function index()
    {
        $ventas = sale::with(['client', 'details.product', 'ticket'])
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->map(function ($venta) {
                return [
                    'id'               => $venta->id,
                    'cliente'          => $venta->client->name ?? 'Sin cliente',
                    'telefono'         => $venta->client->telefono ?? null,
                    'puntos_cliente'   => $venta->client->puntos ?? 0,
                    'total'            => $venta->total,
                    'estado'           => 'completada',
                    'fecha'            => $venta->created_at->format('d/m/Y H:i'),
                    'folio'            => $venta->ticket->folio ?? '—',
                    'metodo_pago'      => $venta->metodo_pago ?? 'efectivo',
                    'tipo_venta'       => $venta->tipo_venta ?? 'menudeo',
                    'puntos_ganados'   => $venta->puntos_ganados ?? 0,
                    'puntos_canjeados' => $venta->puntos_canjeados ?? 0,
                    'descuento_puntos' => $venta->descuento_puntos ?? 0,
                    'productos'        => $venta->details->map(fn($d) => [
                        'nombre'  => $d->product->name ?? '—',
                        'detalle' => 'x' . $d->cantidad . ($d->talla ? ' · Talla ' . $d->talla : ''),
                        'precio'  => $d->subtotal,
                    ])->toArray(),
                ];
            });

        $clientes = client::orderBy('name')->get();

        $productos = product::where('estado', true)
            ->with(['category', 'inventories' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'nombre'    => $p->name,
                'precio'    => $p->precio,
                'categoria' => strtolower($p->category->name ?? ''),
                'tallas'    => $p->inventories->map(fn($i) => [
                    'talla' => $i->talla,
                    'stock' => $i->stock,
                ])->toArray(),
            ]);

        $plan          = Auth::user()->plan ?? 'gratis';
        $tenant        = Tenant::find(Auth::user()->tenant_id);
        $lealtadActivo = in_array($plan, ['pro', 'business']) && ($tenant->lealtad_activo ?? false);

        // Créditos de voz restantes
        $creditosVoz = $this->creditosVozRestantes(Auth::user());

        return view('sales', compact('ventas', 'clientes', 'productos', 'plan', 'lealtadActivo', 'creditosVoz'));
    }

    // ── CRÉDITOS DE VOZ ───────────────────────────────────────
    private function creditosVozRestantes($user): int
    {
        $primerDiaMes = now()->format('Y-m-01');
        if ($user->voz_reset_fecha !== $primerDiaMes) {
            return self::LIMITE_VOZ_MENSUAL;
        }
        return max(0, ($user->creditos_voz_mensuales ?? self::LIMITE_VOZ_MENSUAL));
    }

    private function resetearCreditosVozSiNecesario($user): void
    {
        $primerDiaMes = now()->format('Y-m-01');
        if ($user->voz_reset_fecha !== $primerDiaMes) {
            \DB::connection('mysql')->table('users')
                ->where('id', $user->id)
                ->update([
                    'creditos_voz_mensuales' => self::LIMITE_VOZ_MENSUAL,
                    'voz_reset_fecha'        => $primerDiaMes,
                ]);
        }
    }

    // ── VOZ ───────────────────────────────────────────────────
    public function voz(Request $request)
    {
        $user  = Auth::user();
        $plan  = $user->plan ?? 'gratis';
        $esPro = in_array($plan, ['pro', 'business']);

        if (!$esPro) {
            return response()->json([
                'producto_id' => null,
                'mensaje'     => 'El registro por voz requiere Plan Pro.',
            ]);
        }

        // Reset mensual si corresponde
        $this->resetearCreditosVozSiNecesario($user);
        $user->refresh();

        // Verificar créditos
        $creditos = $user->creditos_voz_mensuales ?? self::LIMITE_VOZ_MENSUAL;
        if ($creditos <= 0) {
            return response()->json([
                'producto_id' => null,
                'limite'      => true,
                'mensaje'     => 'Agotaste tus 150 registros de voz este mes. Se renuevan el 1 de ' . now()->addMonth()->locale('es')->isoFormat('MMMM') . '.',
            ]);
        }

        $texto     = $request->input('texto', '');
        $productos = $request->input('productos', []);

        $lista = collect($productos)->map(function($p) {
            $tallas = $p['tallas'] ?? [];
            $partes = [];
            foreach ($tallas as $key => $value) {
                if (is_array($value)) {
                    $nombreTalla = $value['talla'] ?? $key;
                    $stock       = $value['stock'] ?? 0;
                } else {
                    $nombreTalla = $key;
                    $stock       = $value;
                }
                if (is_numeric($stock) && $stock > 0) {
                    $partes[] = "{$nombreTalla}(stock:{$stock})";
                }
            }
            $tallasStr = implode(', ', $partes);
            $nombre    = is_string($p['nombre'] ?? '') ? $p['nombre'] : '';
            $precio    = is_numeric($p['precio'] ?? 0) ? $p['precio'] : 0;
            return "ID:{$p['id']}|{$nombre}|\${$precio}|[{$tallasStr}]";
        })->join("\n");

        // Prompt ultra optimizado para mínimo de tokens
        $prompt = "Tienda deportiva. Vendedor dijo: \"{$texto}\"\nProductos:\n{$lista}\n\nResponde JSON sin backticks: {\"producto_id\":\"id\",\"talla\":\"talla exacta\",\"cantidad\":1,\"mensaje\":\"confirmación\"}\nSi no encuentras: {\"producto_id\":null,\"mensaje\":\"No encontrado\"}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(15)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 100,
                'messages'   => [['role' => 'user', 'content' => $prompt]],
            ]);

            $body  = $response->body();
            $text  = json_decode($body, true)['content'][0]['text'] ?? '{}';
            $clean = preg_replace('/```json|```/', '', $text);
            $data  = json_decode(trim($clean), true);

            if (!$data || !isset($data['producto_id'])) {
                throw new \Exception('Respuesta inválida');
            }

            // Decrementar crédito
            \DB::connection('mysql')->table('users')
                ->where('id', $user->id)
                ->decrement('creditos_voz_mensuales');

            $creditosRestantes = max(0, $creditos - 1);
            $data['creditos_restantes'] = $creditosRestantes;

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'producto_id' => null,
                'mensaje'     => 'Error al procesar. Intenta de nuevo.',
            ]);
        }
    }

    // ── STORE ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'productos'  => 'required|array|min:1',
            'cantidades' => 'required|array|min:1',
            'precios'    => 'required|array|min:1',
        ], [
            'productos.required' => 'Agrega al menos un producto.',
            'productos.min'      => 'Agrega al menos un producto.',
        ]);

        foreach ($request->productos as $i => $productoId) {
            $cantidad = $request->cantidades[$i] ?? 1;
            $talla    = $request->tallas[$i] ?? null;
            if ($talla) {
                $inv      = inventory::where('product_id', $productoId)->where('talla', $talla)->first();
                $producto = product::find($productoId);
                $nombre   = $producto->name ?? 'Producto';
                if (!$inv || $inv->stock < $cantidad) {
                    $stockDisponible = $inv->stock ?? 0;
                    return back()
                        ->with('error', "Solo hay {$stockDisponible} piezas disponibles de \"{$nombre}\" en talla {$talla}.")
                        ->withInput();
                }
            }
        }

        $plan          = Auth::user()->plan ?? 'gratis';
        $esPro         = in_array($plan, ['pro', 'business']);
        $tenant        = Tenant::find(Auth::user()->tenant_id);
        $lealtadActivo = $esPro && ($tenant->lealtad_activo ?? false);
        $puntosCanjear = $lealtadActivo ? (int) ($request->puntos_canjear ?? 0) : 0;

        if ($puntosCanjear > 0) {
            $clienteCheck = client::find($request->cliente_id);
            if ($clienteCheck && $clienteCheck->puntos < $puntosCanjear) {
                return back()
                    ->with('error', "El cliente solo tiene {$clienteCheck->puntos} puntos disponibles.")
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request, $puntosCanjear, $lealtadActivo) {

            $clienteId = $request->cliente_id;
            if (empty($clienteId) || !is_numeric($clienteId)) {
                $nombreCliente = $request->cliente_nombre ?? 'Cliente general';
                $clienteObj    = client::firstOrCreate(
                    ['name' => trim($nombreCliente)],
                    ['telefono' => null, 'direccion' => null, 'puntos' => 0]
                );
                $clienteId = $clienteObj->id;
            }

            $caja = box::where('estado', true)->latest()->first();
            if (!$caja) {
                $caja = box::create([
                    'user_id'        => Auth::id(),
                    'fecha_apertura' => today(),
                    'fecha_cierre'   => today(),
                    'monto_apertura' => 0,
                    'monto_final'    => 0,
                    'estado'         => true,
                ]);
            }

            $subtotal = 0;
            foreach ($request->productos as $i => $productoId) {
                $subtotal += ($request->cantidades[$i] ?? 1) * ($request->precios[$i] ?? 0);
            }

            $descuentoPuntos = $lealtadActivo ? $puntosCanjear : 0;
            $total           = max(0, $subtotal - $descuentoPuntos);
            $puntosGanados   = $lealtadActivo ? (int) floor($total / 50) : 0;

            $venta = sale::create([
                'client_id'        => $clienteId,
                'user_id'          => Auth::id(),
                'box_id'           => $caja->id,
                'total'            => $total,
                'metodo_pago'      => $request->metodo_pago ?? 'efectivo',
                'tipo_venta'       => $request->tipo_venta ?? 'menudeo',
                'puntos_ganados'   => $puntosGanados,
                'puntos_canjeados' => $puntosCanjear,
                'descuento_puntos' => $descuentoPuntos,
            ]);

            foreach ($request->productos as $i => $productoId) {
                $cantidad = $request->cantidades[$i] ?? 1;
                $precio   = $request->precios[$i] ?? 0;
                $talla    = $request->tallas[$i] ?? null;
                $producto = product::find($productoId);
                if ($producto) {
                    detail_sale::create([
                        'sale_id'         => $venta->id,
                        'product_id'      => $producto->id,
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal'        => $cantidad * $precio,
                        'talla'           => $talla,
                    ]);
                    if ($talla) {
                        inventory::where('product_id', $producto->id)
                            ->where('talla', $talla)
                            ->decrement('stock', $cantidad);
                    }
                }
            }

            if ($lealtadActivo) {
                $clienteObj = client::find($clienteId);
                if ($clienteObj) {
                    $nuevosPuntos = max(0, $clienteObj->puntos - $puntosCanjear + $puntosGanados);
                    $clienteObj->update(['puntos' => $nuevosPuntos]);
                }
            }

            ticket::create(['sale_id' => $venta->id]);
        });

        return redirect()->route('sales')
            ->with('success', 'Venta registrada correctamente.')
            ->with('ticket_metodo', $request->metodo_pago ?? 'efectivo')
            ->with('ticket_tipo', $request->tipo_venta ?? 'menudeo')
            ->with('ticket_recibido', $request->recibido ?? 0)
            ->with('ticket_cambio', $request->cambio ?? 0);
    }
}