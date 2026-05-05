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
    tr:hover td { background-color: #f3f3f4; }
    #sidebar { transition: left 0.3s ease; }
    #sidebar-overlay { transition: all 0.3s; }
</style>
</head>

<body class="bg-[#f9f9f9]">

<!-- NAV -->
<nav class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <button onclick="abrirSidebar()" class="hover:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-[#0035c5]">menu</span>
        </button>
        <span class="font-black tracking-tighter text-xl text-[#1a1c1c]">Robles Sport</span>
    </div>
    <div class="relative group">
        <button class="flex items-center gap-2 hover:opacity-80 transition-all">
            <span class="material-symbols-outlined text-[#0035c5]">account_circle</span>
        </button>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-[#c4c5da]/20 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Sesión activa</p>
                <p class="text-sm font-bold text-[#1a1c1c] mt-1">{{ Auth::user()->name ?? 'Usuario' }}</p>
            </div>
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] text-[#747688] font-semibold">{{ Auth::user()->email ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- BREADCRUMB -->
    <div class="pt-24 px-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#0035c5] transition-colors">
                <span class="material-symbols-outlined text-[14px]">grid_view</span>
                Inicio
            </a>
            <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
            <span class="text-[#1a1c1c]">Reporte completo</span>
        </div>
    </div>
<main class="pb-20 md:pb-12 px-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#0035c5] mb-2 block">Reportes</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Reporte completo</h1>
        </div>
        <a href="{{ route('dashboard') }}">
            <button class="bg-[#1a1c1c] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                VOLVER AL INICIO
            </button>
        </a>
    </header>

    <!-- FILTROS -->
    <section class="bg-white p-6 border border-[#c4c5da]/20 mb-8">
        <form method="GET" action="{{ route('reporte') }}" class="flex flex-col md:flex-row gap-4 items-end flex-wrap">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}"
                       class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#0035c5] transition-colors">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}"
                       class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#0035c5] transition-colors">
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-[#0035c5] text-white px-6 py-2 text-xs font-black tracking-widest uppercase hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    FILTRAR
                </button>
                <a href="{{ route('reporte') }}">
                    <button type="button"
                            class="border border-[#c4c5da]/40 px-6 py-2 text-xs font-black tracking-widest uppercase hover:bg-[#f3f3f4] transition-all">
                        LIMPIAR
                    </button>
                </a>
            </div>
        </form>
    </section>

    <!-- RESUMEN RÁPIDO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-[#0035c5] text-white p-6 flex justify-between items-center">
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

    <!-- TABLA DE VENTAS -->
    <section class="bg-white border border-[#c4c5da]/20 overflow-hidden">
        <div class="p-6 border-b border-[#c4c5da]/20">
            <h2 class="text-xl font-bold tracking-tight">Todas las ventas</h2>
        </div>
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
                        <td class="px-6 py-4">
                            <p class="font-bold text-sm">{{ $venta['cliente'] }}</p>
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider">{{ $venta['email'] ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold bg-[#f3f3f4] px-2 py-1 uppercase tracking-wider">
                                {{ $venta['num_productos'] }} artículo(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#747688]">{{ $venta['fecha'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black tracking-widest uppercase px-2 py-1
                                {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ strtoupper($venta['estado'] ?? 'completada') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-[#0035c5]">${{ $venta['total'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-[#747688] text-sm">
                            Sin ventas para el período seleccionado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($ventas) && method_exists($ventas, 'links'))
        <div class="p-6 border-t border-[#c4c5da]/20">
            {{ $ventas->links() }}
        </div>
        @endif
    </section>

</main>

<!-- BOTTOM NAV (móvil) -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
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
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span>
    </a>
    <a href="/clientes" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span>
    </a>
</nav>

    <!-- SIDEBAR OVERLAY -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300"
         onclick="cerrarSidebar()"></div>

    <!-- SIDEBAR -->
    <div id="sidebar" class="fixed top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 overflow-y-auto" style="left: -300px;">
        <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
            <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 px-4 py-3 flex items-center gap-3 flex-1">
                <div class="w-8 h-8 bg-[#0035c5] flex items-center justify-center shrink-0">
                    <span class="text-white font-black text-sm">R</span>
                </div>
                <div>
                    <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Robles Sport</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5">Panel de administración</p>
                </div>
            </div>
            <button onclick="cerrarSidebar()" class="p-2 hover:bg-[#f3f3f4] transition-colors ml-3">
                <span class="material-symbols-outlined text-[#747688] text-lg">close</span>
            </button>
        </div>

        <div class="flex-1 p-4 space-y-1">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-2 pt-2 pb-3">Acciones rápidas</p>
            <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">grid_view</span>Ir al inicio
            </a>
            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Inicio</p>
            <a href="{{ route('sales') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">point_of_sale</span>Abrir registro de ventas
            </a>
            <a href="{{ route('resumen') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">summarize</span>Ver resumen diario
            </a>
            <a href="{{ route('reporte') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">bar_chart</span>Ver reporte completo
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Ventas</p>
            <a href="{{ route('sales') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">add_circle</span>Nueva venta
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Catálogo</p>
            <a href="{{ route('catalog') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">shopping_bag</span>Ir al catálogo
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Inventario</p>
            <a href="{{ route('inventario') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-50 transition-all">
                <span class="material-symbols-outlined text-[16px]">warning</span>Ver stock bajo
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Clientes</p>
            <a href="{{ route('clientes') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">person_add</span>Nuevo cliente
            </a>
        </div>

        <div class="border-t border-[#c4c5da]/15 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-[#0035c5] rounded-full flex items-center justify-center shrink-0">
                    <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-[#1a1c1c]">{{ Auth::user()->name ?? 'Usuario' }}</p>
                    <p class="text-[10px] text-[#747688]">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

<script>
function abrirSidebar() {
    document.getElementById('sidebar').style.left = '0';
    document.getElementById('sidebar-overlay').classList.remove('opacity-0', 'invisible');
    document.getElementById('sidebar-overlay').classList.add('opacity-100', 'visible');
    document.body.style.overflow = 'hidden';
}
function cerrarSidebar() {
    document.getElementById('sidebar').style.left = '-300px';
    document.getElementById('sidebar-overlay').classList.add('opacity-0', 'invisible');
    document.getElementById('sidebar-overlay').classList.remove('opacity-100', 'visible');
    document.body.style.overflow = '';
}
</script>

</body>
</html>