<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .bar-hover:hover { background-color: rgba(0, 53, 197, 0.2); }
    .btn-report:hover { background-color: #1a1c1c; color: #ffffff; }
</style>
</head>

<body class="bg-[#f9f9f9]">

<!-- NAV -->
<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <span class="material-symbols-outlined text-[#0035c5]">menu</span>
        <span class="font-black tracking-tighter text-xl text-[#1a1c1c]">Robles Sport</span>
    </div>

    <div class="hidden md:flex items-center gap-8">
        <a href="/dashboard" class="text-[#0035c5] font-bold transition-all duration-300">Inicio</a>
        <a href="/sales"     class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Ventas</a>
        <a href="/catalog"   class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Catálogo</a>
        <a href="#"          class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Inventario</a>
        <a href="#"          class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Clientes</a>
    </div>

    <span class="material-symbols-outlined text-[#0035c5]">account_circle</span>
</nav>

<main class="pt-24 pb-20 md:pb-12 px-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#0035c5] mb-2 block">Descripción general</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Rendimiento diario</h1>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('sales') }}">
                <button class="bg-[#1a1c1c] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>
                    ABRIR REGISTRO
                </button>
            </a>

            <form method="POST" action="{{ route('resumen') }}">
                @csrf
                <button class="bg-[#0035c5] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">summarize</span>
                    RESUMEN DIARIO
                </button>
            </form>
        </div>
    </header>

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        <!-- GRÁFICA -->
        <section class="md:col-span-8 bg-[#f3f3f4] p-8 relative overflow-hidden">
            <div class="flex justify-between items-start mb-12">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight mb-1">Analítica de ventas</h2>
                    <p class="text-sm text-[#747688] font-medium tracking-wide">VENTAS NETAS DE TODOS LOS CANALES</p>
                </div>
                <div class="flex bg-white p-1">
                    <a href="{{ route('dashboard', ['periodo' => 'dia']) }}">
                        <button class="px-4 py-1 text-xs font-bold {{ request('periodo', 'dia') == 'dia' ? 'bg-[#1a1c1c] text-white' : 'text-[#1a1c1c]/50 hover:text-[#1a1c1c] transition-colors' }}">DÍA</button>
                    </a>
                    <a href="{{ route('dashboard', ['periodo' => 'semana']) }}">
                        <button class="px-4 py-1 text-xs font-bold {{ request('periodo') == 'semana' ? 'bg-[#1a1c1c] text-white' : 'text-[#1a1c1c]/50 hover:text-[#1a1c1c] transition-colors' }}">SEMANA</button>
                    </a>
                    <a href="{{ route('dashboard', ['periodo' => 'mes']) }}">
                        <button class="px-4 py-1 text-xs font-bold {{ request('periodo') == 'mes' ? 'bg-[#1a1c1c] text-white' : 'text-[#1a1c1c]/50 hover:text-[#1a1c1c] transition-colors' }}">MES</button>
                    </a>
                </div>
            </div>

            <!-- BARRAS -->
            <div class="h-[300px] flex items-end justify-between gap-2">
                @foreach($ventas as $venta)
                <div class="w-full bg-[#1a1c1c]/5 relative group/bar transition-all duration-500 hover:bg-[#0035c5]/20"
                     style="height: {{ $venta['altura'] }}px">
                    <div class="w-full h-full bg-[#0035c5]"></div>
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover/bar:opacity-100 text-[10px] font-bold bg-[#0035c5] text-white px-2 py-1 transition-opacity whitespace-nowrap">
                        ${{ $venta['valor'] }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between mt-6 text-[10px] font-bold tracking-widest text-[#747688] uppercase">
                @foreach($labels as $label)
                <span>{{ $label }}</span>
                @endforeach
            </div>
        </section>

        <!-- LO MÁS VENDIDO -->
        <section class="md:col-span-4 bg-white p-8 border-l border-[#c4c5da]/20">
            <h2 class="text-xl font-bold tracking-tight mb-8">Lo más vendido</h2>

            <div class="space-y-8">
                @forelse($topProductos as $producto)
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <h3 class="text-sm font-bold leading-tight uppercase">{{ $producto['nombre'] }}</h3>
                        <span class="text-[10px] text-[#747688] font-semibold tracking-widest uppercase">{{ $producto['ventas'] }} ventas</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-[#0035c5]">{{ $producto['porcentaje'] }}%</span>
                    </div>
                </div>
                @empty
                <p class="text-[#747688] text-sm">Sin datos</p>
                @endforelse
            </div>

            <a href="{{ route('reporte') }}">
                <button class="btn-report w-full mt-12 py-3 border-b-2 border-[#1a1c1c] text-xs font-black tracking-widest uppercase transition-all">
                    VER REPORTE COMPLETO
                </button>
            </a>
        </section>

        <!-- RESTOCK -->
        <section class="md:col-span-12">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Restock requerido</h2>
                    <p class="text-sm text-[#747688] font-medium">PRODUCTOS POR DEBAJO DEL MÍNIMO</p>
                </div>
                <div class="flex gap-2">
                    <button class="p-2 border border-[#c4c5da]/30 hover:bg-[#1a1c1c] hover:text-white transition-all">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </button>
                    <button class="p-2 border border-[#c4c5da]/30 hover:bg-[#1a1c1c] hover:text-white transition-all">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </button>
                </div>
            </div>

            <div class="flex gap-6 overflow-x-auto no-scrollbar pb-4">
                @forelse($lowStock as $item)
                <div class="min-w-[280px] bg-white p-6 shadow-sm flex flex-col group border border-[#c4c5da]/10">

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-sm uppercase">{{ $item['nombre'] }}</h3>
                            <p class="text-[10px] text-[#747688] font-bold tracking-widest uppercase">{{ $item['stock'] }} piezas</p>
                        </div>
                        <div class="bg-red-100 text-red-700 px-2 py-1 text-[10px] font-black tracking-widest uppercase">
                            {{ $item['stock'] }} LEFT
                        </div>
                    </div>

                    <form method="POST" action="{{ route('reponer') }}">
                        @csrf
                        <input type="hidden" name="producto" value="{{ $item['id'] }}">
                        <button class="w-full py-3 bg-[#0035c5] text-white text-[10px] font-black tracking-[0.2em] uppercase hover:opacity-90 transition-all">
                            REPONER STOCK
                        </button>
                    </form>

                </div>
                @empty
                <p class="text-[#747688] text-sm">Sin productos con bajo stock</p>
                @endforelse
            </div>
        </section>

    </div>
</main>

<!-- BOTTOM NAV (móvil) -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#0035c5] border-t-2 border-[#0035c5] pt-2">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span>
    </a>
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">receipt_long</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span>
    </a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span>
    </a>
    <a href="#" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span>
    </a>
    <a href="#" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span>
    </a>
</nav>

</body>
</html>