<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\ChatIaMensaje;
use App\Models\GastoOperativo;
use App\Models\sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatIAController extends Controller
{
    const LIMITE_MENSUAL = 60;

    public function index()
    {
        $user     = Auth::user();
        $mensajes = ChatIaMensaje::where('user_id', $user->id)->latest()->take(20)->get()->reverse()->values();
        $consultas_restantes = $this->consultasRestantes($user);
        $plan     = $user->plan ?? 'gratis';

            return view('chat_ia', compact('mensajes', 'consultas_restantes', 'plan'));
        }

    public function mensaje(Request $request)
    {
        $user = Auth::user();

        // Reset contador mensual
        $primerDiaMes = now()->format('Y-m-01');
        if ($user->chat_reset_fecha !== $primerDiaMes) {
            $user->update([
                'chat_consultas_mes' => 0,
                'chat_reset_fecha'   => $primerDiaMes,
            ]);
            $user->refresh();
        }

        // Verificar límite
        if ($user->chat_consultas_mes >= self::LIMITE_MENSUAL) {
            return response()->json([
                'error'   => true,
                'mensaje' => 'Alcanzaste tu límite de ' . self::LIMITE_MENSUAL . ' consultas este mes. Se renueva el 1 de ' . now()->addMonth()->translatedFormat('F') . '.',
            ]);
        }

        $request->validate(['mensaje' => 'required|string|max:500']);

        // Empaquetar contexto financiero
        $mes    = now()->format('Y-m');
        $anio   = substr($mes, 0, 4);
        $mesNum = substr($mes, 5, 2);

        $ventas = sale::with('details.product')
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mesNum)
            ->get();

        $ingresosBrutos  = $ventas->sum('total');
        $costoProveedor  = $ventas->flatMap->details->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad);
        $gastos          = GastoOperativo::whereYear('fecha', $anio)->whereMonth('fecha', $mesNum)->get();
        $totalGastos     = $gastos->sum('monto');
        $utilidadNeta    = $ingresosBrutos - $costoProveedor - $totalGastos;

        $topProductos = $ventas->flatMap->details
            ->groupBy('product_id')
            ->map(fn($g) => [
                'nombre'   => $g->first()->product->name ?? '—',
                'unidades' => $g->sum('cantidad'),
                'ingreso'  => $g->sum('subtotal'),
                'costo'    => $g->sum(fn($d) => ($d->product->costo ?? 0) * $d->cantidad),
            ])
            ->sortByDesc('ingreso')
            ->take(5)
            ->values();

        $contexto = [
            'mes'             => $mes,
            'tienda'          => $user->store_name ?? 'Mi tienda',
            'ingresos_brutos' => $ingresosBrutos,
            'costo_proveedor' => $costoProveedor,
            'gastos_totales'  => $totalGastos,
            'utilidad_neta'   => $utilidadNeta,
            'gastos_detalle'  => $gastos->groupBy('categoria')->map->sum('monto'),
            'top_productos'   => $topProductos,
            'almacenes'       => Almacen::where('activo', true)->get(['id', 'nombre', 'tipo']),
        ];

        $systemPrompt = "Eres el CFO virtual de una tienda de streetwear mexicana llamada \"{$user->store_name}\".
Tienes acceso a los datos financieros reales del negocio para el mes {$mes}.
Responde SIEMPRE en español mexicano, de forma ejecutiva, breve y muy legible.
Usa Markdown: **negritas** para cifras importantes, listas para comparativas.
Todas las cifras en pesos mexicanos con formato $ XX,XXX MXN.
Nunca inventes datos — solo analiza lo que está en el contexto financiero.
Si el usuario pregunta algo que no puedes responder con los datos disponibles, dilo claramente.
Contexto financiero: " . json_encode($contexto, JSON_UNESCAPED_UNICODE);

        // Últimos 3 mensajes del historial
        $historial = ChatIaMensaje::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get()
            ->reverse()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->contenido])
            ->values()
            ->toArray();

        // Agregar mensaje actual
        $historial[] = ['role' => 'user', 'content' => $request->mensaje];

        // Guardar mensaje del usuario
        ChatIaMensaje::create([
            'user_id'   => $user->id,
            'role'      => 'user',
            'contenido' => $request->mensaje,
        ]);

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 600,
                'system'     => $systemPrompt,
                'messages'   => $historial,
            ]);

            $body    = $response->body();
            $content = json_decode($body, true)['content'][0]['text'] ?? 'No pude procesar tu consulta.';

            // Guardar respuesta
            ChatIaMensaje::create([
                'user_id'   => $user->id,
                'role'      => 'assistant',
                'contenido' => $content,
            ]);

            // Incrementar contador
            $user->increment('chat_consultas_mes');

            return response()->json([
                'error'               => false,
                'respuesta'           => $content,
                'consultas_restantes' => self::LIMITE_MENSUAL - $user->fresh()->chat_consultas_mes,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'mensaje' => 'Error al conectar con la IA. Intenta de nuevo.',
            ]);
        }
    }

    private function consultasRestantes($user): int
    {
        $primerDiaMes = now()->format('Y-m-01');
        if ($user->chat_reset_fecha !== $primerDiaMes) {
            return self::LIMITE_MENSUAL;
        }
        return max(0, self::LIMITE_MENSUAL - $user->chat_consultas_mes);
    }
}