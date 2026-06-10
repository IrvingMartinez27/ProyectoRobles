<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Gastos</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; min-height: 100dvh; overflow-x: hidden; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    /* Animación fluida y 3D para el modal */
    #modal-gasto { 
        opacity: 0; 
        visibility: hidden; 
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
        display: flex; 
    }
    #modal-gasto.activo { 
        opacity: 1; 
        visibility: visible; 
    }
    #modal-gasto > div { 
        transform: scale(0.9) translateY(20px); 
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
    }
    #modal-gasto.activo > div { 
        transform: scale(1) translateY(0); 
    }
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
        <a href="{{ route('rentabilidad.index') }}" class="hover:text-[#1737c8] dark:hover:text-blue-400 transition-colors duration-300 whitespace-nowrap">Rentabilidad</a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da] dark:text-gray-600 shrink-0">chevron_right</span>
        <span class="text-[#1a1c1c] dark:text-gray-100 whitespace-nowrap">Gastos</span>
    </div>
</div>

@if(session('success'))
<div class="mx-4 md:mx-6 mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-400 px-4 py-3 text-sm font-bold flex items-center gap-2 rounded-2xl shadow-sm border border-green-200 dark:border-green-800 transition-all break-words">
    <span class="material-symbols-outlined text-sm shrink-0">check_circle</span>
    <span class="min-w-0">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="mx-4 md:mx-6 mb-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-400 px-4 py-3 text-sm font-bold flex items-center gap-2 rounded-2xl shadow-sm border border-red-200 dark:border-red-800 transition-all break-words">
    <span class="material-symbols-outlined text-sm shrink-0">error</span>
    <span class="min-w-0">{{ session('error') }}</span>
</div>
@endif

<main class="pb-24 px-4 md:px-6 max-w-7xl mx-auto">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-8">
        <div class="min-w-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] dark:text-blue-400 mb-1">Business</p>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight dark:text-white truncate">Gastos operativos</h1>
            <p class="text-xs sm:text-sm text-[#747688] dark:text-gray-400 mt-1">Registra renta, envíos, publicidad y más</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <form method="GET" action="{{ route('gastos.index') }}" class="flex w-full sm:w-auto gap-2">
                <input type="month" name="mes" value="{{ $mes }}"
                       class="w-full sm:w-auto flex-1 border border-gray-200 dark:border-gray-700 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-white dark:bg-gray-800 dark:text-white rounded-xl shadow-inner transition-all duration-300"/>
                <button type="submit" class="shrink-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-[#1a1c1c] dark:text-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] dark:hover:bg-blue-600 hover:border-[#1737c8] dark:hover:border-blue-600 hover:text-white rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    Filtrar
                </button>
            </form>
            <button onclick="abrirModalGasto()" class="w-full sm:w-auto justify-center bg-[#1737c8] dark:bg-blue-600 text-white px-5 py-2.5 sm:py-2 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span>Nuevo gasto
            </button>
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-1">Total gastos</p>
            <p class="text-xl sm:text-2xl font-black text-red-500 dark:text-red-400 truncate">-${{ number_format($totalGastos, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-1">Fijos</p>
            <p class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400 truncate">${{ number_format($porCategoria['fijo'] ?? 0, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-1">Variables</p>
            <p class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 truncate">${{ number_format($porCategoria['variable'] ?? 0, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-out">
            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 mb-1">Hormiga</p>
            <p class="text-xl sm:text-2xl font-black text-red-400 dark:text-red-400 truncate">${{ number_format($porCategoria['hormiga'] ?? 0, 0) }}</p>
        </div>
    </div>

    {{-- LISTA DE GASTOS --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl sm:rounded-3xl shadow-lg overflow-hidden transition-all">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400">{{ count($gastos) }} gasto(s) registrados</p>
        </div>
        <div class="p-2 sm:p-3 w-full">
            @forelse($gastos as $gasto)
            <div class="flex items-center justify-between px-3 sm:px-4 py-3 sm:py-4 mb-1 rounded-xl sm:rounded-2xl hover:bg-[#f9f9f9] dark:hover:bg-gray-700 hover:shadow-sm hover:scale-[1.005] transition-all duration-300 group gap-2">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center shrink-0 rounded-xl shadow-inner
                        {{ $gasto->categoria === 'fijo' ? 'bg-blue-100 dark:bg-blue-900/50' : ($gasto->categoria === 'variable' ? 'bg-amber-100 dark:bg-amber-900/50' : 'bg-red-100 dark:bg-red-900/50') }}">
                        <span class="material-symbols-outlined text-sm
                            {{ $gasto->categoria === 'fijo' ? 'text-blue-600 dark:text-blue-400' : ($gasto->categoria === 'variable' ? 'text-amber-600 dark:text-amber-400' : 'text-red-500 dark:text-red-400') }}">
                            {{ $gasto->categoria === 'fijo' ? 'home' : ($gasto->categoria === 'variable' ? 'local_shipping' : 'coffee') }}
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <!-- EL truncate AQUÍ EVITA EL DESBORDAMIENTO -->
                        <p class="font-bold text-xs sm:text-sm uppercase dark:text-gray-100 group-hover:text-[#1737c8] dark:group-hover:text-blue-400 transition-colors truncate">{{ $gasto->concepto }}</p>
                        
                        <!-- FLEX-WRAP EN LAS ETIQUETAS -->
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-1">
                            <span class="text-[8px] sm:text-[9px] font-black px-2 py-0.5 uppercase rounded-md shadow-sm whitespace-nowrap
                                {{ $gasto->categoria === 'fijo' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : ($gasto->categoria === 'variable' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300') }}">
                                {{ $gasto->categoria }}
                            </span>
                            @if($gasto->almacen)
                            <span class="text-[9px] sm:text-[10px] font-medium text-[#747688] dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md whitespace-nowrap truncate max-w-[80px] sm:max-w-none">{{ $gasto->almacen->nombre }}</span>
                            @endif
                            <span class="text-[9px] sm:text-[10px] font-medium text-[#747688] dark:text-gray-500 whitespace-nowrap">{{ $gasto->fecha->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <p class="font-black text-xs sm:text-sm text-red-500 dark:text-red-400 whitespace-nowrap">-${{ number_format($gasto->monto, 2) }}</p>
                    <form method="POST" action="{{ route('gastos.destroy', $gasto->id) }}" onsubmit="return confirm('¿Eliminar este gasto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 sm:p-2 text-[#c4c5da] dark:text-gray-500 hover:text-white dark:hover:text-white hover:bg-red-500 dark:hover:bg-red-600 rounded-lg sm:rounded-xl transition-all duration-300 hover:shadow-md active:scale-95">
                            <span class="material-symbols-outlined text-[16px] sm:text-sm block">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-4 sm:px-6 py-16 sm:py-20 text-center">
                <span class="material-symbols-outlined text-4xl sm:text-5xl text-[#c4c5da] dark:text-gray-600 block mb-4 animate-bounce">receipt_long</span>
                <p class="text-sm font-bold text-[#747688] dark:text-gray-400">Sin gastos registrados este mes</p>
                <button onclick="abrirModalGasto()" class="mt-5 bg-[#1737c8] dark:bg-blue-600 text-white px-5 sm:px-6 py-3 text-[10px] sm:text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    Registrar primer gasto
                </button>
            </div>
            @endforelse
        </div>
    </div>
</main>

{{-- MODAL NUEVO GASTO --}}
<div id="modal-gasto" class="fixed inset-0 z-50 items-center justify-center bg-[#1a1c1c]/40 dark:bg-black/60 backdrop-blur-md px-3 sm:px-4 py-4" onclick="if(event.target===this) cerrarModalGasto()">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl sm:rounded-[2rem] shadow-2xl overflow-hidden border border-white/20 dark:border-gray-700 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-5 sm:px-7 py-4 sm:py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shrink-0">
            <div>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-[#1737c8] dark:text-blue-400 mb-0.5 sm:mb-1">Gastos operativos</p>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight dark:text-white">Nuevo gasto</h2>
            </div>
            <button onclick="cerrarModalGasto()" class="p-1.5 sm:p-2 text-[#747688] dark:text-gray-400 hover:text-[#1a1c1c] dark:hover:text-white bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <!-- overflow-y-auto en caso de que la pantalla horizontal de móvil sea muy baja -->
        <form method="POST" action="{{ route('gastos.store') }}" class="px-5 sm:px-7 py-5 sm:py-6 space-y-4 sm:space-y-5 overflow-y-auto">
            @csrf
            <div class="flex flex-col gap-1.5 sm:gap-2">
                <label class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 ml-1">Concepto</label>
                <input type="text" name="concepto" placeholder="Ej. Renta local, Guías DHL"
                       class="w-full border border-gray-200 dark:border-gray-600 px-3 sm:px-4 py-3 sm:py-3.5 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-[#f9f9f9] dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 rounded-xl sm:rounded-2xl shadow-inner transition-all duration-300"/>
            </div>
            <div class="flex flex-col gap-1.5 sm:gap-2">
                <label class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 ml-1">Categoría</label>
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <button type="button" onclick="seleccionarCategoria('fijo', this)"
                            class="cat-btn py-3 sm:py-4 border border-gray-200 dark:border-gray-600 bg-[#f9f9f9] dark:bg-gray-900 text-[8px] sm:text-[10px] font-black uppercase tracking-widest dark:text-gray-300 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 hover:border-blue-500 dark:hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 flex flex-col items-center gap-1 sm:gap-1.5 active:scale-95">
                        <span class="material-symbols-outlined text-sm sm:text-base">home</span>Fijo
                    </button>
                    <button type="button" onclick="seleccionarCategoria('variable', this)"
                            class="cat-btn py-3 sm:py-4 border border-gray-200 dark:border-gray-600 bg-[#f9f9f9] dark:bg-gray-900 text-[8px] sm:text-[10px] font-black uppercase tracking-widest dark:text-gray-300 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 hover:border-amber-500 dark:hover:border-amber-400 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-300 flex flex-col items-center gap-1 sm:gap-1.5 active:scale-95">
                        <span class="material-symbols-outlined text-sm sm:text-base">local_shipping</span>Variable
                    </button>
                    <button type="button" onclick="seleccionarCategoria('hormiga', this)"
                            class="cat-btn py-3 sm:py-4 border border-gray-200 dark:border-gray-600 bg-[#f9f9f9] dark:bg-gray-900 text-[8px] sm:text-[10px] font-black uppercase tracking-widest dark:text-gray-300 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 hover:border-red-500 dark:hover:border-red-400 hover:text-red-500 dark:hover:text-red-400 transition-all duration-300 flex flex-col items-center gap-1 sm:gap-1.5 active:scale-95">
                        <span class="material-symbols-outlined text-sm sm:text-base">coffee</span>Hormiga
                    </button>
                </div>
                <input type="hidden" name="categoria" id="categoria-hidden"/>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div class="flex flex-col gap-1.5 sm:gap-2">
                    <label class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 ml-1">Monto</label>
                    <input type="number" name="monto" placeholder="0.00" min="0" step="0.01"
                           class="w-full border border-gray-200 dark:border-gray-600 px-3 sm:px-4 py-3 sm:py-3.5 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-[#f9f9f9] dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 rounded-xl sm:rounded-2xl shadow-inner transition-all duration-300"/>
                </div>
                <div class="flex flex-col gap-1.5 sm:gap-2">
                    <label class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 ml-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ now()->format('Y-m-d') }}"
                           class="w-full border border-gray-200 dark:border-gray-600 px-3 sm:px-4 py-3 sm:py-3.5 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-[#f9f9f9] dark:bg-gray-900 dark:text-white rounded-xl sm:rounded-2xl shadow-inner transition-all duration-300 dark:[color-scheme:dark]"/>
                </div>
            </div>
            @if(count($almacenes) > 0)
            <div class="flex flex-col gap-1.5 sm:gap-2">
                <label class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-[#747688] dark:text-gray-400 ml-1">Almacén</label>
                <select name="almacen_id" class="w-full border border-gray-200 dark:border-gray-600 px-3 sm:px-4 py-3 sm:py-3.5 text-sm focus:outline-none focus:border-[#1737c8] dark:focus:border-blue-500 focus:ring-4 focus:ring-[#1737c8]/10 bg-[#f9f9f9] dark:bg-gray-900 dark:text-white rounded-xl sm:rounded-2xl shadow-inner transition-all duration-300 cursor-pointer">
                    <option value="">Global (todos)</option>
                    @foreach($almacenes as $a)
                    <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <!-- APILADO EN MÓVIL, EN FILA EN ESCRITORIO -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-3 sm:pt-4">
                <button type="submit" class="w-full flex-1 bg-[#1737c8] dark:bg-blue-600 text-white py-3.5 sm:py-4 text-xs font-black uppercase tracking-widest rounded-xl sm:rounded-2xl shadow-lg hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    Guardar gasto
                </button>
                <button type="button" onclick="cerrarModalGasto()" class="w-full sm:w-auto px-6 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-gray-200 py-3.5 sm:py-4 text-xs font-black uppercase tracking-widest rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials._sidebar')

<script>
function abrirModalGasto() { 
    document.getElementById('modal-gasto').classList.add('activo'); 
    document.body.style.overflow = 'hidden'; 
}
function cerrarModalGasto() { 
    document.getElementById('modal-gasto').classList.remove('activo'); 
    document.body.style.overflow = ''; 
}

function seleccionarCategoria(cat, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => {
        b.classList.remove('bg-blue-500', 'bg-amber-500', 'bg-red-500', 'text-white', 'border-blue-500', 'border-amber-500', 'border-red-500', 'dark:border-blue-400', 'dark:border-amber-400', 'dark:border-red-400', 'shadow-lg');
        b.classList.add('bg-[#f9f9f9]', 'dark:bg-gray-900', 'border-gray-200', 'dark:border-gray-600', 'dark:text-gray-300');
    });

    const colores = { fijo: 'bg-blue-500', variable: 'bg-amber-500', hormiga: 'bg-red-500' };
    const borders = { fijo: 'border-blue-500', variable: 'border-amber-500', hormiga: 'border-red-500' };
    const darkBorders = { fijo: 'dark:border-blue-500', variable: 'dark:border-amber-500', hormiga: 'dark:border-red-500' };
    
    btn.classList.remove('bg-[#f9f9f9]', 'dark:bg-gray-900', 'border-gray-200', 'dark:border-gray-600', 'dark:text-gray-300');
    btn.classList.add(colores[cat], borders[cat], darkBorders[cat], 'text-white', 'shadow-lg');
    document.getElementById('categoria-hidden').value = cat;
}
</script>
</body>
</html>