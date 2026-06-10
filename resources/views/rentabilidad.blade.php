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
    body { font-family: 'Inter', sans-serif; min-height: 100dvh; overflow-x: hidden; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
</head>
<body class="bg-[#f9f9f9] dark:bg-gray-900 text-[#1a1c1c] dark:text-gray-100 transition-colors duration-300">

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex flex-wrap items-center gap-1 sm:gap-2 text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-[#747688] dark:text-gray-400 py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] dark:hover:text-blue-400 transition-colors duration-300 whitespace-nowrap">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da] dark:text-gray-600 shrink-0">chevron_right</span>
        <span class="text-[#1a1c1c] dark:text-gray-100 whitespace-nowrap">Rentabilidad</span>
    </div>
</div>

<main class="pb-24 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-8">
        <div class="min-w-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] dark:text-blue-400 mb-1">Business</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight dark:text-white truncate">Rentabilidad real</h1>
            <p class="text-xs sm:text-sm text-[#747688] dark:text-gray-400 mt-1 truncate">Ventas − Costo proveedor − Gastos = Utilidad neta</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <form method="GET" action="{{ route('rentabilidad.index') }}" class="flex flex-col sm:flex-row w-full sm:w-auto gap-2">
                <div class="flex flex-1 gap-2">
                    <input type="month" name="mes" value="{{ $mes }}"
                           class="w-full sm:w-auto flex-1 border border-gray-200 dark:border-gray-700 px-3 py-2.5 sm:py-2 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-white dark:bg-gray-800 dark:text-white rounded-xl shadow-inner transition-all duration-300 dark:[color-scheme:dark]"/>
                    
                    @if(count($almacenes) > 0)
                    <select name="almacen_id" class="w-full sm:w-auto flex-1 border border-gray-200 dark:border-gray-700 px-3 py-2.5 sm:py-2 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-white dark:bg-gray-800 dark:text-white rounded-xl shadow-inner transition-all duration-300 cursor-pointer">
                        <option value="">Todos los almacenes</option>
                        @foreach($almacenes as $a)
                        <option value="{{ $a->id }}" {{ $almacenId == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="w-full sm:w-auto justify-center bg-[#1737c8] dark:bg-blue-600 text-white px-5 py-2.5 sm:py-2 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95">
                        Filtrar
                    </button>
                    <!-- El botón extra de agregar gastos también se vuelve adaptable -->
                    <a href="{{ route('gastos.index') }}" class="w-full sm:w-auto justify-center bg-[#1a1c1c] dark:bg-white text-white dark:text-[#1a1c1c] px-4 py-2.5 sm:py-2 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">add</span>Gastos
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- KPIs PRINCIPALES --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-2">Ingresos brutos</p>
            <p class="text-xl sm:text-2xl font-black text-[#1737c8] dark:text-blue-400 truncate">${{ number_format($ingresosBrutos, 0) }}</p>
            <p class="text-[8px] sm:text-[9px] text-[#747688] dark:text-gray-500 mt-1">Total vendido</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-2">Costo proveedor</p>
            <p class="text-xl sm:text-2xl font-black text-red-500 dark:text-red-400 truncate">-${{ number_format($costoProveedor, 0) }}</p>
            <p class="text-[8px] sm:text-[9px] text-[#747688] dark:text-gray-500 mt-1">Lo que pagaste</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-2">Gastos operativos</p>
            <p class="text-xl sm:text-2xl font-black text-orange-500 dark:text-orange-400 truncate">-${{ number_format($totalGastos, 0) }}</p>
            <p class="text-[8px] sm:text-[9px] text-[#747688] dark:text-gray-500 mt-1">Renta, envíos, etc.</p>
        </div>
        <div class="bg-{{ $utilidadNeta >= 0 ? 'green' : 'red' }}-500 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-lg shadow-{{ $utilidadNeta >= 0 ? 'green' : 'red' }}-500/30 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-white/80 mb-2">Utilidad neta</p>
            <p class="text-xl sm:text-2xl font-black text-white truncate">${{ number_format($utilidadNeta, 0) }}</p>
            <p class="text-[8px] sm:text-[9px] font-bold text-white mt-1 bg-black/10 inline-block px-2 py-0.5 rounded-full">Margen: {{ $margen }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6 mb-8">

        {{-- TOP PRODUCTOS --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl sm:rounded-3xl shadow-lg overflow-hidden transition-all">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400">Top productos por rentabilidad</p>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700 p-2 sm:p-3">
                @forelse($topProductos as $prod)
                <div class="px-3 sm:px-4 py-3 sm:py-4 flex items-center justify-between gap-3 sm:gap-4 rounded-xl sm:rounded-2xl hover:bg-[#f9f9f9] dark:hover:bg-gray-700 hover:shadow-sm hover:scale-[1.005] transition-all duration-300 group">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-xs sm:text-sm uppercase truncate dark:text-gray-100 group-hover:text-[#1737c8] dark:group-hover:text-blue-400 transition-colors">{{ $prod['nombre'] }}</p>
                        <p class="text-[9px] sm:text-[10px] text-[#747688] dark:text-gray-400 mt-0.5 truncate">{{ $prod['unidades'] }} uds · Ingreso ${{ number_format($prod['ingreso'], 0) }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-black text-xs sm:text-sm {{ $prod['ganancia'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                            ${{ number_format($prod['ganancia'], 0) }}
                        </p>
                        <p class="text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded-md mt-1 inline-block
                            {{ $prod['margen'] >= 30 ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($prod['margen'] >= 15 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400') }}">
                            {{ $prod['margen'] }}%
                        </p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-16 text-center text-[#747688] dark:text-gray-500 text-xs sm:text-sm font-medium">Sin ventas en este período</div>
                @endforelse
            </div>
        </div>

        {{-- GASTOS POR CATEGORÍA --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl sm:rounded-3xl shadow-lg overflow-hidden transition-all flex flex-col">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center justify-between">
                <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400">Gastos por categoría</p>
                <a href="{{ route('gastos.index') }}" class="text-[8px] sm:text-[10px] font-black text-[#1737c8] dark:text-blue-400 uppercase tracking-widest hover:underline bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-lg">Ver todos</a>
            </div>
            
            @if($gastosPorCategoria->isEmpty())
            <div class="px-4 sm:px-6 py-16 sm:py-20 text-center flex-1 flex flex-col justify-center items-center">
                <span class="material-symbols-outlined text-4xl sm:text-5xl text-[#c4c5da] dark:text-gray-600 block mb-3 animate-bounce">receipt_long</span>
                <p class="text-xs sm:text-sm font-bold text-[#747688] dark:text-gray-400 mb-5">Sin gastos registrados</p>
                <a href="{{ route('gastos.index') }}" class="bg-[#1737c8] dark:bg-blue-600 text-white px-5 sm:px-6 py-3 text-[10px] sm:text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    Registrar gastos
                </a>
            </div>
            @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700 p-2 sm:p-3 flex-1">
                @foreach(['fijo' => ['label' => 'Fijos', 'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'], 'variable' => ['label' => 'Variables', 'color' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'], 'hormiga' => ['label' => 'Hormiga', 'color' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300']] as $cat => $info)
                @if(isset($gastosPorCategoria[$cat]))
                <div class="px-3 sm:px-4 py-3 sm:py-4 flex items-center justify-between gap-2 rounded-xl sm:rounded-2xl hover:bg-[#f9f9f9] dark:hover:bg-gray-700 hover:shadow-sm hover:scale-[1.005] transition-all duration-300">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 min-w-0">
                        <span class="text-[8px] sm:text-[9px] font-black px-2 py-1 {{ $info['color'] }} uppercase rounded-md shadow-sm shrink-0">{{ $info['label'] }}</span>
                        <p class="text-xs sm:text-sm text-[#747688] dark:text-gray-400 font-medium truncate">
                            {{ $gastos->where('categoria', $cat)->count() }} concepto(s)
                        </p>
                    </div>
                    <p class="font-black text-xs sm:text-sm text-red-500 dark:text-red-400 shrink-0">-${{ number_format($gastosPorCategoria[$cat], 0) }}</p>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- FÓRMULA VISUAL --}}
    <div class="bg-[#1a1c1c] dark:bg-gray-900 text-white px-4 sm:px-6 py-6 sm:py-8 flex flex-wrap gap-4 sm:gap-6 items-center justify-center text-center rounded-2xl sm:rounded-3xl shadow-xl border border-gray-800 transition-all duration-300 hover:shadow-2xl">
        <div class="min-w-[80px]">
            <p class="text-[8px] sm:text-[9px] text-white/50 uppercase tracking-widest mb-1">Ingresos</p>
            <p class="text-lg sm:text-xl md:text-2xl font-black text-[#1737c8] dark:text-blue-500">${{ number_format($ingresosBrutos, 0) }}</p>
        </div>
        <span class="text-xl sm:text-2xl font-black text-white/20">−</span>
        <div class="min-w-[80px]">
            <p class="text-[8px] sm:text-[9px] text-white/50 uppercase tracking-widest mb-1">Costo prov.</p>
            <p class="text-lg sm:text-xl md:text-2xl font-black text-red-400">${{ number_format($costoProveedor, 0) }}</p>
        </div>
        <span class="text-xl sm:text-2xl font-black text-white/20">−</span>
        <div class="min-w-[80px]">
            <p class="text-[8px] sm:text-[9px] text-white/50 uppercase tracking-widest mb-1">Gastos</p>
            <p class="text-lg sm:text-xl md:text-2xl font-black text-orange-400">${{ number_format($totalGastos, 0) }}</p>
        </div>
        <span class="text-xl sm:text-2xl font-black text-white/20 w-full md:w-auto">=</span>
        <div class="min-w-[100px] bg-white/5 p-3 rounded-2xl shadow-inner">
            <p class="text-[8px] sm:text-[9px] text-white/50 uppercase tracking-widest mb-1">Utilidad neta</p>
            <p class="text-xl sm:text-2xl md:text-3xl font-black {{ $utilidadNeta >= 0 ? 'text-green-400' : 'text-red-400' }}">${{ number_format($utilidadNeta, 0) }}</p>
        </div>
    </div>

</main>

@include('partials._sidebar')
</body>
</html>