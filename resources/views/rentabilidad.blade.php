<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Rentabilidad</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
</head>
<body class="bg-[#f9f9f9] text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Rentabilidad</span>
    </div>
</div>

<main class="pb-24 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Business</p>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight">Rentabilidad real</h1>
            <p class="text-sm text-[#747688] mt-1">Ventas − Costo proveedor − Gastos = Utilidad neta</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <form method="GET" action="{{ route('rentabilidad.index') }}" class="flex gap-2">
                <input type="month" name="mes" value="{{ $mes }}"
                       class="border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-white"/>
                @if(count($almacenes) > 0)
                <select name="almacen_id" class="border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-white">
                    <option value="">Todos los almacenes</option>
                    @foreach($almacenes as $a)
                    <option value="{{ $a->id }}" {{ $almacenId == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>
                    @endforeach
                </select>
                @endif
                <button type="submit" class="bg-[#1737c8] text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:opacity-90">
                    Filtrar
                </button>
            </form>
            <a href="{{ route('gastos.index') }}" class="bg-[#1a1c1c] text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:opacity-90 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span>Gastos
            </a>
        </div>
    </div>

    {{-- KPIs PRINCIPALES --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-2">Ingresos brutos</p>
            <p class="text-2xl font-black text-[#1737c8]">${{ number_format($ingresosBrutos, 0) }}</p>
            <p class="text-[9px] text-[#747688] mt-1">Total vendido</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-2">Costo proveedor</p>
            <p class="text-2xl font-black text-red-500">-${{ number_format($costoProveedor, 0) }}</p>
            <p class="text-[9px] text-[#747688] mt-1">Lo que pagaste</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-5">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-2">Gastos operativos</p>
            <p class="text-2xl font-black text-orange-500">-${{ number_format($totalGastos, 0) }}</p>
            <p class="text-[9px] text-[#747688] mt-1">Renta, envíos, etc.</p>
        </div>
        <div class="bg-{{ $utilidadNeta >= 0 ? 'green' : 'red' }}-500 p-5">
            <p class="text-[9px] font-black uppercase tracking-widest text-white/70 mb-2">Utilidad neta</p>
            <p class="text-2xl font-black text-white">${{ number_format($utilidadNeta, 0) }}</p>
            <p class="text-[9px] text-white/70 mt-1">Margen: {{ $margen }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- TOP PRODUCTOS --}}
        <div class="bg-white border border-[#c4c5da]/20">
            <div class="px-6 py-4 border-b border-[#c4c5da]/10">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Top productos por rentabilidad</p>
            </div>
            <div class="divide-y divide-[#c4c5da]/10">
                @forelse($topProductos as $prod)
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm uppercase truncate">{{ $prod['nombre'] }}</p>
                        <p class="text-[10px] text-[#747688]">{{ $prod['unidades'] }} uds · Ingreso ${{ number_format($prod['ingreso'], 0) }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-black text-sm {{ $prod['ganancia'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            ${{ number_format($prod['ganancia'], 0) }}
                        </p>
                        <p class="text-[10px] font-bold {{ $prod['margen'] >= 30 ? 'text-green-500' : ($prod['margen'] >= 15 ? 'text-amber-500' : 'text-red-400') }}">
                            {{ $prod['margen'] }}% margen
                        </p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-[#747688] text-sm">Sin ventas en este período</div>
                @endforelse
            </div>
        </div>

        {{-- GASTOS POR CATEGORÍA --}}
        <div class="bg-white border border-[#c4c5da]/20">
            <div class="px-6 py-4 border-b border-[#c4c5da]/10 flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Gastos por categoría</p>
                <a href="{{ route('gastos.index') }}" class="text-[10px] font-black text-[#1737c8] uppercase tracking-widest hover:underline">Ver todos</a>
            </div>
            @if($gastosPorCategoria->isEmpty())
            <div class="px-6 py-12 text-center">
                <span class="material-symbols-outlined text-3xl text-[#c4c5da] block mb-2">receipt_long</span>
                <p class="text-sm text-[#747688] mb-4">Sin gastos registrados</p>
                <a href="{{ route('gastos.index') }}" class="bg-[#1737c8] text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:opacity-90">
                    Registrar gastos
                </a>
            </div>
            @else
            <div class="divide-y divide-[#c4c5da]/10">
                @foreach(['fijo' => ['label' => 'Fijos', 'color' => 'bg-blue-100 text-blue-700'], 'variable' => ['label' => 'Variables', 'color' => 'bg-amber-100 text-amber-700'], 'hormiga' => ['label' => 'Hormiga', 'color' => 'bg-red-100 text-red-600']] as $cat => $info)
                @if(isset($gastosPorCategoria[$cat]))
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] font-black px-2 py-1 {{ $info['color'] }} uppercase">{{ $info['label'] }}</span>
                        <p class="text-sm text-[#747688]">
                            {{ $gastos->where('categoria', $cat)->count() }} concepto(s)
                        </p>
                    </div>
                    <p class="font-black text-sm text-red-500">-${{ number_format($gastosPorCategoria[$cat], 0) }}</p>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- FÓRMULA VISUAL --}}
    <div class="bg-[#1a1c1c] text-white px-6 py-5 flex flex-wrap gap-4 items-center justify-center text-center">
        <div>
            <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1">Ingresos</p>
            <p class="text-xl font-black text-[#1737c8]">${{ number_format($ingresosBrutos, 0) }}</p>
        </div>
        <span class="text-2xl font-black text-white/30">−</span>
        <div>
            <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1">Costo proveedor</p>
            <p class="text-xl font-black text-red-400">${{ number_format($costoProveedor, 0) }}</p>
        </div>
        <span class="text-2xl font-black text-white/30">−</span>
        <div>
            <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1">Gastos</p>
            <p class="text-xl font-black text-orange-400">${{ number_format($totalGastos, 0) }}</p>
        </div>
        <span class="text-2xl font-black text-white/30">=</span>
        <div>
            <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1">Utilidad neta</p>
            <p class="text-xl font-black {{ $utilidadNeta >= 0 ? 'text-green-400' : 'text-red-400' }}">${{ number_format($utilidadNeta, 0) }}</p>
        </div>
    </div>

</main>

@include('partials._sidebar')
</body>
</html>