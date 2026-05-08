<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
         $clientes = Client::withCount('sales')
            ->withSum('sales', 'total')
            ->get()
            ->map(function ($cliente) {
                return [
                    'id'            => $cliente->id,
                    'nombre'        => $cliente->name,
                    'telefono'      => $cliente->telefono,
                    'direccion'     => $cliente->direccion,
                    'num_compras'   => $cliente->sales_count,
                    'total_gastado' => $cliente->sales_sum_total ?? 0,
                    'ultima_compra' => $cliente->sales()->latest()->first()?->created_at?->format('d/m/Y') ?? '—',
                    'compras'       => $cliente->sales()->latest()->get()->map(fn($s) => [
                        'id'           => $s->id,
                        'fecha'        => $s->created_at->format('d/m/Y'),
                        'total'        => $s->total,
                        'num_productos'=> $s->details()->count() ?? 0,
                    ])->toArray(),
                ];
            });

        $topCompradores = collect($clientes)->sortByDesc('total_gastado')->take(5)->values();

        $nuevosEsteMes = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('clientes', compact('clientes', 'topCompradores', 'nuevosEsteMes'));
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

        if (Client::whereRaw('LOWER(name) = ?', [strtolower($nameCompleto)])->exists()) {
            return back()->withErrors(['name' => 'Ya existe un cliente con ese nombre.'])->withInput();
        }

        Client::create([
            'name'      => $nameCompleto,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('clientes');
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        return redirect()->back();
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->back();
    }
}