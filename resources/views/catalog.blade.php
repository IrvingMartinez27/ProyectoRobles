<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Catálogo</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    /* Transiciones globales para evitar saltos de color y hacer fluido el Dark Mode */
    body, main, div, header, nav, aside, button, input, select { 
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease; 
    }
    
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; scroll-behavior: smooth; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    .filtro-btn { border-radius: 100px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; box-shadow: 0 4px 12px rgba(26,28,28,0.2); transform: translateY(-2px); }
    
    .producto-card { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .producto-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: transparent; }
    .producto-card:hover .producto-imagen { transform: scale(1.08); }
    .producto-imagen { transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    
    .btn-agregar { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); border-radius: 100px; }
    .btn-agregar:hover { transform: scale(1.1) rotate(5deg); box-shadow: 0 8px 20px rgba(23,55,200,0.3); }
    
    /* Modales con desenfoque y animación suave */
    .modal-backdrop { display: none; opacity: 0; transition: opacity 0.3s ease; }
    .modal-backdrop.activo { display: flex; opacity: 1; }
    
    .metodo-btn { border-radius: 20px; transition: all 0.2s ease; }
    .metodo-btn.activo { background: #1a1c1c; color: #ffffff; border-color: #1a1c1c; box-shadow: 0 4px 12px rgba(26,28,28,0.15); transform: translateY(-2px); }
    
    /* Toggle Switch Fino */
    .toggle-mayoreo { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-mayoreo input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #c4c5da; transition: .4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 24px; }
    .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    input:checked + .toggle-slider { background-color: #f59e0b; }
    input:checked + .toggle-slider:before { transform: translateX(20px); }

    /* ANIMACIONES */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes modalScale { from { opacity: 0; transform: scale(0.95) translateY(15px); } to { opacity: 1; transform: scale(1) translateY(0); } }

    .anim-header { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .anim-header-1 { animation-delay: 0.05s; }
    .anim-header-2 { animation-delay: 0.12s; }

    .producto-card { animation: popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .producto-card:nth-child(1){animation-delay:0.04s;}
    .producto-card:nth-child(2){animation-delay:0.08s;}
    .producto-card:nth-child(3){animation-delay:0.12s;}
    .producto-card:nth-child(4){animation-delay:0.16s;}
    .producto-card:nth-child(5){animation-delay:0.20s;}
    .producto-card:nth-child(6){animation-delay:0.24s;}
    .producto-card:nth-child(n+7){animation-delay:0.28s;}

    .modal-content { animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
    .carrito-aside { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both; }

    /* ── DARK MODE REFINADO ─────────────────────────────────── */
    [data-theme="dark"] body { background-color: #0f1012 !important; color: #f3f3f4 !important; }
    [data-theme="dark"] main { background-color: #0f1012 !important; }
    [data-theme="dark"] .bg-white { background-color: #1e2022 !important; border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\] { background-color: #0f1012 !important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background-color: #141618 !important; }
    [data-theme="dark"] .bg-\[\#e8e8e8\] { background-color: #1a1c1e !important; }
    
    /* Textos oscuros */
    [data-theme="dark"] .text-\[\#1a1c1c\] { color: #f3f3f4 !important; }
    [data-theme="dark"] .text-\[\#747688\] { color: #9496a8 !important; }
    [data-theme="dark"] .text-\[\#1737c8\] { color: #5a75ff !important; } /* Azul más brillante para contraste */
    
    /* Bordes y Sombras oscuras */
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color: rgba(255,255,255,0.12) !important; }
    [data-theme="dark"] .producto-card { background-color: #1e2022; border-color: rgba(255,255,255,0.05); }
    [data-theme="dark"] .producto-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); }
    [data-theme="dark"] .aspect-\[4\/5\] { background-color: #141618 !important; }

    /* Inputs y botones dark */
    [data-theme="dark"] input, [data-theme="dark"] select { background-color: #141618 !important; color: #f3f3f4 !important; border-color: rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] input::placeholder { color: #5e5e5e !important; }
    [data-theme="dark"] .filtro-btn { color: #9496a8; background: #1e2022; }
    [data-theme="dark"] .filtro-btn.activo { background: #f3f3f4 !important; color: #1a1c1c !important; }
    [data-theme="dark"] .metodo-btn { border-color: rgba(255,255,255,0.1) !important; color: #9496a8; }
    [data-theme="dark"] .metodo-btn.activo { background: #f3f3f4 !important; color: #1a1c1c !important; }
    
    /* Colores de estado dark */
    [data-theme="dark"] .bg-green-100 { background-color: rgba(34,197,94,0.15) !important; }
    [data-theme="dark"] .bg-red-100 { background-color: rgba(239,68,68,0.15) !important; }
    [data-theme="dark"] .bg-blue-50 { background-color: rgba(23,55,200,0.1) !important; }
    [data-theme="dark"] .bg-amber-50 { background-color: rgba(245,158,11,0.1) !important; }
    
    /* Scrollbar estilizada */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(196,197,218,0.5); border-radius: 10px; }
    [data-theme="dark"] ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>
</head>
<body>

@include('partials._nav')

@php
$esPro = ($plan ?? 'gratis') === 'pro' || ($plan ?? 'gratis') === 'business';
$lealtadActivo = $lealtadActivo ?? false;
@endphp

<div class="px-6 max-w-[1600px] mx-auto pt-24">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3 anim-header">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Catálogo</span>
    </div>
</div>

{{-- Alertas --}}
@if(session('success'))
<div class="mx-6 max-w-[1600px] xl:mx-auto mb-4 bg-green-100 text-green-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 anim-header shadow-sm">
    <span class="material-symbols-outlined text-lg">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('ticket_metodo'))
<div class="mx-6 max-w-[1600px] xl:mx-auto mb-6 bg-blue-50 border border-blue-200 px-6 py-5 rounded-3xl flex items-center justify-between gap-4 flex-wrap anim-header shadow-sm">
    <div class="flex gap-8 flex-wrap">
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-1 tracking-widest">Tipo</p>
            <p class="font-black text-sm {{ session('ticket_tipo') === 'mayoreo' ? 'text-amber-600' : 'text-[#1a1c1c]' }}">{{ ucfirst(session('ticket_tipo') ?? 'menudeo') }}</p>
        </div>
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-1 tracking-widest">Método</p>
            <p class="font-black text-sm">{{ ucfirst(session('ticket_metodo')) }}</p>
        </div>
        @if(session('ticket_metodo') === 'efectivo')
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-1 tracking-widest">Recibido</p>
            <p class="font-black text-sm">${{ number_format(session('ticket_recibido'), 2) }}</p>
        </div>
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-1 tracking-widest">Cambio</p>
            <p class="font-black text-sm text-green-600">${{ number_format(session('ticket_cambio'), 2) }}</p>
        </div>
        @endif
    </div>
    <button onclick="enviarWhatsAppUltimaVenta()" class="shrink-0 px-6 py-3.5 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-green-600 transition-all rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
        WhatsApp
    </button>
</div>
@endif

@if(session('error'))
<div class="mx-6 max-w-[1600px] xl:mx-auto mb-6 bg-red-100 text-red-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
    <span class="material-symbols-outlined text-lg">error</span>{{ session('error') }}
</div>
@endif

<main class="max-w-[1600px] mx-auto px-6 pb-28">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <section class="lg:col-span-8">
            <div class="mb-8 pt-2 flex flex-col md:flex-row md:items-end justify-between gap-4 anim-header anim-header-1">
                <div>
                    <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-2">Gestión de inventario</p>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-[#1a1c1c]">Catálogo</h1>
                </div>
                {{-- BOTÓN COMPARTIR CATÁLOGO --}}
                <button onclick="abrirModalCompartir()"
                        class="shrink-0 flex items-center justify-center gap-2 px-6 py-3.5 border-2 border-[#1737c8] text-[#1737c8] text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] hover:text-white transition-all rounded-2xl md:mb-1 hover:shadow-lg">
                    <span class="material-symbols-outlined text-sm">share</span>
                    Compartir catálogo
                </button>
            </div>
            
            <div class="flex gap-3 mb-10 overflow-x-auto pb-3 custom-scrollbar anim-header anim-header-2">
                <button class="filtro-btn activo px-6 py-2.5 bg-[#1a1c1c] text-white text-xs font-bold uppercase tracking-widest whitespace-nowrap" onclick="filtrar('todos', this)">Todos</button>
                <button class="filtro-btn px-6 py-2.5 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#d1d1d1] whitespace-nowrap" onclick="filtrar('ropa', this)">Ropa</button>
                <button class="filtro-btn px-6 py-2.5 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#d1d1d1] whitespace-nowrap" onclick="filtrar('calzado', this)">Calzado</button>
                <button class="filtro-btn px-6 py-2.5 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#d1d1d1] whitespace-nowrap" onclick="filtrar('accesorios', this)">Accesorios</button>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-8" id="grid-productos">
                @forelse($productos ?? [] as $producto)
                <div class="producto-card group flex flex-col rounded-3xl overflow-hidden bg-white border border-[#c4c5da]/30 shadow-sm" data-categoria="{{ $producto['categoria'] }}">
                    <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative">
                        @if(isset($producto['imagen']) && $producto['imagen'])
                        <img class="producto-imagen w-full h-full object-cover" src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"/>
                        @else
                        <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                        @endif
                        
                        {{-- Etiquetas Stock --}}
                        @if(isset($producto['stock']))
                            @if($producto['stock'] === 'disponible')
                                <div class="absolute top-4 left-4 bg-[#1737c8] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md">En stock</div>
                            @elseif($producto['stock'] === 'poco')
                                <div class="absolute top-4 left-4 bg-[#f59e0b] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md">Poco stock</div>
                            @elseif($producto['stock'] === 'agotado')
                                <div class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md">Sin stock</div>
                            @endif
                        @endif
                        
                        {{-- Botón Flotante --}}
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="abrirModalTallas({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio'], 'tallas' => $producto['tallas']]) }})"
                                class="btn-agregar absolute bottom-4 right-4 bg-[#1737c8] text-white w-12 h-12 flex items-center justify-center shadow-xl opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0">
                            <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                        </button>
                        @endif
                    </div>
                    
                    <div class="flex flex-col p-6 flex-1 justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-2 mb-1">
                                <h3 class="text-lg font-black tracking-tight text-[#1a1c1c] leading-tight">{{ $producto['nombre'] }}</h3>
                                <span class="text-[#1737c8] font-black text-lg">${{ number_format($producto['precio'], 2) }}</span>
                            </div>
                            <p class="text-[#747688] font-mono text-[9px] font-bold tracking-widest uppercase mb-4">ID: {{ $producto['id'] }}</p>
                            
                            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-2">Inventario</p>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                <span class="bg-[#f3f3f4] border border-[#c4c5da]/20 px-2.5 py-1 rounded-lg text-[10px] font-bold text-[#1a1c1c]">{{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span></span>
                                @endforeach
                            </div>
                        </div>
                        
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="abrirModalTallas({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio'], 'tallas' => $producto['tallas']]) }})"
                                class="w-full py-3.5 bg-[#f3f3f4] text-[#1a1c1c] text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#1737c8] hover:text-white transition-all flex items-center justify-center gap-2 mt-auto">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Agregar
                        </button>
                        @else
                        <div class="w-full py-3.5 bg-[#f3f3f4] text-[#c4c5da] text-[10px] font-black uppercase tracking-widest rounded-2xl text-center mt-auto border border-[#c4c5da]/20">Agotado</div>
                        @endif
                    </div>
                </div>
                @empty
                @for($i = 0; $i < 6; $i++)
                <div class="producto-card group flex flex-col rounded-3xl overflow-hidden bg-white border border-[#c4c5da]/20">
                    <div class="aspect-[4/5] bg-[#f3f3f4] flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3"><div class="h-5 bg-[#f3f3f4] rounded w-32"></div><div class="h-5 bg-[#f3f3f4] rounded w-16"></div></div>
                        <div class="h-3 bg-[#f3f3f4] rounded w-24 mb-6"></div>
                        <div class="w-full h-10 bg-[#f3f3f4] rounded-2xl mt-2"></div>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>

        <aside id="seccion-carrito" class="carrito-aside lg:col-span-4 lg:sticky lg:top-24 h-fit pt-6 lg:pt-0">
            <div class="bg-white border border-[#c4c5da]/30 rounded-3xl shadow-xl overflow-hidden flex flex-col max-h-[calc(100vh-120px)]">
                
                {{-- Header Carrito --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#1737c8]/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1737c8]">shopping_cart</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Punto de Venta</p>
                            <p class="text-sm font-black text-[#1a1c1c]" id="carrito-count">0 productos</p>
                        </div>
                    </div>
                    <button onclick="limpiarCarrito()" class="w-8 h-8 rounded-full flex items-center justify-center text-[#747688] hover:text-red-600 hover:bg-red-50 transition-colors" title="Limpiar carrito">
                        <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                    </button>
                </div>
                
                {{-- Items --}}
                <div id="carrito-items" class="flex-1 overflow-y-auto custom-scrollbar p-2">
                    <div id="carrito-vacio" class="px-6 py-16 text-center text-[#747688] text-sm flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-[#f3f3f4] flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-3xl text-[#c4c5da]">shopping_basket</span>
                        </div>
                        <p class="font-bold">Tu carrito está vacío</p>
                        <p class="text-[10px] mt-1 opacity-70">Agrega productos desde el catálogo</p>
                    </div>
                </div>
                
                {{-- Total Banner --}}
                <div class="px-6 py-4 bg-[#1737c8] flex justify-between items-center mx-2 rounded-2xl shadow-inner my-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Total a Pagar</span>
                    <span class="text-3xl font-black text-white tracking-tight" id="carrito-total">$0.00</span>
                </div>
                
                {{-- Formulario Checkout --}}
                <form method="POST" action="{{ route('catalog.store') }}" id="form-carrito" class="px-6 py-5 space-y-5 bg-[#f9f9f9] border-t border-[#c4c5da]/20">
                    @csrf
                    <input type="hidden" name="productos_carrito" id="productos-carrito-input"/>
                    <input type="hidden" name="total_carrito" id="total-carrito-input"/>
                    <input type="hidden" name="metodo_pago" id="metodo-pago-carrito" value="efectivo"/>
                    <input type="hidden" name="tipo_venta" id="tipo-venta-carrito" value="menudeo"/>
                    <input type="hidden" name="recibido" id="cat-recibido-hidden" value="0"/>
                    <input type="hidden" name="cambio" id="cat-cambio-hidden" value="0"/>
                    <input type="hidden" name="puntos_canjear" id="cat-puntos-canjear-hidden" value="0"/>

                    @if($esPro)
                    <div class="flex items-center justify-between bg-white border border-[#c4c5da]/30 rounded-2xl px-5 py-4 shadow-sm">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">Venta mayoreo</p>
                            <p class="text-[9px] font-bold text-[#747688] mt-0.5" id="mayoreo-label-cat">Activar para precios especiales</p>
                        </div>
                        <label class="toggle-mayoreo">
                            <input type="checkbox" id="toggle-mayoreo-cat" onchange="toggleMayoreoCat(this.checked)"/>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    @else
                    <a href="{{ route('planes') }}" class="flex items-center justify-between bg-white border border-[#c4c5da]/30 rounded-2xl px-5 py-4 group hover:border-[#1737c8]/50 transition-all">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#c4c5da]">Venta mayoreo</p>
                                <span class="text-[9px] font-black bg-[#1737c8] text-white px-2 py-0.5 rounded-full">PRO</span>
                            </div>
                            <p class="text-[9px] font-bold text-[#c4c5da] mt-0.5">Actualiza a Pro para desbloquear</p>
                        </div>
                        <span class="material-symbols-outlined text-[#c4c5da] group-hover:text-[#1737c8] transition-colors">lock</span>
                    </a>
                    @endif

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Método de pago</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="seleccionarMetodoCat('efectivo', this)" class="metodo-btn activo py-3 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-lg">payments</span>Efectivo
                            </button>
                            <button type="button" onclick="seleccionarMetodoCat('tarjeta', this)" class="metodo-btn py-3 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center justify-center gap-1.5 hover:border-[#1a1c1c] hover:bg-black/5">
                                <span class="material-symbols-outlined text-lg">credit_card</span>Tarjeta
                            </button>
                            <button type="button" onclick="seleccionarMetodoCat('transferencia', this)" class="metodo-btn py-3 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center justify-center gap-1.5 hover:border-[#1a1c1c] hover:bg-black/5">
                                <span class="material-symbols-outlined text-lg">account_balance</span>Transfer
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Cliente</label>
                        <input type="text" id="cliente-carrito-search" name="cliente_nombre" placeholder="Nombre del cliente..." autocomplete="off" oninput="buscarClienteCarrito(this.value)"
                               class="border border-[#c4c5da]/40 px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all font-medium shadow-sm"/>
                        <input type="hidden" name="cliente_id" id="cliente-carrito-id"/>
                        <p class="text-[9px] text-[#747688] font-semibold mt-1 ml-1">Se registrará si es nuevo</p>
                        
                        <div id="carrito-sugerencias" class="absolute top-[76px] left-0 right-0 bg-white border rounded-2xl border-[#c4c5da]/40 shadow-xl z-50 hidden max-h-48 overflow-y-auto overflow-hidden"></div>
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
                    <div id="lealtad-section-cat" class="hidden bg-amber-50 border border-amber-200 px-5 py-4 rounded-2xl shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-lg">stars</span>
                                <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Lealtad</p>
                            </div>
                            <span class="text-xs font-black text-amber-600 bg-amber-200/50 px-2 py-1 rounded-lg" id="cat-lealtad-puntos-display">0 pts</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="number" id="cat-puntos-canjear-input" placeholder="Puntos a canjear" min="0" oninput="actualizarCanjePuntosCat()"
                                   class="flex-1 border border-amber-300 bg-white rounded-xl px-4 py-2 text-sm font-black focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"/>
                            <span class="text-[14px] text-amber-600 font-black shrink-0" id="cat-lealtad-descuento-display">-$0</span>
                        </div>
                    </div>
                    @endif

                    <button type="button" onclick="procesarCarrito()" class="w-full py-4 bg-[#1a1c1c] text-white text-[11px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <span class="material-symbols-outlined text-lg">point_of_sale</span>Cobrar ticket
                    </button>
                </form>
            </div>
        </aside>
    </div>
</main>

<nav class="md:hidden fixed bottom-0 w-full z-40 flex justify-around items-center h-16 px-4 bg-white/90 backdrop-blur-xl border-t border-[#c4c5da]/20 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]">
    @if(Auth::user()->role === 'admin')
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">dashboard</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Inicio</span></a>
    @endif
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">receipt_long</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">inventory_2</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Stock</span></a>
</nav>

<div id="modal-tallas" class="modal-backdrop fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalTallas()">
    <div class="modal-content bg-white rounded-3xl w-full max-w-sm mx-4 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Seleccionar talla</p>
                <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]" id="modal-tallas-nombre">—</h2>
                <p class="text-base font-black text-[#1737c8] mt-0.5" id="modal-tallas-precio">$0.00</p>
            </div>
            <button onclick="cerrarModalTallas()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-[#c4c5da]/30 hover:bg-[#f3f3f4] hover:text-red-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <div class="px-6 py-6">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-4 ml-1">Tallas disponibles</p>
            <div id="modal-tallas-lista" class="grid grid-cols-3 gap-3"></div>
        </div>
    </div>
</div>

<div id="modal-efectivo-cat" class="modal-backdrop fixed inset-0 z-[60] items-center justify-center bg-black/60 backdrop-blur-md" onclick="if(event.target===this) cerrarModalEfectivoCat()">
    <div class="modal-content bg-white rounded-3xl w-full max-w-sm mx-4 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Pago en efectivo</p>
                <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]">Calculadora</h2>
            </div>
            <button onclick="cerrarModalEfectivoCat()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-[#c4c5da]/30 hover:bg-[#f3f3f4] hover:text-red-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <div class="px-6 py-6 space-y-5">
            <div class="bg-[#1737c8] rounded-2xl px-5 py-4 flex justify-between items-center shadow-inner">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Total</span>
                <span class="text-2xl font-black text-white tracking-tight" id="cat-efectivo-total">$0.00</span>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Monto recibido</label>
                <input type="number" id="cat-efectivo-recibido" placeholder="0.00" min="0" step="0.01" oninput="calcularCambioCat()"
                       class="border border-[#c4c5da]/40 px-5 py-4 text-2xl font-black focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all shadow-sm text-center"/>
            </div>
            <div class="grid grid-cols-4 gap-2" id="cat-efectivo-rapidos"></div>
            
            <div class="bg-[#f9f9f9] border border-[#c4c5da]/20 rounded-2xl px-5 py-4 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cambio</span>
                <span class="text-2xl font-black text-green-600" id="cat-efectivo-cambio">$0.00</span>
            </div>
            <p id="cat-efectivo-error" class="text-xs text-red-500 font-bold hidden text-center bg-red-50 py-2 rounded-lg">Monto insuficiente</p>
        </div>
        <div class="flex gap-3 px-6 pb-6 pt-2">
            <button onclick="confirmarEfectivoCat()" class="flex-1 py-4 bg-[#1a1c1c] text-white text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2 rounded-2xl shadow-lg hover:-translate-y-1">
                <span class="material-symbols-outlined text-lg">check_circle</span>Confirmar
            </button>
        </div>
    </div>
</div>

<div id="modal-compartir" class="modal-backdrop fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalCompartir()">
    <div class="modal-content bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Social</p>
                <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]">Compartir Catálogo</h2>
            </div>
            <button onclick="cerrarModalCompartir()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-[#c4c5da]/30 hover:bg-[#f3f3f4] hover:text-red-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <div class="px-6 py-6 space-y-6">
            {{-- Mensaje del catálogo --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-2 ml-1">Vista previa del mensaje</p>
                <div class="bg-[#f9f9f9] border border-[#c4c5da]/30 rounded-2xl px-5 py-4 text-sm text-[#1a1c1c] font-medium leading-relaxed max-h-48 overflow-y-auto whitespace-pre-wrap custom-scrollbar" id="preview-catalogo"></div>
            </div>
            {{-- Filtro --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-2 ml-1">Filtrar por categoría</p>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="generarCatalogo('todos')" class="cat-share-btn activo px-5 py-2 text-[10px] font-black uppercase tracking-widest border-2 border-[#1a1c1c] bg-[#1a1c1c] text-white rounded-full transition-all">Todos</button>
                    <button onclick="generarCatalogo('ropa')" class="cat-share-btn px-5 py-2 text-[10px] font-black uppercase tracking-widest border-2 border-[#c4c5da]/40 hover:border-[#1a1c1c] text-[#747688] hover:text-[#1a1c1c] rounded-full transition-all">Ropa</button>
                    <button onclick="generarCatalogo('calzado')" class="cat-share-btn px-5 py-2 text-[10px] font-black uppercase tracking-widest border-2 border-[#c4c5da]/40 hover:border-[#1a1c1c] text-[#747688] hover:text-[#1a1c1c] rounded-full transition-all">Calzado</button>
                    <button onclick="generarCatalogo('accesorios')" class="cat-share-btn px-5 py-2 text-[10px] font-black uppercase tracking-widest border-2 border-[#c4c5da]/40 hover:border-[#1a1c1c] text-[#747688] hover:text-[#1a1c1c] rounded-full transition-all">Accesorios</button>
                </div>
            </div>
            {{-- Botones --}}
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button onclick="compartirWhatsApp()" class="share-btn flex items-center justify-center gap-2 py-4 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition-all rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                    WhatsApp
                </button>
                <button onclick="copiarCatalogo()" class="share-btn flex items-center justify-center gap-2 py-4 bg-[#1a1c1c] text-white text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1">
                    <span class="material-symbols-outlined text-sm">content_copy</span>
                    <span id="btn-copiar-texto">Copiar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const PLAN_CAT = '{{ $plan ?? "gratis" }}';
const ES_PRO_CAT = PLAN_CAT === 'pro' || PLAN_CAT === 'business';
const ES_LEALTAD_CAT = {{ $lealtadActivo ? 'true' : 'false' }};
const STORE_NAME = '{{ Auth::user()->store_name ?? "Mi Tienda" }}';
const CATALOGO_LINK_BASE = '{{ route("catalogo.publico", Auth::user()->tenant_id) }}';

let carrito = [];
let productoActual = null;
let metodoPagoCat = 'efectivo';
let tipoCat = 'menudeo';
let totalCatActual = 0;
let esMayoreoCat = false;
let _telefonoClienteCarrito = null;
let _catalogoMensaje = '';
let _catalogoCategoria = 'todos';

let _ultimaVentaResumen = {
    metodo: '{{ session("ticket_metodo") ?? "" }}',
    tipo:   '{{ session("ticket_tipo") ?? "menudeo" }}',
    total:  '{{ session("ticket_recibido") ?? "0" }}',
    cambio: '{{ session("ticket_cambio") ?? "0" }}',
};

// ── SCROLL SUAVE A CARRITO EN MÓVIL ───────────────────────────
// Detecta si es pantalla móvil (< 1024px) y hace scroll hacia la vista del carrito
function scrollHaciaCarritoEnMovil() {
    if (window.innerWidth < 1024) {
        // Le damos un pequeño timeout para asegurar que el DOM actualizó y el modal cerró
        setTimeout(() => {
            const seccionCarrito = document.getElementById('seccion-carrito');
            if(seccionCarrito) {
                // Hacemos scroll para que el carrito quede a la vista, dejando un margen superior
                const yOffset = -70; // Margen para el nav/header
                const y = seccionCarrito.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});
            }
        }, 150);
    }
}

function abrirModalCompartir() {
    document.getElementById('modal-compartir').classList.add('activo');
    document.body.style.overflow = 'hidden';
    generarCatalogo('todos');
}
function cerrarModalCompartir() {
    document.getElementById('modal-compartir').classList.remove('activo');
    document.body.style.overflow = '';
}

// ── COMPARTIR CATÁLOGO (genera link en lugar de texto) ────────
function generarCatalogo(categoria) {
    _catalogoCategoria = categoria;

    document.querySelectorAll('.cat-share-btn').forEach(b => {
        b.classList.remove('activo', 'bg-[#1a1c1c]', 'text-white', 'border-[#1a1c1c]');
        b.classList.add('border-[#c4c5da]/40', 'text-[#747688]');
    });

    if (event && event.target) {
        event.target.classList.remove('border-[#c4c5da]/40', 'text-[#747688]');
        event.target.classList.add('activo', 'bg-[#1a1c1c]', 'text-white', 'border-[#1a1c1c]');
    }

    let link = CATALOGO_LINK_BASE;
    if (categoria !== 'todos') {
        link += `?categoria=${categoria}`;
    }

    let msg = `🏪 *${STORE_NAME}*\n`;
    msg += `📦 Mira nuestro catálogo y elige tus productos aquí:\n\n`;
    msg += `${link}\n\n`;
    msg += `👆 Selecciona lo que te guste y te llevará directo a WhatsApp para hacer tu pedido`;

    _catalogoMensaje = msg;
    document.getElementById('preview-catalogo').textContent = msg;
}

function compartirWhatsApp() {
    window.open(`https://wa.me/?text=${encodeURIComponent(_catalogoMensaje)}`, '_blank');
}

function copiarCatalogo() {
    navigator.clipboard.writeText(_catalogoMensaje).then(() => {
        const btn = document.getElementById('btn-copiar-texto');
        btn.textContent = '¡Copiado!';
        setTimeout(() => btn.textContent = 'Copiar', 2000);
    });
}

// ── WHATSAPP ÚLTIMA VENTA ─────────────────────────────────────
function enviarWhatsAppUltimaVenta() {
    const m = _ultimaVentaResumen.metodo, t = _ultimaVentaResumen.tipo;
    let texto = `🧾 *Ticket de venta*\nTipo: ${t.charAt(0).toUpperCase()+t.slice(1)}\nMétodo: ${m.charAt(0).toUpperCase()+m.slice(1)}`;
    if (m === 'efectivo') texto += `\nRecibido: $${parseFloat(_ultimaVentaResumen.total||0).toFixed(2)}\nCambio: $${parseFloat(_ultimaVentaResumen.cambio||0).toFixed(2)}`;
    texto += `\n\n_Gracias por tu compra en ${STORE_NAME}_`;
    const tel = _telefonoClienteCarrito?.replace(/\D/g,'');
    window.open(tel ? `https://wa.me/52${tel}?text=${encodeURIComponent(texto)}` : `https://wa.me/?text=${encodeURIComponent(texto)}`, '_blank');
}

// ── TOGGLE MAYOREO ────────────────────────────────────────────
function toggleMayoreoCat(activo) {
    esMayoreoCat = activo; tipoCat = activo ? 'mayoreo' : 'menudeo';
    document.getElementById('tipo-venta-carrito').value = tipoCat;
    document.getElementById('mayoreo-label-cat').textContent = activo ? 'Mayoreo activo — modifica precios' : 'Activar para precios especiales';
    actualizarCarrito();
}
function seleccionarMetodoCat(metodo, btn) {
    metodoPagoCat = metodo;
    document.getElementById('metodo-pago-carrito').value = metodo;
    document.querySelectorAll('.metodo-btn').forEach(b => {
        b.classList.remove('activo', 'bg-[#1a1c1c]', 'text-white', 'border-[#1a1c1c]');
    });
    btn.classList.add('activo');
}

// ── PROCESAR CARRITO ──────────────────────────────────────────
function procesarCarrito() {
    if (!carrito.length) { alert('Agrega al menos un producto al carrito'); return; }
    if (!document.getElementById('cliente-carrito-search').value.trim()) { alert('Escribe el nombre del cliente'); return; }
    totalCatActual = carrito.reduce((s, p) => s + p.precio * p.cantidad, 0);
    document.getElementById('productos-carrito-input').value = JSON.stringify(carrito);
    document.getElementById('total-carrito-input').value = totalCatActual.toFixed(2);
    if (metodoPagoCat === 'efectivo') abrirModalEfectivoCat();
    else document.getElementById('form-carrito').submit();
}

// ── MODAL EFECTIVO ────────────────────────────────────────────
function abrirModalEfectivoCat() {
    document.getElementById('cat-efectivo-total').textContent = '$' + totalCatActual.toFixed(2);
    document.getElementById('cat-efectivo-recibido').value = '';
    document.getElementById('cat-efectivo-cambio').textContent = '$0.00';
    document.getElementById('cat-efectivo-cambio').className = 'text-2xl font-black text-green-600';
    document.getElementById('cat-efectivo-error').classList.add('hidden');
    const rapidos = [totalCatActual,50,100,200,500,1000].filter((v,i,a)=>a.indexOf(v)===i&&v>=totalCatActual).sort((a,b)=>a-b).slice(0,4);
    document.getElementById('cat-efectivo-rapidos').innerHTML = rapidos.map(v=>`<button type="button" onclick="setCatRecibido(${v})" class="py-3 text-sm font-black border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] bg-white rounded-xl transition-all shadow-sm hover:shadow-md">$${Number.isInteger(v)?v:v.toFixed(2)}</button>`).join('');
    document.getElementById('modal-efectivo-cat').classList.add('activo');
    setTimeout(() => document.getElementById('cat-efectivo-recibido').focus(), 100);
}
function cerrarModalEfectivoCat() { document.getElementById('modal-efectivo-cat').classList.remove('activo'); }
function setCatRecibido(v) { document.getElementById('cat-efectivo-recibido').value = v; calcularCambioCat(); }
function calcularCambioCat() {
    const r = parseFloat(document.getElementById('cat-efectivo-recibido').value)||0;
    const c = r - totalCatActual;
    const el = document.getElementById('cat-efectivo-cambio');
    if (r>0&&c<0){el.textContent='-$'+Math.abs(c).toFixed(2);el.className='text-2xl font-black text-red-500';document.getElementById('cat-efectivo-error').classList.remove('hidden');}
    else{el.textContent='$'+Math.max(0,c).toFixed(2);el.className='text-2xl font-black text-green-600';document.getElementById('cat-efectivo-error').classList.add('hidden');}
}
function confirmarEfectivoCat() {
    const r = parseFloat(document.getElementById('cat-efectivo-recibido').value)||0;
    if (r<totalCatActual){document.getElementById('cat-efectivo-error').classList.remove('hidden');return;}
    document.getElementById('cat-recibido-hidden').value=r.toFixed(2);
    document.getElementById('cat-cambio-hidden').value=(r-totalCatActual).toFixed(2);
    cerrarModalEfectivoCat();
    document.getElementById('form-carrito').submit();
}

// ── MODAL TALLAS ──────────────────────────────────────────────
function abrirModalTallas(producto) {
    productoActual = producto;
    document.getElementById('modal-tallas-nombre').textContent = producto.nombre;
    document.getElementById('modal-tallas-precio').textContent = '$' + parseFloat(producto.precio).toFixed(2);
    const tallas = producto.tallas;
    
    // Si no tiene tallas, se agrega directo al carrito y hace scroll en móvil
    if (!tallas || !Object.keys(tallas).length) { 
        agregarAlCarrito(producto, null); 
        scrollHaciaCarritoEnMovil();
        return; 
    }
    
    document.getElementById('modal-tallas-lista').innerHTML = Object.entries(tallas).map(([talla, cantidad]) => {
        const agotada = cantidad <= 0;
        return `<button onclick="seleccionarTalla('${talla}')" ${agotada?'disabled':''}
            class="px-2 py-4 text-sm font-black uppercase tracking-widest border rounded-2xl ${agotada
                ?'border-[#c4c5da]/20 text-[#c4c5da] cursor-not-allowed bg-[#f9f9f9]'
                :'border-[#c4c5da]/40 hover:border-[#1737c8] hover:bg-[#1737c8]/5 hover:text-[#1737c8] transition-all bg-white shadow-sm hover:shadow-md'
            } flex flex-col items-center justify-center gap-1">
            <span>${talla}</span>
            <span class="text-[9px] font-bold ${agotada?'text-[#c4c5da]':cantidad<=2?'text-red-500':'text-[#747688]'}">${cantidad} pzs</span>
        </button>`;
    }).join('');
    document.getElementById('modal-tallas').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

function seleccionarTalla(talla) { 
    if (!productoActual) return; 
    agregarAlCarrito(productoActual, talla); 
    cerrarModalTallas(); 
    // Después de seleccionar talla y cerrar el modal, hace scroll si es móvil
    scrollHaciaCarritoEnMovil();
}

function cerrarModalTallas() { 
    document.getElementById('modal-tallas').classList.remove('activo'); 
    document.body.style.overflow=''; 
    productoActual=null; 
}

// ── CARRITO ───────────────────────────────────────────────────
function actualizarPrecioCarrito(i, valor) { carrito[i].precio = parseFloat(valor)||0; actualizarCarrito(); }
function agregarAlCarrito(producto, talla) {
    const key = producto.id + '_' + (talla??'sin_talla');
    const e = carrito.find(p=>p.key===key);
    if (e) e.cantidad+=1; else carrito.push({key,id:producto.id,nombre:producto.nombre,precio:producto.precio,precio_original:producto.precio,cantidad:1,talla});
    actualizarCarrito();
}
function actualizarCarrito() {
    const items = document.getElementById('carrito-items');
    const count = document.getElementById('carrito-count');
    const total = document.getElementById('carrito-total');
    if (!carrito.length) {
        items.innerHTML='<div class="px-6 py-16 text-center text-[#747688] text-sm flex flex-col items-center justify-center"><div class="w-16 h-16 rounded-full bg-[#f3f3f4] flex items-center justify-center mb-3"><span class="material-symbols-outlined text-3xl text-[#c4c5da]">shopping_basket</span></div><p class="font-bold">Tu carrito está vacío</p><p class="text-[10px] mt-1 opacity-70">Agrega productos desde el catálogo</p></div>';
        count.textContent='0 productos'; total.textContent='$0.00'; return;
    }
    let t = 0;
    items.innerHTML = carrito.map((p,i)=>{
        const s=p.precio*p.cantidad; t+=s;
        return `<div class="flex items-center gap-3 px-4 py-4 mb-2 rounded-2xl border border-[#c4c5da]/20 shadow-sm ${esMayoreoCat?'bg-amber-50/50':'bg-white'}">
            <div class="flex-1 min-w-0">
                <p class="font-black text-xs uppercase truncate text-[#1a1c1c] mb-0.5">${p.nombre}</p>
                ${p.talla?`<p class="text-[10px] text-[#1737c8] font-bold">Talla: ${p.talla}</p>`:''}
                ${esMayoreoCat
                    ?`<div class="flex items-center gap-2 mt-2"><span class="text-[9px] text-amber-600 font-bold">Precio:</span><input type="number" value="${p.precio}" step="0.01" min="0" onchange="actualizarPrecioCarrito(${i},this.value)" class="w-20 text-xs font-black border border-amber-300 rounded-lg bg-white px-2 py-1 focus:outline-none focus:border-amber-500"/><span class="text-[9px] text-[#747688] line-through">$${(p.precio_original||p.precio).toFixed(2)}</span></div>`
                    :`<p class="text-[10px] text-[#747688] font-medium mt-1">$${parseFloat(p.precio).toFixed(2)} c/u</p>`}
            </div>
            <div class="flex flex-col items-end gap-2">
                <p class="text-sm font-black ${esMayoreoCat?'text-amber-600':'text-[#1737c8]'}">$${s.toFixed(2)}</p>
                <div class="flex items-center gap-1 bg-[#f9f9f9] border border-[#c4c5da]/30 rounded-lg p-0.5">
                    <button onclick="cambiarCantidad(${i},-1)" class="w-7 h-7 bg-white rounded-md flex items-center justify-center text-sm font-black text-[#747688] hover:text-[#1a1c1c] shadow-sm">−</button>
                    <span class="w-8 text-center text-xs font-black text-[#1a1c1c]">${p.cantidad}</span>
                    <button onclick="cambiarCantidad(${i},1)" class="w-7 h-7 bg-white rounded-md flex items-center justify-center text-sm font-black text-[#747688] hover:text-[#1a1c1c] shadow-sm">+</button>
                </div>
            </div>
            <button onclick="quitarDelCarrito(${i})" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center ml-1"><span class="material-symbols-outlined text-[16px]">close</span></button>
        </div>`;
    }).join('');
    count.textContent = carrito.length + (carrito.length===1?' producto':' productos');
    total.textContent = '$' + t.toFixed(2);
    
    // Smooth scroll down dentro del propio contenedor del carrito
    items.scrollTo({ top: items.scrollHeight, behavior: 'smooth' });
}
function cambiarCantidad(i,d){carrito[i].cantidad+=d;if(carrito[i].cantidad<=0)carrito.splice(i,1);actualizarCarrito();}
function quitarDelCarrito(i){carrito.splice(i,1);actualizarCarrito();}
function limpiarCarrito(){carrito=[];_telefonoClienteCarrito=null;actualizarCarrito();}

// ── CLIENTE ───────────────────────────────────────────────────
function buscarClienteCarrito(query) {
    const s=document.getElementById('carrito-sugerencias'), q=query.toLowerCase().trim();
    if(q.length<1){s.classList.add('hidden');return;}
    const c=Array.from(document.querySelectorAll('#clientes-data-carrito span')).filter(x=>x.dataset.nombre.toLowerCase().includes(q));
    s.innerHTML=c.length===0?'<div class="px-5 py-4 text-sm text-[#747688]">Sin coincidencias</div>':c.map(x=>`<div class="px-5 py-3.5 text-sm font-bold cursor-pointer hover:bg-[#f9f9f9] border-b border-[#c4c5da]/10 last:border-0 uppercase transition-colors" onclick="seleccionarClienteCarrito('${x.dataset.id}','${x.dataset.nombre}','${x.dataset.telefono}')">${x.dataset.nombre}</div>`).join('');
    s.classList.remove('hidden');
}
function seleccionarClienteCarrito(id, nombre, telefono) {
    document.getElementById('cliente-carrito-search').value=nombre;
    document.getElementById('cliente-carrito-id').value=id;
    document.getElementById('carrito-sugerencias').classList.add('hidden');
    _telefonoClienteCarrito = telefono||null;
    if (!ES_LEALTAD_CAT) return;
    const span=Array.from(document.querySelectorAll('#clientes-data-carrito span')).find(s=>s.dataset.id==id);
    const puntos=parseInt(span?.dataset.puntos??0);
    const sec=document.getElementById('lealtad-section-cat');
    if(sec){
        if(puntos>0){document.getElementById('cat-lealtad-puntos-display').textContent=puntos+' pts';document.getElementById('cat-puntos-canjear-input').max=puntos;document.getElementById('cat-puntos-canjear-input').value='';document.getElementById('cat-lealtad-descuento-display').textContent='-$0';document.getElementById('cat-puntos-canjear-hidden').value=0;sec.classList.remove('hidden');}
        else{sec.classList.add('hidden');document.getElementById('cat-puntos-canjear-hidden').value=0;}
    }
}
document.addEventListener('click',e=>{if(!e.target.closest('#cliente-carrito-search')&&!e.target.closest('#carrito-sugerencias'))document.getElementById('carrito-sugerencias')?.classList.add('hidden');});

function filtrar(cat, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b=>{
        b.classList.remove('activo', 'bg-[#1a1c1c]', 'text-white');
        b.classList.add('bg-[#e8e8e8]', 'text-[#1a1c1c]');
    });
    btn.classList.remove('bg-[#e8e8e8]', 'text-[#1a1c1c]');
    btn.classList.add('activo', 'bg-[#1a1c1c]', 'text-white');
    
    document.querySelectorAll('.producto-card').forEach(c=>{
        if(cat==='todos'||c.dataset.categoria===cat) {
            c.style.display='';
            // Reiniciar animación al filtrar
            c.style.animation = 'none';
            c.offsetHeight; /* trigger reflow */
            c.style.animation = null; 
        } else {
            c.style.display='none';
        }
    });
}
function actualizarCanjePuntosCat() {
    const p=parseInt(document.getElementById('cat-puntos-canjear-input').value)||0;
    const m=parseInt(document.getElementById('cat-puntos-canjear-input').max)||0;
    const r=Math.min(p,m);
    document.getElementById('cat-puntos-canjear-hidden').value=r;
    document.getElementById('cat-lealtad-descuento-display').textContent='-$'+r;
}
</script>

@include('partials._sidebar')
</body>
</html>