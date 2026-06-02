<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Ventas</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: rgba(196,197,218,0.3); }
    .receipt-texture { background-image: radial-gradient(rgba(26,28,28,0.03) 1px, transparent 0); background-size: 8px 8px; }
    .venta-card { transition: all 0.2s; cursor: pointer; }
    .venta-card:hover { background-color: #f3f3f4; }
    .venta-card.selected { background-color: rgba(23,55,200,0.04); box-shadow: inset 0 0 0 1.5px rgba(23,55,200,0.25); }
    #modal-nueva-venta { display: none; }
    #modal-nueva-venta.activo { display: flex; }
    #modal-ticket { display: none; }
    #modal-ticket.activo { display: flex; }
    #modal-efectivo { display: none; }
    #modal-efectivo.activo { display: flex; }
    .metodo-btn.activo { background: #1a1c1c; color: #ffffff; border-color: #1a1c1c; }
    .toggle-mayoreo { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-mayoreo input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #c4c5da; transition: .3s; border-radius: 24px; }
    .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
    input:checked + .toggle-slider { background-color: #f59e0b; }
    input:checked + .toggle-slider:before { transform: translateX(20px); }
    .pro-lock { cursor: pointer; }
    .pro-lock:hover .pro-badge { opacity: 100; }
    #btn-voz { transition: all 0.2s; }
    #btn-voz.escuchando { background: #dc2626 !important; animation: pulse-voz 1s infinite; }
    @keyframes pulse-voz { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.8; transform:scale(1.05); } }
    @media print {
        body > *:not(#print-ticket) { display: none !important; }
        #print-ticket { display: block !important; position: fixed; inset: 0; background: white; padding: 24px; z-index: 9999; }
    }
</style>
</head>
<body class="bg-[#f9f9f9] text-[#1a1c1c]">

@include('partials._nav')

@php 
$esPro = ($plan ?? 'gratis') === 'pro' || ($plan ?? 'gratis') === 'business'; 
$lealtadActivo = $lealtadActivo ?? false;
@endphp

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Ventas</span>
    </div>
</div>

@if(session('success'))
<div class="mx-4 md:mx-6 mb-2 bg-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('ticket_metodo'))
<div class="mx-4 md:mx-6 mb-4 bg-blue-50 border border-blue-200 px-4 py-4">
    <p class="font-black uppercase tracking-widest text-[#1737c8] text-[10px] mb-3">Resumen del último pago</p>
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
</div>
@endif

@if(session('error'))
<div class="mx-4 md:mx-6 mb-4 bg-red-100 text-red-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
</div>
@endif
<main class="pb-32 md:pb-12 px-4 md:px-6 max-w-[1600px] mx-auto">
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Ventas hoy</p>
            <p class="text-xl font-black text-[#1737c8]">{{ count($ventas ?? []) }}</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Total</p>
            <p class="text-xl font-black text-[#1a1c1c]">${{ number_format(collect($ventas ?? [])->sum('total'), 0) }}</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Promedio</p>
            <p class="text-xl font-black text-[#1a1c1c]">
                ${{ count($ventas ?? []) > 0 ? number_format(collect($ventas ?? [])->sum('total') / count($ventas), 0) : '0' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <section class="lg:col-span-7 xl:col-span-8">
            <div class="flex items-center justify-between mb-4 gap-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight">Ventas del día</h1>
                    <p class="text-[10px] text-[#747688] uppercase tracking-widest font-bold mt-0.5">{{ now()->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative hidden md:block">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                        <input id="buscador" class="pl-9 pr-4 py-2 bg-white border border-[#c4c5da]/30 focus:border-[#1737c8] focus:outline-none text-sm w-48" placeholder="Buscar..." oninput="filtrarVentas(this.value)"/>
                    </div>
                    <button onclick="abrirModalVenta()" class="bg-[#1737c8] text-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-1.5 shrink-0">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span class="hidden sm:inline">Nueva venta</span>
                        <span class="sm:hidden">Nueva</span>
                    </button>
                </div>
            </div>
            <div class="relative md:hidden mb-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                <input class="pl-9 pr-4 py-2.5 bg-white border border-[#c4c5da]/30 focus:border-[#1737c8] focus:outline-none text-sm w-full" placeholder="Buscar..." oninput="filtrarVentas(this.value)"/>
            </div>
            <div id="lista-ventas" class="space-y-2">
                @forelse($ventas ?? [] as $venta)
                <div class="venta-card lg:hidden bg-white border border-[#c4c5da]/15 p-4"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVentaMovil({{ json_encode($venta) }}, this)">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                                <span class="text-[#1737c8] font-black text-xs">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm truncate">{{ $venta['cliente'] }}</p>
                                <p class="text-[10px] text-[#747688] uppercase tracking-wider">#ID-{{ $venta['id'] }} · {{ $venta['fecha'] ?? '' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-black text-[#1737c8] text-sm">${{ number_format($venta['total'], 2) }}</p>
                            <div class="flex gap-1 justify-end mt-0.5">
                                <span class="text-[9px] font-bold px-2 py-0.5 bg-green-100 text-green-700 uppercase">Completada</span>
                                @if(($venta['tipo_venta'] ?? 'menudeo') === 'mayoreo')
                                <span class="text-[9px] font-bold px-2 py-0.5 bg-amber-100 text-amber-700 uppercase">Mayoreo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3 pt-3 border-t border-[#c4c5da]/10">
                        <button onclick="event.stopPropagation(); abrirTicketModal({{ json_encode($venta) }})"
                                class="flex-1 py-2 bg-[#1737c8] text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">receipt</span>Ver ticket
                        </button>
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})"
                                class="px-3 py-2 bg-[#1a1c1c] text-white hover:opacity-80 transition-all">
                            <span class="material-symbols-outlined text-sm">print</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarWhatsApp({{ json_encode($venta) }})"
                                class="px-3 py-2 bg-green-500 text-white hover:opacity-80 transition-all">
                            <span class="material-symbols-outlined text-sm">chat</span>
                        </button>
                    </div>
                </div>
                <div class="venta-card hidden lg:grid grid-cols-12 px-5 py-4 items-center bg-white border border-[#c4c5da]/15"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVenta({{ json_encode($venta) }}, this)">
                    <div class="col-span-4 flex items-center gap-3">
                        <div class="w-9 h-9 bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                            <span class="text-[#1737c8] font-black text-xs">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ $venta['cliente'] }}</p>
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider">#ID-{{ $venta['id'] }}</p>
                        </div>
                    </div>
                    <div class="col-span-2 text-right">
                        <span class="font-black text-sm text-[#1737c8]">${{ number_format($venta['total'], 2) }}</span>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-green-100 text-green-700">Completada</span>
                    </div>
                    <div class="col-span-1 text-center">
                        @if(($venta['tipo_venta'] ?? 'menudeo') === 'mayoreo')
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-amber-100 text-amber-700">Mayoreo</span>
                        @else
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-[#f3f3f4] text-[#747688]">Menudeo</span>
                        @endif
                    </div>
                    <div class="col-span-3 flex justify-end gap-1.5">
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})"
                                class="p-2 bg-[#1a1c1c] text-white hover:bg-[#1737c8] transition-colors">
                            <span class="material-symbols-outlined text-sm">print</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarWhatsApp({{ json_encode($venta) }})"
                                class="p-2 bg-green-500 text-white hover:opacity-80 transition-colors">
                            <span class="material-symbols-outlined text-sm">chat</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-[#c4c5da]/15 px-6 py-16 text-center">
                    <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">receipt_long</span>
                    <p class="text-sm font-bold text-[#747688]">Sin ventas registradas hoy</p>
                    <button onclick="abrirModalVenta()" class="mt-4 bg-[#1737c8] text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
                        Registrar primera venta
                    </button>
                </div>
                @endforelse
            </div>
        </section>

        <aside class="hidden lg:block lg:col-span-5 xl:col-span-4 lg:sticky lg:top-24 h-fit">
            <div class="bg-white border border-[#c4c5da]/20 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#c4c5da]/10 bg-[#f9f9f9]">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Ticket de venta</p>
                    <p class="text-sm font-bold text-[#1a1c1c] mt-0.5">Selecciona una venta para ver el ticket</p>
                </div>
                <div class="p-6 receipt-texture" id="ticket-contenido">
                    <div class="text-center mb-8">
                        <h3 class="font-black tracking-tighter text-xl text-[#1a1c1c]">{{ Str::limit(Auth::user()->store_name ?? 'MI TIENDA', 20) }}</h3>
                        <p class="text-[9px] text-[#747688] uppercase tracking-[0.3em] mt-1">Tienda deportiva</p>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-[#c4c5da]/40 pb-4 mb-5">
                        <div><p class="text-[9px] text-[#747688] uppercase tracking-wider">Transacción</p><p class="text-sm font-bold" id="ticket-id">—</p></div>
                        <div class="text-right"><p class="text-[9px] text-[#747688] uppercase tracking-wider">Fecha</p><p class="text-sm font-bold" id="ticket-fecha">—</p></div>
                    </div>
                    <div class="space-y-3 mb-6 min-h-[80px]" id="ticket-productos">
                        <p class="text-xs text-[#747688] text-center pt-4">Selecciona una venta</p>
                    </div>
                    <div class="border-t border-dashed border-[#c4c5da]/40 pt-4 space-y-1.5">
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>Subtotal</span><span id="ticket-subtotal">$0.00</span></div>
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>IVA (0%)</span><span>$0.00</span></div>
                        <div class="flex justify-between text-base font-black text-[#1a1c1c] pt-2"><span>TOTAL</span><span id="ticket-total" class="text-[#1737c8]">$0.00</span></div>
                        <div id="ticket-pago-section" class="hidden pt-3 mt-2 border-t border-dashed border-[#c4c5da]/40 space-y-1.5">
                            <div class="flex justify-between text-[10px] uppercase font-bold"><span class="text-[#747688]">Tipo venta</span><span id="ticket-tipo-display">—</span></div>
                            <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>Método</span><span id="ticket-metodo-display">—</span></div>
                            <div id="ticket-efectivo-section" class="hidden">
                                <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>Recibido</span><span id="ticket-recibido-display">$0.00</span></div>
                                <div class="flex justify-between text-[10px] font-black text-green-600 uppercase"><span>Cambio</span><span id="ticket-cambio-display">$0.00</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center">
                        <div class="flex justify-center gap-1 mb-3 opacity-20">
                            @foreach([1,0.5,1.5,1,0.5,1.5,0.5,2,1,0.5,1.5] as $w)
                            <div class="h-6 bg-[#1a1c1c]" style="width: {{ $w * 4 }}px"></div>
                            @endforeach
                        </div>
                        <p class="text-[9px] text-[#747688] uppercase tracking-widest italic">Gracias por tu compra</p>
                    </div>
                </div>
                <div class="flex gap-px border-t border-[#c4c5da]/10">
                    <button onclick="imprimirTicketActual()" class="flex-1 py-3.5 bg-[#1737c8] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-1.5 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-sm">print</span>Imprimir
                    </button>
                    <button onclick="enviarWhatsAppActual()" class="flex-1 py-3.5 bg-green-500 text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-1.5 hover:opacity-80 transition-all">
                        <span class="material-symbols-outlined text-sm">chat</span>WhatsApp
                    </button>
                </div>
            </div>
        </aside>
    </div>
</main>

<button onclick="abrirModalVenta()"
        class="lg:hidden fixed bottom-20 right-4 z-40 w-14 h-14 bg-[#1737c8] text-white rounded-full shadow-xl flex items-center justify-center hover:opacity-90 transition-all active:scale-95">
    <span class="material-symbols-outlined text-2xl">add</span>
</button>

{{-- MODAL TICKET MÓVIL --}}
<div id="modal-ticket" class="fixed inset-0 z-50 items-end justify-center bg-black/50 backdrop-blur-sm lg:hidden" onclick="if(event.target===this) cerrarTicketModal()">
    <div class="bg-white w-full max-w-md rounded-t-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8]">Ticket de venta</p>
                <h2 class="text-lg font-bold tracking-tight" id="modal-ticket-titulo">—</h2>
            </div>
            <button onclick="cerrarTicketModal()" class="p-2 hover:bg-[#f3f3f4] transition-colors rounded-full">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 receipt-texture">
            <div class="text-center mb-6">
                <h3 class="font-black tracking-tighter text-xl">{{ Str::limit(Auth::user()->store_name ?? 'MI TIENDA', 20) }}</h3>
                <p class="text-[9px] text-[#747688] uppercase tracking-[0.3em] mt-1">Tienda deportiva</p>
            </div>
            <div class="flex justify-between border-b border-dashed border-[#c4c5da]/40 pb-4 mb-5">
                <div><p class="text-[9px] text-[#747688] uppercase">Transacción</p><p class="text-sm font-bold" id="mt-ticket-id">—</p></div>
                <div class="text-right"><p class="text-[9px] text-[#747688] uppercase">Fecha</p><p class="text-sm font-bold" id="mt-ticket-fecha">—</p></div>
            </div>
            <div class="space-y-3 mb-6" id="mt-ticket-productos"></div>
            <div class="border-t border-dashed border-[#c4c5da]/40 pt-4 space-y-1.5">
                <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>Subtotal</span><span id="mt-ticket-subtotal">$0.00</span></div>
                <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>IVA (0%)</span><span>$0.00</span></div>
                <div class="flex justify-between text-lg font-black pt-2"><span>TOTAL</span><span class="text-[#1737c8]" id="mt-ticket-total">$0.00</span></div>
            </div>
            <p class="text-center text-[9px] text-[#747688] uppercase tracking-widest italic mt-6">Gracias por tu compra</p>
        </div>
        <div class="flex gap-3 p-4">
            <button onclick="imprimirTicketModal()" class="flex-1 py-3 bg-[#1737c8] text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-sm">print</span>Imprimir
            </button>
            <button onclick="enviarWhatsAppModal()" class="flex-1 py-3 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-sm">chat</span>WhatsApp
            </button>
        </div>
    </div>
</div>

{{-- MODAL NUEVA VENTA --}}
<div id="modal-nueva-venta" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-3 md:px-4" onclick="if(event.target===this) cerrarModalVenta()">
    <div class="bg-white w-full max-w-lg max-h-[92vh] overflow-y-auto rounded-sm">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 sticky top-0 bg-white z-10">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Registro</p>
                <h2 class="text-xl font-bold tracking-tight">Nueva venta</h2>
            </div>
            <div class="flex items-center gap-2">
                @if($esPro)
                <button type="button" id="btn-voz" onclick="toggleVoz()"
        class="relative p-2 bg-[#f3f3f4] border border-[#c4c5da]/40 hover:bg-[#1737c8] hover:text-white hover:border-[#1737c8] transition-all"
        title="Agregar producto por voz ({{ $creditosVoz }} créditos restantes)">
    <span class="material-symbols-outlined text-sm" id="icon-voz">mic</span>
    <span id="badge-voz"
          class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 bg-[#1737c8] text-white text-[9px] font-black flex items-center justify-center rounded-full leading-none">
        {{ $creditosVoz }}
    </span>
</button>
                @endif
                <button onclick="cerrarModalVenta()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>

        @if($esPro)
        <div id="voz-status" class="hidden px-6 pt-4">
            <div id="voz-escuchando" class="hidden bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-500 text-sm animate-pulse">mic</span>
                <div class="flex-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-700">Escuchando...</p>
                    <p class="text-xs text-red-500" id="voz-transcript">Di el producto, ej: "Air Force One talla 27"</p>
                </div>
                <button type="button" onclick="detenerVoz()" class="text-red-400 hover:text-red-600">
                    <span class="material-symbols-outlined text-sm">stop_circle</span>
                </button>
            </div>
            <div id="voz-procesando" class="hidden bg-[#1737c8]/5 border border-[#1737c8]/20 px-4 py-3 flex items-center gap-3">
                <svg class="animate-spin w-4 h-4 text-[#1737c8] shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-xs font-bold text-[#1737c8]">Interpretando con IA...</p>
            </div>
            <div id="voz-resultado" class="hidden bg-green-50 border border-green-200 px-4 py-3 flex items-center gap-3">
                <span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>
                <p class="text-xs font-bold text-green-700" id="voz-resultado-text"></p>
            </div>
            <div id="voz-error" class="hidden bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-400 text-sm">error</span>
                <p class="text-xs font-bold text-red-600" id="voz-error-text"></p>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('ventas.store') }}" id="form-nueva-venta" class="px-6 py-5 space-y-5">
            @csrf
            <input type="hidden" name="metodo_pago" id="metodo-pago-hidden" value="efectivo"/>
            <input type="hidden" name="tipo_venta" id="tipo-venta-hidden" value="menudeo"/>
            <input type="hidden" name="recibido" id="recibido-hidden" value="0"/>
            <input type="hidden" name="cambio" id="cambio-hidden" value="0"/>
            <input type="hidden" name="puntos_canjear" id="puntos-canjear-hidden" value="0"/>

            {{-- Cliente --}}
            <div class="flex flex-col gap-1.5 relative">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                <input type="text" id="cliente-search" name="cliente_nombre"
                       placeholder="Busca o escribe el nombre..." autocomplete="off"
                       oninput="buscarClienteVenta(this.value)"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                <input type="hidden" name="cliente_id" id="cliente-id-hidden"/>
                <p class="text-[9px] text-[#747688]">Si no existe se registra automáticamente</p>
                <div id="clientes-sugerencias" class="absolute top-[72px] left-0 right-0 bg-white border border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-40 overflow-y-auto"></div>
                <div id="clientes-data" class="hidden">
                    @foreach($clientes ?? [] as $cliente)
                    <span data-id="{{ $cliente->id ?? $cliente['id'] }}"
                          data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"
                          data-puntos="{{ $cliente->puntos ?? 0 }}"></span>
                    @endforeach
                </div>
            </div>

            {{-- PROGRAMA DE LEALTAD --}}
@if($lealtadActivo)
<div id="lealtad-section" class="hidden space-y-2">
    {{-- Pregunta inicial --}}
    <div id="lealtad-pregunta" class="bg-amber-50 border border-amber-200 px-4 py-4">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-amber-500 text-sm">stars</span>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Programa de lealtad</p>
            <span class="text-sm font-black text-amber-600 ml-auto" id="lealtad-puntos-display">0 pts</span>
        </div>
        <p class="text-xs text-amber-800 mb-3" id="lealtad-mensaje">¿Qué deseas hacer con tus puntos?</p>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" onclick="elegirLealtad('acumular')"
                    class="py-2.5 text-[10px] font-black uppercase tracking-widest border-2 border-amber-300 text-amber-700 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">savings</span>Acumular
            </button>
            <button type="button" onclick="elegirLealtad('usar')"
                    class="py-2.5 text-[10px] font-black uppercase tracking-widest bg-amber-500 text-white hover:bg-amber-600 transition-all flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">redeem</span>Usar puntos
            </button>
        </div>
    </div>
    {{-- Sección de canje --}}
    <div id="lealtad-canje" class="hidden bg-amber-50 border border-amber-200 px-4 py-3 space-y-2">
        <div class="flex items-center gap-2">
            <label class="text-[9px] font-black uppercase tracking-widest text-amber-700 shrink-0">Puntos a canjear:</label>
            <input type="number" id="puntos-canjear-input" placeholder="0" min="0"
                   oninput="actualizarCanjePuntos()"
                   class="flex-1 border border-amber-300 bg-white px-3 py-1.5 text-sm font-black focus:outline-none focus:border-amber-500"/>
            <span class="text-[9px] text-amber-600 font-bold shrink-0" id="lealtad-descuento-display">-$0</span>
        </div>
        <div class="flex items-center justify-between">
            <p class="text-[9px] text-amber-600">1 punto = $1 de descuento</p>
            <button type="button" onclick="elegirLealtad('acumular')" class="text-[9px] text-amber-500 underline font-bold">Cancelar</button>
        </div>
    </div>
</div>
@endif

            {{-- TIPO DE VENTA --}}
            @if($esPro)
            <div class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">Venta mayoreo</p>
                    <p class="text-[9px] text-[#747688] mt-0.5" id="mayoreo-label">Activar para modificar precios manualmente</p>
                </div>
                <label class="toggle-mayoreo">
                    <input type="checkbox" id="toggle-mayoreo-sales" onchange="toggleMayoreoSales(this.checked)"/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            @else
            <a href="{{ route('planes') }}" class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3 pro-lock group">
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
                    <button type="button" onclick="seleccionarMetodo('efectivo', this)"
                            class="metodo-btn activo py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all">
                        <span class="material-symbols-outlined text-sm">payments</span>Efectivo
                    </button>
                    <button type="button" onclick="seleccionarMetodo('tarjeta', this)"
                            class="metodo-btn py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c]">
                        <span class="material-symbols-outlined text-sm">credit_card</span>Tarjeta
                    </button>
                    <button type="button" onclick="seleccionarMetodo('transferencia', this)"
                            class="metodo-btn py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c]">
                        <span class="material-symbols-outlined text-sm">account_balance</span>Transfer.
                    </button>
                </div>
            </div>

            {{-- Categorías --}}
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Agregar productos</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="filtrarCategoria('ropa', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Ropa</button>
                    <button type="button" onclick="filtrarCategoria('calzado', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Calzado</button>
                    <button type="button" onclick="filtrarCategoria('accesorios', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Accesorios</button>
                </div>
                <div id="lista-productos-categoria" class="hidden space-y-0 max-h-52 overflow-y-auto border border-[#c4c5da]/20 bg-[#f9f9f9] rounded-sm"></div>
                <div id="productos-data" class="hidden">
                    @foreach($productos ?? [] as $prod)
                    <span data-id="{{ $prod['id'] ?? '' }}" data-nombre="{{ $prod['nombre'] ?? '' }}"
                          data-precio="{{ $prod['precio'] ?? 0 }}" data-categoria="{{ $prod['categoria'] ?? '' }}"
                          data-tallas="{{ json_encode($prod['tallas'] ?? []) }}"></span>
                    @endforeach
                </div>
            </div>

            <div id="productos-container" class="space-y-2"></div>
            <div id="productos-hidden"></div>

            <div class="bg-[#1737c8] px-5 py-4 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                <span class="text-2xl font-black text-white" id="total-calculado">$0.00</span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="procesarVenta()"
                        class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>Registrar venta
                </button>
                <button type="button" onclick="cerrarModalVenta()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EFECTIVO --}}
<div id="modal-efectivo" class="fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Pago en efectivo</p>
                <h2 class="text-xl font-bold tracking-tight">¿Cuánto recibiste?</h2>
            </div>
            <button onclick="cerrarModalEfectivo()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total a cobrar</span>
                <span class="text-xl font-black text-[#1737c8]" id="efectivo-total">$0.00</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Monto recibido</label>
                <input type="number" id="efectivo-recibido" placeholder="0.00" min="0" step="0.01"
                       oninput="calcularCambio()"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-lg font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            </div>
            <div class="grid grid-cols-4 gap-2" id="efectivo-rapidos"></div>
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cambio</span>
                <span class="text-xl font-black text-green-600" id="efectivo-cambio">$0.00</span>
            </div>
            <p id="efectivo-error" class="text-xs text-red-500 font-bold hidden">El monto recibido es menor al total.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button onclick="confirmarEfectivo()" class="flex-1 py-4 bg-[#1a1c1c] text-white text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">check</span>Confirmar venta
            </button>
            <button onclick="cerrarModalEfectivo()" class="px-4 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                Cancelar
            </button>
        </div>
    </div>
</div>

{{-- PRINT TICKET --}}
<div id="print-ticket" style="display:none; font-family: 'Inter', sans-serif; max-width: 320px; margin: 0 auto; padding: 24px;">
    <div style="text-align:center; margin-bottom: 20px;">
        <h3 style="font-weight:900; font-size:20px; margin:0;">{{ Auth::user()->store_name ?? 'MI TIENDA' }}</h3>
        <p style="font-size:10px; color:#747688; margin:4px 0 0; letter-spacing:0.2em; text-transform:uppercase;">Tienda deportiva</p>
    </div>
    <div id="print-contenido"></div>
    <p style="text-align:center; font-size:9px; color:#747688; margin-top:24px; font-style:italic; text-transform:uppercase; letter-spacing:0.2em;">Gracias por tu compra</p>
</div>

<script>
const PLAN = '{{ $plan ?? "gratis" }}';
const ES_PRO = PLAN === 'pro' || PLAN === 'business';
const ES_LEALTAD = {{ $lealtadActivo ? 'true' : 'false' }};
const CSRF_TOKEN = '{{ csrf_token() }}';

let ventaActual = null;
let ventaModalActual = null;
let metodoPagoActual = 'efectivo';
let tipoVentaActual  = 'menudeo';
let totalVentaActual = 0;
let esMayoreo = false;
let recognition = null;
let vozActiva = false;

// ── REGISTRO POR VOZ ──────────────────────────────────────────
function toggleVoz() {
    if (vozActiva) { detenerVoz(); } else { iniciarVoz(); }
}

function iniciarVoz() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        mostrarVozError('Tu navegador no soporta voz. Usa Chrome o Edge.');
        return;
    }
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'es-MX';
    recognition.continuous = false;
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    let transcriptFinal = '';

    recognition.onstart = () => {
        vozActiva = true;
        transcriptFinal = '';
        document.getElementById('btn-voz').classList.add('escuchando');
        document.getElementById('voz-status').classList.remove('hidden');
        document.getElementById('voz-escuchando').classList.remove('hidden');
        document.getElementById('voz-procesando').classList.add('hidden');
        document.getElementById('voz-resultado').classList.add('hidden');
        document.getElementById('voz-error').classList.add('hidden');
        document.getElementById('voz-transcript').textContent = 'Di el producto, ej: "Air Force One talla 27"';
    };

    recognition.onresult = (event) => {
        let interino = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                transcriptFinal += event.results[i][0].transcript;
            } else {
                interino += event.results[i][0].transcript;
            }
        }
        document.getElementById('voz-transcript').textContent = transcriptFinal || interino;
    };

    recognition.onspeechend = () => {
        recognition.stop();
    };

    recognition.onend = () => {
        vozActiva = false;
        document.getElementById('btn-voz')?.classList.remove('escuchando');
        document.getElementById('icon-voz').textContent = 'mic';
        document.getElementById('voz-escuchando').classList.add('hidden');
        const texto = transcriptFinal || document.getElementById('voz-transcript').textContent;
        const placeholder = 'Di el producto, ej: "Air Force One talla 27"';
        if (texto && texto !== placeholder) {
            interpretarVoz(texto);
        }
    };

    recognition.onerror = (e) => {
        vozActiva = false;
        document.getElementById('btn-voz')?.classList.remove('escuchando');
        if (e.error !== 'no-speech') {
            mostrarVozError('Error de micrófono: ' + e.error);
        }
    };

    recognition.start();
}

function detenerVoz() {
    if (recognition) { recognition.stop(); recognition = null; }
    vozActiva = false;
    document.getElementById('btn-voz')?.classList.remove('escuchando');
    document.getElementById('voz-escuchando')?.classList.add('hidden');
}

    function detenerVozManual() {
    if (recognition) recognition.stop();
}

async function interpretarVoz(texto) {
    document.getElementById('voz-procesando').classList.remove('hidden');
    document.getElementById('voz-escuchando').classList.add('hidden');
    const productos = Array.from(document.querySelectorAll('#productos-data span')).map(p => ({
        id: p.dataset.id, nombre: p.dataset.nombre, precio: p.dataset.precio,
        tallas: JSON.parse(p.dataset.tallas || '{}'),
    }));
    try {
        const resp = await fetch('{{ route("ventas.voz") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ texto, productos }),
        });
        const data = await resp.json();
        document.getElementById('voz-procesando').classList.add('hidden');
        if (data.creditos_restantes !== undefined) actualizarBadgeVoz(data.creditos_restantes);
        if (data.limite) { mostrarVozError(data.mensaje); return; }
        if (data.producto_id) {
            const span = document.querySelector(`#productos-data span[data-id="${data.producto_id}"]`);
            if (span) {
                agregarProductoVenta(span.dataset.id, span.dataset.nombre, span.dataset.precio, data.talla || null);
                const cantidad = parseInt(data.cantidad ?? 1);
                if (cantidad > 1) {
                    const key = span.dataset.id + '_' + (data.talla ?? 'sin_talla');
                    const idx = productosVenta.findIndex(p => p.key === key);
                    if (idx > -1) { productosVenta[idx].cantidad = cantidad; renderizarProductosVenta(); }
                }
                mostrarVozResultado(data.mensaje ?? '✓ Producto agregado');
            } else {
                mostrarVozError('Producto no encontrado en el catálogo.');
            }
        } else {
            mostrarVozError(data.mensaje ?? 'No se pudo identificar el producto.');
        }
    } catch (err) {
        document.getElementById('voz-procesando').classList.add('hidden');
        mostrarVozError('Error al procesar. Intenta de nuevo.');
    }
}


function actualizarBadgeVoz(creditos) {
    const badge = document.getElementById('badge-voz');
    if (!badge) return;
    badge.textContent = creditos;
    if (creditos <= 10) {
        badge.classList.remove('bg-\\[#1737c8\\]');
        badge.style.backgroundColor = '#ef4444';
    }
    if (creditos <= 0) {
        const btn = document.getElementById('btn-voz');
        if (btn) { btn.disabled = true; btn.style.opacity = '0.4'; btn.style.cursor = 'not-allowed'; }
    }
}

function mostrarVozResultado(msg) {
    document.getElementById('voz-resultado-text').textContent = msg;
    document.getElementById('voz-resultado').classList.remove('hidden');
    setTimeout(() => document.getElementById('voz-resultado').classList.add('hidden'), 3000);
}

function mostrarVozError(msg) {
    document.getElementById('voz-procesando').classList.add('hidden');
    document.getElementById('voz-error-text').textContent = msg;
    document.getElementById('voz-error').classList.remove('hidden');
    setTimeout(() => document.getElementById('voz-error').classList.add('hidden'), 4000);
}

// ── FUNCIONES VENTAS ──────────────────────────────────────────
function toggleMayoreoSales(activo) {
    esMayoreo = activo;
    tipoVentaActual = activo ? 'mayoreo' : 'menudeo';
    document.getElementById('tipo-venta-hidden').value = tipoVentaActual;
    document.getElementById('mayoreo-label').textContent = activo
        ? '⚠️ Mayoreo activo — puedes editar precios'
        : 'Activar para modificar precios manualmente';
    renderizarProductosVenta();
}

function seleccionarMetodo(metodo, btn) {
    metodoPagoActual = metodo;
    document.getElementById('metodo-pago-hidden').value = metodo;
    document.querySelectorAll('.metodo-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
}

function procesarVenta() {
    const productos = document.querySelectorAll('#productos-hidden input[name="productos[]"]');
    if (productos.length === 0) { alert('Agrega al menos un producto.'); return; }
    const cliente = document.getElementById('cliente-search').value.trim();
    if (!cliente) { alert('Escribe el nombre del cliente.'); return; }
    totalVentaActual = parseFloat(document.getElementById('total-calculado').textContent.replace(/[$,]/g, '')) || 0;
    if (metodoPagoActual === 'efectivo') {
        abrirModalEfectivo();
    } else {
        document.getElementById('recibido-hidden').value = 0;
        document.getElementById('cambio-hidden').value = 0;
        document.getElementById('form-nueva-venta').submit();
    }
}

function abrirModalEfectivo() {
    document.getElementById('efectivo-total').textContent = '$' + totalVentaActual.toFixed(2);
    document.getElementById('efectivo-recibido').value = '';
    document.getElementById('efectivo-cambio').textContent = '$0.00';
    document.getElementById('efectivo-cambio').className = 'text-xl font-black text-green-600';
    document.getElementById('efectivo-error').classList.add('hidden');
    const rapidos = [totalVentaActual, 50, 100, 200, 500, 1000]
        .filter((v, i, a) => a.indexOf(v) === i && v >= totalVentaActual)
        .sort((a,b) => a-b).slice(0,4);
    document.getElementById('efectivo-rapidos').innerHTML = rapidos.map(v =>
        `<button type="button" onclick="setRecibido(${v})" class="py-2 text-xs font-black border border-[#c4c5da]/40 hover:border-[#1a1c1c] hover:bg-[#f3f3f4] transition-all">
            $${Number.isInteger(v) ? v : v.toFixed(2)}</button>`
    ).join('');
    document.getElementById('modal-efectivo').classList.add('activo');
    setTimeout(() => document.getElementById('efectivo-recibido').focus(), 100);
}

function cerrarModalEfectivo() { document.getElementById('modal-efectivo').classList.remove('activo'); }
function setRecibido(valor) { document.getElementById('efectivo-recibido').value = valor; calcularCambio(); }

function calcularCambio() {
    const recibido = parseFloat(document.getElementById('efectivo-recibido').value) || 0;
    const cambio = recibido - totalVentaActual;
    const cambioEl = document.getElementById('efectivo-cambio');
    const errorEl = document.getElementById('efectivo-error');
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

function confirmarEfectivo() {
    const recibido = parseFloat(document.getElementById('efectivo-recibido').value) || 0;
    if (recibido < totalVentaActual) { document.getElementById('efectivo-error').classList.remove('hidden'); return; }
    document.getElementById('recibido-hidden').value = recibido.toFixed(2);
    document.getElementById('cambio-hidden').value = (recibido - totalVentaActual).toFixed(2);
    cerrarModalEfectivo();
    document.getElementById('form-nueva-venta').submit();
}

function seleccionarVenta(venta, el) {
    ventaActual = venta;
    document.querySelectorAll('.venta-card').forEach(r => r.classList.remove('selected'));
    el.classList.add('selected');
    actualizarTicketDesktop(venta);
}

function actualizarTicketDesktop(venta) {
    document.getElementById('ticket-id').textContent = '#ID-' + venta.id;
    document.getElementById('ticket-fecha').textContent = venta.fecha ?? '—';
    document.getElementById('ticket-subtotal').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('ticket-total').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('ticket-productos').innerHTML = renderProductos(venta);
    if (venta.metodo_pago) {
        document.getElementById('ticket-pago-section').classList.remove('hidden');
        const tipo = venta.tipo_venta ?? 'menudeo';
        const tipoEl = document.getElementById('ticket-tipo-display');
        tipoEl.textContent = tipo.charAt(0).toUpperCase() + tipo.slice(1);
        tipoEl.className = tipo === 'mayoreo' ? 'text-amber-600 font-black text-[10px]' : 'text-[#747688] font-bold text-[10px]';
        document.getElementById('ticket-metodo-display').textContent = venta.metodo_pago.charAt(0).toUpperCase() + venta.metodo_pago.slice(1);
        document.getElementById('ticket-efectivo-section').classList.add('hidden');
    }
}

function seleccionarVentaMovil(venta, el) {
    document.querySelectorAll('.venta-card').forEach(r => r.classList.remove('selected'));
    el.classList.add('selected');
    ventaActual = venta;
    actualizarTicketDesktop(venta);
}

function abrirTicketModal(venta) {
    ventaModalActual = venta;
    document.getElementById('modal-ticket-titulo').textContent = '#ID-' + venta.id + ' · ' + venta.cliente;
    document.getElementById('mt-ticket-id').textContent = '#ID-' + venta.id;
    document.getElementById('mt-ticket-fecha').textContent = venta.fecha ?? '—';
    document.getElementById('mt-ticket-subtotal').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('mt-ticket-total').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('mt-ticket-productos').innerHTML = renderProductos(venta);
    document.getElementById('modal-ticket').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

function cerrarTicketModal() { document.getElementById('modal-ticket').classList.remove('activo'); document.body.style.overflow = ''; }

function renderProductos(venta) {
    if (!venta.productos || venta.productos.length === 0)
        return '<p class="text-xs text-[#747688] text-center">Sin detalle de productos</p>';
    return venta.productos.map(p =>
        `<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
            <div style="flex:1;padding-right:12px;">
                <p style="font-weight:700;font-size:12px;">${p.nombre}</p>
                ${p.detalle ? `<p style="font-size:10px;color:#747688;">${p.detalle}</p>` : ''}
            </div>
            <span style="font-weight:700;font-size:12px;">$${parseFloat(p.precio).toFixed(2)}</span>
        </div>`
    ).join('');
}

function imprimirTicket(venta) { ventaActual = venta; prepararPrint(venta); setTimeout(() => window.print(), 800); }
function imprimirTicketActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } prepararPrint(ventaActual); setTimeout(() => window.print(), 800); }
function imprimirTicketModal() { if (!ventaModalActual) return; prepararPrint(ventaModalActual); cerrarTicketModal(); setTimeout(() => window.print(), 900); }

function prepararPrint(venta) {
    document.getElementById('print-ticket').style.display = 'block';
    const metodo = venta.metodo_pago ?? 'efectivo';
    const tipo   = venta.tipo_venta ?? 'menudeo';
    document.getElementById('print-contenido').innerHTML = `
        <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #ccc;padding-bottom:12px;margin-bottom:16px;">
            <div><p style="font-size:9px;color:#747688;text-transform:uppercase;">Transacción</p><p style="font-weight:700;">#ID-${venta.id}</p></div>
            <div style="text-align:right;"><p style="font-size:9px;color:#747688;text-transform:uppercase;">Fecha</p><p style="font-weight:700;">${venta.fecha ?? ''}</p></div>
        </div>
        ${venta.productos && venta.productos.length > 0 ? venta.productos.map(p => `
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <div style="flex:1;padding-right:12px;">
                    <p style="font-weight:700;font-size:13px;margin:0;">${p.nombre}</p>
                    ${p.detalle ? `<p style="font-size:10px;color:#747688;margin:2px 0 0;">${p.detalle}</p>` : ''}
                </div>
                <span style="font-weight:700;font-size:13px;">$${parseFloat(p.precio).toFixed(2)}</span>
            </div>`).join('') : '<p style="font-size:11px;color:#747688;">Sin detalle</p>'}
        <div style="border-top:1px dashed #ccc;padding-top:12px;margin-top:16px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <span style="font-size:10px;color:#747688;text-transform:uppercase;font-weight:700;">Subtotal</span>
                <span style="font-size:10px;color:#747688;font-weight:700;">$${parseFloat(venta.total).toFixed(2)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-weight:900;font-size:16px;margin-bottom:12px;">
                <span>TOTAL</span><span>$${parseFloat(venta.total).toFixed(2)}</span>
            </div>
            <div style="border-top:1px dashed #ccc;padding-top:10px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:10px;color:#747688;text-transform:uppercase;font-weight:700;">Tipo de venta</span>
                    <span style="font-size:10px;font-weight:900;color:${tipo === 'mayoreo' ? '#d97706' : '#1a1c1c'};">${tipo.charAt(0).toUpperCase() + tipo.slice(1)}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:10px;color:#747688;text-transform:uppercase;font-weight:700;">Método de pago</span>
                    <span style="font-size:10px;font-weight:700;">${metodo.charAt(0).toUpperCase() + metodo.slice(1)}</span>
                </div>
                ${metodo === 'efectivo' && venta.recibido ? `
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:10px;color:#747688;text-transform:uppercase;font-weight:700;">Recibido</span>
                    <span style="font-size:10px;font-weight:700;">$${parseFloat(venta.recibido).toFixed(2)}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:10px;color:#747688;text-transform:uppercase;font-weight:700;">Cambio</span>
                    <span style="font-size:10px;font-weight:900;color:#16a34a;">$${parseFloat(venta.cambio ?? 0).toFixed(2)}</span>
                </div>` : ''}
            </div>
        </div>`;
}

function enviarWhatsApp(venta) {
    const metodo = venta.metodo_pago ?? 'efectivo';
    const tipo   = venta.tipo_venta ?? 'menudeo';
    let pagoInfo = `Tipo: ${tipo.charAt(0).toUpperCase() + tipo.slice(1)}\nMétodo: ${metodo.charAt(0).toUpperCase() + metodo.slice(1)}`;
    if (metodo === 'efectivo' && venta.recibido) {
        pagoInfo += `\nRecibido: $${parseFloat(venta.recibido).toFixed(2)}\nCambio: $${parseFloat(venta.cambio ?? 0).toFixed(2)}`;
    }
    const texto = `🧾 *Ticket - ${venta.cliente}*\n#ID-${venta.id}\nTotal: $${parseFloat(venta.total).toFixed(2)}\n${pagoInfo}\n\n_Gracias por tu compra en {{ Auth::user()->store_name ?? 'nuestra tienda' }}_`;
    const telefono = venta.telefono ? venta.telefono.replace(/\D/g, '') : null;
    const url = telefono
        ? `https://wa.me/52${telefono}?text=${encodeURIComponent(texto)}`
        : `https://wa.me/?text=${encodeURIComponent(texto)}`;
    window.open(url, '_blank');
}

function enviarWhatsAppActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } enviarWhatsApp(ventaActual); }
function enviarWhatsAppModal() { if (!ventaModalActual) return; enviarWhatsApp(ventaModalActual); }

function filtrarVentas(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.venta-card').forEach(row => {
        row.style.display = ((row.dataset.nombre ?? '').includes(q) || (row.dataset.id ?? '').includes(q)) ? '' : 'none';
    });
}

function abrirModalVenta() {
    document.getElementById('modal-nueva-venta').classList.add('activo');
    document.body.style.overflow = 'hidden';
    metodoPagoActual = 'efectivo';
    tipoVentaActual  = 'menudeo';
    esMayoreo = false;
    document.getElementById('metodo-pago-hidden').value = 'efectivo';
    document.getElementById('tipo-venta-hidden').value  = 'menudeo';
    if (ES_PRO) {
        document.getElementById('toggle-mayoreo-sales').checked = false;
        document.getElementById('mayoreo-label').textContent = 'Activar para modificar precios manualmente';
    }
    document.querySelectorAll('.metodo-btn').forEach(b => b.classList.remove('activo'));
    document.querySelectorAll('.metodo-btn')[0].classList.add('activo');
}

function cerrarModalVenta() {
    if (vozActiva) detenerVoz();
    document.getElementById('modal-nueva-venta').classList.remove('activo');
    document.body.style.overflow = '';
    document.getElementById('cliente-search').value = '';
    document.getElementById('cliente-id-hidden').value = '';
    document.getElementById('clientes-sugerencias').classList.add('hidden');
    document.getElementById('lista-productos-categoria').classList.add('hidden');
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    productosVenta = [];
    esMayoreo = false;
    renderizarProductosVenta();
    if (ES_PRO) document.getElementById('voz-status')?.classList.add('hidden');
    if (ES_LEALTAD) {
    document.getElementById('lealtad-section')?.classList.add('hidden');
    document.getElementById('lealtad-pregunta')?.classList.remove('hidden');
    document.getElementById('lealtad-canje')?.classList.add('hidden');
    document.getElementById('puntos-canjear-hidden').value = 0;
    }
}

function buscarClienteVenta(query) {
    const sugerencias = document.getElementById('clientes-sugerencias');
    const q = query.toLowerCase().trim();
    if (q.length < 1) { sugerencias.classList.add('hidden'); return; }
    const coincidencias = Array.from(document.querySelectorAll('#clientes-data span')).filter(c => c.dataset.nombre.toLowerCase().includes(q));
    sugerencias.innerHTML = coincidencias.length === 0
        ? '<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias — se creará automáticamente</div>'
        : coincidencias.map(c => `<div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] uppercase" onclick="seleccionarCliente('${c.dataset.id}','${c.dataset.nombre}')">${c.dataset.nombre}</div>`).join('');
    sugerencias.classList.remove('hidden');
}

function seleccionarCliente(id, nombre) {
    document.getElementById('cliente-search').value = nombre;
    document.getElementById('cliente-id-hidden').value = id;
    document.getElementById('clientes-sugerencias').classList.add('hidden');

    if (ES_LEALTAD) {
        const span = Array.from(document.querySelectorAll('#clientes-data span')).find(s => s.dataset.id == id);
        const puntos = parseInt(span?.dataset.puntos ?? 0);
        const seccion = document.getElementById('lealtad-section');

        if (puntos > 0) {
            // Mostrar mensaje tipo membresía
            document.getElementById('lealtad-puntos-display').textContent = puntos + ' pts';
            document.getElementById('lealtad-mensaje').textContent =
                `⭐ ${nombre.split(' ')[0]} tiene ${puntos} punto${puntos !== 1 ? 's' : ''} ($${puntos} de descuento). ¿Los acumula o los quiere utilizar?`;
            document.getElementById('puntos-canjear-input').max = puntos;
            document.getElementById('puntos-canjear-input').value = '';
            document.getElementById('lealtad-descuento-display').textContent = '-$0';
            document.getElementById('puntos-canjear-hidden').value = 0;
            document.getElementById('lealtad-pregunta').classList.remove('hidden');
            document.getElementById('lealtad-canje').classList.add('hidden');
            seccion.classList.remove('hidden');
        } else {
            seccion.classList.add('hidden');
            document.getElementById('puntos-canjear-hidden').value = 0;
        }
    }
}

function elegirLealtad(opcion) {
    if (opcion === 'usar') {
        document.getElementById('lealtad-pregunta').classList.add('hidden');
        document.getElementById('lealtad-canje').classList.remove('hidden');
        document.getElementById('puntos-canjear-input').focus();
    } else {
        document.getElementById('lealtad-canje').classList.add('hidden');
        document.getElementById('lealtad-pregunta').classList.remove('hidden');
        document.getElementById('puntos-canjear-input').value = '';
        document.getElementById('puntos-canjear-hidden').value = 0;
        document.getElementById('lealtad-descuento-display').textContent = '-$0';
        calcularTotalConDescuento();
    }
}


document.addEventListener('click', e => {
    if (!e.target.closest('#cliente-search') && !e.target.closest('#clientes-sugerencias'))
        document.getElementById('clientes-sugerencias')?.classList.add('hidden');
});

let productosVenta = [];

function filtrarCategoria(categoria, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    btn.classList.add('bg-[#1a1c1c]','text-white','border-[#1a1c1c]');
    const lista = document.getElementById('lista-productos-categoria');
    const filtrados = Array.from(document.querySelectorAll('#productos-data span')).filter(p => p.dataset.categoria === categoria);
    if (filtrados.length === 0) {
        lista.innerHTML = '<div class="px-4 py-3 text-sm text-[#747688]">Sin productos en esta categoría</div>';
    } else {
        lista.innerHTML = filtrados.map(p => {
            const tallas = JSON.parse(p.dataset.tallas || '[]');
            const tallasHtml = tallas.length > 0
                ? tallas.map(t => `<button type="button" onclick="agregarProductoVenta('${p.dataset.id}','${p.dataset.nombre}',${p.dataset.precio},'${t.talla}')"
                    class="px-2 py-1 text-[9px] font-black border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] hover:bg-white transition-all uppercase">
                    ${t.talla} <span class="text-[#747688]">(${t.stock})</span></button>`).join('')
                : '<span class="text-[10px] text-red-500 font-bold">Sin stock</span>';
            return `<div class="px-4 py-3 border-b border-[#c4c5da]/10">
                <div class="mb-2"><p class="text-xs font-bold uppercase">${p.dataset.nombre}</p><p class="text-[10px] text-[#747688]">$${parseFloat(p.dataset.precio).toFixed(2)}</p></div>
                <div class="flex flex-wrap gap-1.5">${tallasHtml}</div></div>`;
        }).join('');
    }
    lista.classList.remove('hidden');
}

function agregarProductoVenta(id, nombre, precio, talla = null) {
    const key = id + '_' + (talla ?? 'sin_talla');
    const existe = productosVenta.find(p => p.key === key);
    if (existe) { existe.cantidad += 1; } else { productosVenta.push({ key, id, nombre, precio: parseFloat(precio), precio_original: parseFloat(precio), cantidad: 1, talla }); }
    renderizarProductosVenta();
}

function renderizarProductosVenta() {
    const container = document.getElementById('productos-container');
    if (productosVenta.length === 0) { container.innerHTML = ''; actualizarHiddens(); calcularTotal(); return; }
    container.innerHTML = `<div class="border ${esMayoreo ? 'border-amber-200' : 'border-[#c4c5da]/20'} divide-y divide-[#c4c5da]/10">` +
        productosVenta.map((p, i) => `
        <div class="flex items-center gap-3 px-3 py-2.5 ${esMayoreo ? 'bg-amber-50' : ''}">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold uppercase truncate">${p.nombre}</p>
                ${p.talla ? `<p class="text-[10px] text-[#1737c8] font-bold">Talla: ${p.talla}</p>` : ''}
                ${esMayoreo
                    ? `<div class="flex items-center gap-1 mt-1">
                        <span class="text-[9px] text-amber-600 font-bold">Precio:</span>
                        <input type="number" value="${p.precio}" step="0.01" min="0"
                               onchange="actualizarPrecioMayoreo(${i}, this.value)"
                               class="w-20 text-xs font-black border border-amber-300 bg-white px-2 py-0.5 focus:outline-none focus:border-amber-500"/>
                        <span class="text-[9px] text-[#747688] line-through">$${p.precio_original.toFixed(2)}</span>
                       </div>`
                    : `<p class="text-[10px] text-[#747688]">$${p.precio.toFixed(2)} c/u</p>`}
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button type="button" onclick="cambiarCantidadVenta(${i},-1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black">−</button>
                <span class="w-8 text-center text-sm font-black">${p.cantidad}</span>
                <button type="button" onclick="cambiarCantidadVenta(${i},1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black">+</button>
            </div>
            <p class="text-xs font-black ${esMayoreo ? 'text-amber-600' : 'text-[#1737c8]'} w-16 text-right shrink-0">$${(p.precio*p.cantidad).toFixed(2)}</p>
            <button type="button" onclick="quitarProductoVenta(${i})" class="text-[#c4c5da] hover:text-red-500 transition-colors shrink-0">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>`).join('') + `</div>`;
    actualizarHiddens(); calcularTotal();
}

function actualizarPrecioMayoreo(i, valor) { productosVenta[i].precio = parseFloat(valor) || 0; calcularTotal(); actualizarHiddens(); }
function cambiarCantidadVenta(i, d) { productosVenta[i].cantidad += d; if (productosVenta[i].cantidad <= 0) productosVenta.splice(i, 1); renderizarProductosVenta(); }
function quitarProductoVenta(i) { productosVenta.splice(i, 1); renderizarProductosVenta(); }

function actualizarHiddens() {
    document.getElementById('productos-hidden').innerHTML = productosVenta.map(p =>
        `<input type="hidden" name="productos[]" value="${p.id}"/>
         <input type="hidden" name="cantidades[]" value="${p.cantidad}"/>
         <input type="hidden" name="precios[]" value="${p.precio}"/>
         <input type="hidden" name="tallas[]" value="${p.talla ?? ''}"/>`
    ).join('');
}

function calcularTotal() { calcularTotalConDescuento(); }

function actualizarCanjePuntos() {
    const puntos = parseInt(document.getElementById('puntos-canjear-input').value) || 0;
    const max = parseInt(document.getElementById('puntos-canjear-input').max) || 0;
    const real = Math.min(puntos, max);
    document.getElementById('puntos-canjear-hidden').value = real;
    document.getElementById('lealtad-descuento-display').textContent = '-$' + real;
    calcularTotalConDescuento();
}

function calcularTotalConDescuento() {
    const subtotal = productosVenta.reduce((s, p) => s + p.precio * p.cantidad, 0);
    const descuento = parseInt(document.getElementById('puntos-canjear-hidden')?.value ?? 0);
    const total = Math.max(0, subtotal - descuento);
    document.getElementById('total-calculado').textContent = '$' + total.toFixed(2);
}
</script>

@include('partials._sidebar')
</body>
</html>