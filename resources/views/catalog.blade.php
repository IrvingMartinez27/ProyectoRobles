<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Catálogo</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }
    .producto-card { transition: all 0.2s; }
    .producto-card:hover .producto-imagen { transform: scale(1.05); }
    .producto-imagen { transition: transform 0.7s ease; }
    .btn-agregar { transition: all 0.2s; }
    .btn-agregar:hover { transform: scale(1.05); }
    #modal-tallas { display: none; }
    #modal-tallas.activo { display: flex; }
    #modal-efectivo-cat { display: none; }
    #modal-efectivo-cat.activo { display: flex; }
    .metodo-btn.activo { background: #1a1c1c; color: #ffffff; border-color: #1a1c1c; }
    .toggle-mayoreo { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-mayoreo input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #c4c5da; transition: .3s; border-radius: 24px; }
    .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
    input:checked + .toggle-slider { background-color: #f59e0b; }
    input:checked + .toggle-slider:before { transform: translateX(20px); }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

@include('partials._nav')

@php
 $esPro = ($plan ?? 'gratis') === 'pro' || ($plan ?? 'gratis') === 'business';
 $lealtadActivo = $lealtadActivo ?? false;
  @endphp

<div class="px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Catálogo</span>
    </div>
</div>

@if(session('success'))
<div class="mx-6 mb-2 bg-green-100 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif

{{-- RESUMEN ÚLTIMO PAGO + BOTÓN WHATSAPP --}}
@if(session('ticket_metodo'))
<div class="mx-6 mb-4 bg-blue-50 border border-blue-200 px-4 py-4 flex items-center justify-between gap-4 flex-wrap">
    <div class="flex gap-6 flex-wrap">
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-0.5">Tipo</p>
            <p class="font-black text-sm {{ session('ticket_tipo') === 'mayoreo' ? 'text-amber-600' : 'text-[#1a1c1c]' }}">
                {{ ucfirst(session('ticket_tipo') ?? 'menudeo') }}
            </p>
        </div>
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-0.5">Método</p>
            <p class="font-black text-sm">{{ ucfirst(session('ticket_metodo')) }}</p>
        </div>
        @if(session('ticket_metodo') === 'efectivo')
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-0.5">Recibido</p>
            <p class="font-black text-sm">${{ number_format(session('ticket_recibido'), 2) }}</p>
        </div>
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-0.5">Cambio</p>
            <p class="font-black text-sm text-green-600">${{ number_format(session('ticket_cambio'), 2) }}</p>
        </div>
        @endif
    </div>
    <button onclick="enviarWhatsAppUltimaVenta()" class="shrink-0 px-4 py-3 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:opacity-90 transition-all">
        <span class="material-symbols-outlined text-sm">chat</span>WhatsApp
    </button>
</div>
@endif

@if(session('error'))
<div class="mx-4 md:mx-6 mb-4 bg-red-100 text-red-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
</div>
@endif

<main class="max-w-[1600px] mx-auto px-6 pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- PRODUCTOS -->
        <section class="lg:col-span-8">
            <div class="mb-8 pt-6">
                <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-2">Gestión de inventario</p>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tighter text-[#1a1c1c]">Catálogo de productos</h1>
            </div>
            <div class="flex gap-4 mb-8 overflow-x-auto pb-2">
                <button class="filtro-btn activo px-6 py-2 bg-[#1a1c1c] text-white text-xs font-bold uppercase tracking-widest" onclick="filtrar('todos', this)">Todos</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('ropa', this)">Ropa</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('calzado', this)">Calzado</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('accesorios', this)">Accesorios</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-6" id="grid-productos">
                @forelse($productos ?? [] as $producto)
                <div class="producto-card group flex flex-col space-y-4" data-categoria="{{ $producto['categoria'] }}">
                    <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative">
                        @if(isset($producto['imagen']) && $producto['imagen'])
                            <img class="producto-imagen w-full h-full object-cover" src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                        @endif
                        @if(isset($producto['stock']))
                            @if($producto['stock'] === 'disponible')<div class="absolute top-4 left-4 bg-[#1737c8] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">En stock</div>
                            @elseif($producto['stock'] === 'poco')<div class="absolute top-4 left-4 bg-[#1a1c1c] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Poco stock</div>
                            @elseif($producto['stock'] === 'agotado')<div class="absolute top-4 left-4 bg-red-700 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Sin stock</div>
                            @endif
                        @endif
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="abrirModalTallas({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio'], 'tallas' => $producto['tallas']]) }})"
                                class="btn-agregar absolute bottom-4 right-4 bg-[#1737c8] text-white w-10 h-10 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all">
                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                        </button>
                        @endif
                    </div>
                    <div class="flex flex-col space-y-1">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold tracking-tight">{{ $producto['nombre'] }}</h3>
                            <span class="text-[#1737c8] font-bold text-sm">${{ number_format($producto['precio'], 2) }}</span>
                        </div>
                        <p class="text-[#5e5e5e]/60 font-mono text-[10px] tracking-widest uppercase">ID: {{ $producto['id'] }}</p>
                        <div class="pt-3 border-t border-[#c4c5da]/20">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#1a1c1c]/40 mb-2">Inventario</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                <span class="bg-[#eeeeee] px-2 py-1 text-[10px] font-bold">{{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span></span>
                                @endforeach
                            </div>
                        </div>
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="abrirModalTallas({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio'], 'tallas' => $producto['tallas']]) }})"
                                class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#1a1c1c] text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] hover:text-white transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Agregar al carrito
                        </button>
                        @else
                        <div class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#c4c5da] text-[10px] font-black uppercase tracking-widest text-center">Sin stock</div>
                        @endif
                    </div>
                </div>
                @empty
                @for($i = 0; $i < 6; $i++)
                <div class="producto-card group flex flex-col space-y-4">
                    <div class="aspect-[4/5] bg-[#f3f3f4] flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                    <div class="flex justify-between items-start"><div class="h-4 bg-[#f3f3f4] w-32 rounded"></div><div class="h-4 bg-[#f3f3f4] w-16 rounded"></div></div>
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
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-white">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8]">shopping_cart</span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Carrito de venta</p>
                            <p class="text-sm font-black text-[#1a1c1c]" id="carrito-count">0 productos</p>
                        </div>
                    </div>
                    <button onclick="limpiarCarrito()" class="text-[10px] font-black uppercase tracking-widest text-[#747688] hover:text-red-600 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete_sweep</span>Limpiar
                    </button>
                </div>
                <div id="carrito-items" class="max-h-64 overflow-y-auto">
                    <div id="carrito-vacio" class="px-6 py-12 text-center text-[#747688] text-sm">
                        <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>Agrega productos del catálogo
                    </div>
                </div>
                <div class="px-6 py-4 bg-[#1737c8] flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                    <span class="text-2xl font-black text-white" id="carrito-total">$0.00</span>
                </div>
                <form method="POST" action="{{ route('catalog.store') }}" id="form-carrito" class="px-6 py-5 space-y-4 bg-white border-t border-[#c4c5da]/20">
                    @csrf
                    <input type="hidden" name="productos_carrito" id="productos-carrito-input"/>
                    <input type="hidden" name="total_carrito" id="total-carrito-input"/>
                    <input type="hidden" name="metodo_pago" id="metodo-pago-carrito" value="efectivo"/>
                    <input type="hidden" name="tipo_venta" id="tipo-venta-carrito" value="menudeo"/>
                    <input type="hidden" name="recibido" id="cat-recibido-hidden" value="0"/>
                    <input type="hidden" name="cambio" id="cat-cambio-hidden" value="0"/>
                    <input type="hidden" name="puntos_canjear" id="cat-puntos-canjear-hidden" value="0"/>

                    {{-- Toggle Mayoreo con bloqueo de plan --}}
                    @if($esPro)
                    <div class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">Venta mayoreo</p>
                            <p class="text-[9px] text-[#747688] mt-0.5" id="mayoreo-label-cat">Activar para precios especiales</p>
                        </div>
                        <label class="toggle-mayoreo">
                            <input type="checkbox" id="toggle-mayoreo-cat" onchange="toggleMayoreoCat(this.checked)"/>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    @else
                    <a href="{{ route('planes') }}" class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3 group">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#c4c5da]">Venta mayoreo</p>
                                <span class="text-[9px] font-black bg-[#1737c8] text-white px-2 py-0.5 rounded-full">PRO</span>
                            </div>
                            <p class="text-[9px] text-[#c4c5da] mt-0.5">Actualiza a Pro para desbloquear</p>
                        </div>
                        <span class="material-symbols-outlined text-[#c4c5da] group-hover:text-[#1737c8] transition-colors">lock</span>
                    </a>
                    @endif

                    {{-- Método de pago --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Método de pago</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="seleccionarMetodoCat('efectivo', this)"
                                    class="metodo-btn activo py-2.5 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all">
                                <span class="material-symbols-outlined text-sm">payments</span>Efectivo
                            </button>
                            <button type="button" onclick="seleccionarMetodoCat('tarjeta', this)"
                                    class="metodo-btn py-2.5 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c]">
                                <span class="material-symbols-outlined text-sm">credit_card</span>Tarjeta
                            </button>
                            <button type="button" onclick="seleccionarMetodoCat('transferencia', this)"
                                    class="metodo-btn py-2.5 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c]">
                                <span class="material-symbols-outlined text-sm">account_balance</span>Transfer.
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                        <input type="text" id="cliente-carrito-search" name="cliente_nombre" placeholder="Nombre del cliente..." autocomplete="off" oninput="buscarClienteCarrito(this.value)" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <input type="hidden" name="cliente_id" id="cliente-carrito-id"/>
                        <p class="text-[9px] text-[#747688] font-semibold mt-1">Si el cliente no existe se registrará automáticamente</p>
                        <div id="carrito-sugerencias" class="absolute top-[68px] left-0 right-0 bg-white border border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-40 overflow-y-auto"></div>
                        <div id="clientes-data-carrito" class="hidden">
                            @foreach($clientes ?? [] as $cliente)
                            <span data-id="{{ $cliente->id ?? $cliente['id'] }}"
                                  data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"
                                  data-telefono="{{ $cliente->telefono ?? '' }}"
                                  data-puntos="{{ $cliente->puntos ?? 0 }}"></span>
                            @endforeach
                        </div>
                    </div>

                    @if($lealtadActivo)
                    <div id="lealtad-section-cat" class="hidden bg-amber-50 border border-amber-200 px-4 py-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-sm">stars</span>
                                <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Programa de lealtad</p>
                            </div>
                            <span class="text-sm font-black text-amber-600" id="cat-lealtad-puntos-display">0 puntos</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-amber-700 shrink-0">Canjear:</label>
                            <input type="number" id="cat-puntos-canjear-input" placeholder="0" min="0"
                                oninput="actualizarCanjePuntosCat()"
                                class="flex-1 border border-amber-300 bg-white px-3 py-1.5 text-sm font-black focus:outline-none focus:border-amber-500"/>
                            <span class="text-[9px] text-amber-600 font-bold shrink-0" id="cat-lealtad-descuento-display">-$0</span>
                        </div>
                        <p class="text-[9px] text-amber-600">1 punto = $1 de descuento</p>
                    </div>
                    @endif

                    <button type="button" onclick="procesarCarrito()" class="w-full py-4 bg-[#1a1c1c] text-white text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">point_of_sale</span>Registrar venta
                    </button>
                </form>
            </div>
        </aside>
    </div>
</main>

<!-- BOTTOM NAV móvil -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    @endif
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
</nav>

<!-- MODAL SELECTOR DE TALLA -->
<div id="modal-tallas" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalTallas()">
    <div class="bg-white w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Seleccionar talla</p>
                <h2 class="text-lg font-bold tracking-tight" id="modal-tallas-nombre">—</h2>
                <p class="text-sm font-black text-[#1737c8] mt-1" id="modal-tallas-precio">$0.00</p>
            </div>
            <button onclick="cerrarModalTallas()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-6 py-5">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-3">Tallas disponibles</p>
            <div id="modal-tallas-lista" class="flex flex-wrap gap-2"></div>
        </div>
    </div>
</div>

<!-- MODAL EFECTIVO CATÁLOGO -->
<div id="modal-efectivo-cat" class="fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Pago en efectivo</p>
                <h2 class="text-xl font-bold tracking-tight">¿Cuánto recibiste?</h2>
            </div>
            <button onclick="cerrarModalEfectivoCat()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total a cobrar</span>
                <span class="text-xl font-black text-[#1737c8]" id="cat-efectivo-total">$0.00</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Monto recibido</label>
                <input type="number" id="cat-efectivo-recibido" placeholder="0.00" min="0" step="0.01"
                       oninput="calcularCambioCat()"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-lg font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            </div>
            <div class="grid grid-cols-4 gap-2" id="cat-efectivo-rapidos"></div>
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cambio</span>
                <span class="text-xl font-black" id="cat-efectivo-cambio">$0.00</span>
            </div>
            <p id="cat-efectivo-error" class="text-xs text-red-500 font-bold hidden">El monto recibido es menor al total.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button onclick="confirmarEfectivoCat()" class="flex-1 py-4 bg-[#1a1c1c] text-white text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">check</span>Confirmar venta
            </button>
            <button onclick="cerrarModalEfectivoCat()" class="px-4 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
const PLAN_CAT = '{{ $plan ?? "gratis" }}';
const ES_PRO_CAT = PLAN_CAT === 'pro' || PLAN_CAT === 'business';
const ES_LEALTAD_CAT = {{ $lealtadActivo ? 'true' : 'false' }};

let carrito = [];
let productoActual = null;
let metodoPagoCat = 'efectivo';
let tipoCat = 'menudeo';
let totalCatActual = 0;
let esMayoreoCat = false;
let _telefonoClienteCarrito = null;
let _ultimaVentaResumen = {
    metodo: '{{ session("ticket_metodo") ?? "" }}',
    tipo:   '{{ session("ticket_tipo") ?? "menudeo" }}',
    total:  '{{ session("ticket_recibido") ?? "0" }}',
    cambio: '{{ session("ticket_cambio") ?? "0" }}',
};

// ── WHATSAPP ──────────────────────────────────────────────────
function enviarWhatsAppUltimaVenta() {
    const metodo = _ultimaVentaResumen.metodo;
    const tipo   = _ultimaVentaResumen.tipo;
    let texto = `🧾 *Ticket de venta*\nTipo: ${tipo.charAt(0).toUpperCase() + tipo.slice(1)}\nMétodo: ${metodo.charAt(0).toUpperCase() + metodo.slice(1)}`;
    if (metodo === 'efectivo') {
        texto += `\nRecibido: $${parseFloat(_ultimaVentaResumen.total || 0).toFixed(2)}\nCambio: $${parseFloat(_ultimaVentaResumen.cambio || 0).toFixed(2)}`;
    }
    texto += `\n\n_Gracias por tu compra en {{ Auth::user()->store_name ?? 'nuestra tienda' }}_`;
    enviarWhatsAppCarrito(texto);
}

function enviarWhatsAppCarrito(texto) {
    const telefono = _telefonoClienteCarrito
        ? _telefonoClienteCarrito.replace(/\D/g, '')
        : null;
    const url = telefono
        ? `https://wa.me/52${telefono}?text=${encodeURIComponent(texto)}`
        : `https://wa.me/?text=${encodeURIComponent(texto)}`;
    window.open(url, '_blank');
}

// ── TOGGLE MAYOREO ────────────────────────────────────────────
function toggleMayoreoCat(activo) {
    esMayoreoCat = activo;
    tipoCat = activo ? 'mayoreo' : 'menudeo';
    document.getElementById('tipo-venta-carrito').value = tipoCat;
    document.getElementById('mayoreo-label-cat').textContent = activo
        ? '⚠️ Mayoreo activo — modifica precios en el carrito'
        : 'Activar para precios especiales';
    actualizarCarrito();
}

// ── MÉTODO DE PAGO ────────────────────────────────────────────
function seleccionarMetodoCat(metodo, btn) {
    metodoPagoCat = metodo;
    document.getElementById('metodo-pago-carrito').value = metodo;
    document.querySelectorAll('.metodo-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
}

// ── PROCESAR CARRITO ──────────────────────────────────────────
function procesarCarrito() {
    if (carrito.length === 0) { alert('Agrega al menos un producto al carrito'); return; }
    if (!document.getElementById('cliente-carrito-search').value.trim()) { alert('Escribe el nombre del cliente'); return; }
    totalCatActual = carrito.reduce((s, p) => s + p.precio * p.cantidad, 0);
    document.getElementById('productos-carrito-input').value = JSON.stringify(carrito);
    document.getElementById('total-carrito-input').value = totalCatActual.toFixed(2);
    if (metodoPagoCat === 'efectivo') {
        abrirModalEfectivoCat();
    } else {
        document.getElementById('form-carrito').submit();
    }
}

// ── MODAL EFECTIVO ────────────────────────────────────────────
function abrirModalEfectivoCat() {
    document.getElementById('cat-efectivo-total').textContent = '$' + totalCatActual.toFixed(2);
    document.getElementById('cat-efectivo-recibido').value = '';
    document.getElementById('cat-efectivo-cambio').textContent = '$0.00';
    document.getElementById('cat-efectivo-cambio').className = 'text-xl font-black';
    document.getElementById('cat-efectivo-error').classList.add('hidden');
    const rapidos = [totalCatActual, 50, 100, 200, 500, 1000]
        .filter((v, i, a) => a.indexOf(v) === i && v >= totalCatActual)
        .sort((a,b) => a-b).slice(0,4);
    document.getElementById('cat-efectivo-rapidos').innerHTML = rapidos.map(v =>
        `<button type="button" onclick="setCatRecibido(${v})"
                 class="py-2 text-xs font-black border border-[#c4c5da]/40 hover:border-[#1a1c1c] hover:bg-[#f3f3f4] transition-all">
            $${Number.isInteger(v) ? v : v.toFixed(2)}</button>`
    ).join('');
    document.getElementById('modal-efectivo-cat').classList.add('activo');
    setTimeout(() => document.getElementById('cat-efectivo-recibido').focus(), 100);
}

function cerrarModalEfectivoCat() { document.getElementById('modal-efectivo-cat').classList.remove('activo'); }
function setCatRecibido(valor) { document.getElementById('cat-efectivo-recibido').value = valor; calcularCambioCat(); }

function calcularCambioCat() {
    const recibido = parseFloat(document.getElementById('cat-efectivo-recibido').value) || 0;
    const cambio = recibido - totalCatActual;
    const cambioEl = document.getElementById('cat-efectivo-cambio');
    const errorEl = document.getElementById('cat-efectivo-error');
    if (recibido > 0 && cambio < 0) {
        cambioEl.textContent = '-$' + Math.abs(cambio).toFixed(2);
        cambioEl.className = 'text-xl font-black text-red-500';
        errorEl.classList.remove('hidden');
    } else {
        cambioEl.textContent = '$' + Math.max(0, cambio).toFixed(2);
        cambioEl.className = 'text-xl font-black text-green-600';
        errorEl.classList.add('hidden');
    }
}

function confirmarEfectivoCat() {
    const recibido = parseFloat(document.getElementById('cat-efectivo-recibido').value) || 0;
    if (recibido < totalCatActual) { document.getElementById('cat-efectivo-error').classList.remove('hidden'); return; }
    document.getElementById('cat-recibido-hidden').value = recibido.toFixed(2);
    document.getElementById('cat-cambio-hidden').value = (recibido - totalCatActual).toFixed(2);
    cerrarModalEfectivoCat();
    document.getElementById('form-carrito').submit();
}

// ── MODAL TALLAS ──────────────────────────────────────────────
function abrirModalTallas(producto) {
    productoActual = producto;
    document.getElementById('modal-tallas-nombre').textContent = producto.nombre;
    document.getElementById('modal-tallas-precio').textContent = '$' + parseFloat(producto.precio).toFixed(2);
    const lista = document.getElementById('modal-tallas-lista');
    const tallas = producto.tallas;
    if (!tallas || Object.keys(tallas).length === 0) { agregarAlCarrito(producto, null); return; }
    lista.innerHTML = Object.entries(tallas).map(([talla, cantidad]) => {
        const agotada = cantidad <= 0;
        return `<button onclick="seleccionarTalla('${talla}')" ${agotada ? 'disabled' : ''}
            class="px-4 py-3 text-xs font-black uppercase tracking-widest border ${agotada
                ? 'border-[#c4c5da]/20 text-[#c4c5da] cursor-not-allowed bg-[#f9f9f9]'
                : 'border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] hover:bg-[#f3f3f4] transition-all'
            } flex flex-col items-center gap-1">
            <span>${talla}</span>
            <span class="text-[9px] font-bold ${agotada ? 'text-[#c4c5da]' : cantidad <= 2 ? 'text-red-500' : 'text-[#747688]'}">${cantidad} pzs</span>
        </button>`;
    }).join('');
    document.getElementById('modal-tallas').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

function seleccionarTalla(talla) { if (!productoActual) return; agregarAlCarrito(productoActual, talla); cerrarModalTallas(); }
function cerrarModalTallas() { document.getElementById('modal-tallas').classList.remove('activo'); document.body.style.overflow = ''; productoActual = null; }

// ── CARRITO ───────────────────────────────────────────────────
function actualizarPrecioCarrito(i, valor) { carrito[i].precio = parseFloat(valor) || 0; actualizarCarrito(); }

function agregarAlCarrito(producto, talla) {
    const key = producto.id + '_' + (talla ?? 'sin_talla');
    const existente = carrito.find(p => p.key === key);
    if (existente) { existente.cantidad += 1; } else { carrito.push({ key, id: producto.id, nombre: producto.nombre, precio: producto.precio, precio_original: producto.precio, cantidad: 1, talla }); }
    actualizarCarrito();
}

function actualizarCarrito() {
    const itemsEl = document.getElementById('carrito-items');
    const countEl = document.getElementById('carrito-count');
    const totalEl = document.getElementById('carrito-total');
    if (carrito.length === 0) {
        itemsEl.innerHTML = '<div class="px-6 py-12 text-center text-[#747688] text-sm"><span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>Agrega productos del catálogo</div>';
        countEl.textContent = '0 productos'; totalEl.textContent = '$0.00'; return;
    }
    let total = 0;
    itemsEl.innerHTML = carrito.map((p, i) => {
        const s = p.precio * p.cantidad; total += s;
        return `<div class="flex items-center gap-3 px-4 py-3 border-b border-[#c4c5da]/10 ${esMayoreoCat ? 'bg-amber-50' : 'bg-white'}">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-xs uppercase truncate">${p.nombre}</p>
                ${p.talla ? `<p class="text-[10px] text-[#1737c8] font-bold">Talla: ${p.talla}</p>` : ''}
                ${esMayoreoCat
                    ? `<div class="flex items-center gap-1 mt-1">
                        <span class="text-[9px] text-amber-600 font-bold">Precio:</span>
                        <input type="number" value="${p.precio}" step="0.01" min="0"
                               onchange="actualizarPrecioCarrito(${i}, this.value)"
                               class="w-20 text-xs font-black border border-amber-300 bg-white px-2 py-0.5 focus:outline-none"/>
                        <span class="text-[9px] text-[#747688] line-through">$${(p.precio_original||p.precio).toFixed(2)}</span>
                       </div>`
                    : `<p class="text-[10px] text-[#747688]">$${parseFloat(p.precio).toFixed(2)} c/u</p>`
                }
            </div>
            <div class="flex items-center gap-1">
                <button onclick="cambiarCantidad(${i},-1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">−</button>
                <span class="w-8 text-center text-sm font-black">${p.cantidad}</span>
                <button onclick="cambiarCantidad(${i},1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">+</button>
            </div>
            <p class="text-xs font-black ${esMayoreoCat ? 'text-amber-600' : 'text-[#1737c8]'}">$${s.toFixed(2)}</p>
            <button onclick="quitarDelCarrito(${i})" class="text-[#747688] hover:text-red-600 ml-1">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>`;
    }).join('');
    countEl.textContent = carrito.length + (carrito.length === 1 ? ' producto' : ' productos');
    totalEl.textContent = '$' + total.toFixed(2);
}

function cambiarCantidad(i, d) { carrito[i].cantidad += d; if (carrito[i].cantidad <= 0) carrito.splice(i, 1); actualizarCarrito(); }
function quitarDelCarrito(i) { carrito.splice(i, 1); actualizarCarrito(); }
function limpiarCarrito() { carrito = []; _telefonoClienteCarrito = null; actualizarCarrito(); }

// ── CLIENTE AUTOCOMPLETE ──────────────────────────────────────
function buscarClienteCarrito(query) {
    const sugerencias = document.getElementById('carrito-sugerencias');
    const q = query.toLowerCase().trim();
    if (q.length < 1) { sugerencias.classList.add('hidden'); return; }
    const c = Array.from(document.querySelectorAll('#clientes-data-carrito span')).filter(c => c.dataset.nombre.toLowerCase().includes(q));
    sugerencias.innerHTML = c.length === 0
        ? '<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias</div>'
        : c.map(c => `<div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] uppercase" onclick="seleccionarClienteCarrito('${c.dataset.id}','${c.dataset.nombre}','${c.dataset.telefono}')">${c.dataset.nombre}</div>`).join('');
    sugerencias.classList.remove('hidden');
}

function seleccionarClienteCarrito(id, nombre, telefono) {
    document.getElementById('cliente-carrito-search').value = nombre;
    document.getElementById('cliente-carrito-id').value = id;
    document.getElementById('carrito-sugerencias').classList.add('hidden');
    _telefonoClienteCarrito = telefono || null;

    if (ES_LEALTAD_CAT) {
        const span = Array.from(document.querySelectorAll('#clientes-data-carrito span')).find(s => s.dataset.id == id);
        const puntos = parseInt(span?.dataset.puntos ?? 0);
        const seccion = document.getElementById('lealtad-section-cat');
        if (seccion) {
            if (puntos > 0) {
                document.getElementById('cat-lealtad-puntos-display').textContent = puntos + ' puntos disponibles';
                document.getElementById('cat-puntos-canjear-input').max = puntos;
                document.getElementById('cat-puntos-canjear-input').value = '';
                document.getElementById('cat-lealtad-descuento-display').textContent = '-$0';
                document.getElementById('cat-puntos-canjear-hidden').value = 0;
                seccion.classList.remove('hidden');
            } else {
                seccion.classList.add('hidden');
                document.getElementById('cat-puntos-canjear-hidden').value = 0;
            }
        }
    }
}

document.addEventListener('click', e => {
    if (!e.target.closest('#cliente-carrito-search') && !e.target.closest('#carrito-sugerencias'))
        document.getElementById('carrito-sugerencias')?.classList.add('hidden');
});

function filtrar(categoria, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    document.querySelectorAll('.producto-card').forEach(c => {
        c.style.display = (categoria === 'todos' || c.dataset.categoria === categoria) ? '' : 'none';
    });
}

function actualizarCanjePuntosCat() {
    const puntos = parseInt(document.getElementById('cat-puntos-canjear-input').value) || 0;
    const max = parseInt(document.getElementById('cat-puntos-canjear-input').max) || 0;
    const real = Math.min(puntos, max);
    document.getElementById('cat-puntos-canjear-hidden').value = real;
    document.getElementById('cat-lealtad-descuento-display').textContent = '-$' + real;
}
</script>

@include('partials._sidebar')

</body>
</html>