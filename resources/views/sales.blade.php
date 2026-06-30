<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Ventas</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&family=Roboto+Mono:wght@400;500;600;700&family=Courier+Prime:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

@php
    $esPro = ($plan ?? 'gratis') === 'pro' || ($plan ?? 'gratis') === 'business';
    $lealtadActivo = $lealtadActivo ?? false;
    $tenant = tenant();
    $tTemplate = $tenant->ticket_template ?? 'minimalista';
    $tColor    = $tenant->ticket_color    ?? '#1737c8';
    $tFont     = $tenant->ticket_font     ?? 'Inter';
    $tMsg      = $tenant->ticket_mensaje  ?? '¡Gracias por tu compra!';
    $tLogo     = $tenant->ticket_logo     ?? null;
    $tWa       = $tenant->ticket_whatsapp ?? null;
    $esBusiness = ($plan ?? 'gratis') === 'business';
@endphp

<style>
    body { font-family:'Inter',sans-serif; background-color:#f9f9f9; color:#1a1c1c; min-height:100dvh; transition:background 0.3s,color 0.3s; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }
    ::-webkit-scrollbar { width:4px; }
    ::-webkit-scrollbar-thumb { background:rgba(196,197,218,0.3); }
    .venta-card { transition:all 0.2s; cursor:pointer; }
    .venta-card:hover { background-color:#f3f3f4; }
    .venta-card.selected { background-color:rgba(23,55,200,0.04); box-shadow:inset 0 0 0 1.5px rgba(23,55,200,0.25); }
    #modal-nueva-venta,#modal-ticket,#modal-efectivo { display:none; }
    #modal-nueva-venta.activo,#modal-ticket.activo,#modal-efectivo.activo { display:flex; }
    .metodo-btn.activo { background:#1a1c1c; color:#fff; border-color:#1a1c1c; }
    .toggle-mayoreo { position:relative; display:inline-block; width:44px; height:24px; }
    .toggle-mayoreo input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#c4c5da; transition:.3s; border-radius:24px; }
    .toggle-slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background-color:white; transition:.3s; border-radius:50%; }
    input:checked + .toggle-slider { background-color:#f59e0b; }
    input:checked + .toggle-slider:before { transform:translateX(20px); }
    #btn-voz { transition:all 0.2s; }
    #btn-voz.escuchando { background:#dc2626 !important; animation:pulse-voz 1s infinite; }
    @keyframes pulse-voz { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.8;transform:scale(1.05);} }
    @keyframes fadeInUp { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }
    @keyframes slideInCard { from{opacity:0;transform:translateX(-6px);}to{opacity:1;transform:translateX(0);} }
    @keyframes popIn { 0%{opacity:0;transform:scale(0.92);}70%{transform:scale(1.02);}100%{opacity:1;transform:scale(1);} }
    @keyframes modalIn { from{opacity:0;transform:scale(0.96) translateY(8px);}to{opacity:1;transform:scale(1) translateY(0);} }
    .kpi-stat { animation:popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both; transition:transform 0.2s,box-shadow 0.2s; }
    .kpi-stat:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(23,55,200,0.1); }
    .kpi-stat:nth-child(1){animation-delay:0.05s;} .kpi-stat:nth-child(2){animation-delay:0.12s;} .kpi-stat:nth-child(3){animation-delay:0.19s;}
    .venta-card { animation:slideInCard 0.35s ease both; }
    .modal-content { animation:modalIn 0.25s ease both; }

    /* ── TICKET PAPER ─────────────────────────────────────── */
    .ticket-paper {
        --accent: {{ $tColor }};
        background:#ffffff !important; color:#1a1c1c !important;
        font-size:11px; width:100%; border-radius:8px;
        position:relative; overflow:hidden;
        box-shadow:0 10px 40px rgba(0,0,0,0.15);
        font-family: '{{ $tFont }}', Arial, sans-serif;
    }
    .ticket-watermark { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; z-index:0; overflow:hidden; }
    .ticket-watermark img { width:160px; opacity:0.05; transform:rotate(-30deg); filter:grayscale(100%); user-select:none; }
    .ticket-content { position:relative; z-index:1; display:flex; flex-direction:column; }

    /* MINIMALISTA */
    .tpl-minimalista .tk-header { padding:20px 16px 14px; text-align:center; border-bottom:2px solid var(--accent); }
    .tpl-minimalista .tk-logo-wrap { display:flex; justify-content:center; margin-bottom:8px; }
    .tpl-minimalista .tk-logo-wrap img { max-height:32px; max-width:90px; object-fit:contain; }
    .tpl-minimalista .tk-store { font-size:14px; font-weight:900; color:var(--accent); }
    .tpl-minimalista .tk-folio { font-size:8px; color:#9496a8; margin-top:3px; text-transform:uppercase; letter-spacing:.1em; }
    .tpl-minimalista .tk-section { padding:9px 16px; border-bottom:1px dashed #e5e5f0; }
    .tpl-minimalista .tk-sectitle { font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.15em; color:#9496a8; margin-bottom:5px; }
    .tpl-minimalista .tk-total { margin:12px 16px; border-radius:8px; padding:12px 14px; background:#f9f9fb; border:1.5px solid var(--accent); text-align:center; }
    .tpl-minimalista .tk-total-l { font-size:8px; font-weight:700; color:#9496a8; text-transform:uppercase; }
    .tpl-minimalista .tk-total-v { font-size:22px; font-weight:900; color:var(--accent); }
    .tpl-minimalista .tk-footer { padding:10px 16px 14px; text-align:center; }

    /* DESTACADO */
    .tpl-destacado .tk-header { background:var(--accent); padding:18px 16px 14px; position:relative; overflow:hidden; }
    .tpl-destacado .tk-header::after { content:''; position:absolute; bottom:-20px; right:-20px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.08); }
    .tpl-destacado .tk-logo-wrap { margin-bottom:8px; }
    .tpl-destacado .tk-logo-wrap img { max-height:28px; max-width:80px; object-fit:contain; filter:brightness(0) invert(1); opacity:0.9; }
    .tpl-destacado .tk-store { font-size:14px; font-weight:900; color:#fff; }
    .tpl-destacado .tk-folio { font-size:8px; color:rgba(255,255,255,.6); margin-top:2px; }
    .tpl-destacado .tk-section { padding:9px 16px; border-bottom:1px solid #f0f0f0; }
    .tpl-destacado .tk-sectitle { font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.15em; color:var(--accent); margin-bottom:5px; }
    .tpl-destacado .tk-total { background:#1a1c1c; padding:14px 16px; }
    .tpl-destacado .tk-total-l { font-size:8px; font-weight:700; color:rgba(255,255,255,.4); text-transform:uppercase; }
    .tpl-destacado .tk-total-v { font-size:22px; font-weight:900; color:var(--accent); }
    .tpl-destacado .tk-footer { padding:10px 16px 14px; background:#fafafa; }

    /* CLÁSICO */
    .tpl-clasico .tk-header { padding:14px; border-bottom:2px dashed #1a1c1c; }
    .tpl-clasico .tk-logo-wrap img { max-height:28px; max-width:80px; object-fit:contain; }
    .tpl-clasico .tk-store { font-size:12px; font-weight:900; color:#1a1c1c; text-transform:uppercase; letter-spacing:.04em; margin-top:4px; }
    .tpl-clasico .tk-folio { font-size:8px; color:#9496a8; margin-top:1px; }
    .tpl-clasico .tk-section { padding:8px 14px; border-bottom:1px dashed #c4c5da; }
    .tpl-clasico .tk-sectitle { font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.12em; color:#1a1c1c; margin-bottom:4px; padding-bottom:3px; border-bottom:1px dotted #c4c5da; }
    .tpl-clasico .tk-total { padding:10px 14px; border-top:2px dashed #1a1c1c; border-bottom:2px dashed #1a1c1c; background:#f9f9f9; }
    .tpl-clasico .tk-total-l { font-size:8px; font-weight:700; color:#9496a8; text-transform:uppercase; }
    .tpl-clasico .tk-total-v { font-size:20px; font-weight:900; color:#1a1c1c; }
    .tpl-clasico .tk-footer { padding:8px 14px 12px; }

    /* COMUNES */
    .tk-prod-row { display:flex; justify-content:space-between; margin-bottom:6px; }
    .tk-prod-name { font-size:10px; font-weight:700; color:#1a1c1c; }
    .tk-prod-det { font-size:8px; color:#9496a8; margin-top:1px; }
    .tk-prod-precio { font-size:10px; font-weight:900; color:#1a1c1c; text-align:right; }
    .tk-lbl { font-size:8px; color:#9496a8; }
    .tk-val { font-size:9px; font-weight:700; color:#1a1c1c; text-align:right; }
    .tk-footer-msg { font-size:9px; color:#1a1c1c; font-weight:600; margin-bottom:4px; }
    .tk-footer-sub { font-size:7px; color:#9496a8; }
    .tk-powered { font-size:7px; color:#c4c5da; text-align:center; margin-top:4px; letter-spacing:.1em; text-transform:uppercase; }

    @media print {
        body > *:not(#print-ticket) { display:none !important; }
        #print-ticket { display:block !important; position:fixed; inset:0; background:white; padding:20px; z-index:9999; }
        * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
        .ticket-paper { box-shadow:none !important; border:1px solid #e5e5f0; max-width:320px; margin:0 auto; }
    }

    /* DARK MODE */
    [data-theme="dark"] body { background-color:#0f1012 !important; color:#f3f3f4 !important; }
    [data-theme="dark"] main { background-color:#0f1012 !important; }
    [data-theme="dark"] .bg-white { background-color:#1e2022 !important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\] { background-color:#0f1012 !important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background-color:#141618 !important; }
    [data-theme="dark"] .text-\[\#1a1c1c\] { color:#f3f3f4 !important; }
    [data-theme="dark"] .text-\[\#747688\] { color:#9496a8 !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/15 { border-color:rgba(255,255,255,0.06) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color:rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .venta-card:hover { background-color:rgba(255,255,255,0.04) !important; }
    [data-theme="dark"] .venta-card.selected { background-color:rgba(23,55,200,0.12) !important; }
    [data-theme="dark"] input,[data-theme="dark"] select { background-color:#141618 !important; color:#f3f3f4 !important; border-color:rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] input::placeholder { color:#9496a8 !important; }
    [data-theme="dark"] .bg-white\/80 { background:rgba(30,32,34,0.85) !important; }
    [data-theme="dark"] .bg-blue-50 { background-color:rgba(23,55,200,0.08) !important; }
    [data-theme="dark"] .bg-green-100 { background-color:rgba(34,197,94,0.12) !important; }
    [data-theme="dark"] .bg-amber-100 { background-color:rgba(245,158,11,0.12) !important; }
    [data-theme="dark"] .bg-red-100 { background-color:rgba(239,68,68,0.12) !important; }
    [data-theme="dark"] .bg-green-50 { background-color:rgba(34,197,94,0.08) !important; }
    [data-theme="dark"] .bg-red-50 { background-color:rgba(239,68,68,0.08) !important; }
    [data-theme="dark"] .bg-amber-50 { background-color:rgba(245,158,11,0.08) !important; }
    [data-theme="dark"] #modal-nueva-venta .bg-white,[data-theme="dark"] #modal-ticket .modal-content,[data-theme="dark"] #modal-efectivo .bg-white { background-color:#1e2022 !important; }
    [data-theme="dark"] #modal-nueva-venta .sticky.bg-white { background-color:#1e2022 !important; }
    [data-theme="dark"] nav.fixed.bottom-0 { background:rgba(15,16,18,0.92) !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] #clientes-sugerencias { background-color:#1e2022 !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] #clientes-sugerencias div:hover { background-color:#141618 !important; }
    [data-theme="dark"] #lista-productos-categoria { background-color:#141618 !important; }
    [data-theme="dark"] .metodo-btn { border-color:rgba(255,255,255,0.1) !important; color:#9496a8; }
    [data-theme="dark"] .metodo-btn.activo { background:#f3f3f4 !important; color:#1a1c1c !important; }
    [data-theme="dark"] .cat-btn { border-color:rgba(255,255,255,0.1) !important; color:#9496a8; }
    [data-theme="dark"] .ticket-paper { box-shadow:0 10px 40px rgba(0,0,0,0.4); }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background-color:#141618 !important; }
</style>
</head>
<body>

@include('partials._nav')

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
<div class="mx-4 md:mx-6 mb-2 bg-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2 rounded-xl">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('ticket_metodo'))
<div class="mx-4 md:mx-6 mb-4 bg-blue-50 border border-blue-200 px-4 py-4 rounded-xl">
    <p class="font-black uppercase tracking-widest text-[#1737c8] text-[10px] mb-3">Resumen del último pago</p>
    <div class="flex gap-6 flex-wrap">
        <div>
            <p class="text-[9px] text-[#747688] uppercase font-bold mb-0.5">Tipo</p>
            <p class="font-black text-sm {{ session('ticket_tipo') === 'mayoreo' ? 'text-amber-600' : 'text-[#1a1c1c]' }}">{{ ucfirst(session('ticket_tipo') ?? 'menudeo') }}</p>
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
<div class="mx-4 md:mx-6 mb-4 bg-red-100 text-red-700 px-4 py-3 text-sm font-bold flex items-center gap-2 rounded-xl">
    <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
</div>
@endif

<main class="pb-32 md:pb-12 px-4 md:px-6 max-w-[1600px] mx-auto">
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="kpi-stat rounded-xl bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Ventas hoy</p>
            <p class="text-xl font-black" style="color:{{ $tColor }}">{{ count($ventas ?? []) }}</p>
        </div>
        <div class="kpi-stat rounded-xl bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Total</p>
            <p class="text-xl font-black text-[#1a1c1c]">${{ number_format(collect($ventas ?? [])->sum('total'), 0) }}</p>
        </div>
        <div class="kpi-stat rounded-xl bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Promedio</p>
            <p class="text-xl font-black text-[#1a1c1c]">${{ count($ventas ?? []) > 0 ? number_format(collect($ventas ?? [])->sum('total') / count($ventas), 0) : '0' }}</p>
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
                        <input id="buscador" class="pl-9 pr-4 py-2 bg-white border rounded-lg border-[#c4c5da]/30 focus:border-[#1737c8] focus:outline-none text-sm w-48" placeholder="Buscar..." oninput="filtrarVentas(this.value)"/>
                    </div>
                    @if($esBusiness)
                    <a href="{{ route('ticket.studio') }}" class="bg-[#1a1c1c] text-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-80 transition-all flex items-center gap-1.5 shrink-0 rounded-lg">
                        <span class="material-symbols-outlined text-sm">palette</span>
                        <span class="hidden sm:inline">Ticket Studio</span>
                    </a>
                    @endif
                    <button onclick="abrirModalVenta()" class="text-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-1.5 shrink-0 rounded-lg" style="background:{{ $tColor }}">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span class="hidden sm:inline">Nueva venta</span>
                        <span class="sm:hidden">Nueva</span>
                    </button>
                </div>
            </div>
            <div class="relative md:hidden mb-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                <input class="pl-9 pr-4 py-2.5 bg-white border rounded-lg border-[#c4c5da]/30 focus:border-[#1737c8] focus:outline-none text-sm w-full" placeholder="Buscar..." oninput="filtrarVentas(this.value)"/>
            </div>

            <div id="lista-ventas" class="space-y-2">
                @forelse($ventas ?? [] as $venta)
                {{-- MÓVIL --}}
                <div class="venta-card lg:hidden bg-white border rounded-lg border-[#c4c5da]/15 p-4"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVentaMovil({{ json_encode($venta) }}, this)">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 flex items-center justify-center shrink-0 rounded-lg" style="background:{{ $tColor }}1a">
                                <span class="font-black text-xs" style="color:{{ $tColor }}">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm truncate">{{ $venta['cliente'] }}</p>
                                <p class="text-[10px] text-[#747688] uppercase tracking-wider">#ID-{{ $venta['id'] }} · {{ $venta['fecha'] ?? '' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-black text-sm" style="color:{{ $tColor }}">${{ number_format($venta['total'], 2) }}</p>
                            <div class="flex gap-1 justify-end mt-0.5">
                                <span class="text-[9px] font-bold px-2 py-0.5 bg-green-100 text-green-700 uppercase rounded">Completada</span>
                                @if(($venta['tipo_venta'] ?? 'menudeo') === 'mayoreo')
                                <span class="text-[9px] font-bold px-2 py-0.5 bg-amber-100 text-amber-700 uppercase rounded">Mayoreo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-[#c4c5da]/10">
                        <button onclick="event.stopPropagation(); abrirTicketModal({{ json_encode($venta) }})"
                                class="flex-1 py-2 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1 rounded-lg" style="background:{{ $tColor }}">
                            <span class="material-symbols-outlined text-sm">receipt</span>Ver ticket
                        </button>
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})"
                                class="px-3 py-2 bg-[#1a1c1c] text-white hover:opacity-80 transition-all rounded-lg shrink-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                        </button>
                        <button onclick="event.stopPropagation(); enviarWhatsApp({{ json_encode($venta) }})"
                                class="px-3 py-2 bg-green-500 text-white hover:opacity-80 transition-all rounded-lg shrink-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                        </button>
                    </div>
                </div>
                {{-- DESKTOP --}}
                <div class="venta-card hidden lg:grid grid-cols-12 px-5 py-4 items-center bg-white border rounded-lg border-[#c4c5da]/15"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVenta({{ json_encode($venta) }}, this)">
                    <div class="col-span-4 flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center shrink-0 rounded-lg" style="background:{{ $tColor }}1a">
                            <span class="font-black text-xs" style="color:{{ $tColor }}">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ $venta['cliente'] }}</p>
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider">#ID-{{ $venta['id'] }}</p>
                        </div>
                    </div>
                    <div class="col-span-2 text-right">
                        <span class="font-black text-sm" style="color:{{ $tColor }}">${{ number_format($venta['total'], 2) }}</span>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-green-100 text-green-700 rounded">Completada</span>
                    </div>
                    <div class="col-span-1 text-center">
                        @if(($venta['tipo_venta'] ?? 'menudeo') === 'mayoreo')
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-amber-100 text-amber-700 rounded">Mayoreo</span>
                        @else
                        <span class="text-[9px] font-bold px-2 py-1 uppercase bg-[#f3f3f4] text-[#747688] rounded">Menudeo</span>
                        @endif
                    </div>
                    <div class="col-span-3 flex justify-end gap-1.5">
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})"
                                class="p-2 bg-[#1a1c1c] text-white hover:opacity-80 transition-colors rounded-lg">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                        </button>
                        <button onclick="event.stopPropagation(); enviarWhatsApp({{ json_encode($venta) }})"
                                class="p-2 bg-green-500 text-white hover:opacity-80 transition-colors rounded-lg">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="bg-white border rounded-lg border-[#c4c5da]/15 px-6 py-16 text-center">
                    <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">receipt_long</span>
                    <p class="text-sm font-bold text-[#747688]">Sin ventas registradas hoy</p>
                    <button onclick="abrirModalVenta()" class="mt-4 text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all rounded-lg" style="background:{{ $tColor }}">
                        Registrar primera venta
                    </button>
                </div>
                @endforelse
            </div>
        </section>

        {{-- TICKET ASIDE --}}
        <aside class="hidden lg:block lg:col-span-5 xl:col-span-4 lg:sticky lg:top-24 h-fit">
            <div class="bg-white border rounded-xl border-[#c4c5da]/20 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-[#c4c5da]/10 bg-[#f9f9f9]">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Ticket de venta</p>
                    <p class="text-sm font-bold text-[#1a1c1c] mt-0.5" id="aside-subtitle">Selecciona una venta</p>
                </div>
                <div class="p-5 bg-[#f3f3f4] flex justify-center min-h-[400px]">
                    {{-- TICKET DINÁMICO con diseño del Studio --}}
                    <div id="ticket-paper-desktop" class="ticket-paper tpl-{{ $tTemplate }}" style="--accent:{{ $tColor }}; font-family:'{{ $tFont }}',Arial,sans-serif;">
                        <div class="ticket-watermark">
                            <img src="{{ url('images/quivex-logo.png') }}" alt=""/>
                        </div>
                        <div class="ticket-content">
                            <div class="tk-header">
                                <div class="tk-logo-wrap">
                                    @if($tLogo)
                                    <img src="{{ asset('storage/'.$tLogo) }}" alt="logo"/>
                                    @else
                                    <img src="{{ url('images/quivex-logo.png') }}" alt="logo" style="{{ $tTemplate === 'destacado' ? 'filter:brightness(0) invert(1);opacity:0.9;' : '' }}max-height:28px;max-width:80px;object-fit:contain;"/>
                                    @endif
                                </div>
                                <div class="tk-store">{{ Auth::user()->store_name ?? 'Mi Tienda' }}</div>
                                <div class="tk-folio folio-txt">FOLIO: —</div>
                            </div>
                            <div class="tk-section">
                                <div class="tk-sectitle">Transacción</div>
                                <table width="100%" style="border-collapse:collapse;">
                                    <tr><td class="tk-lbl">Cliente</td><td class="tk-val cliente-txt" style="text-align:right;">—</td></tr>
                                    <tr><td class="tk-lbl">Método</td><td class="tk-val metodo-txt" style="text-align:right;">—</td></tr>
                                </table>
                            </div>
                            <div class="tk-section">
                                <div class="tk-sectitle">Productos</div>
                                <div class="productos-txt">
                                    <p style="font-size:10px;color:#9496a8;text-align:center;padding:8px 0;">Selecciona una venta</p>
                                </div>
                            </div>
                            <div class="tk-section" style="border-bottom:none;">
                                <table width="100%" style="border-collapse:collapse;">
                                    <tr><td class="tk-lbl">Subtotal</td><td class="tk-val subtotal-txt" style="text-align:right;">$0.00</td></tr>
                                    <tr><td class="tk-lbl">IVA (0%)</td><td class="tk-val" style="text-align:right;">$0.00</td></tr>
                                </table>
                            </div>
                            <div class="tk-total">
                                <div class="tk-total-l">Total</div>
                                <div class="tk-total-v total-txt">$0.00</div>
                            </div>
                            <div class="tk-footer">
                                <div class="tk-footer-msg">{{ $tMsg }}</div>
                                <div class="tk-footer-sub">{{ now()->format('d/m/Y H:i') }}</div>
                                <div class="tk-powered">Generado por Quivex</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-px border-t border-[#c4c5da]/10">
                    <button onclick="imprimirTicketActual()" class="flex-1 py-3.5 text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-1.5 hover:opacity-90 transition-all" style="background:{{ $tColor }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>Imprimir
                    </button>
                    <button onclick="enviarWhatsAppActual()" class="flex-1 py-3.5 bg-green-500 text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-1.5 hover:opacity-80 transition-all">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>WhatsApp
                    </button>
                </div>
            </div>
        </aside>
    </div>
</main>

<button onclick="abrirModalVenta()"
        class="lg:hidden fixed bottom-20 right-4 z-40 w-14 h-14 text-white rounded-full shadow-xl flex items-center justify-center hover:opacity-90 transition-all active:scale-95" style="background:{{ $tColor }}">
    <span class="material-symbols-outlined text-2xl">add</span>
</button>

{{-- MODAL TICKET MÓVIL --}}
<div id="modal-ticket" class="fixed inset-0 z-50 items-end justify-center bg-black/50 backdrop-blur-sm lg:hidden" onclick="if(event.target===this) cerrarTicketModal()">
    <div class="modal-content bg-[#f3f3f4] rounded-t-2xl w-full max-w-md max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-[#c4c5da]/20 sticky top-0 z-10">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:{{ $tColor }}">Ticket de venta</p>
                <h2 class="text-lg font-bold tracking-tight" id="modal-ticket-titulo">—</h2>
            </div>
            <button onclick="cerrarTicketModal()" class="p-2 hover:bg-[#f3f3f4] transition-colors rounded-full">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-5 flex justify-center">
            <div id="ticket-paper-mobile" class="ticket-paper tpl-{{ $tTemplate }}" style="--accent:{{ $tColor }}; font-family:'{{ $tFont }}',Arial,sans-serif;">
                <div class="ticket-watermark">
                    <img src="{{ url('images/quivex-logo.png') }}" alt=""/>
                </div>
                <div class="ticket-content">
                    <div class="tk-header">
                        <div class="tk-logo-wrap">
                            @if($tLogo)
                            <img src="{{ asset('storage/'.$tLogo) }}" alt="logo"/>
                            @else
                            <img src="{{ url('images/quivex-logo.png') }}" alt="logo" style="max-height:28px;max-width:80px;object-fit:contain;"/>
                            @endif
                        </div>
                        <div class="tk-store">{{ Auth::user()->store_name ?? 'Mi Tienda' }}</div>
                        <div class="tk-folio folio-txt">FOLIO: —</div>
                    </div>
                    <div class="tk-section">
                        <div class="tk-sectitle">Transacción</div>
                        <table width="100%" style="border-collapse:collapse;">
                            <tr><td class="tk-lbl">Cliente</td><td class="tk-val cliente-txt" style="text-align:right;">—</td></tr>
                            <tr><td class="tk-lbl">Método</td><td class="tk-val metodo-txt" style="text-align:right;">—</td></tr>
                        </table>
                    </div>
                    <div class="tk-section">
                        <div class="tk-sectitle">Productos</div>
                        <div class="productos-txt"></div>
                    </div>
                    <div class="tk-section" style="border-bottom:none;">
                        <table width="100%" style="border-collapse:collapse;">
                            <tr><td class="tk-lbl">Subtotal</td><td class="tk-val subtotal-txt" style="text-align:right;">$0.00</td></tr>
                            <tr><td class="tk-lbl">IVA (0%)</td><td class="tk-val" style="text-align:right;">$0.00</td></tr>
                        </table>
                    </div>
                    <div class="tk-total">
                        <div class="tk-total-l">Total</div>
                        <div class="tk-total-v total-txt">$0.00</div>
                    </div>
                    <div class="tk-footer">
                        <div class="tk-footer-msg">{{ $tMsg }}</div>
                        <div class="tk-footer-sub">{{ now()->format('d/m/Y H:i') }}</div>
                        <div class="tk-powered">Generado por Quivex</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-3 p-4 bg-white sticky bottom-0 border-t border-[#c4c5da]/20">
            <button onclick="imprimirTicketModal()" class="flex-1 py-3 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 rounded-xl" style="background:{{ $tColor }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>Imprimir
            </button>
            <button onclick="enviarWhatsAppModal()" class="flex-1 py-3 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 rounded-xl">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>WhatsApp
            </button>
        </div>
    </div>
</div>

{{-- MODAL NUEVA VENTA --}}
<div id="modal-nueva-venta" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-3 md:px-4" onclick="if(event.target===this) cerrarModalVenta()">
    <div class="modal-content bg-white rounded-2xl w-full max-w-lg max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 sticky top-0 bg-white z-10">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest mb-0.5" style="color:{{ $tColor }}">Registro</p>
                <h2 class="text-xl font-bold tracking-tight">Nueva venta</h2>
            </div>
            <div class="flex items-center gap-2">
                @if($esPro)
                <button type="button" id="btn-voz" onclick="toggleVoz()"
                        class="relative p-2 bg-[#f3f3f4] border border-[#c4c5da]/40 hover:text-white transition-all rounded-lg"
                        title="Agregar por voz ({{ $creditosVoz ?? 0 }} créditos)">
                    <span class="material-symbols-outlined text-sm" id="icon-voz">mic</span>
                    <span id="badge-voz" class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 text-white text-[9px] font-black flex items-center justify-center rounded-full" style="background:{{ $tColor }}">{{ $creditosVoz ?? 0 }}</span>
                </button>
                @endif
                <button onclick="cerrarModalVenta()" class="p-2 hover:bg-[#f3f3f4] transition-colors rounded-lg">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>

        @if($esPro)
        <div id="voz-status" class="hidden px-6 pt-4">
            <div id="voz-escuchando" class="hidden bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-3 rounded-lg">
                <span class="material-symbols-outlined text-red-500 text-sm animate-pulse">mic</span>
                <div class="flex-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-700">Escuchando...</p>
                    <p class="text-xs text-red-500" id="voz-transcript">Di el producto, ej: "Air Force One talla 27"</p>
                </div>
                <button type="button" onclick="detenerVoz()" class="text-red-400 hover:text-red-600">
                    <span class="material-symbols-outlined text-sm">stop_circle</span>
                </button>
            </div>
            <div id="voz-procesando" class="hidden bg-[#1737c8]/5 border border-[#1737c8]/20 px-4 py-3 flex items-center gap-3 rounded-lg">
                <svg class="animate-spin w-4 h-4 text-[#1737c8] shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <p class="text-xs font-bold text-[#1737c8]">Interpretando con IA...</p>
            </div>
            <div id="voz-resultado" class="hidden bg-green-50 border border-green-200 px-4 py-3 flex items-center gap-3 rounded-lg">
                <span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>
                <p class="text-xs font-bold text-green-700" id="voz-resultado-text"></p>
            </div>
            <div id="voz-error" class="hidden bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-3 rounded-lg">
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

            <div class="flex flex-col gap-1.5 relative">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                <input type="text" id="cliente-search" name="cliente_nombre"
                       placeholder="Busca o escribe el nombre..." autocomplete="off"
                       oninput="buscarClienteVenta(this.value)"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] rounded-lg"/>
                <input type="hidden" name="cliente_id" id="cliente-id-hidden"/>
                <p class="text-[9px] text-[#747688]">Si no existe se registra automáticamente</p>
                <div id="clientes-sugerencias" class="absolute top-[72px] left-0 right-0 bg-white border rounded-lg border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-40 overflow-y-auto"></div>
                <div id="clientes-data" class="hidden">
                    @foreach($clientes ?? [] as $cliente)
                    <span data-id="{{ $cliente->id ?? $cliente['id'] }}" data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}" data-puntos="{{ $cliente->puntos ?? 0 }}"></span>
                    @endforeach
                </div>
            </div>

            @if($lealtadActivo)
            <div id="lealtad-section" class="hidden space-y-2">
                <div id="lealtad-pregunta" class="bg-amber-50 border border-amber-200 px-4 py-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-amber-500 text-sm">stars</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Programa de lealtad</p>
                        <span class="text-sm font-black text-amber-600 ml-auto" id="lealtad-puntos-display">0 pts</span>
                    </div>
                    <p class="text-xs text-amber-800 mb-3" id="lealtad-mensaje">¿Qué deseas hacer con tus puntos?</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="elegirLealtad('acumular')" class="py-2.5 text-[10px] font-black uppercase tracking-widest border-2 border-amber-300 text-amber-700 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center gap-1 rounded-lg">
                            <span class="material-symbols-outlined text-sm">savings</span>Acumular
                        </button>
                        <button type="button" onclick="elegirLealtad('usar')" class="py-2.5 text-[10px] font-black uppercase tracking-widest bg-amber-500 text-white hover:bg-amber-600 transition-all flex items-center justify-center gap-1 rounded-lg">
                            <span class="material-symbols-outlined text-sm">redeem</span>Usar puntos
                        </button>
                    </div>
                </div>
                <div id="lealtad-canje" class="hidden bg-amber-50 border border-amber-200 px-4 py-3 space-y-2 rounded-lg">
                    <div class="flex items-center gap-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-amber-700 shrink-0">Puntos a canjear:</label>
                        <input type="number" id="puntos-canjear-input" placeholder="0" min="0" oninput="actualizarCanjePuntos()"
                               class="flex-1 border border-amber-300 bg-white px-3 py-1.5 text-sm font-black focus:outline-none focus:border-amber-500 rounded"/>
                        <span class="text-[9px] text-amber-600 font-bold shrink-0" id="lealtad-descuento-display">-$0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] text-amber-600">1 punto = $1 de descuento</p>
                        <button type="button" onclick="elegirLealtad('acumular')" class="text-[9px] text-amber-500 underline font-bold">Cancelar</button>
                    </div>
                </div>
            </div>
            @endif

            @if($esPro)
            <div class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3 rounded-lg">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">Venta mayoreo</p>
                    <p class="text-[9px] text-[#747688] mt-0.5" id="mayoreo-label">Activar para modificar precios</p>
                </div>
                <label class="toggle-mayoreo">
                    <input type="checkbox" id="toggle-mayoreo-sales" onchange="toggleMayoreoSales(this.checked)"/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            @else
            <a href="{{ route('planes') }}" class="flex items-center justify-between bg-[#f9f9f9] border border-[#c4c5da]/40 px-4 py-3 group rounded-lg">
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

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Método de pago</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="seleccionarMetodo('efectivo', this)" class="metodo-btn activo py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all rounded-lg">
                        <span class="material-symbols-outlined text-sm">payments</span>Efectivo
                    </button>
                    <button type="button" onclick="seleccionarMetodo('tarjeta', this)" class="metodo-btn py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c] rounded-lg">
                        <span class="material-symbols-outlined text-sm">credit_card</span>Tarjeta
                    </button>
                    <button type="button" onclick="seleccionarMetodo('transferencia', this)" class="metodo-btn py-3 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 flex flex-col items-center gap-1 transition-all hover:border-[#1a1c1c] rounded-lg">
                        <span class="material-symbols-outlined text-sm">account_balance</span>Transfer.
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Agregar productos</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="filtrarCategoria('ropa', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all rounded-lg">Ropa</button>
                    <button type="button" onclick="filtrarCategoria('calzado', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all rounded-lg">Calzado</button>
                    <button type="button" onclick="filtrarCategoria('accesorios', this)" class="cat-btn py-2.5 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all rounded-lg">Accesorios</button>
                </div>
                <div id="lista-productos-categoria" class="hidden max-h-52 overflow-y-auto border border-[#c4c5da]/20 bg-[#f9f9f9] rounded-lg"></div>
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

            <div class="px-5 py-4 flex justify-between items-center rounded-xl" style="background:{{ $tColor }}">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                <span class="text-2xl font-black text-white" id="total-calculado">$0.00</span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="procesarVenta()" class="flex-1 text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2 rounded-xl" style="background:{{ $tColor }}">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>Registrar venta
                </button>
                <button type="button" onclick="cerrarModalVenta()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all rounded-xl">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EFECTIVO --}}
<div id="modal-efectivo" class="fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="modal-content bg-white rounded-2xl w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:{{ $tColor }}">Pago en efectivo</p>
                <h2 class="text-xl font-bold tracking-tight">¿Cuánto recibiste?</h2>
            </div>
            <button onclick="cerrarModalEfectivo()" class="p-2 hover:bg-[#f3f3f4] transition-colors rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center rounded-lg">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total a cobrar</span>
                <span class="text-xl font-black" id="efectivo-total" style="color:{{ $tColor }}">$0.00</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Monto recibido</label>
                <input type="number" id="efectivo-recibido" placeholder="0.00" min="0" step="0.01" oninput="calcularCambio()"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-lg font-black focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] rounded-lg"/>
            </div>
            <div class="grid grid-cols-4 gap-2" id="efectivo-rapidos"></div>
            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center rounded-lg">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cambio</span>
                <span class="text-xl font-black text-green-600" id="efectivo-cambio">$0.00</span>
            </div>
            <p id="efectivo-error" class="text-xs text-red-500 font-bold hidden">El monto recibido es menor al total.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button onclick="confirmarEfectivo()" class="flex-1 py-4 bg-[#1a1c1c] text-white text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2 rounded-xl">
                <span class="material-symbols-outlined text-sm">check</span>Confirmar venta
            </button>
            <button onclick="cerrarModalEfectivo()" class="px-4 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all rounded-xl">Cancelar</button>
        </div>
    </div>
</div>

{{-- PRINT TICKET --}}
<div id="print-ticket" style="display:none; padding:20px;">
    <div id="print-contenido"></div>
</div>

{{-- BOTTOM NAV --}}
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    <a href="/sales" class="flex flex-col items-center pt-2" style="color:{{ $tColor }};border-top:2px solid {{ $tColor }}"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
    <a href="/clientes" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">group</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span></a>
</nav>

<script>
const PLAN         = '{{ $plan ?? "gratis" }}';
const ES_PRO       = PLAN === 'pro' || PLAN === 'business';
const ES_BUSINESS  = PLAN === 'business';
const ES_LEALTAD   = {{ $lealtadActivo ? 'true' : 'false' }};
const CSRF_TOKEN   = '{{ csrf_token() }}';
const TICKET_COLOR = '{{ $tColor }}';
const STORE_NAME   = '{{ addslashes(Auth::user()->store_name ?? "nuestra tienda") }}';
let ventaActual = null, ventaModalActual = null;
let metodoPagoActual = 'efectivo', tipoVentaActual = 'menudeo';
let totalVentaActual = 0, esMayoreo = false;
let recognition = null, vozActiva = false;
let productosVenta = [];

// ── FILL TICKET ───────────────────────────────────────────
function fillTicketData(containerId, v) {
    const c = document.getElementById(containerId);
    if (!c) return;
    c.querySelector('.folio-txt').textContent = 'FOLIO: #' + v.id + ' · ' + (v.fecha || '—');
    c.querySelector('.cliente-txt').textContent = v.cliente || '—';
    const tipo   = v.tipo_venta   ? v.tipo_venta.charAt(0).toUpperCase()   + v.tipo_venta.slice(1)   : 'Menudeo';
    const metodo = v.metodo_pago  ? v.metodo_pago.charAt(0).toUpperCase()  + v.metodo_pago.slice(1)  : '—';
    c.querySelector('.metodo-txt').textContent   = metodo + ' · ' + tipo;
    c.querySelector('.subtotal-txt').textContent = '$' + parseFloat(v.total).toFixed(2);
    c.querySelector('.total-txt').textContent    = '$' + parseFloat(v.total).toFixed(2);
    let prodHtml = '';
    if (v.productos && v.productos.length) {
        prodHtml = v.productos.map(p => `
            <div class="tk-prod-row">
                <div><div class="tk-prod-name">${p.nombre}</div><div class="tk-prod-det">${p.detalle || ''}</div></div>
                <div class="tk-prod-precio">$${parseFloat(p.precio).toFixed(2)}</div>
            </div>`).join('');
    } else {
        prodHtml = '<p style="font-size:10px;color:#9496a8;text-align:center;padding:8px 0;">Sin detalle</p>';
    }
    c.querySelector('.productos-txt').innerHTML = prodHtml;
}

// ── SELECCIÓN VENTAS ──────────────────────────────────────
function seleccionarVenta(v, el) {
    ventaActual = v;
    document.querySelectorAll('.venta-card').forEach(r => r.classList.remove('selected'));
    el.classList.add('selected');
    fillTicketData('ticket-paper-desktop', v);
    document.getElementById('aside-subtitle').textContent = '#ID-' + v.id + ' · ' + v.cliente;
}
function seleccionarVentaMovil(v, el) { seleccionarVenta(v, el); }

function abrirTicketModal(v) {
    ventaModalActual = v;
    document.getElementById('modal-ticket-titulo').textContent = '#ID-' + v.id + ' · ' + v.cliente;
    fillTicketData('ticket-paper-mobile', v);
    document.getElementById('modal-ticket').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarTicketModal() { document.getElementById('modal-ticket').classList.remove('activo'); document.body.style.overflow = ''; }

// ── IMPRIMIR ──────────────────────────────────────────────
function imprimirTicket(v)      { ventaActual = v; prepararPrint(v); setTimeout(() => window.print(), 500); }
function imprimirTicketActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } prepararPrint(ventaActual); setTimeout(() => window.print(), 500); }
function imprimirTicketModal()  { if (!ventaModalActual) return; prepararPrint(ventaModalActual); cerrarTicketModal(); setTimeout(() => window.print(), 600); }

function prepararPrint(v) {
    document.getElementById('print-ticket').style.display = 'block';
    fillTicketData('ticket-paper-desktop', v);
    const cloned = document.getElementById('ticket-paper-desktop').cloneNode(true);
    cloned.style.boxShadow = 'none';
    cloned.style.border    = '1px solid #ccc';
    cloned.style.maxWidth  = '320px';
    cloned.style.margin    = '0 auto';
    const cont = document.getElementById('print-contenido');
    cont.innerHTML = '';
    cont.appendChild(cloned);
}

// ── WHATSAPP ──────────────────────────────────────────────
function enviarWhatsApp(v) {
    if (ES_BUSINESS) {
        const pdfUrl = window.location.origin + '/ticket/' + v.id + '/pdf';
        const msg = '🧾 *Ticket de compra — ' + v.cliente + '*\nTotal: $' + parseFloat(v.total).toFixed(2) + '\n\nDescarga tu ticket aquí:\n' + pdfUrl;
        if (v.telefono) {
            window.open('https://wa.me/52' + v.telefono.replace(/\D/g,'') + '?text=' + encodeURIComponent(msg), '_blank');
        } else {
            window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
        }
    } else {
        const m = v.metodo_pago ?? 'efectivo', t = v.tipo_venta ?? 'menudeo';
        const texto = '🧾 *Ticket - ' + v.cliente + '*\n#ID-' + v.id + '\nTotal: $' + parseFloat(v.total).toFixed(2) + '\nTipo: ' + t.charAt(0).toUpperCase() + t.slice(1) + '\nMétodo: ' + m.charAt(0).toUpperCase() + m.slice(1) + '\n\n_Gracias por tu compra en ' + STORE_NAME + '_';
        const tel = v.telefono ? v.telefono.replace(/\D/g,'') : null;
        window.open(tel ? 'https://wa.me/52' + tel + '?text=' + encodeURIComponent(texto) : 'https://wa.me/?text=' + encodeURIComponent(texto), '_blank');
    }
}
function enviarWhatsAppActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } enviarWhatsApp(ventaActual); }
function enviarWhatsAppModal()  { if (!ventaModalActual) return; enviarWhatsApp(ventaModalActual); }

// ── VOZ ───────────────────────────────────────────────────
function toggleVoz() { vozActiva ? detenerVoz() : iniciarVoz(); }
function iniciarVoz() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) { mostrarVozError('Tu navegador no soporta voz.'); return; }
    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SR(); recognition.lang = 'es-MX'; recognition.continuous = false; recognition.interimResults = true;
    let tf = '';
    recognition.onstart = () => {
        vozActiva = true; tf = '';
        document.getElementById('btn-voz').classList.add('escuchando');
        document.getElementById('voz-status').classList.remove('hidden');
        document.getElementById('voz-escuchando').classList.remove('hidden');
        ['voz-procesando','voz-resultado','voz-error'].forEach(id => document.getElementById(id)?.classList.add('hidden'));
        document.getElementById('voz-transcript').textContent = 'Di el producto, ej: "Air Force One talla 27"';
    };
    recognition.onresult = e => {
        let interim = '';
        for (let i = e.resultIndex; i < e.results.length; i++) {
            if (e.results[i].isFinal) tf += e.results[i][0].transcript;
            else interim += e.results[i][0].transcript;
        }
        document.getElementById('voz-transcript').textContent = tf || interim;
    };
    recognition.onspeechend = () => recognition.stop();
    recognition.onend = () => {
        vozActiva = false;
        document.getElementById('btn-voz')?.classList.remove('escuchando');
        document.getElementById('voz-escuchando').classList.add('hidden');
        const texto = tf || document.getElementById('voz-transcript').textContent;
        if (texto && texto !== 'Di el producto, ej: "Air Force One talla 27"') interpretarVoz(texto);
    };
    recognition.onerror = e => { vozActiva = false; document.getElementById('btn-voz')?.classList.remove('escuchando'); if (e.error !== 'no-speech') mostrarVozError('Error: ' + e.error); };
    recognition.start();
}
function detenerVoz() { recognition?.stop(); recognition = null; vozActiva = false; document.getElementById('btn-voz')?.classList.remove('escuchando'); document.getElementById('voz-escuchando')?.classList.add('hidden'); }
async function interpretarVoz(texto) {
    document.getElementById('voz-procesando').classList.remove('hidden');
    document.getElementById('voz-escuchando').classList.add('hidden');
    const productos = Array.from(document.querySelectorAll('#productos-data span')).map(p => ({ id:p.dataset.id, nombre:p.dataset.nombre, precio:p.dataset.precio, tallas:JSON.parse(p.dataset.tallas||'{}') }));
    try {
        const resp = await fetch('{{ route("ventas.voz") }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN}, body:JSON.stringify({texto,productos}) });
        const data = await resp.json();
        document.getElementById('voz-procesando').classList.add('hidden');
        if (data.creditos_restantes !== undefined) actualizarBadgeVoz(data.creditos_restantes);
        if (data.limite) { mostrarVozError(data.mensaje); return; }
        if (data.producto_id) {
            const span = document.querySelector(`#productos-data span[data-id="${data.producto_id}"]`);
            if (span) {
                agregarProductoVenta(span.dataset.id, span.dataset.nombre, span.dataset.precio, data.talla || null);
                const cantidad = parseInt(data.cantidad ?? 1);
                if (cantidad > 1) { const key = span.dataset.id+'_'+(data.talla??'sin_talla'); const idx = productosVenta.findIndex(p=>p.key===key); if(idx>-1){productosVenta[idx].cantidad=cantidad;renderizarProductosVenta();} }
                mostrarVozResultado(data.mensaje ?? '✓ Producto agregado');
            } else { mostrarVozError('Producto no encontrado.'); }
        } else { mostrarVozError(data.mensaje ?? 'No se pudo identificar.'); }
    } catch { document.getElementById('voz-procesando').classList.add('hidden'); mostrarVozError('Error al procesar.'); }
}
function actualizarBadgeVoz(c) {
    const b = document.getElementById('badge-voz'); if (!b) return; b.textContent = c;
    if (c <= 10) b.style.backgroundColor = '#ef4444';
    if (c <= 0) { const btn = document.getElementById('btn-voz'); if(btn){btn.disabled=true;btn.style.opacity='0.4';} }
}
function mostrarVozResultado(msg) { document.getElementById('voz-resultado-text').textContent=msg; document.getElementById('voz-resultado').classList.remove('hidden'); setTimeout(()=>document.getElementById('voz-resultado').classList.add('hidden'),3000); }
function mostrarVozError(msg) { document.getElementById('voz-procesando')?.classList.add('hidden'); document.getElementById('voz-error-text').textContent=msg; document.getElementById('voz-error').classList.remove('hidden'); setTimeout(()=>document.getElementById('voz-error').classList.add('hidden'),4000); }

// ── MODAL VENTA ───────────────────────────────────────────
function toggleMayoreoSales(activo) {
    esMayoreo = activo; tipoVentaActual = activo ? 'mayoreo' : 'menudeo';
    document.getElementById('tipo-venta-hidden').value = tipoVentaActual;
    document.getElementById('mayoreo-label').textContent = activo ? 'Mayoreo activo — edita precios' : 'Activar para modificar precios';
    renderizarProductosVenta();
}
function seleccionarMetodo(metodo, btn) {
    metodoPagoActual = metodo;
    document.getElementById('metodo-pago-hidden').value = metodo;
    document.querySelectorAll('.metodo-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
}
function procesarVenta() {
    if (!document.querySelectorAll('#productos-hidden input[name="productos[]"]').length) { alert('Agrega al menos un producto.'); return; }
    if (!document.getElementById('cliente-search').value.trim()) { alert('Escribe el nombre del cliente.'); return; }
    totalVentaActual = parseFloat(document.getElementById('total-calculado').textContent.replace(/[$,]/g,'')) || 0;
    if (metodoPagoActual === 'efectivo') abrirModalEfectivo();
    else { document.getElementById('recibido-hidden').value=0; document.getElementById('cambio-hidden').value=0; document.getElementById('form-nueva-venta').submit(); }
}
function abrirModalEfectivo() {
    document.getElementById('efectivo-total').textContent = '$'+totalVentaActual.toFixed(2);
    document.getElementById('efectivo-recibido').value = '';
    document.getElementById('efectivo-cambio').textContent = '$0.00';
    document.getElementById('efectivo-cambio').className = 'text-xl font-black text-green-600';
    document.getElementById('efectivo-error').classList.add('hidden');
    const rapidos = [totalVentaActual,50,100,200,500,1000].filter((v,i,a)=>a.indexOf(v)===i&&v>=totalVentaActual).sort((a,b)=>a-b).slice(0,4);
    document.getElementById('efectivo-rapidos').innerHTML = rapidos.map(v=>`<button type="button" onclick="setRecibido(${v})" class="py-2 text-xs font-black border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all rounded-lg">$${Number.isInteger(v)?v:v.toFixed(2)}</button>`).join('');
    document.getElementById('modal-efectivo').classList.add('activo');
    setTimeout(()=>document.getElementById('efectivo-recibido').focus(),100);
}
function cerrarModalEfectivo() { document.getElementById('modal-efectivo').classList.remove('activo'); }
function setRecibido(v) { document.getElementById('efectivo-recibido').value=v; calcularCambio(); }
function calcularCambio() {
    const r=parseFloat(document.getElementById('efectivo-recibido').value)||0, c=r-totalVentaActual;
    const el=document.getElementById('efectivo-cambio');
    if(r>0&&c<0){el.textContent='-$'+Math.abs(c).toFixed(2);el.className='text-xl font-black text-red-500';document.getElementById('efectivo-error').classList.remove('hidden');}
    else{el.textContent='$'+Math.max(0,c).toFixed(2);el.className='text-xl font-black text-green-600';document.getElementById('efectivo-error').classList.add('hidden');}
}
function confirmarEfectivo() {
    const r=parseFloat(document.getElementById('efectivo-recibido').value)||0;
    if(r<totalVentaActual){document.getElementById('efectivo-error').classList.remove('hidden');return;}
    document.getElementById('recibido-hidden').value=r.toFixed(2);
    document.getElementById('cambio-hidden').value=(r-totalVentaActual).toFixed(2);
    cerrarModalEfectivo(); document.getElementById('form-nueva-venta').submit();
}
function abrirModalVenta() {
    document.getElementById('modal-nueva-venta').classList.add('activo');
    document.body.style.overflow='hidden';
    metodoPagoActual='efectivo'; tipoVentaActual='menudeo'; esMayoreo=false;
    document.getElementById('metodo-pago-hidden').value='efectivo';
    document.getElementById('tipo-venta-hidden').value='menudeo';
    if(ES_PRO){document.getElementById('toggle-mayoreo-sales').checked=false; document.getElementById('mayoreo-label').textContent='Activar para modificar precios';}
    document.querySelectorAll('.metodo-btn').forEach(b=>b.classList.remove('activo'));
    document.querySelectorAll('.metodo-btn')[0].classList.add('activo');
}
function cerrarModalVenta() {
    if(vozActiva)detenerVoz();
    document.getElementById('modal-nueva-venta').classList.remove('activo');
    document.body.style.overflow='';
    document.getElementById('cliente-search').value='';
    document.getElementById('cliente-id-hidden').value='';
    document.getElementById('clientes-sugerencias').classList.add('hidden');
    document.getElementById('lista-productos-categoria').classList.add('hidden');
    document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    productosVenta=[]; esMayoreo=false; renderizarProductosVenta();
    document.getElementById('voz-status')?.classList.add('hidden');
    if(ES_LEALTAD){
        document.getElementById('lealtad-section')?.classList.add('hidden');
        document.getElementById('lealtad-pregunta')?.classList.remove('hidden');
        document.getElementById('lealtad-canje')?.classList.add('hidden');
        document.getElementById('puntos-canjear-hidden').value=0;
    }
}
function buscarClienteVenta(query) {
    const s=document.getElementById('clientes-sugerencias'), q=query.toLowerCase().trim();
    if(q.length<1){s.classList.add('hidden');return;}
    const c=Array.from(document.querySelectorAll('#clientes-data span')).filter(x=>x.dataset.nombre.toLowerCase().includes(q));
    s.innerHTML=c.length===0?'<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias — se creará automáticamente</div>':c.map(x=>`<div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] uppercase" onclick="seleccionarCliente('${x.dataset.id}','${x.dataset.nombre}')">${x.dataset.nombre}</div>`).join('');
    s.classList.remove('hidden');
}
function seleccionarCliente(id, nombre) {
    document.getElementById('cliente-search').value=nombre;
    document.getElementById('cliente-id-hidden').value=id;
    document.getElementById('clientes-sugerencias').classList.add('hidden');
    if(!ES_LEALTAD)return;
    const span=Array.from(document.querySelectorAll('#clientes-data span')).find(s=>s.dataset.id==id);
    const puntos=parseInt(span?.dataset.puntos??0);
    const sec=document.getElementById('lealtad-section');
    if(puntos>0){
        document.getElementById('lealtad-puntos-display').textContent=puntos+' pts';
        document.getElementById('lealtad-mensaje').textContent=`${nombre.split(' ')[0]} tiene ${puntos} punto${puntos!==1?'s':''} ($${puntos} de descuento). ¿Los acumula o quiere utilizarlos?`;
        document.getElementById('puntos-canjear-input').max=puntos;
        document.getElementById('puntos-canjear-input').value='';
        document.getElementById('lealtad-descuento-display').textContent='-$0';
        document.getElementById('puntos-canjear-hidden').value=0;
        document.getElementById('lealtad-pregunta').classList.remove('hidden');
        document.getElementById('lealtad-canje').classList.add('hidden');
        sec.classList.remove('hidden');
    } else { sec.classList.add('hidden'); document.getElementById('puntos-canjear-hidden').value=0; }
}
function elegirLealtad(op) {
    if(op==='usar'){document.getElementById('lealtad-pregunta').classList.add('hidden');document.getElementById('lealtad-canje').classList.remove('hidden');document.getElementById('puntos-canjear-input').focus();}
    else{document.getElementById('lealtad-canje').classList.add('hidden');document.getElementById('lealtad-pregunta').classList.remove('hidden');document.getElementById('puntos-canjear-input').value='';document.getElementById('puntos-canjear-hidden').value=0;document.getElementById('lealtad-descuento-display').textContent='-$0';calcularTotalConDescuento();}
}
document.addEventListener('click',e=>{if(!e.target.closest('#cliente-search')&&!e.target.closest('#clientes-sugerencias'))document.getElementById('clientes-sugerencias')?.classList.add('hidden');});
function filtrarCategoria(cat,btn) {
    document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    btn.classList.add('bg-[#1a1c1c]','text-white','border-[#1a1c1c]');
    const lista=document.getElementById('lista-productos-categoria');
    const f=Array.from(document.querySelectorAll('#productos-data span')).filter(p=>p.dataset.categoria===cat);
    lista.innerHTML=f.length===0?'<div class="px-4 py-3 text-sm text-[#747688]">Sin productos</div>':f.map(p=>{
        const tallas=JSON.parse(p.dataset.tallas||'[]');
        const th=tallas.length>0?tallas.map(t=>`<button type="button" onclick="agregarProductoVenta('${p.dataset.id}','${p.dataset.nombre}',${p.dataset.precio},'${t.talla}')" class="px-2 py-1 text-[9px] font-black border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] hover:bg-white transition-all uppercase rounded">${t.talla} <span class="text-[#747688]">(${t.stock})</span></button>`).join(''):'<span class="text-[10px] text-red-500 font-bold">Sin stock</span>';
        return `<div class="px-4 py-3 border-b border-[#c4c5da]/10"><div class="mb-2"><p class="text-xs font-bold uppercase">${p.dataset.nombre}</p><p class="text-[10px] text-[#747688]">$${parseFloat(p.dataset.precio).toFixed(2)}</p></div><div class="flex flex-wrap gap-1.5">${th}</div></div>`;
    }).join('');
    lista.classList.remove('hidden');
}
function agregarProductoVenta(id,nombre,precio,talla=null) {
    const key=id+'_'+(talla??'sin_talla');
    const e=productosVenta.find(p=>p.key===key);
    if(e){e.cantidad+=1;}else{productosVenta.push({key,id,nombre,precio:parseFloat(precio),precio_original:parseFloat(precio),cantidad:1,talla});}
    renderizarProductosVenta();
}
function renderizarProductosVenta() {
    const c=document.getElementById('productos-container');
    if(!productosVenta.length){c.innerHTML='';actualizarHiddens();calcularTotal();return;}
    c.innerHTML=`<div class="border ${esMayoreo?'border-amber-200':'border-[#c4c5da]/20'} divide-y divide-[#c4c5da]/10 rounded-xl overflow-hidden">`+productosVenta.map((p,i)=>`
    <div class="flex items-center gap-3 px-3 py-2.5 ${esMayoreo?'bg-amber-50':'bg-white'}">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold uppercase truncate">${p.nombre}</p>
            ${p.talla?`<p class="text-[10px] font-bold" style="color:${TICKET_COLOR}">Talla: ${p.talla}</p>`:''}
            ${esMayoreo?`<div class="flex items-center gap-1 mt-1"><span class="text-[9px] text-amber-600 font-bold">Precio:</span><input type="number" value="${p.precio}" step="0.01" min="0" onchange="actualizarPrecioMayoreo(${i},this.value)" class="w-20 text-xs font-black border border-amber-300 bg-white px-2 py-0.5 focus:outline-none rounded"/><span class="text-[9px] text-[#747688] line-through">$${p.precio_original.toFixed(2)}</span></div>`:`<p class="text-[10px] text-[#747688]">$${p.precio.toFixed(2)} c/u</p>`}
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" onclick="cambiarCantidadVenta(${i},-1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black rounded">−</button>
            <span class="w-8 text-center text-sm font-black">${p.cantidad}</span>
            <button type="button" onclick="cambiarCantidadVenta(${i},1)" class="w-7 h-7 bg-[#f3f3f4] flex items-center justify-center text-sm font-black rounded">+</button>
        </div>
        <p class="text-xs font-black ${esMayoreo?'text-amber-600':''} w-16 text-right shrink-0" style="${esMayoreo?'':'color:'+TICKET_COLOR}">$${(p.precio*p.cantidad).toFixed(2)}</p>
        <button type="button" onclick="quitarProductoVenta(${i})" class="text-[#c4c5da] hover:text-red-500 transition-colors shrink-0"><span class="material-symbols-outlined text-sm">close</span></button>
    </div>`).join('')+`</div>`;
    actualizarHiddens(); calcularTotal();
}
function actualizarPrecioMayoreo(i,v){productosVenta[i].precio=parseFloat(v)||0;calcularTotal();actualizarHiddens();}
function cambiarCantidadVenta(i,d){productosVenta[i].cantidad+=d;if(productosVenta[i].cantidad<=0)productosVenta.splice(i,1);renderizarProductosVenta();}
function quitarProductoVenta(i){productosVenta.splice(i,1);renderizarProductosVenta();}
function actualizarHiddens(){document.getElementById('productos-hidden').innerHTML=productosVenta.map(p=>`<input type="hidden" name="productos[]" value="${p.id}"/><input type="hidden" name="cantidades[]" value="${p.cantidad}"/><input type="hidden" name="precios[]" value="${p.precio}"/><input type="hidden" name="tallas[]" value="${p.talla??''}"/>`).join('');}
function calcularTotal(){calcularTotalConDescuento();}
function actualizarCanjePuntos(){
    const p=parseInt(document.getElementById('puntos-canjear-input').value)||0;
    const m=parseInt(document.getElementById('puntos-canjear-input').max)||0;
    const r=Math.min(p,m);
    document.getElementById('puntos-canjear-hidden').value=r;
    document.getElementById('lealtad-descuento-display').textContent='-$'+r;
    calcularTotalConDescuento();
}
function calcularTotalConDescuento(){
    const s=productosVenta.reduce((a,p)=>a+p.precio*p.cantidad,0);
    const d=parseInt(document.getElementById('puntos-canjear-hidden')?.value??0);
    document.getElementById('total-calculado').textContent='$'+Math.max(0,s-d).toFixed(2);
}
function filtrarVentas(q){q=q.toLowerCase();document.querySelectorAll('.venta-card').forEach(r=>{r.style.display=((r.dataset.nombre??'').includes(q)||(r.dataset.id??'').includes(q))?'':'none';});}
</script>

@include('partials._sidebar')
</body>
</html>