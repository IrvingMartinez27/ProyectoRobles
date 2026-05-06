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
            'name' => 'required',
            'telefono' => 'required'
        ]);

        $nameCompleto = $request->name . ' ' . $request->apellido;

        Client::create([
            'name' => $nameCompleto,
            'telefono' => $request->telefono,
            'direccion' => 'N/A'
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