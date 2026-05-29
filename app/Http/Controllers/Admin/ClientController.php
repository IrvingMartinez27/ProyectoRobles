<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\client;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $plan      = Auth::user()->plan ?? 'gratis';
        $esPro     = in_array($plan, ['pro', 'business']);
        $tenant    = Tenant::find(Auth::user()->tenant_id);
        $lealtadActivo = $esPro && ($tenant->lealtad_activo ?? false);

        $clientes = client::withCount('sales')
            ->withSum('sales', 'total')
            ->get()
            ->map(function ($cliente) {
                return [
                    'id'            => $cliente->id,
                    'nombre'        => $cliente->name,
                    'telefono'      => $cliente->telefono,
                    'direccion'     => $cliente->direccion,
                    'puntos'        => $cliente->puntos ?? 0,
                    'num_compras'   => $cliente->sales_count,
                    'total_gastado' => $cliente->sales_sum_total ?? 0,
                    'ultima_compra' => $cliente->sales()->latest()->first()?->created_at?->format('d/m/Y') ?? '—',
                    'compras'       => $cliente->sales()->latest()->get()->map(fn($s) => [
                        'id'             => $s->id,
                        'fecha'          => $s->created_at->format('d/m/Y'),
                        'total'          => $s->total,
                        'num_productos'  => $s->details()->count() ?? 0,
                        'puntos_ganados' => $s->puntos_ganados ?? 0,
                    ])->toArray(),
                ];
            });

        $topCompradores = collect($clientes)->sortByDesc('total_gastado')->take(5)->values();

        $nuevosEsteMes = client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('clientes', compact('clientes', 'topCompradores', 'nuevosEsteMes', 'plan', 'esPro', 'lealtadActivo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'telefono' => 'required|unique:clients,telefono',
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique'   => 'Este teléfono ya está registrado.',
        ]);

        $nameCompleto = trim($request->name . ' ' . $request->apellido);

        if (client::whereRaw('LOWER(name) = ?', [strtolower($nameCompleto)])->exists()) {
            return back()->withErrors(['name' => 'Ya existe un cliente con ese nombre.'])->withInput();
        }

        client::create([
            'name'      => $nameCompleto,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
            'puntos'    => 0,
        ]);

        return redirect()->route('clientes');
    }

    public function canjearPuntos(Request $request, $id)
    {
        $request->validate([
            'puntos_canjear' => 'required|integer|min:1',
        ]);

        $cliente = client::findOrFail($id);
        $puntos  = (int) $request->puntos_canjear;

        if ($cliente->puntos < $puntos) {
            return redirect('/clientes')->with('error', "El cliente solo tiene {$cliente->puntos} puntos disponibles.");
        }

        $cliente->decrement('puntos', $puntos);
        return redirect('/clientes')->with('success', "Se canjearon {$puntos} puntos (\${$puntos} de descuento) del cliente {$cliente->name}.");
    }

    public function update(Request $request, client $client)
    {
        $client->update($request->all());
        return redirect()->back();
    }

    public function destroy(client $client)
    {
        $client->delete();
        return redirect()->back();
    }
}