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
    .periodo-btn.activo { background: #1a1c1c; color: #fff; border-color: #1a1c1c; }
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

    <header class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
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

    {{-- BANNER PLAN GRATIS --}}
    @if(!$esPro)
    <div class="mb-6 bg-[#1737c8]/5 border border-[#1737c8]/20 px-6 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[#1737c8] text-xl">lock</span>
            <div>
                <p class="text-sm font-bold text-[#1a1c1c]">Estás viendo el reporte de hoy</p>
                <p class="text-xs text-[#747688]">Actualiza al Plan Pro para filtrar por semana, mes, exportar y ver historial completo.</p>
            </div>
        </div>
        <a href="{{ route('planes') }}" class="shrink-0 bg-[#1737c8] text-white px-5 py-2 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
            Ver planes →
        </a>
    </div>
    @endif

    {{-- FILTROS PRO ────────────────────────────────────────────────────────────────────── --}}
    @if($esPro)
    <section class="bg-white p-6 border border-[#c4c5da]/20 mb-8 space-y-4">

        {{-- Filtros rápidos --}}
        <div class="flex flex-wrap gap-2">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] self-center mr-2">Periodo:</p>
            @foreach([
                ['hoy',        'Hoy'],
                ['semana',     'Esta semana'],
                ['mes',        'Este mes'],
                ['mes_pasado', 'Mes pasado'],
            ] as [$val, $label])
            <a href="{{ route('reporte', ['periodo' => $val]) }}"
               class="periodo-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all {{ request('periodo') === $val ? 'activo' : '' }}">
                {{ $label }}
            </a>
            @endforeach
            <a href="{{ route('reporte') }}"
               class="periodo-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all {{ !request('periodo') && !request('desde') ? 'activo' : '' }}">
                Todo
            </a>
        </div>

        {{-- Rango personalizado --}}
        <form method="GET" action="{{ route('reporte') }}" class="flex flex-col md:flex-row gap-4 items-end flex-wrap border-t border-[#c4c5da]/20 pt-4">
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
    @endif

    {{-- MÉTRICAS PRINCIPALES --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-[#1737c8] text-white p-5">
            <p class="text-[9px] font-black tracking-widest uppercase opacity-70 mb-1">
                {{ !$esPro ? 'Total hoy' : 'Total período' }}
            </p>
            <h2 class="text-2xl font-black">${{ $totalPeriodo }}</h2>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black tracking-widest uppercase text-[#747688] mb-1">Ventas</p>
            <h2 class="text-2xl font-black text-[#1a1c1c]">{{ $totalRegistros }}</h2>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black tracking-widest uppercase text-[#747688] mb-1">Ticket promedio</p>
            <h2 class="text-2xl font-black text-[#1a1c1c]">${{ $ticketPromedio }}</h2>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black tracking-widest uppercase text-[#747688] mb-1">Fecha</p>
            <h2 class="text-lg font-black text-[#1a1c1c]">{{ now()->format('d/m/Y') }}</h2>
        </div>
    </div>

    {{-- DESGLOSE PRO ───────────────────────────────────────────────────────────────────── --}}
    @if($esPro)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- Por método de pago --}}
        <div class="bg-white border border-[#c4c5da]/20 p-6">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-4">Por método de pago</h3>
            @forelse($porMetodo as $metodo => $datos)
            <div class="flex items-center justify-between py-3 border-b border-[#c4c5da]/10 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#1737c8] text-sm">
                        {{ $metodo === 'efectivo' ? 'payments' : ($metodo === 'tarjeta' ? 'credit_card' : 'account_balance') }}
                    </span>
                    <p class="text-sm font-bold uppercase">{{ $metodo }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-[#1737c8]">${{ $datos['monto'] }}</p>
                    <p class="text-[10px] text-[#747688]">{{ $datos['cantidad'] }} ventas</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-[#747688]">Sin datos</p>
            @endforelse
        </div>

        {{-- Por tipo de venta --}}
        <div class="bg-white border border-[#c4c5da]/20 p-6">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-4">Por tipo de venta</h3>
            @forelse($porTipo as $tipo => $datos)
            <div class="flex items-center justify-between py-3 border-b border-[#c4c5da]/10 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full {{ $tipo === 'mayoreo' ? 'bg-amber-400' : 'bg-[#1737c8]' }}"></span>
                    <p class="text-sm font-bold uppercase">{{ $tipo }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black {{ $tipo === 'mayoreo' ? 'text-amber-600' : 'text-[#1737c8]' }}">${{ $datos['monto'] }}</p>
                    <p class="text-[10px] text-[#747688]">{{ $datos['cantidad'] }} ventas</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-[#747688]">Sin datos</p>
            @endforelse
        </div>

    </div>
    @endif

    {{-- TABLA DE VENTAS --}}
    <section class="bg-white border border-[#c4c5da]/20 overflow-hidden">
        <div class="p-6 border-b border-[#c4c5da]/20 flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight">
                {{ !$esPro ? 'Ventas de hoy' : 'Detalle de ventas' }}
            </h2>
            @if(!$esPro)
            <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">{{ now()->format('d/m/Y') }}</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">#</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Cliente</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Productos</th>
                        @if($esPro)
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Método</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Tipo</th>
                        @endif
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Fecha</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas ?? [] as $venta)
                    <tr class="border-b border-[#c4c5da]/10 transition-colors cursor-default">
                        <td class="px-6 py-4 text-[#747688] font-mono text-xs">#{{ $venta['id'] }}</td>
                        <td class="px-6 py-4"><p class="font-bold text-sm">{{ $venta['cliente'] }}</p></td>
                        <td class="px-6 py-4"><span class="text-[10px] font-bold bg-[#f3f3f4] px-2 py-1 uppercase tracking-wider">{{ $venta['num_productos'] }} art.</span></td>
                        @if($esPro)
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold uppercase text-[#747688]">{{ $venta['metodo_pago'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-1 uppercase {{ ($venta['tipo_venta'] ?? 'menudeo') === 'mayoreo' ? 'bg-amber-100 text-amber-700' : 'bg-[#f3f3f4] text-[#747688]' }}">
                                {{ $venta['tipo_venta'] ?? 'menudeo' }}
                            </span>
                        </td>
                        @endif
                        <td class="px-6 py-4 text-sm text-[#747688]">{{ $venta['fecha'] }}</td>
                        <td class="px-6 py-4 text-right font-black text-[#1737c8]">${{ $venta['total'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $esPro ? 7 : 5 }}" class="px-6 py-16 text-center text-[#747688] text-sm">Sin ventas para este periodo</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($ventas) && method_exists($ventas, 'links'))
        <div class="p-6 border-t border-[#c4c5da]/20">{{ $ventas->links() }}</div>
        @endif
    </section>

    {{-- BANNER UPGRADE al final --}}
    @if(!$esPro)
    <div class="mt-8 bg-[#1a1c1c] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-2">Plan Pro</p>
            <h3 class="text-xl font-black text-white mb-1">Desbloquea reportes avanzados</h3>
            <p class="text-sm text-white/50">Filtra por semana, mes, ve desglose por método de pago y tipo de venta.</p>
        </div>
        <a href="{{ route('planes') }}" class="shrink-0 bg-[#1737c8] text-white px-8 py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
            Actualizar a Pro →
        </a>
    </div>
    @endif

</main>

@include('partials._sidebar')

</body>
</html>