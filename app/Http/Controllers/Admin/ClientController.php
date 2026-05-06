<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return view('clientes', [
            'clientes' => $clients,
            'topCompradores' => [],
            'nuevosEsteMes' => 0
        ]);
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