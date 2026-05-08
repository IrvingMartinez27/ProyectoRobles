<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\detail_sale;

class DashboardController extends Controller
{
    public function index()
    {
        $periodo = request('periodo', 'dia');

        if ($periodo == 'semana') {
            $labels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
            $ventas = [
                ['altura' => 210, 'valor' => '18k'],
                ['altura' => 280, 'valor' => '23k'],
                ['altura' => 190, 'valor' => '16k'],
                ['altura' => 320, 'valor' => '27k'],
            ];

        } elseif ($periodo == 'mes') {
            $labels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            $ventas = [
                ['altura' => 180, 'valor' => '15k'],
                ['altura' => 220, 'valor' => '18k'],
                ['altura' => 160, 'valor' => '13k'],
                ['altura' => 300, 'valor' => '25k'],
                ['altura' => 270, 'valor' => '22k'],
                ['altura' => 310, 'valor' => '26k'],
                ['altura' => 290, 'valor' => '24k'],
                ['altura' => 250, 'valor' => '21k'],
                ['altura' => 330, 'valor' => '28k'],
                ['altura' => 280, 'valor' => '23k'],
                ['altura' => 200, 'valor' => '17k'],
                ['altura' => 340, 'valor' => '29k'],
            ];

        } else {
            $labels = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
            $ventas = [
                ['altura' => 60,  'valor' => '5k'],
                ['altura' => 120, 'valor' => '10k'],
                ['altura' => 180, 'valor' => '15k'],
                ['altura' => 240, 'valor' => '20k'],
                ['altura' => 300, 'valor' => '25k'],
                ['altura' => 150, 'valor' => '12k'],
                ['altura' => 210, 'valor' => '18k'],
            ];
        }

        // ── TOP PRODUCTOS ──────────────────────────────────────────
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
                'imagen'     => null,
            ]);

        // ── LOW STOCK ──────────────────────────────────────────────
        $lowStock = product::where('estado', true)
            ->with('inventories')
            ->get()
            ->map(fn($producto) => [
                'id'     => $producto->id,
                'nombre' => $producto->name,
                'stock'  => $producto->inventories->sum('stock'),
                'imagen' => null,
            ])
            ->filter(fn($p) => $p['stock'] < 10)
            ->sortBy('stock')
            ->values();

        return view('dashboard', [
            'ventas'       => $ventas,
            'topProductos' => $topProductos,
            'lowStock'     => $lowStock,
            'labels'       => $labels,
        ]);
    }

    public function reponer(Request $request)
    {
        return redirect()->route('inventario')
            ->with('reponer_producto', $request->producto);
    }
}