<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Reporte</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    tr:hover td { background-color: #f3f3f4; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

<div class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Reporte completo</span>
    </div>
</div>

<main class="pb-20 md:pb-12 px-6 max-w-7xl mx-auto">
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Reportes</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Reporte completo</h1>
        </div>
        <a href="{{ route('dashboard') }}">
            <button class="bg-[#1a1c1c] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>VOLVER AL INICIO
            </button>
        </a>
    </header>

    <section class="bg-white p-6 border border-[#c4c5da]/20 mb-8">
        <form method="GET" action="{{ route('reporte') }}" class="flex flex-col md:flex-row gap-4 items-end flex-wrap">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#1737c8] transition-colors">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#1737c8] transition-colors">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-[#1737c8] text-white px-6 py-2 text-xs font-black tracking-widest uppercase hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span>FILTRAR
                </button>
                <a href="{{ route('reporte') }}">
                    <button type="button" class="border border-[#c4c5da]/40 px-6 py-2 text-xs font-black tracking-widest uppercase hover:bg-[#f3f3f4] transition-all">LIMPIAR</button>
                </a>
            </div>
        </form>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-[#1737c8] text-white p-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black tracking-widest uppercase opacity-70 mb-1">Total del período</p>
                <h2 class="text-3xl font-black">${{ $totalPeriodo ?? '0.00' }}</h2>
            </div>
            <span class="material-symbols-outlined text-5xl opacity-20">payments</span>
        </div>
        <div class="bg-[#f3f3f4] p-6 flex justify-between items-center border border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-black tracking-widest uppercase text-[#747688] mb-1">Total de ventas</p>
                <h2 class="text-3xl font-black text-[#1a1c1c]">{{ $totalRegistros ?? '0' }}</h2>
            </div>
            <span class="material-symbols-outlined text-5xl text-[#c4c5da]">receipt_long</span>
        </div>
    </div>

    <section class="bg-white border border-[#c4c5da]/20 overflow-hidden">
        <div class="p-6 border-b border-[#c4c5da]/20"><h2 class="text-xl font-bold tracking-tight">Todas las ventas</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">#</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Cliente</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Productos</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Fecha</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Estado</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas ?? [] as $venta)
                    <tr class="border-b border-[#c4c5da]/10 transition-colors cursor-default">
                        <td class="px-6 py-4 text-[#747688] font-mono text-xs">#{{ $venta['id'] }}</td>
                        <td class="px-6 py-4"><p class="font-bold text-sm">{{ $venta['cliente'] }}</p><p class="text-[10px] text-[#747688] uppercase tracking-wider">{{ $venta['email'] ?? '' }}</p></td>
                        <td class="px-6 py-4"><span class="text-[10px] font-bold bg-[#f3f3f4] px-2 py-1 uppercase tracking-wider">{{ $venta['num_productos'] }} artículo(s)</span></td>
                        <td class="px-6 py-4 text-sm text-[#747688]">{{ $venta['fecha'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black tracking-widest uppercase px-2 py-1 {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ strtoupper($venta['estado'] ?? 'completada') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-[#1737c8]">${{ $venta['total'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-[#747688] text-sm">Sin ventas para el período seleccionado</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($ventas) && method_exists($ventas, 'links'))
        <div class="p-6 border-t border-[#c4c5da]/20">{{ $ventas->links() }}</div>
        @endif
    </section>
</main>

@include('partials._sidebar')

</body>
</html>