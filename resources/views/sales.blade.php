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
    @media print {
        * { display: none !important; }
        #print-ticket { display: block !important; position: fixed; inset: 0; background: white; padding: 24px; }
    }
</style>
</head>
<body class="bg-[#f9f9f9] text-[#1a1c1c]">

@include('partials._nav')

{{-- BREADCRUMB --}}
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
<div class="mx-4 md:mx-6 mb-4 bg-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif

<main class="pb-32 md:pb-12 px-4 md:px-6 max-w-[1600px] mx-auto">

    {{-- KPIs rápidos --}}
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

        {{-- LISTA DE VENTAS --}}
        <section class="lg:col-span-7 xl:col-span-8">

            {{-- Header --}}
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

            {{-- Buscador móvil --}}
            <div class="relative md:hidden mb-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                <input class="pl-9 pr-4 py-2.5 bg-white border border-[#c4c5da]/30 focus:border-[#1737c8] focus:outline-none text-sm w-full" placeholder="Buscar por nombre o ID..." oninput="filtrarVentas(this.value)"/>
            </div>

            {{-- TARJETAS MÓVIL / TABLA DESKTOP --}}
            <div id="lista-ventas" class="space-y-2">

                @forelse($ventas ?? [] as $venta)

                {{-- TARJETA (visible en móvil y tablet) --}}
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
                            <span class="text-[9px] font-bold px-2 py-0.5 {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} uppercase">
                                {{ $venta['estado'] === 'completada' ? 'Completada' : 'Pendiente' }}
                            </span>
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

                {{-- FILA DESKTOP --}}
                <div class="venta-card hidden lg:grid grid-cols-12 px-5 py-4 items-center bg-white border border-[#c4c5da]/15"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVenta({{ json_encode($venta) }}, this)">
                    <div class="col-span-5 flex items-center gap-3">
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
                        <span class="text-[9px] font-bold px-2 py-1 uppercase {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $venta['estado'] === 'completada' ? 'Completada' : 'Pendiente' }}
                        </span>
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
                        <button onclick="event.stopPropagation(); enviarEmail({{ json_encode($venta) }})"
                                class="p-2 border border-[#c4c5da]/30 hover:border-[#1a1c1c] transition-colors">
                            <span class="material-symbols-outlined text-sm">mail</span>
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

        {{-- TICKET DESKTOP --}}
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
                        <div>
                            <p class="text-[9px] text-[#747688] uppercase tracking-wider">Transacción</p>
                            <p class="text-sm font-bold" id="ticket-id">—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-[#747688] uppercase tracking-wider">Fecha</p>
                            <p class="text-sm font-bold" id="ticket-fecha">—</p>
                        </div>
                    </div>
                    <div class="space-y-3 mb-6 min-h-[80px]" id="ticket-productos">
                        <p class="text-xs text-[#747688] text-center pt-4">Selecciona una venta</p>
                    </div>
                    <div class="border-t border-dashed border-[#c4c5da]/40 pt-4 space-y-1.5">
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>Subtotal</span><span id="ticket-subtotal">$0.00</span></div>
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase font-bold"><span>IVA (0%)</span><span>$0.00</span></div>
                        <div class="flex justify-between text-base font-black text-[#1a1c1c] pt-2"><span>TOTAL</span><span id="ticket-total" class="text-[#1737c8]">$0.00</span></div>
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
                    <button onclick="enviarDigital()" class="flex-1 py-3.5 bg-[#1a1c1c] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-1.5 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-sm">mail</span>Email
                    </button>
                </div>
            </div>
        </aside>

    </div>
</main>

{{-- BOTÓN FLOTANTE MÓVIL --}}
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
        <div class="flex gap-px p-4 gap-3">
            <button onclick="imprimirTicketModal()" class="flex-1 py-3 bg-[#1737c8] text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 hover:opacity-90">
                <span class="material-symbols-outlined text-sm">print</span>Imprimir
            </button>
            <button onclick="enviarWhatsAppModal()" class="flex-1 py-3 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 hover:opacity-80">
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
            <button onclick="cerrarModalVenta()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('ventas.store') }}" id="form-nueva-venta" class="px-6 py-5 space-y-5">
            @csrf

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
                    <span data-id="{{ $cliente->id ?? $cliente['id'] }}" data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"></span>
                    @endforeach
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
                    <span
                        data-id="{{ $prod['id'] ?? '' }}"
                        data-nombre="{{ $prod['nombre'] ?? '' }}"
                        data-precio="{{ $prod['precio'] ?? 0 }}"
                        data-categoria="{{ $prod['categoria'] ?? '' }}"
                        data-tallas="{{ json_encode($prod['tallas'] ?? []) }}">
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Productos agregados --}}
            <div id="productos-container" class="space-y-2"></div>
            <div id="productos-hidden"></div>

            {{-- Total --}}
            <div class="bg-[#1737c8] px-5 py-4 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                <span class="text-2xl font-black text-white" id="total-calculado">$0.00</span>
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>Registrar venta
                </button>
                <button type="button" onclick="cerrarModalVenta()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                    Cancelar
                </button>
            </div>
        </form>
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
let ventaActual = null;
let ventaModalActual = null;

// ── SELECCIÓN DESKTOP ─────────────────────────────────────────
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
}

// ── TICKET MODAL MÓVIL ────────────────────────────────────────
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

function cerrarTicketModal() {
    document.getElementById('modal-ticket').classList.remove('activo');
    document.body.style.overflow = '';
}

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

// ── IMPRIMIR ──────────────────────────────────────────────────
function imprimirTicket(venta) { ventaActual = venta; prepararPrint(venta); setTimeout(() => window.print(), 300); }
function imprimirTicketActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } prepararPrint(ventaActual); setTimeout(() => window.print(), 300); }
function imprimirTicketModal() { if (!ventaModalActual) return; prepararPrint(ventaModalActual); cerrarTicketModal(); setTimeout(() => window.print(), 400); }

function prepararPrint(venta) {
    document.getElementById('print-ticket').style.display = 'block';
    document.getElementById('print-contenido').innerHTML = `
        <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #ccc;padding-bottom:12px;margin-bottom:16px;">
            <div><p style="font-size:9px;color:#747688;text-transform:uppercase;">Transacción</p><p style="font-weight:700;">#ID-${venta.id}</p></div>
            <div style="text-align:right;"><p style="font-size:9px;color:#747688;text-transform:uppercase;">Fecha</p><p style="font-weight:700;">${venta.fecha ?? ''}</p></div>
        </div>
        ${renderProductos(venta)}
        <div style="border-top:1px dashed #ccc;padding-top:12px;margin-top:16px;">
            <div style="display:flex;justify-content:space-between;font-weight:900;font-size:16px;"><span>TOTAL</span><span>$${parseFloat(venta.total).toFixed(2)}</span></div>
        </div>`;
}

// ── WHATSAPP ──────────────────────────────────────────────────
function enviarWhatsApp(venta) {
    const texto = `🧾 *Ticket de venta - ${venta.cliente}*\n#ID-${venta.id}\nTotal: $${parseFloat(venta.total).toFixed(2)}\n\n_Gracias por tu compra en {{ Auth::user()->store_name ?? 'nuestra tienda' }}_`;
    window.open('https://wa.me/?text=' + encodeURIComponent(texto), '_blank');
}
function enviarWhatsAppActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } enviarWhatsApp(ventaActual); }
function enviarWhatsAppModal() { if (!ventaModalActual) return; enviarWhatsApp(ventaModalActual); }

// ── EMAIL ─────────────────────────────────────────────────────
function enviarEmail(venta) { alert('Enviando por email la venta #ID-' + venta.id); }
function enviarDigital() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } enviarEmail(ventaActual); }

// ── BUSCADOR ──────────────────────────────────────────────────
function filtrarVentas(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.venta-card').forEach(row => {
        row.style.display = ((row.dataset.nombre ?? '').includes(q) || (row.dataset.id ?? '').includes(q)) ? '' : 'none';
    });
}

// ── MODAL NUEVA VENTA ─────────────────────────────────────────
function abrirModalVenta() {
    document.getElementById('modal-nueva-venta').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalVenta() {
    document.getElementById('modal-nueva-venta').classList.remove('activo');
    document.body.style.overflow = '';
    document.getElementById('cliente-search').value = '';
    document.getElementById('cliente-id-hidden').value = '';
    document.getElementById('clientes-sugerencias').classList.add('hidden');
    document.getElementById('lista-productos-categoria').classList.add('hidden');
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    productosVenta = [];
    renderizarProductosVenta();
}

// ── CLIENTE AUTOCOMPLETE ──────────────────────────────────────
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
}
document.addEventListener('click', e => {
    if (!e.target.closest('#cliente-search') && !e.target.closest('#clientes-sugerencias'))
        document.getElementById('clientes-sugerencias')?.classList.add('hidden');
});

// ── PRODUCTOS CON TALLAS ──────────────────────────────────────
let productosVenta = [];

function filtrarCategoria(categoria, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    btn.classList.add('bg-[#1a1c1c]','text-white','border-[#1a1c1c]');
    const productos = document.querySelectorAll('#productos-data span');
    const lista = document.getElementById('lista-productos-categoria');
    const filtrados = Array.from(productos).filter(p => p.dataset.categoria === categoria);

    if (filtrados.length === 0) {
        lista.innerHTML = '<div class="px-4 py-3 text-sm text-[#747688]">Sin productos en esta categoría</div>';
    } else {
        lista.innerHTML = filtrados.map(p => {
            const tallas = JSON.parse(p.dataset.tallas || '[]');
            const tallasHtml = tallas.length > 0
                ? tallas.map(t =>
                    `<button type="button"
                        onclick="agregarProductoVenta('${p.dataset.id}','${p.dataset.nombre}',${p.dataset.precio},'${t.talla}')"
                        class="px-2 py-1 text-[9px] font-black border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] hover:bg-white transition-all uppercase">
                        ${t.talla} <span class="text-[#747688]">(${t.stock})</span>
                    </button>`
                ).join('')
                : '<span class="text-[10px] text-red-500 font-bold">Sin stock disponible</span>';

            return `<div class="px-4 py-3 border-b border-[#c4c5da]/10">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs font-bold uppercase">${p.dataset.nombre}</p>
                        <p class="text-[10px] text-[#747688]">$${parseFloat(p.dataset.precio).toFixed(2)}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5">${tallasHtml}</div>
            </div>`;
        }).join('');
    }
    lista.classList.remove('hidden');
}

function agregarProductoVenta(id, nombre, precio, talla = null) {
    // Clave única por producto + talla
    const key = id + '_' + (talla ?? 'sin_talla');
    const existe = productosVenta.find(p => p.key === key);
    if (existe) {
        existe.cantidad += 1;
    } else {
        productosVenta.push({ key, id, nombre, precio: parseFloat(precio), cantidad: 1, talla });
    }
    renderizarProductosVenta();
}

function renderizarProductosVenta() {
    const container = document.getElementById('productos-container');
    if (productosVenta.length === 0) { container.innerHTML = ''; actualizarHiddens(); calcularTotal(); return; }
    container.innerHTML = `<div class="border border-[#c4c5da]/20 divide-y divide-[#c4c5da]/10">` +
        productosVenta.map((p, i) => `
        <div class="flex items-center gap-3 px-3 py-2.5">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold uppercase truncate">${p.nombre}</p>
                ${p.talla ? `<p class="text-[10px] text-[#1737c8] font-bold">Talla: ${p.talla}</p>` : ''}
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button type="button" onclick="cambiarCantidadVenta(${i},-1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black">−</button>
                <span class="w-8 text-center text-sm font-black">${p.cantidad}</span>
                <button type="button" onclick="cambiarCantidadVenta(${i},1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black">+</button>
            </div>
            <p class="text-xs font-black text-[#1737c8] w-16 text-right shrink-0">$${(p.precio*p.cantidad).toFixed(2)}</p>
            <button type="button" onclick="quitarProductoVenta(${i})" class="text-[#c4c5da] hover:text-red-500 transition-colors shrink-0">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>`).join('') + `</div>`;
    actualizarHiddens();
    calcularTotal();
}

function cambiarCantidadVenta(i, d) {
    productosVenta[i].cantidad += d;
    if (productosVenta[i].cantidad <= 0) productosVenta.splice(i, 1);
    renderizarProductosVenta();
}
function quitarProductoVenta(i) { productosVenta.splice(i, 1); renderizarProductosVenta(); }

function actualizarHiddens() {
    document.getElementById('productos-hidden').innerHTML = productosVenta.map(p =>
        `<input type="hidden" name="productos[]" value="${p.id}"/>
         <input type="hidden" name="cantidades[]" value="${p.cantidad}"/>
         <input type="hidden" name="precios[]" value="${p.precio}"/>
         <input type="hidden" name="tallas[]" value="${p.talla ?? ''}"/>`
    ).join('');
}

function calcularTotal() {
    document.getElementById('total-calculado').textContent = '$' + productosVenta.reduce((s, p) => s + p.precio * p.cantidad, 0).toFixed(2);
}
</script>

@include('partials._sidebar')

</body>
</html>