<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Inventario</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family:'Inter',sans-serif; background:#f9f9f9; color:#1a1c1c; min-height:100dvh; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }

    #modal-editar        { display:none; } #modal-editar.activo        { display:flex; }
    #modal-mover         { display:none; } #modal-mover.activo         { display:flex; }
    #modal-nuevo-producto{ display:none; } #modal-nuevo-producto.activo{ display:flex; }
    #modal-confirmar-eliminar { display:none; } #modal-confirmar-eliminar.activo { display:flex; }

    .filtro-btn.activo { background:#1a1c1c; color:#fff; border-color:#1a1c1c; }

    .dot { width:8px;height:8px;border-radius:50%;display:inline-block;flex-shrink:0;margin-right:6px; }
    .dot-ok      { background:#22c55e; }
    .dot-poco    { background:#f59e0b; }
    .dot-agotado { background:#ef4444; }

    /* ── ANIMACIONES ─────────────────────────────────── */
    @keyframes fadeInUp  { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    @keyframes slideInRow{ from{opacity:0;transform:translateX(-8px)} to{opacity:1;transform:translateX(0)} }
    @keyframes popIn     { 0%{opacity:0;transform:scale(0.9)} 70%{transform:scale(1.02)} 100%{opacity:1;transform:scale(1)} }
    
    /* Animaciones fluidas para los modales */
    @keyframes fadeInBg { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleUpModal { 
        from { opacity: 0; transform: scale(0.95) translateY(10px); } 
        to { opacity: 1; transform: scale(1) translateY(0); } 
    }

    #modal-editar.activo, #modal-mover.activo, #modal-nuevo-producto.activo, #modal-confirmar-eliminar.activo {
        animation: fadeInBg 0.2s ease-out both;
    }
    #modal-editar.activo > div, #modal-mover.activo > div, #modal-nuevo-producto.activo > div, #modal-confirmar-eliminar.activo > div {
        animation: scaleUpModal 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    .anim-header   { animation:fadeInUp 0.5s ease both; }
    .anim-header-1 { animation-delay:0.05s; }
    .anim-header-2 { animation-delay:0.12s; }
    .anim-header-3 { animation-delay:0.19s; }

    /* Tabla desktop */
    .fila-producto { animation:slideInRow 0.4s ease both; transition:background 0.15s; }
    .fila-producto:nth-child(1) {animation-delay:.04s} .fila-producto:nth-child(2) {animation-delay:.07s}
    .fila-producto:nth-child(3) {animation-delay:.10s} .fila-producto:nth-child(4) {animation-delay:.13s}
    .fila-producto:nth-child(5) {animation-delay:.16s} .fila-producto:nth-child(6) {animation-delay:.19s}
    .fila-producto:nth-child(n+7){animation-delay:.22s}
    .fila-producto:hover { background:rgba(23,55,200,0.03) !important; }
    .fila-poco    { border-left:3px solid #f59e0b; }
    .fila-poco td { background:#fffbeb; }
    .fila-agotado    { border-left:3px solid #ef4444; }
    .fila-agotado td { background:#fef2f2; }
    .fila-ok td   { background:#fff; }

    /* Cards móvil */
    .prod-card { animation:popIn 0.4s cubic-bezier(0.34,1.56,0.64,1) both; transition:transform .2s ease, box-shadow .2s ease; }
    .prod-card:nth-child(1){animation-delay:.05s} .prod-card:nth-child(2){animation-delay:.10s}
    .prod-card:nth-child(3){animation-delay:.15s} .prod-card:nth-child(4){animation-delay:.20s}
    .prod-card:nth-child(5){animation-delay:.25s} .prod-card:nth-child(6){animation-delay:.30s}
    .prod-card:nth-child(n+7){animation-delay:.35s}

    .stat-card { animation:popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both; transition:transform .2s ease,box-shadow .2s ease; }
    .stat-card:nth-child(1){animation-delay:.1s} .stat-card:nth-child(2){animation-delay:.18s}
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(23,55,200,.1); }

    /* Zona imagen */
    #zona-imagen     { min-height:120px; }
    #zona-imagen.con-imagen { min-height:180px; }

    /* ── DARK MODE ────────────────────────────────────── */
    [data-theme="dark"] body { background:#0f1012!important;color:#f3f3f4!important; }
    [data-theme="dark"] .bg-white { background:#1e2022!important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\] { background:#0f1012!important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background:#141618!important; }
    [data-theme="dark"] .text-\[\#1a1c1c\] { color:#f3f3f4!important; }
    [data-theme="dark"] .text-\[\#747688\] { color:#9496a8!important; }
    [data-theme="dark"] .text-\[\#5e5e5e\]  { color:#9496a8!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color:rgba(255,255,255,.05)!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] .fila-ok td { background:#1e2022!important; }
    [data-theme="dark"] .fila-poco td { background:rgba(245,158,11,.08)!important; }
    [data-theme="dark"] .fila-agotado td { background:rgba(239,68,68,.08)!important; }
    [data-theme="dark"] .fila-producto:hover { background:rgba(23,55,200,.08)!important; }
    [data-theme="dark"] .fila-producto:hover td { background:transparent!important; }
    [data-theme="dark"] thead tr { background:#141618!important; }
    [data-theme="dark"] thead th { color:#9496a8!important; }
    [data-theme="dark"] input,[data-theme="dark"] select { background:#141618!important;color:#f3f3f4!important;border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] input::placeholder { color:#9496a8!important; }
    [data-theme="dark"] .filtro-btn { border-color:rgba(255,255,255,.1)!important;color:#9496a8; }
    [data-theme="dark"] .filtro-btn.activo { background:#f3f3f4!important;color:#1a1c1c!important; }
    [data-theme="dark"] .hover\:bg-\[\#f3f3f4\]:hover { background:#141618!important; }
    [data-theme="dark"] .hover\:bg-\[\#f9f9f9\]:hover { background:#1a1c1e!important; }
    [data-theme="dark"] .bg-green-100 { background:rgba(34,197,94,.15)!important; }
    [data-theme="dark"] .bg-yellow-100 { background:rgba(245,158,11,.15)!important; }
    [data-theme="dark"] .bg-red-100 { background:rgba(239,68,68,.15)!important; }
    [data-theme="dark"] .bg-red-50 { background:rgba(239,68,68,.08)!important; }
    [data-theme="dark"] .bg-green-50 { background:rgba(34,197,94,.08)!important; }
    [data-theme="dark"] .prod-card { background:#1e2022!important;border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .card-poco { border-color:#f59e0b!important; }
    [data-theme="dark"] .card-agotado { border-color:#ef4444!important; }
    [data-theme="dark"] #modal-editar .bg-white,
    [data-theme="dark"] #modal-mover .bg-white,
    [data-theme="dark"] #modal-nuevo-producto .bg-white,
    [data-theme="dark"] #modal-confirmar-eliminar .bg-white { background:#1e2022!important; }
    [data-theme="dark"] #zona-imagen { background:#141618!important;border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] nav.fixed.bottom-0 { background:rgba(15,16,18,.92)!important;border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] nav.fixed.bottom-0 a { color:rgba(243,243,244,.5)!important; }
    [data-theme="dark"] .qty-btn { border-color:rgba(255,255,255,.1);color:#9496a8; }
    [data-theme="dark"] .qty-btn:hover { border-color:#1737c8;color:#1737c8; }
    [data-theme="dark"] .bg-yellow-100 { background-color: rgba(245,158,11,0.2) !important; }
    [data-theme="dark"] .text-yellow-700 { color: #fbbf24 !important; }

    .qty-btn { width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1.5px solid rgba(196,197,218,.4);transition:all .15s ease;cursor:pointer;font-size:18px;font-weight:700;color:#747688;background:transparent; }
    .qty-btn:hover { border-color:#1737c8;color:#1737c8;background:rgba(23,55,200,.06); }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

<div class="pt-24 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3 anim-header">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span>Inventario</span>
    </div>
</div>

<main class="max-w-7xl mx-auto px-4 md:px-6 pb-28">

    {{-- HEADER --}}
    <section class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="anim-header anim-header-1">
            <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-2">Gestión de stock</p>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tighter text-[#1a1c1c] mb-2">Inventario</h1>
            <p class="text-[#5e5e5e] text-sm md:text-base">Consulta, edita y agrega productos al inventario.</p>
        </div>
        <div class="flex gap-3 flex-wrap anim-header anim-header-2">
            <button onclick="abrirModalProducto()"
                    class="bg-[#1737c8] text-white px-5 py-3 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center gap-2"
                    style="box-shadow:0 4px 16px rgba(23,55,200,.25)">
                <span class="material-symbols-outlined text-sm">add</span>Nuevo producto
            </button>
            <div class="stat-card rounded-xl bg-[#f3f3f4] px-5 py-3 text-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Productos</p>
                <p class="text-2xl font-black text-[#1a1c1c]">{{ $productos->count() }}</p>
            </div>
            <div class="stat-card rounded-xl bg-red-50 px-5 py-3 text-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-red-400 mb-1">Stock bajo</p>
                <p class="text-2xl font-black text-red-600">{{ $productos->filter(fn($p) => $p['stock_total'] < 10)->count() }}</p>
            </div>
        </div>
    </section>

    {{-- FLASH --}}
    @php $flashS = session('success'); $flashE = session('error'); @endphp
    @if($flashS)
    <script>document.addEventListener('DOMContentLoaded',()=>showToast('¡Listo!',@json($flashS),'green',5000));</script>
    @endif
    @if($flashE)
    <script>document.addEventListener('DOMContentLoaded',()=>showToast('Error',@json($flashE),'red',5000));</script>
    @endif

    {{-- FILTROS + BUSQUEDA --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6 justify-between items-start sm:items-center anim-header anim-header-3">
        <div class="flex gap-2 flex-wrap">
            <button class="filtro-btn activo px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#1a1c1c] rounded-xl transition-all" onclick="filtrar('todos',this)">Todos</button>
            <button class="filtro-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 rounded-xl hover:border-[#1a1c1c] transition-all" onclick="filtrar('ropa',this)">Ropa</button>
            <button class="filtro-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 rounded-xl hover:border-[#1a1c1c] transition-all" onclick="filtrar('calzado',this)">Calzado</button>
            <button class="filtro-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 rounded-xl hover:border-[#1a1c1c] transition-all" onclick="filtrar('accesorios',this)">Accesorios</button>
        </div>
        <div class="relative w-full sm:w-auto">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
            <input type="text" placeholder="Buscar producto..." oninput="buscar(this.value)"
                   class="pl-9 pr-4 py-2.5 border border-[#c4c5da]/40 rounded-xl focus:border-[#1737c8] focus:outline-none text-sm w-full sm:w-56 bg-white transition-all"/>
        </div>
    </div>

    {{-- ── CARDS MÓVIL (visible solo en < md) ───────────────── --}}
    <div class="md:hidden space-y-3" id="cards-movil">
        @forelse($productos ?? [] as $producto)
        @php
            $cardBorder = $producto['stock_total'] == 0 ? 'border-red-300' : ($producto['stock_total'] < 10 ? 'border-amber-300' : 'border-[#c4c5da]/20');
            $esBusiness = (Auth::user()->plan ?? 'gratis') === 'business';
        @endphp
        <div class="prod-card {{ $producto['stock_total'] == 0 ? 'card-agotado' : ($producto['stock_total'] < 10 ? 'card-poco' : '') }} bg-white rounded-2xl border {{ $cardBorder }} p-4"
             data-categoria="{{ $producto['categoria'] }}" data-nombre="{{ strtolower($producto['nombre']) }}">
            <div class="flex items-start gap-3 mb-3">
                {{-- Imagen --}}
                <div class="w-12 h-12 rounded-xl bg-[#f3f3f4] shrink-0 overflow-hidden">
                    @if(!empty($producto['imagen']))
                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-full h-full object-cover"/>
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#c4c5da] text-sm">image</span>
                    </div>
                    @endif
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="dot {{ $producto['stock_total'] == 0 ? 'dot-agotado' : ($producto['stock_total'] < 10 ? 'dot-poco' : 'dot-ok') }}"></span>
                        <p class="font-black text-sm uppercase truncate text-[#1a1c1c]">{{ $producto['nombre'] }}</p>
                    </div>
                    <p class="text-[9px] text-[#747688] uppercase tracking-widest font-bold">{{ ucfirst($producto['categoria']) }}</p>
                </div>
                {{-- Badge estado --}}
                @if($producto['stock_total'] == 0)
                <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-red-100 text-red-700 rounded-full uppercase shrink-0">Sin stock</span>
                @elseif($producto['stock_total'] < 10)
                <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full uppercase shrink-0">Poco</span>
                @else
                <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-green-100 text-green-700 rounded-full uppercase shrink-0">OK</span>
                @endif
            </div>

            {{-- Tallas --}}
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                <span class="bg-[#f3f3f4] px-2 py-1 text-[10px] font-bold rounded-lg text-[#1a1c1c]">
                    {{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span>
                </span>
                @endforeach
                <span class="bg-[#1737c8]/10 text-[#1737c8] px-2 py-1 text-[10px] font-black rounded-lg">Total: {{ $producto['stock_total'] }}</span>
            </div>

            {{-- Acciones --}}
            <div class="flex gap-2">
                <button onclick="abrirEditar({{ json_encode($producto) }})"
                        class="flex-1 flex items-center justify-center gap-1 border border-[#c4c5da]/40 rounded-xl py-2.5 text-[10px] font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all text-[#1a1c1c]">
                    <span class="material-symbols-outlined text-sm">edit</span>Editar
                </button>
                @if($esBusiness && $producto['stock_total'] > 0)
                <button onclick="abrirMover({{ $producto['id'] }}, '{{ addslashes($producto['nombre']) }}', {{ json_encode($producto['tallas'] ?? []) }})"
                        class="flex-1 flex items-center justify-center gap-1 bg-[#1737c8] text-white rounded-xl py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>Para entrega
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-[#c4c5da]/20 p-12 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">inventory_2</span>
            <p class="text-sm font-bold text-[#747688]">Sin productos en inventario</p>
        </div>
        @endforelse
    </div>

    {{-- ── TABLA DESKTOP (visible solo en >= md) ─────────────── --}}
    <div class="hidden md:block overflow-hidden rounded-2xl border border-[#c4c5da]/20 bg-white">
        <table class="w-full text-sm" id="tabla-inventario">
            <thead>
                <tr class="bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Producto</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Categoría</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Tallas</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Total</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Estado</th>
                    <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos ?? [] as $producto)
                @php $esBusiness = (Auth::user()->plan ?? 'gratis') === 'business'; @endphp
                <tr class="fila-producto border-b border-[#c4c5da]/10 {{ $producto['stock_total'] == 0 ? 'fila-agotado' : ($producto['stock_total'] < 10 ? 'fila-poco' : 'fila-ok') }}"
                    data-categoria="{{ $producto['categoria'] }}" data-nombre="{{ strtolower($producto['nombre']) }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if(!empty($producto['imagen']))
                            <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-10 h-10 object-cover rounded-xl flex-shrink-0"/>
                            @else
                            <div class="w-10 h-10 bg-[#f3f3f4] rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-[#c4c5da] text-sm">image</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1.5">
                                <span class="dot {{ $producto['stock_total'] == 0 ? 'dot-agotado' : ($producto['stock_total'] < 10 ? 'dot-poco' : 'dot-ok') }}"></span>
                                <p class="font-bold text-sm uppercase">{{ $producto['nombre'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-[#f3f3f4] rounded-lg text-[#1a1c1c]">{{ ucfirst($producto['categoria']) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                            <span class="bg-[#f3f3f4] px-2 py-1 text-[10px] font-bold rounded-lg text-[#1a1c1c]">
                                {{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span>
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-black {{ $producto['stock_total'] < 10 ? 'text-red-600' : 'text-[#1a1c1c]' }}">{{ $producto['stock_total'] }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($producto['stock_total'] == 0)
                        <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-red-100 text-red-700 rounded-full uppercase">Sin stock</span>
                        @elseif($producto['stock_total'] < 10)
                        <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full uppercase">Poco stock</span>
                        @else
                        <span class="inline-block whitespace-nowrap text-[9px] font-black px-2 py-1 bg-green-100 text-green-700 rounded-full uppercase">En stock</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="abrirEditar({{ json_encode($producto) }})"
                                    class="px-3 py-2 border border-[#c4c5da]/40 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>Editar
                            </button>
                            @if($esBusiness && $producto['stock_total'] > 0)
                            <button onclick="abrirMover({{ $producto['id'] }}, '{{ addslashes($producto['nombre']) }}', {{ json_encode($producto['tallas'] ?? []) }})"
                                    class="px-3 py-2 bg-[#1737c8] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">local_shipping</span>Para entrega
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                @for($i=0;$i<5;$i++)
                <tr class="border-b border-[#c4c5da]/10">
                    <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] rounded w-32"></div></td>
                    <td class="px-6 py-4"><div class="h-6 bg-[#f3f3f4] rounded w-16"></div></td>
                    <td class="px-6 py-4"><div class="h-6 bg-[#f3f3f4] rounded w-40"></div></td>
                    <td class="px-6 py-4"><div class="h-4 bg-[#f3f3f4] rounded w-8 mx-auto"></div></td>
                    <td class="px-6 py-4"><div class="h-6 bg-[#f3f3f4] rounded w-16 mx-auto"></div></td>
                    <td class="px-6 py-4"><div class="h-8 bg-[#f3f3f4] rounded w-24 ml-auto"></div></td>
                </tr>
                @endfor
                @endforelse
            </tbody>
        </table>
    </div>

</main>

{{-- ── MODAL EDITAR STOCK ──────────────────────────────────── --}}
<div id="modal-editar" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarEditar()">
    <div class="bg-white rounded-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Inventario</p><h2 class="text-xl font-black tracking-tight text-[#1a1c1c]" id="modal-nombre">Editar stock</h2></div>
            <button onclick="cerrarEditar()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
        <form method="POST" action="{{ route('inventario.update') }}" class="px-6 py-6 space-y-5">
            @csrf @method('PUT')
            <input type="hidden" name="producto_id" id="modal-producto-id"/>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] block mb-3">Tallas y existencias</label>
                <div id="modal-tallas" class="space-y-3"></div>
            </div>
            <div class="bg-[#f3f3f4] rounded-xl px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Stock total</span>
                <span class="text-lg font-black text-[#1a1c1c]" id="modal-total">0</span>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">Guardar</button>
                <button type="button" onclick="cerrarEditar()" class="flex-1 border border-[#c4c5da]/40 py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#f3f3f4] transition-all">Cancelar</button>
            </div>
            <button type="button" onclick="abrirConfirmarEliminar()"
                    class="w-full border border-red-200 text-red-500 py-3 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">delete</span>Eliminar producto
            </button>
        </form>
    </div>
</div>

{{-- ── MODAL MOVER A PARA ENTREGA ──────────────────────────── --}}
<div id="modal-mover" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarMover()">
    <div class="bg-white rounded-2xl w-full max-w-sm" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Para Entrega</p>
                <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]" id="mover-titulo">—</h2>
            </div>
            <button onclick="cerrarMover()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
        <form method="POST" action="{{ route('entrega.mover') }}" class="px-6 py-6 space-y-4">
            @csrf
            <input type="hidden" name="inventory_id" id="mover-inv-id"/>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Talla</label>
                <select name="talla" id="mover-talla" onchange="actualizarMaxMover()"
                        class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"></select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cantidad a mover</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="cambiarCantMover(-1)" class="qty-btn">−</button>
                    <input type="number" name="cantidad" id="mover-cantidad" value="1" min="1"
                           class="flex-1 text-center border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                    <button type="button" onclick="cambiarCantMover(1)" class="qty-btn">+</button>
                </div>
                <p class="text-[9px] text-[#747688]">Disponible en local: <span id="mover-max" class="font-black text-[#1737c8]">0</span> pcs</p>
            </div>
            <div class="rounded-xl bg-[#1737c8]/5 border border-[#1737c8]/20 px-4 py-3 flex items-start gap-2">
                <span class="material-symbols-outlined text-[#1737c8] text-sm mt-0.5">info</span>
                <p class="text-[11px] text-[#1737c8]">El producto desaparecerá del catálogo público al moverlo.</p>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-[#1737c8] text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2"
                        style="box-shadow:0 4px 16px rgba(23,55,200,.25)">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>Mover
                </button>
                <button type="button" onclick="cerrarMover()"
                        class="px-5 border border-[#c4c5da]/40 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all text-[#747688]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL NUEVO PRODUCTO ────────────────────────────────── --}}
<div id="modal-nuevo-producto" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalProducto()">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Inventario</p><h2 class="text-xl font-black tracking-tight text-[#1a1c1c]">Nuevo producto</h2></div>
            <button onclick="cerrarModalProducto()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
        <form method="POST" action="{{ route('productos.store') }}" id="form-nuevo-producto" class="px-6 py-6 space-y-5" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Imagen <span class="normal-case font-normal text-[#c4c5da]">(opcional)</span></label>
                <div id="zona-imagen" onclick="document.getElementById('input-imagen').click()"
                     class="border-2 border-dashed border-[#c4c5da]/40 rounded-2xl hover:border-[#1737c8] transition-colors cursor-pointer flex flex-col items-center justify-center py-8 gap-2 relative bg-[#f9f9f9] overflow-hidden">
                    <img id="preview-imagen" src="" alt="" class="hidden absolute inset-0 w-full h-full object-cover"/>
                    <div id="zona-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                        <span class="material-symbols-outlined text-3xl text-[#c4c5da]">add_photo_alternate</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Haz clic para subir imagen</p>
                        <p class="text-[9px] text-[#c4c5da]">JPG, PNG o WEBP · Máx. 2MB</p>
                    </div>
                    <input type="file" id="input-imagen" name="imagen" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previsualizarImagen(this)"/>
                </div>
                <button type="button" id="btn-quitar-imagen" onclick="quitarImagen()" class="hidden text-[9px] font-black uppercase tracking-widest text-red-500 hover:opacity-70 text-left">✕ Quitar imagen</button>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre</label>
                <input type="text" name="nombre" id="input-nombre-producto" placeholder="Ej. Tenis Nike Air Max" oninput="verificarDuplicado(this.value)"
                       class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                <p id="alerta-duplicado" class="text-[10px] text-yellow-600 font-black uppercase tracking-widest hidden">⚠ Ya existe — se agregarán piezas al stock actual</p>
                <input type="hidden" name="producto_existente" id="producto-existente" value="0"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Precio de venta</label>
                    <input type="number" name="precio" placeholder="0.00" step="0.01" class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">
                        Costo proveedor
                        @if(!$esBusiness)<span class="text-[9px] font-black bg-[#1737c8] text-white px-1.5 py-0.5 rounded-full ml-1">Business</span>@endif
                    </label>
                    <input type="number" name="costo" placeholder="0.00" step="0.01" {{ !$esBusiness ? 'disabled' : '' }}
                           class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] {{ !$esBusiness ? 'opacity-40 cursor-not-allowed' : '' }}"/>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Categoría</label>
                <select name="categoria" class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]">
                    <option value="ropa">Ropa</option>
                    <option value="calzado">Calzado</option>
                    <option value="accesorios">Accesorios</option>
                </select>
            </div>
            <div class="flex flex-col gap-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tallas y existencias</label>
                <div id="tallas-container" class="space-y-2">
                    <div class="flex gap-2 items-center talla-fila">
                        <input type="text" name="tallas[]" placeholder="Talla (S, M, 42…)" class="flex-1 border border-[#c4c5da]/40 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <input type="number" name="cantidades[]" placeholder="Qty" min="0" class="w-20 border border-[#c4c5da]/40 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <button type="button" onclick="eliminarTalla(this)" class="p-2 hover:bg-[#f3f3f4] rounded-xl text-[#747688]"><span class="material-symbols-outlined text-sm">delete</span></button>
                    </div>
                </div>
                <button type="button" onclick="agregarTalla()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-[#1737c8] hover:opacity-70 transition-opacity">
                    <span class="material-symbols-outlined text-sm">add</span>Agregar talla
                </button>
            </div>
            <div class="flex gap-3 pt-4 border-t border-[#c4c5da]/20">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">Guardar producto</button>
                <button type="button" onclick="cerrarModalProducto()" class="flex-1 border border-[#c4c5da]/40 py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#f3f3f4] transition-all">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL CONFIRMAR ELIMINAR ────────────────────────────── --}}
<div id="modal-confirmar-eliminar" class="fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl w-full max-w-sm">
        <div class="px-6 py-5 border-b border-[#c4c5da]/20">
            <p class="text-[10px] font-bold uppercase tracking-widest text-red-500 mb-0.5">Confirmar</p>
            <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]">¿Eliminar producto?</h2>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-[#5e5e5e] mb-1">Vas a eliminar:</p>
            <p class="font-black text-lg uppercase tracking-tight mb-3 text-[#1a1c1c]" id="confirmar-nombre-producto">—</p>
            <p class="text-xs text-[#747688]">Esta acción no se puede deshacer.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <form id="form-eliminar-producto" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all">Eliminar</button>
            </form>
            <button type="button" onclick="cerrarConfirmarEliminar()" class="flex-1 border border-[#c4c5da]/40 py-3 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#f3f3f4] transition-all">Cancelar</button>
        </div>
    </div>
</div>

@include('partials._sidebar')

<script>
// ── EDITAR ────────────────────────────────────────────────────
function abrirEditar(producto) {
    document.getElementById('modal-nombre').textContent     = producto.nombre;
    document.getElementById('modal-producto-id').value      = producto.id;
    const tallasEl = document.getElementById('modal-tallas');
    tallasEl.innerHTML = '';
    Object.entries(producto.tallas ?? {}).forEach(([talla, cantidad]) => {
        const fila = document.createElement('div');
        fila.className = 'flex gap-3 items-center';
        fila.innerHTML = `
            <div class="flex-1 border border-[#c4c5da]/40 rounded-xl px-3 py-2 text-sm font-bold bg-[#f9f9f9] text-[#747688] uppercase">${talla}</div>
            <input type="number" name="tallas[${talla}]" value="${cantidad}" min="0" oninput="calcularTotal()"
                   class="w-24 border border-[#c4c5da]/40 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] font-bold"/>`;
        tallasEl.appendChild(fila);
    });
    calcularTotal();
    document.getElementById('modal-editar').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function calcularTotal() {
    let t = 0;
    document.querySelectorAll('#modal-tallas input[type="number"]').forEach(i => t += parseInt(i.value)||0);
    document.getElementById('modal-total').textContent = t;
}
function cerrarEditar() {
    document.getElementById('modal-editar').classList.remove('activo');
    document.body.style.overflow = '';
}

// ── MOVER A PARA ENTREGA ──────────────────────────────────────
var moverTallas = {};
var moverMax = 1;

function abrirMover(productoId, nombre, tallas) {
    moverTallas = tallas;
    document.getElementById('mover-titulo').textContent = nombre;
    const sel = document.getElementById('mover-talla');
    sel.innerHTML = '';
    Object.entries(tallas).forEach(([talla, cantidad]) => {
        if (cantidad > 0) {
            const opt = document.createElement('option');
            opt.value = talla;
            opt.textContent = 'Talla ' + talla + ' — ' + cantidad + ' pcs';
            opt.dataset.max = cantidad;
            sel.appendChild(opt);
        }
    });
    actualizarMaxMover();

    // El inventory_id real lo necesitamos — buscamos por producto_id y talla
    // Lo pasamos via data attribute en el select
    document.getElementById('mover-inv-id').value = productoId;
    document.getElementById('modal-mover').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function actualizarMaxMover() {
    const sel = document.getElementById('mover-talla');
    const opt = sel.options[sel.selectedIndex];
    moverMax = opt ? parseInt(opt.dataset.max) : 1;
    document.getElementById('mover-max').textContent = moverMax;
    document.getElementById('mover-cantidad').value = 1;
    document.getElementById('mover-cantidad').max = moverMax;
}
function cambiarCantMover(d) {
    const el = document.getElementById('mover-cantidad');
    el.value = Math.max(1, Math.min(moverMax, parseInt(el.value||1) + d));
}
function cerrarMover() {
    document.getElementById('modal-mover').classList.remove('activo');
    document.body.style.overflow = '';
}

// ── ELIMINAR ──────────────────────────────────────────────────
function abrirConfirmarEliminar() {
    const id     = document.getElementById('modal-producto-id').value;
    const nombre = document.getElementById('modal-nombre').textContent;
    document.getElementById('confirmar-nombre-producto').textContent = nombre;
    document.getElementById('form-eliminar-producto').action = '/productos/' + id;
    document.getElementById('modal-confirmar-eliminar').classList.add('activo');
}
function cerrarConfirmarEliminar() {
    document.getElementById('modal-confirmar-eliminar').classList.remove('activo');
}

// ── FILTROS ───────────────────────────────────────────────────
function filtrar(categoria, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    // Tabla
    document.querySelectorAll('.fila-producto').forEach(f => {
        f.style.display = (categoria === 'todos' || f.dataset.categoria === categoria) ? '' : 'none';
    });
    // Cards móvil
    document.querySelectorAll('.prod-card').forEach(f => {
        f.style.display = (categoria === 'todos' || f.dataset.categoria === categoria) ? '' : 'none';
    });
}
function buscar(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.fila-producto, .prod-card').forEach(f => {
        f.style.display = (f.dataset.nombre || '').includes(q) ? '' : 'none';
    });
}

// ── NUEVO PRODUCTO ────────────────────────────────────────────
function abrirModalProducto() {
    document.getElementById('modal-nuevo-producto').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalProducto() {
    document.getElementById('modal-nuevo-producto').classList.remove('activo');
    document.body.style.overflow = '';
    document.getElementById('form-nuevo-producto').reset();
    document.getElementById('alerta-duplicado').classList.add('hidden');
    quitarImagen();
}
function verificarDuplicado(nombre) {
    const q = nombre.toLowerCase().trim();
    const existe = Array.from(document.querySelectorAll('.fila-producto,.prod-card')).some(f => f.dataset.nombre?.toLowerCase() === q);
    document.getElementById('alerta-duplicado').classList.toggle('hidden', !(existe && q.length > 0));
    document.getElementById('producto-existente').value = existe && q.length > 0 ? '1' : '0';
}
function agregarTalla() {
    const container = document.getElementById('tallas-container');
    const fila = document.createElement('div');
    fila.className = 'flex gap-2 items-center talla-fila';
    fila.innerHTML = `
        <input type="text" name="tallas[]" placeholder="Talla" class="flex-1 border border-[#c4c5da]/40 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
        <input type="number" name="cantidades[]" placeholder="Qty" min="0" class="w-20 border border-[#c4c5da]/40 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
        <button type="button" onclick="eliminarTalla(this)" class="p-2 hover:bg-[#f3f3f4] rounded-xl text-[#747688]"><span class="material-symbols-outlined text-sm">delete</span></button>`;
    container.appendChild(fila);
}
function eliminarTalla(btn) {
    if (document.querySelectorAll('.talla-fila').length > 1) btn.closest('.talla-fila').remove();
}
function previsualizarImagen(input) {
    const file = input.files[0];
    if (!file) return;
    if (file.size > 2*1024*1024) { alert('Máx. 2MB.'); input.value=''; return; }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview-imagen').src = e.target.result;
        document.getElementById('preview-imagen').classList.remove('hidden');
        document.getElementById('zona-placeholder').classList.add('hidden');
        document.getElementById('btn-quitar-imagen').classList.remove('hidden');
        document.getElementById('zona-imagen').classList.add('con-imagen');
    };
    reader.readAsDataURL(file);
}
function quitarImagen() {
    document.getElementById('input-imagen').value = '';
    document.getElementById('preview-imagen').src = '';
    document.getElementById('preview-imagen').classList.add('hidden');
    document.getElementById('zona-placeholder').classList.remove('hidden');
    document.getElementById('btn-quitar-imagen').classList.add('hidden');
    document.getElementById('zona-imagen').classList.remove('con-imagen');
}
</script>
</body>
</html>