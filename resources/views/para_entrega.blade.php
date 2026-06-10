<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - {{ isset($almacen) ? $almacen->nombre : 'Para Entrega' }}</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family:'Inter',sans-serif; background:#f9f9f9; color:#1a1c1c; min-height:100dvh; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }

    .modal-wrap { display:none; position:fixed; inset:0; z-index:50; align-items:center; justify-content:center; background:rgba(0,0,0,0.5); backdrop-filter:blur(6px); padding:16px; }
    .modal-wrap.activo { display:flex; }

    @keyframes fadeUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    @keyframes popIn   { 0%{opacity:0;transform:scale(0.92)} 60%{transform:scale(1.02)} 100%{opacity:1;transform:scale(1)} }

    .anim-fade   { opacity:0; animation:fadeUp 0.45s ease forwards; }
    .anim-fade-1 { animation-delay:.06s; }
    .anim-fade-2 { animation-delay:.12s; }
    .anim-fade-3 { animation-delay:.18s; }

    .prod-card { opacity:0; animation:popIn 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; transition:transform .2s ease, box-shadow .2s ease; }
    .prod-card:nth-child(1){animation-delay:.05s} .prod-card:nth-child(2){animation-delay:.10s}
    .prod-card:nth-child(3){animation-delay:.15s} .prod-card:nth-child(4){animation-delay:.20s}
    .prod-card:nth-child(5){animation-delay:.25s} .prod-card:nth-child(6){animation-delay:.30s}
    .prod-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(23,55,200,.10); }

    .modal-box { animation:popIn .3s cubic-bezier(0.34,1.56,0.64,1) forwards; }

    .qty-btn { width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1.5px solid rgba(196,197,218,.4);transition:all .15s ease;cursor:pointer;font-size:18px;font-weight:700;color:#747688;background:transparent; }
    .qty-btn:hover { border-color:#1737c8;color:#1737c8;background:rgba(23,55,200,.06); }

    [data-theme="dark"] body                { background:#0f1012!important;color:#f3f3f4!important; }
    [data-theme="dark"] .card               { background:#1e2022!important;border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .modal-box          { background:#1e2022!important; }
    [data-theme="dark"] .modal-sep          { border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .input-f            { background:#141618!important;color:#f3f3f4!important;border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] .txt-gray           { color:#9496a8!important; }
    [data-theme="dark"] .txt-dark           { color:#f3f3f4!important; }
    [data-theme="dark"] .bg-light           { background:#141618!important; }
    [data-theme="dark"] .qty-btn            { border-color:rgba(255,255,255,.1);color:#9496a8; }
    [data-theme="dark"] .qty-btn:hover      { border-color:#1737c8;color:#1737c8; }
    [data-theme="dark"] .btn-cancel         { border-color:rgba(255,255,255,.1)!important;color:#9496a8!important; }
    [data-theme="dark"] .btn-cancel:hover   { background:#141618!important; }
    [data-theme="dark"] .metodo-btn         { border-color:rgba(255,255,255,.1)!important;color:#9496a8!important; }
    [data-theme="dark"] .stat-card          { background:#1e2022!important;border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .empty-box          { background:#1e2022!important;border-color:rgba(255,255,255,.08)!important; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

@php
    $esVirtual = isset($almacen) && $almacen->tipo === 'virtual';
    $esFisico  = isset($almacen) && $almacen->tipo === 'fisico';
    $nombreAlmacen = $almacen->nombre ?? 'Para Entrega';
@endphp

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="anim-fade flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest txt-gray text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <a href="{{ route('almacenes.index') }}" class="hover:text-[#1737c8] transition-colors">Almacenes</a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="txt-dark text-[#1a1c1c]">{{ $nombreAlmacen }}</span>
    </div>
</div>

<main class="pb-28 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="anim-fade anim-fade-1 flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8]">Business</p>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#c4c5da]">·</span>
                <span class="text-[10px] font-black uppercase tracking-widest {{ $esVirtual ? 'text-amber-500' : 'text-[#1737c8]' }}">
                    {{ $esVirtual ? 'En Tránsito' : 'Inventario Local' }}
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight txt-dark text-[#1a1c1c]">{{ $nombreAlmacen }}</h1>
            <p class="text-sm txt-gray text-[#747688] mt-1">
                {{ $esVirtual ? 'Productos listos para entregar al cliente' : 'Inventario disponible en este almacén' }}
            </p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('almacenes.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl border border-[#c4c5da]/40 hover:bg-[#f3f3f4] transition-all txt-dark text-[#1a1c1c] self-start">
                <span class="material-symbols-outlined text-sm">store</span>
                Ver almacenes
            </a>
            @if($esFisico)
            <a href="{{ route('inventario') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl bg-[#1737c8] text-white hover:opacity-90 transition-all self-start">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                Agregar producto
            </a>
            @endif
        </div>
    </div>

    {{-- STATS --}}
    @php
        $totalPiezas    = $enTransito->sum($esVirtual ? 'en_transito' : 'stock');
        $totalProductos = $enTransito->count();
        $valorEst       = $enTransito->sum(fn($i) => ($esVirtual ? $i->en_transito : $i->stock) * $i->precio_decimal);
    @endphp
    <div class="anim-fade anim-fade-2 grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="stat-card card rounded-2xl bg-white border border-[#c4c5da]/20 p-4">
            <p class="text-[9px] font-black uppercase tracking-widest txt-gray text-[#747688] mb-1">
                {{ $esVirtual ? 'Piezas en tránsito' : 'Piezas disponibles' }}
            </p>
            <p class="text-2xl font-black txt-dark text-[#1a1c1c]">{{ $totalPiezas }}</p>
        </div>
        <div class="stat-card card rounded-2xl bg-white border border-[#c4c5da]/20 p-4">
            <p class="text-[9px] font-black uppercase tracking-widest txt-gray text-[#747688] mb-1">Productos distintos</p>
            <p class="text-2xl font-black txt-dark text-[#1a1c1c]">{{ $totalProductos }}</p>
        </div>
        <div class="stat-card card rounded-2xl bg-[#1737c8] p-4 col-span-2 md:col-span-1">
            <p class="text-[9px] font-black uppercase tracking-widest text-white/60 mb-1">Valor estimado</p>
            <p class="text-2xl font-black text-white">${{ number_format($valorEst, 2) }}</p>
        </div>
    </div>

    {{-- CONTENIDO --}}
    @if($enTransito->isEmpty())
    <div class="empty-box card rounded-2xl bg-white border border-[#c4c5da]/20 px-6 py-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-[#f3f3f4] bg-light flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl text-[#c4c5da]">{{ $esVirtual ? 'local_shipping' : 'inventory_2' }}</span>
        </div>
        <p class="text-sm font-bold txt-dark text-[#1a1c1c] mb-1">
            {{ $esVirtual ? 'Sin productos en tránsito' : 'Sin productos en este almacén' }}
        </p>
        <p class="text-xs txt-gray text-[#747688] mb-5">
            {{ $esVirtual ? 'Mueve productos desde el inventario para prepararlos' : 'Agrega productos desde el inventario' }}
        </p>
        <a href="{{ route('inventario') }}"
           class="inline-flex items-center gap-2 bg-[#1737c8] text-white px-5 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm">{{ $esVirtual ? 'add_circle' : 'inventory_2' }}</span>
            Ir al inventario
        </a>
    </div>

    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($enTransito as $inv)
        @php $cantidad = $esVirtual ? $inv->en_transito : $inv->stock; @endphp
        <div class="prod-card card rounded-2xl bg-white border border-[#c4c5da]/20 p-5 flex flex-col gap-4">

            {{-- Info producto --}}
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-light bg-[#f3f3f4] shrink-0 overflow-hidden">
                    @if($inv->product && $inv->product->imagen)
                    <img src="{{ asset('storage/'.$inv->product->imagen) }}" class="w-full h-full object-cover"/>
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#c4c5da]">image</span>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-sm uppercase truncate txt-dark text-[#1a1c1c]">{{ $inv->product->name ?? '—' }}</p>
                    <p class="text-[10px] text-[#1737c8] font-black uppercase tracking-wider mt-0.5">Talla {{ $inv->talla }}</p>
                    <p class="text-[10px] txt-gray text-[#747688] mt-0.5">${{ number_format($inv->precio_decimal, 2) }} c/u</p>
                </div>
                <div class="shrink-0">
                    <div class="inline-flex items-center gap-1 {{ $esVirtual ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'bg-green-100 text-green-700' }} px-2.5 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[12px]">{{ $esVirtual ? 'local_shipping' : 'inventory_2' }}</span>
                        <span class="text-[10px] font-black">{{ $cantidad }} pcs</span>
                    </div>
                </div>
            </div>

            {{-- Barra visual --}}
            @if($esVirtual)
            @php $total = $inv->stock + $inv->en_transito; $pct = $total > 0 ? round(($inv->en_transito / $total) * 100) : 100; @endphp
            <div>
                <div class="bg-light bg-[#f3f3f4] rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full bg-[#1737c8] transition-all duration-700" style="width:{{ $pct }}%"></div>
                </div>
                <p class="text-[9px] txt-gray text-[#747688] mt-1.5">{{ $inv->en_transito }} en tránsito · {{ $inv->stock }} en local</p>
            </div>
            @else
            <div>
                <div class="bg-light bg-[#f3f3f4] rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full bg-green-500 transition-all duration-700" style="width:100%"></div>
                </div>
                <p class="text-[9px] txt-gray text-[#747688] mt-1.5">{{ $inv->stock }} disponibles en este almacén</p>
            </div>
            @endif

            {{-- Acciones según tipo de almacén --}}
            <div class="flex gap-2 mt-auto">
                @if($esVirtual)
                {{-- Virtual: Vender o Regresar --}}
                <button onclick="abrirModalVenta({{ $inv->id }}, '{{ addslashes($inv->product->name ?? '') }}', '{{ $inv->talla }}', {{ $inv->en_transito }}, {{ $inv->precio_decimal }})"
                        class="flex-1 flex items-center justify-center gap-1.5 bg-green-500 text-white py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all"
                        style="box-shadow:0 4px 12px rgba(34,197,94,.25)">
                    <span class="material-symbols-outlined text-[13px]">point_of_sale</span>Vender
                </button>
                <button onclick="abrirModalRegresar({{ $inv->id }}, '{{ addslashes($inv->product->name ?? '') }}', '{{ $inv->talla }}', {{ $inv->en_transito }})"
                        class="flex-1 flex items-center justify-center gap-1.5 border border-[#c4c5da]/40 txt-dark text-[#1a1c1c] py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#f3f3f4] transition-all">
                    <span class="material-symbols-outlined text-[13px]">undo</span>Regresar
                </button>
                @else
                {{-- Físico: Mover a Para Entrega --}}
                <button onclick="abrirModalMover({{ $inv->id }}, '{{ addslashes($inv->product->name ?? '') }}', '{{ $inv->talla }}', {{ $inv->stock }})"
                        class="flex-1 flex items-center justify-center gap-1.5 bg-[#1737c8] text-white py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all"
                        style="box-shadow:0 4px 12px rgba(23,55,200,.25)">
                    <span class="material-symbols-outlined text-[13px]">local_shipping</span>Para entrega
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

</main>

{{-- MODAL VENTA --}}
<div id="modal-venta" class="modal-wrap" onclick="if(event.target===this) cerrarModalVenta()">
    <div class="modal-box card bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="modal-sep flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-green-600 mb-0.5">Confirmar venta</p>
                <h2 class="text-xl font-black txt-dark text-[#1a1c1c]" id="venta-titulo">—</h2>
            </div>
            <button onclick="cerrarModalVenta()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('entrega.venta') }}" class="px-6 py-6 space-y-4">
            @csrf
            <input type="hidden" name="inventory_id" id="venta-inv-id"/>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Cantidad</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="cambiarCantVenta(-1)" class="qty-btn">−</button>
                    <input type="number" name="cantidad" id="venta-cantidad" value="1" min="1"
                           class="input-f flex-1 text-center border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"
                           oninput="actualizarTotal()"/>
                    <button type="button" onclick="cambiarCantVenta(1)" class="qty-btn">+</button>
                </div>
                <p class="text-[9px] txt-gray text-[#747688]">Máximo: <span id="venta-max" class="font-black text-[#1737c8]">0</span> piezas</p>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Precio por pieza</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black txt-gray text-[#747688]">$</span>
                    <input type="number" name="precio" id="venta-precio" step="0.01" min="0"
                           class="input-f w-full border border-[#c4c5da]/40 rounded-xl pl-8 pr-4 py-3 text-sm font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"
                           oninput="actualizarTotal()"/>
                </div>
            </div>
            <div class="bg-light bg-[#f3f3f4] rounded-xl px-4 py-3 flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Total</p>
                <p class="text-lg font-black text-[#1737c8]" id="venta-total">$0.00</p>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Método de pago</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['efectivo' => '💵', 'tarjeta' => '💳', 'transferencia' => '📲'] as $met => $emoji)
                    <button type="button" onclick="selMetodo('{{ $met }}')" id="met-{{ $met }}"
                            class="metodo-btn py-3 border-2 border-[#c4c5da]/30 rounded-xl text-[9px] font-black uppercase tracking-widest flex flex-col items-center gap-1 transition-all text-[#747688] hover:border-[#1737c8]">
                        <span class="text-base">{{ $emoji }}</span>{{ ucfirst($met) }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="metodo_pago" id="metodo-hidden" value="efectivo"/>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-green-500 text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2"
                        style="box-shadow:0 4px 16px rgba(34,197,94,.3)">
                    <span class="material-symbols-outlined text-sm">check_circle</span>Confirmar venta
                </button>
                <button type="button" onclick="cerrarModalVenta()"
                        class="btn-cancel px-5 border border-[#c4c5da]/40 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all txt-gray text-[#747688]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL REGRESAR --}}
<div id="modal-regresar" class="modal-wrap" onclick="if(event.target===this) cerrarModalRegresar()">
    <div class="modal-box card bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="modal-sep flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-amber-600 mb-0.5">Regresar a local</p>
                <h2 class="text-xl font-black txt-dark text-[#1a1c1c]" id="regresar-titulo">—</h2>
            </div>
            <button onclick="cerrarModalRegresar()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('entrega.regresar') }}" class="px-6 py-6 space-y-4">
            @csrf
            <input type="hidden" name="inventory_id" id="regresar-inv-id"/>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Cantidad a regresar</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="cambiarCantReg(-1)" class="qty-btn">−</button>
                    <input type="number" name="cantidad" id="regresar-cantidad" value="1" min="1"
                           class="input-f flex-1 text-center border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                    <button type="button" onclick="cambiarCantReg(1)" class="qty-btn">+</button>
                </div>
                <p class="text-[9px] txt-gray text-[#747688]">Máximo: <span id="regresar-max" class="font-black text-[#1737c8]">0</span> piezas</p>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-500 text-sm mt-0.5">info</span>
                <p class="text-[11px] text-amber-700">Las piezas regresarán al local y volverán a aparecer en el catálogo.</p>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-amber-500 text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">undo</span>Regresar
                </button>
                <button type="button" onclick="cerrarModalRegresar()"
                        class="btn-cancel px-5 border border-[#c4c5da]/40 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all txt-gray text-[#747688]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL MOVER A PARA ENTREGA (solo físico) --}}
<div id="modal-mover" class="modal-wrap" onclick="if(event.target===this) cerrarModalMover()">
    <div class="modal-box card bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="modal-sep flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Para Entrega</p>
                <h2 class="text-xl font-black txt-dark text-[#1a1c1c]" id="mover-titulo">—</h2>
            </div>
            <button onclick="cerrarModalMover()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('entrega.mover') }}" class="px-6 py-6 space-y-4">
            @csrf
            <input type="hidden" name="inventory_id" id="mover-inv-id"/>
            <input type="hidden" name="talla" id="mover-talla-hidden"/>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest txt-gray text-[#747688]">Cantidad a mover</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="cambiarCantMover(-1)" class="qty-btn">−</button>
                    <input type="number" name="cantidad" id="mover-cantidad" value="1" min="1"
                           class="input-f flex-1 text-center border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                    <button type="button" onclick="cambiarCantMover(1)" class="qty-btn">+</button>
                </div>
                <p class="text-[9px] txt-gray text-[#747688]">Disponible: <span id="mover-max" class="font-black text-[#1737c8]">0</span> pcs</p>
            </div>
            <div class="rounded-xl bg-[#1737c8]/5 border border-[#1737c8]/20 px-4 py-3 flex items-start gap-2">
                <span class="material-symbols-outlined text-[#1737c8] text-sm mt-0.5">info</span>
                <p class="text-[11px] text-[#1737c8]">El producto desaparecerá del catálogo al moverlo.</p>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-[#1737c8] text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2"
                        style="box-shadow:0 4px 16px rgba(23,55,200,.25)">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>Mover
                </button>
                <button type="button" onclick="cerrarModalMover()"
                        class="btn-cancel px-5 border border-[#c4c5da]/40 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all txt-gray text-[#747688]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials._sidebar')

<script>
var ventaMax = 1, regMax = 1, moverMax = 1;

// ── MODAL VENTA ───────────────────────────────────────────────
function abrirModalVenta(id, nombre, talla, transito, precio) {
    ventaMax = transito;
    document.getElementById('venta-titulo').textContent  = nombre + ' — T.' + talla;
    document.getElementById('venta-inv-id').value        = id;
    document.getElementById('venta-cantidad').value      = 1;
    document.getElementById('venta-precio').value        = precio;
    document.getElementById('venta-max').textContent     = transito;
    actualizarTotal();
    selMetodo('efectivo');
    document.getElementById('modal-venta').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalVenta() {
    document.getElementById('modal-venta').classList.remove('activo');
    document.body.style.overflow = '';
}
function cambiarCantVenta(d) {
    var el = document.getElementById('venta-cantidad');
    el.value = Math.max(1, Math.min(ventaMax, parseInt(el.value||1) + d));
    actualizarTotal();
}
function actualizarTotal() {
    var q = parseFloat(document.getElementById('venta-cantidad').value)||0;
    var p = parseFloat(document.getElementById('venta-precio').value)||0;
    document.getElementById('venta-total').textContent = '$' + (q*p).toLocaleString('es-MX',{minimumFractionDigits:2});
}
function selMetodo(m) {
    ['efectivo','tarjeta','transferencia'].forEach(function(x) {
        var b = document.getElementById('met-'+x);
        if (!b) return;
        b.classList.remove('border-[#1737c8]','bg-[#1737c8]','text-white');
        b.classList.add('border-[#c4c5da]/30','text-[#747688]');
    });
    var sel = document.getElementById('met-'+m);
    if (sel) {
        sel.classList.remove('border-[#c4c5da]/30','text-[#747688]');
        sel.classList.add('border-[#1737c8]','bg-[#1737c8]','text-white');
    }
    document.getElementById('metodo-hidden').value = m;
}

// ── MODAL REGRESAR ────────────────────────────────────────────
function abrirModalRegresar(id, nombre, talla, transito) {
    regMax = transito;
    document.getElementById('regresar-titulo').textContent = nombre + ' — T.' + talla;
    document.getElementById('regresar-inv-id').value       = id;
    document.getElementById('regresar-cantidad').value     = 1;
    document.getElementById('regresar-max').textContent    = transito;
    document.getElementById('modal-regresar').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalRegresar() {
    document.getElementById('modal-regresar').classList.remove('activo');
    document.body.style.overflow = '';
}
function cambiarCantReg(d) {
    var el = document.getElementById('regresar-cantidad');
    el.value = Math.max(1, Math.min(regMax, parseInt(el.value||1) + d));
}

// ── MODAL MOVER ───────────────────────────────────────────────
function abrirModalMover(id, nombre, talla, stock) {
    moverMax = stock;
    document.getElementById('mover-titulo').textContent      = nombre + ' — T.' + talla;
    document.getElementById('mover-inv-id').value            = id;
    document.getElementById('mover-talla-hidden').value      = talla;
    document.getElementById('mover-cantidad').value          = 1;
    document.getElementById('mover-max').textContent         = stock;
    document.getElementById('modal-mover').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalMover() {
    document.getElementById('modal-mover').classList.remove('activo');
    document.body.style.overflow = '';
}
function cambiarCantMover(d) {
    var el = document.getElementById('mover-cantidad');
    el.value = Math.max(1, Math.min(moverMax, parseInt(el.value||1) + d));
}

// ── FLASH ─────────────────────────────────────────────────────
@php $flashS = session('success'); $flashE = session('error'); @endphp
@if($flashS)
document.addEventListener('DOMContentLoaded', function() { showToast('¡Listo!', @json($flashS), 'green', 5000); });
@endif
@if($flashE)
document.addEventListener('DOMContentLoaded', function() { showToast('Error', @json($flashE), 'red', 5000); });
@endif
</script>
</body>
</html>