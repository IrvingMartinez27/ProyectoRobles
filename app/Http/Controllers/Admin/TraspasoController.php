<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\inventory;
use App\Models\Almacen;
use App\Models\sale;
use App\Models\detail_sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraspasoController extends Controller
{
    // ── VER PARA ENTREGA ──────────────────────────────────────
    public function index()
    {
        $almacenes  = Almacen::where('activo', true)->get();
        $enTransito = inventory::with('product')
            ->where('en_transito', '>', 0)
            ->whereHas('product', fn($q) => $q->where('estado', true))
            ->get();

        $local   = $almacenes->where('tipo', 'fisico')->first();
        $virtual = $almacenes->where('tipo', 'virtual')->first();

        return view('para_entrega', compact('enTransito', 'local', 'virtual', 'almacenes'));
    }

    // ── MOVER A TRÁNSITO ──────────────────────────────────────
    public function moverATransito(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|integer',
            'cantidad'     => 'required|integer|min:1',
        ]);

        $inv = inventory::findOrFail($request->inventory_id);

        if ($inv->stock < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock en Local.');
        }

        $inv->decrement('stock', $request->cantidad);
        $inv->increment('en_transito', $request->cantidad);

        return back()->with('success', "Se movieron {$request->cantidad} pieza(s) a Para Entrega.");
    }

    // ── REGRESAR A LOCAL ──────────────────────────────────────
    public function regresarALocal(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|integer',
            'cantidad'     => 'required|integer|min:1',
        ]);

        $inv = inventory::findOrFail($request->inventory_id);

        if ($inv->en_transito < $request->cantidad) {
            return back()->with('error', 'No hay suficientes piezas en tránsito.');
        }

        $inv->increment('stock', $request->cantidad);
        $inv->decrement('en_transito', $request->cantidad);

        return back()->with('success', "Se regresaron {$request->cantidad} pieza(s) al Local.");
    }

    // ── CONFIRMAR VENTA DESDE TRÁNSITO ────────────────────────
    public function confirmarVenta(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|integer',
            'cantidad'     => 'required|integer|min:1',
            'precio'       => 'required|numeric|min:0',
            'metodo_pago'  => 'required|in:efectivo,tarjeta,transferencia',
        ]);

        $inv = inventory::with('product')->findOrFail($request->inventory_id);

        if ($inv->en_transito < $request->cantidad) {
            return back()->with('error', 'No hay suficientes piezas en tránsito.');
        }

        $box = \App\Models\Box::where('estado', 'abierto')->latest()->first();
        if (!$box) {
            return back()->with('error', 'No hay caja abierta. Abre una caja primero.');
        }

        $total = $request->precio * $request->cantidad;
        $user  = Auth::user();

        $sale = sale::create([
            'client_id'   => null,
            'user_id'     => $user->id,
            'box_id'      => $box->id,
            'total'       => $total,
            'metodo_pago' => $request->metodo_pago,
        ]);

        detail_sale::create([
            'sale_id'    => $sale->id,
            'product_id' => $inv->product_id,
            'talla'      => $inv->talla,
            'cantidad'   => $request->cantidad,
            'subtotal'   => $total,
        ]);

        $inv->decrement('en_transito', $request->cantidad);

        return back()->with('success', "Venta de $" . number_format($total, 2) . " registrada correctamente.");
    }
}