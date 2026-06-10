<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\inventory;
use App\Models\detail_sale;
use App\Models\sale;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificacionController extends Controller
{
    // ── OBTENER NOTIFICACIONES NO LEÍDAS ─────────────────────
    public function index()
    {
        $userId = Auth::id();
        $plan   = Auth::user()->plan ?? 'gratis';
        $esPro  = in_array($plan, ['pro', 'business']);

        // Generar notificaciones automáticas si aplica
        if ($esPro) {
            $this->generarNotificacionesRestock($userId);
            $this->generarNotificacionSinVentas($userId);
        }

        $notificaciones = Notificacion::delUsuario($userId)
            ->noLeidas()
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(fn($n) => [
                'id'        => $n->id,
                'tipo'      => $n->tipo,
                'titulo'    => $n->titulo,
                'mensaje'   => $n->mensaje,
                'icono'     => $n->icono,
                'color'     => $n->color,
                'data'      => $n->data,
                'hace'      => Carbon::parse($n->created_at)->diffForHumans(),
            ]);

        return response()->json([
            'notificaciones' => $notificaciones,
            'total'          => $notificaciones->count(),
        ]);
    }

    // ── MARCAR UNA COMO LEÍDA ─────────────────────────────────
    public function leer($id)
    {
        $notif = Notificacion::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update(['leida_at' => now()]);

        return response()->json(['ok' => true]);
    }

    // ── MARCAR TODAS COMO LEÍDAS ──────────────────────────────
    public function leerTodas()
    {
        Notificacion::delUsuario(Auth::id())
            ->noLeidas()
            ->update(['leida_at' => now()]);

        return response()->json(['ok' => true]);
    }

    // ── GENERAR NOTIFICACIONES DE RESTOCK ─────────────────────
    private function generarNotificacionesRestock($userId)
    {
        $hace30 = Carbon::now()->subDays(30);

        $inventarios = inventory::with('product')
            ->where('stock', '>', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->get();

        foreach ($inventarios as $inv) {
            $vendidos30 = detail_sale::where('product_id', $inv->product_id)
                ->where('talla', $inv->talla)
                ->whereHas('sale', fn($q) => $q->where('created_at', '>=', $hace30))
                ->sum('cantidad');

            if ($vendidos30 == 0) continue;

            $promedio      = $vendidos30 / 30;
            $diasRestantes = round($inv->stock / $promedio);

            if ($diasRestantes > 7) continue;

            // Evitar duplicados — no crear si ya existe una no leída para este producto/talla
            $existe = Notificacion::where('user_id', $userId)
                ->where('tipo', 'restock')
                ->whereNull('leida_at')
                ->whereJsonContains('data->product_id', $inv->product_id)
                ->whereJsonContains('data->talla', $inv->talla)
                ->exists();

            if ($existe) continue;

            $titulo  = $diasRestantes <= 1
                ? "¡{$inv->product->name} T.{$inv->talla} se acaba hoy!"
                : "{$inv->product->name} T.{$inv->talla} — {$diasRestantes} días de stock";

            $mensaje = "Tienes {$inv->stock} pieza(s) y vendes ~" . round($promedio, 1) . " por día. Considera reponer pronto.";

            Notificacion::create([
                'user_id' => $userId,
                'tipo'    => 'restock',
                'titulo'  => $titulo,
                'mensaje' => $mensaje,
                'icono'   => 'inventory_2',
                'color'   => $diasRestantes <= 1 ? 'red' : ($diasRestantes <= 3 ? 'red' : 'amber'),
                'data'    => [
                    'product_id' => $inv->product_id,
                    'talla'      => $inv->talla,
                    'stock'      => $inv->stock,
                    'dias'       => $diasRestantes,
                ],
            ]);
        }
    }

    // ── GENERAR NOTIFICACIÓN SIN VENTAS ───────────────────────
    private function generarNotificacionSinVentas($userId)
    {
        $hora = Carbon::now()->hour;

        // Solo alertar si ya son más de las 14:00 y no hay ventas hoy
        if ($hora < 14) return;

        $ventasHoy = sale::whereDate('created_at', today())->count();
        if ($ventasHoy > 0) return;

        // Evitar duplicado del mismo día
        $existe = Notificacion::where('user_id', $userId)
            ->where('tipo', 'sin_ventas')
            ->whereNull('leida_at')
            ->whereDate('created_at', today())
            ->exists();

        if ($existe) return;

        Notificacion::create([
            'user_id' => $userId,
            'tipo'    => 'sin_ventas',
            'titulo'  => 'Sin ventas registradas hoy',
            'mensaje' => 'Ya son las ' . Carbon::now()->format('H:i') . ' y no tienes ventas hoy. ¿Todo bien?',
            'icono'   => 'trending_down',
            'color'   => 'amber',
            'data'    => ['fecha' => today()->toDateString()],
        ]);
    }
}