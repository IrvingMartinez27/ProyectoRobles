<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Robles Sport - Catálogo</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #sidebar { transition: left 0.3s ease; }
    #sidebar-overlay { transition: all 0.3s; }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }
    .producto-card { transition: all 0.2s; }
    .producto-card:hover .producto-imagen { transform: scale(1.05); }
    .producto-imagen { transition: transform 0.7s ease; }
    .btn-agregar { transition: all 0.2s; }
    .btn-agregar:hover { transform: scale(1.05); }
</style>
</head>

<body class="bg-white text-[#1a1c1c]">

<!-- NAV -->
<nav class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <button onclick="abrirSidebar()" class="hover:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-[#0035c5]">menu</span>
        </button>
        <a href="{{ route('dashboard') }}" class="font-black tracking-tighter text-xl text-[#1a1c1c] hover:opacity-70 transition-opacity">Robles Sport</a>
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
<div class="px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#0035c5] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>
            Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Catálogo</span>
    </div>
</div>

@if(session('success'))
<div class="mx-6 mb-4 bg-green-100 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>
    {{ session('success') }}
</div>
@endif

<main class="max-w-[1600px] mx-auto px-6 pb-24">

    <!-- LAYOUT: productos izquierda + carrito derecha -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- PRODUCTOS -->
        <section class="lg:col-span-8">

            <!-- HEADER -->
            <div class="mb-8 pt-6">
                <p class="text-[#0035c5] font-bold tracking-widest text-[10px] uppercase mb-2">Gestión de inventario</p>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tighter text-[#1a1c1c]">Catálogo de productos</h1>
            </div>

            <!-- FILTROS -->
            <div class="flex gap-4 mb-8 overflow-x-auto pb-2">
                <button class="filtro-btn activo px-6 py-2 bg-[#1a1c1c] text-white text-xs font-bold uppercase tracking-widest"
                        onclick="filtrar('todos', this)">Todos</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                        onclick="filtrar('ropa', this)">Ropa</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                        onclick="filtrar('calzado', this)">Calzado</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                        onclick="filtrar('accesorios', this)">Accesorios</button>
            </div>

            <!-- GRID DE PRODUCTOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-6" id="grid-productos">

                @forelse($productos ?? [] as $producto)
                <div class="producto-card group flex flex-col space-y-4" data-categoria="{{ $producto['categoria'] }}">

                    <!-- imagen -->
                    <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative">
                        @if(isset($producto['imagen']) && $producto['imagen'])
                            <img class="producto-imagen w-full h-full object-cover"
                                 src="{{ $producto['imagen'] }}"
                                 alt="{{ $producto['nombre'] }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span>
                            </div>
                        @endif

                        <!-- badge estado -->
                        @if(isset($producto['stock']))
                            @if($producto['stock'] === 'disponible')
                                <div class="absolute top-4 left-4 bg-[#0035c5] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">En stock</div>
                            @elseif($producto['stock'] === 'poco')
                                <div class="absolute top-4 left-4 bg-[#1a1c1c] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Poco stock</div>
                            @elseif($producto['stock'] === 'agotado')
                                <div class="absolute top-4 left-4 bg-[#8d1c00] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Sin stock</div>
                            @endif
                        @endif

                        <!-- boton agregar al carrito sobre la imagen -->
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="agregarAlCarrito({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio']]) }})"
                                class="btn-agregar absolute bottom-4 right-4 bg-[#0035c5] text-white w-10 h-10 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all">
                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                        </button>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-1">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold tracking-tight">{{ $producto['nombre'] }}</h3>
                            <span class="text-[#0035c5] font-bold text-sm">${{ number_format($producto['precio'], 2) }}</span>
                        </div>
                        <p class="text-[#5e5e5e]/60 font-mono text-[10px] tracking-widest uppercase">ID: {{ $producto['id'] }}</p>

                        <!-- tallas -->
                        <div class="pt-3 border-t border-[#c4c5da]/20">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#1a1c1c]/40 mb-2">Inventario</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                <span class="bg-[#eeeeee] px-2 py-1 text-[10px] font-bold">
                                    {{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#0035c5]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span>
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- boton agregar visible siempre -->
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="agregarAlCarrito({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio']]) }})"
                                class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#1a1c1c] text-[10px] font-black uppercase tracking-widest hover:bg-[#0035c5] hover:text-white transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                            Agregar al carrito
                        </button>
                        @else
                        <div class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#c4c5da] text-[10px] font-black uppercase tracking-widest text-center">
                            Sin stock
                        </div>
                        @endif
                    </div>
                </div>

                @empty
                @for($i = 0; $i < 6; $i++)
                <div class="producto-card group flex flex-col space-y-4">
                    <div class="aspect-[4/5] bg-[#f3f3f4] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <div class="h-4 bg-[#f3f3f4] w-32 rounded"></div>
                        <div class="h-4 bg-[#f3f3f4] w-16 rounded"></div>
                    </div>
                    <div class="h-3 bg-[#f3f3f4] w-24 rounded"></div>
                    <div class="w-full h-9 bg-[#f3f3f4] rounded mt-2"></div>
                </div>
                @endfor
                @endforelse

            </div>
        </section>

        <!-- CARRITO -->
        <aside class="lg:col-span-4 lg:sticky lg:top-20 h-fit pt-6">
            <div class="bg-[#f3f3f4] border border-[#c4c5da]/20">

                <!-- cabecera carrito -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-white">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#0035c5]">shopping_cart</span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Carrito de venta</p>
                            <p class="text-sm font-black text-[#1a1c1c]" id="carrito-count">0 productos</p>
                        </div>
                    </div>
                    <button onclick="limpiarCarrito()"
                            class="text-[10px] font-black uppercase tracking-widest text-[#747688] hover:text-red-600 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete_sweep</span>
                        Limpiar
                    </button>
                </div>

                <!-- lista de productos en carrito -->
                <div id="carrito-items" class="max-h-64 overflow-y-auto">
                    <div id="carrito-vacio" class="px-6 py-12 text-center text-[#747688] text-sm">
                        <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>
                        Agrega productos del catálogo
                    </div>
                </div>

                <!-- total -->
                <div class="px-6 py-4 bg-[#0035c5] flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                    <span class="text-2xl font-black text-white" id="carrito-total">$0.00</span>
                </div>

                <!-- formulario registro venta -->
                <form method="POST" action="{{ route('catalog.store') }}" id="form-carrito" class="px-6 py-5 space-y-4 bg-white border-t border-[#c4c5da]/20">
                    @csrf
                    <input type="hidden" name="productos_carrito" id="productos-carrito-input"/>
                    <input type="hidden" name="total_carrito" id="total-carrito-input"/>

                    <!-- cliente: si existe lo sugiere, si no existe lo crea nuevo al registrar la venta -->
                    <div class="flex flex-col gap-1 relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                        <input type="text"
                               id="cliente-carrito-search"
                               name="cliente_nombre"
                               placeholder="Nombre del cliente..."
                               autocomplete="off"
                               oninput="buscarClienteCarrito(this.value)"
                               class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9]"/>
                        <input type="hidden" name="cliente_id" id="cliente-carrito-id"/>
                        <p class="text-[9px] text-[#747688] font-semibold mt-1">
                            Si el cliente no existe se registrará automáticamente
                        </p>
                        <div id="carrito-sugerencias"
                             class="absolute top-[68px] left-0 right-0 bg-white border border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-40 overflow-y-auto">
                        </div>
                        <div id="clientes-data-carrito" class="hidden">
                            @foreach($clientes ?? [] as $cliente)
                            <span data-id="{{ $cliente->id ?? $cliente['id'] }}"
                                  data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"></span>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" onclick="prepararFormCarrito(event)"
                            class="w-full py-4 bg-[#1a1c1c] text-white text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">point_of_sale</span>
                        Registrar venta
                    </button>
                </form>

            </div>
        </aside>

    </div>
</main>

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

            <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">grid_view</span>
                Ir al inicio
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Ventas</p>
            <a href="{{ route('sales') }}" onclick="cerrarSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">add_circle</span>
                Nueva venta
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Catálogo</p>
            <a href="{{ route('catalog') }}" onclick="cerrarSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                Ir al catálogo
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Inventario</p>
            <a href="{{ route('inventario') }}" onclick="cerrarSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                Ir al inventario
            </a>

            <div class="border-t border-[#c4c5da]/20 my-2"></div>

            <p class="text-[9px] font-black uppercase tracking-widest text-[#0035c5] px-2 pt-2 pb-1">Clientes</p>
            <a href="{{ route('clientes') }}" onclick="cerrarSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#0035c5] transition-all">
                <span class="material-symbols-outlined text-[16px]">group</span>
                Ir a clientes
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

<script>
let carrito = [];

function agregarAlCarrito(producto) {
    const existente = carrito.find(p => p.id === producto.id);
    if (existente) {
        existente.cantidad += 1;
    } else {
        carrito.push({ ...producto, cantidad: 1 });
    }
    actualizarCarrito();
}

function actualizarCarrito() {
    const itemsEl = document.getElementById('carrito-items');
    const countEl = document.getElementById('carrito-count');
    const totalEl = document.getElementById('carrito-total');

    if (carrito.length === 0) {
        itemsEl.innerHTML = '<div id="carrito-vacio" class="px-6 py-12 text-center text-[#747688] text-sm"><span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>Agrega productos del catálogo</div>';
        countEl.textContent = '0 productos';
        totalEl.textContent = '$0.00';
        return;
    }

    let total = 0;
    itemsEl.innerHTML = carrito.map((p, i) => {
        const subtotal = p.precio * p.cantidad;
        total += subtotal;
        return `
        <div class="flex items-center gap-3 px-4 py-3 border-b border-[#c4c5da]/10 bg-white">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-xs uppercase truncate">${p.nombre}</p>
                <p class="text-[10px] text-[#747688]">$${parseFloat(p.precio).toFixed(2)} c/u</p>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="cambiarCantidad(${i}, -1)" class="w-6 h-6 bg-[#f3f3f4] hover:bg-[#e8e8e8] flex items-center justify-center text-xs font-black transition-colors">−</button>
                <span class="w-8 text-center text-sm font-black">${p.cantidad}</span>
                <button onclick="cambiarCantidad(${i}, 1)" class="w-6 h-6 bg-[#f3f3f4] hover:bg-[#e8e8e8] flex items-center justify-center text-xs font-black transition-colors">+</button>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-[#0035c5]">$${subtotal.toFixed(2)}</p>
            </div>
            <button onclick="quitarDelCarrito(${i})" class="text-[#747688] hover:text-red-600 transition-colors ml-1">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>`;
    }).join('');

    countEl.textContent = carrito.length + (carrito.length === 1 ? ' producto' : ' productos');
    totalEl.textContent = '$' + total.toFixed(2);
}

function cambiarCantidad(index, delta) {
    carrito[index].cantidad += delta;
    if (carrito[index].cantidad <= 0) carrito.splice(index, 1);
    actualizarCarrito();
}

function quitarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

function limpiarCarrito() {
    carrito = [];
    actualizarCarrito();
}

function prepararFormCarrito(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        alert('Agrega al menos un producto al carrito');
        return;
    }
    const cliente = document.getElementById('cliente-carrito-search').value.trim();
    if (!cliente) {
        e.preventDefault();
        alert('Escribe el nombre del cliente');
        return;
    }
    const total = carrito.reduce((sum, p) => sum + p.precio * p.cantidad, 0);
    document.getElementById('productos-carrito-input').value = JSON.stringify(carrito);
    document.getElementById('total-carrito-input').value = total.toFixed(2);
}

function buscarClienteCarrito(query) {
    const sugerencias = document.getElementById('carrito-sugerencias');
    const clientes = document.querySelectorAll('#clientes-data-carrito span');
    const q = query.toLowerCase().trim();
    if (q.length < 1) { sugerencias.classList.add('hidden'); return; }
    const coincidencias = Array.from(clientes).filter(c => c.dataset.nombre.toLowerCase().includes(q));
    if (coincidencias.length === 0) {
        sugerencias.innerHTML = '<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias</div>';
    } else {
        sugerencias.innerHTML = coincidencias.map(c => `
            <div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] transition-colors uppercase tracking-wide"
                 onclick="seleccionarClienteCarrito('${c.dataset.id}', '${c.dataset.nombre}')">
                ${c.dataset.nombre}
            </div>
        `).join('');
    }
    sugerencias.classList.remove('hidden');
}

function seleccionarClienteCarrito(id, nombre) {
    document.getElementById('cliente-carrito-search').value = nombre;
    document.getElementById('cliente-carrito-id').value = id;
    document.getElementById('carrito-sugerencias').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('#cliente-carrito-search') && !e.target.closest('#carrito-sugerencias')) {
        const s = document.getElementById('carrito-sugerencias');
        if (s) s.classList.add('hidden');
    }
});

function filtrar(categoria, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    document.querySelectorAll('.producto-card').forEach(card => {
        card.style.display = (categoria === 'todos' || card.dataset.categoria === categoria) ? '' : 'none';
    });
}

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