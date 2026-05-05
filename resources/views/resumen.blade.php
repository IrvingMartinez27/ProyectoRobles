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

<main class="pt-24 pb-20 md:pb-12 px-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#0035c5] mb-2 block">Resumen</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Resumen diario</h1>
        </div>
        <a href="{{ route('dashboard') }}">
            <button class="bg-[#1a1c1c] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                VOLVER AL INICIO
            </button>
        </a>
    </header>

    <!-- FILTRO POR FECHA -->
    <section class="bg-white p-6 border border-[#c4c5da]/20 mb-8">
        <form method="GET" action="{{ route('resumen') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Fecha</label>
                <input type="date"
                       name="fecha"
                       value="{{ request('fecha', now()->toDateString()) }}"
                       class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#0035c5] transition-colors">
            </div>
            <button type="submit"
                    class="bg-[#0035c5] text-white px-6 py-2 text-xs font-black tracking-widest uppercase hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">filter_list</span>
                FILTRAR
            </button>
            <a href="{{ route('resumen') }}">
                <button type="button"
                        class="border border-[#c4c5da]/40 px-6 py-2 text-xs font-black tracking-widest uppercase hover:bg-[#f3f3f4] transition-all">
                    HOY
                </button>
            </a>
        </form>
    </section>

    <!-- MÉTRICAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

        <!-- Total ventas -->
        <div class="bg-[#0035c5] text-white p-8">
            <p class="text-[10px] font-black tracking-widest uppercase opacity-70 mb-2">Total del día</p>
            <h2 class="text-4xl font-black tracking-tight">${{ $totalDia ?? '0.00' }}</h2>
            <p class="text-[10px] tracking-widest uppercase opacity-60 mt-3">
                {{ request('fecha', now()->toDateString()) }}
            </p>
        </div>

        <!-- Número de ventas -->
        <div class="bg-[#f3f3f4] p-8 border border-[#c4c5da]/20">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#747688] mb-2">Número de ventas</p>
            <h2 class="text-4xl font-black tracking-tight text-[#1a1c1c]">{{ $numVentas ?? '0' }}</h2>
            <p class="text-[10px] tracking-widest uppercase text-[#747688] mt-3">Transacciones</p>
        </div>

        <!-- Ticket promedio -->
        <div class="bg-[#f3f3f4] p-8 border border-[#c4c5da]/20">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#747688] mb-2">Ticket promedio</p>
            <h2 class="text-4xl font-black tracking-tight text-[#1a1c1c]">${{ $ticketPromedio ?? '0.00' }}</h2>
            <p class="text-[10px] tracking-widest uppercase text-[#747688] mt-3">Por transacción</p>
        </div>

    </div>

    <!-- PRODUCTOS MÁS VENDIDOS DEL DÍA -->
    <section class="bg-white p-8 border border-[#c4c5da]/20">
        <h2 class="text-xl font-bold tracking-tight mb-8">Productos vendidos hoy</h2>

        <div class="space-y-6">
            @forelse($productosDelDia ?? [] as $producto)
            <div class="flex items-center justify-between border-b border-[#c4c5da]/20 pb-4">
                <div>
                    <h3 class="text-sm font-bold uppercase">{{ $producto['nombre'] }}</h3>
                    <span class="text-[10px] text-[#747688] font-semibold tracking-widest uppercase">{{ $producto['cantidad'] }} unidades</span>
                </div>
                <span class="text-sm font-black text-[#0035c5]">${{ $producto['subtotal'] }}</span>
            </div>
            @empty
            <p class="text-[#747688] text-sm">Sin ventas para esta fecha</p>
            @endforelse
        </div>
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
    <a href="#" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span>
    </a>
</nav>

    <!-- SIDEBAR OVERLAY -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300"
         onclick="cerrarSidebar()"></div>

    <!-- SIDEBAR -->
    <div id="sidebar" class="fixed left-[-300px] top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 transition-all duration-300 overflow-y-auto">

        <!-- LOGO BOX -->
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

        <!-- NAVEGACIÓN -->
        <div class="flex-1">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-5 pt-4 pb-2">Navegación</p>

            <!-- INICIO -->
            <button onclick="toggleMenu('inicio')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Inicio
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-inicio">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-inicio">
                <a href="{{ route('dashboard') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver rendimiento diario</a>
                <a href="{{ route('resumen') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Resumen diario</a>
                <a href="{{ route('reporte') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Reporte completo</a>
            </div>

            <!-- VENTAS -->
            <button onclick="toggleMenu('ventas')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Ventas
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-ventas">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-ventas">
                <a href="{{ route('sales') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Abrir registro</a>
                <a href="{{ route('sales') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver ventas del día</a>
            </div>

            <!-- CATÁLOGO -->
            <button onclick="toggleMenu('catalogo')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                    Catálogo
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-catalogo">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-catalogo">
                <a href="{{ route('catalog') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver productos</a>
                <a href="{{ route('catalog') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Nuevo producto</a>
            </div>

            <!-- INVENTARIO -->
            <button onclick="toggleMenu('inventario')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    Inventario
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-inventario">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-inventario">
                <a href="{{ route('inventario') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver inventario</a>
                <a href="{{ route('inventario') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver stock bajo</a>
            </div>

            <!-- CLIENTES -->
            <button onclick="toggleMenu('clientes')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    Clientes
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-clientes">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-clientes">
                <a href="#" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver clientes</a>
                <a href="#" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Agregar cliente</a>
            </div>
        </div>

        <!-- FOOTER CON USUARIO -->
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
    function toggleMenu(id) {
        const menu = document.getElementById('menu-' + id);
        const chevron = document.getElementById('chevron-' + id);
        const isOpen = menu.style.maxHeight && menu.style.maxHeight !== '0px';
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.maxHeight = '0px');
        document.querySelectorAll('[id^="chevron-"]').forEach(c => c.style.transform = 'rotate(0deg)');
        if (!isOpen) {
            menu.style.maxHeight = menu.scrollHeight + 'px';
            chevron.style.transform = 'rotate(180deg)';
        }
    }
    </script>

</body>
</html>