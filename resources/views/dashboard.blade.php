<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Vista general</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    #modal-stock { display: none; }
    #modal-stock.activo { display: flex; }
    #modal-upgrade { display: none; }
    #modal-upgrade.activo { display: flex; }

    @keyframes countUp { from { opacity:0; transform:scale(0.8); } to { opacity:1; transform:scale(1); } }
    @keyframes fadeInUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    @keyframes slideInLeft { from { opacity:0; transform:translateX(-10px); } to { opacity:1; transform:translateX(0); } }
    @keyframes pulseGlow { 0%,100% { box-shadow:0 0 0 0 rgba(23,55,200,0.15); } 50% { box-shadow:0 0 0 6px rgba(23,55,200,0); } }
    @keyframes slideOut { from { opacity:1; transform:translateY(0) scale(1); } to { opacity:0; transform:translateY(-10px) scale(0.95); } }

    .kpi-card { transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1) !important; }
    .kpi-card:hover { transform: translateY(-3px) scale(1.01) !important; box-shadow: 0 12px 32px rgba(23,55,200,0.12) !important; }
    .kpi-anim { animation: countUp 0.5s cubic-bezier(0.34,1.56,0.64,1) both; }
    .kpi-anim-1 { animation-delay: 0.05s; }
    .kpi-anim-2 { animation-delay: 0.12s; }
    .kpi-anim-3 { animation-delay: 0.19s; }

    .section-reveal { opacity:0; transform:translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
    .section-reveal.visible { opacity:1; transform:translateY(0); }

    .pro-kpi { animation: countUp 0.5s cubic-bezier(0.34,1.56,0.64,1) both; transition: all 0.2s ease; }
    .pro-kpi:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(23,55,200,0.1); }
    .pro-kpi:nth-child(1) { animation-delay: 0.06s; }
    .pro-kpi:nth-child(2) { animation-delay: 0.12s; }
    .pro-kpi:nth-child(3) { animation-delay: 0.18s; }

    .venta-reciente { animation: slideInLeft 0.35s ease both; }
    .venta-reciente:nth-child(1) { animation-delay: 0.04s; }
    .venta-reciente:nth-child(2) { animation-delay: 0.08s; }
    .venta-reciente:nth-child(3) { animation-delay: 0.12s; }
    .venta-reciente:nth-child(4) { animation-delay: 0.16s; }
    .venta-reciente:nth-child(5) { animation-delay: 0.20s; }
    .venta-reciente:hover { background: rgba(23,55,200,0.03); }

    .estrella-badge { animation: pulseGlow 2.5s ease-in-out infinite; }

    .restock-card { transition: all 0.3s ease; animation: fadeInUp 0.4s ease both; }
    .restock-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
    .restock-card.dismissing { animation: slideOut 0.3s ease forwards; }

    [data-theme="dark"] body, [data-theme="dark"] .bg-\[\#f9f9f9\] { background-color: #0f1012 !important; }
    [data-theme="dark"] main { background-color: #0f1012 !important; }
    [data-theme="dark"] .bg-white { background-color: #1e2022 !important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background-color: #141618 !important; }
    [data-theme="dark"] .text-\[\#1a1c1c\] { color: #f3f3f4 !important; }
    [data-theme="dark"] .text-\[\#747688\] { color: #9496a8 !important; }
    [data-theme="dark"] input, [data-theme="dark"] select { background-color: #141618 !important; color: #f3f3f4 !important; border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color: rgba(255,255,255,0.06) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color: rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/30 { border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .hover\:bg-\[\#f3f3f4\]:hover { background-color: #141618 !important; }
    [data-theme="dark"] .hover\:bg-\[\#f9f9f9\]:hover { background-color: #1a1c1e !important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\].border-b { background-color: #141618 !important; }
    [data-theme="dark"] #modal-stock .bg-white,
    [data-theme="dark"] #modal-upgrade .bg-white { background-color: #1e2022 !important; }
    [data-theme="dark"] .bg-white\/80 { background-color: rgba(30,32,34,0.85) !important; }
    [data-theme="dark"] .bg-\[\#1a1c1c\] { background-color: #0a0b0c !important; }
    [data-theme="dark"] nav.fixed.bottom-0 { background: rgba(15,16,18,0.92) !important; border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .hover\:bg-\[\#1a1c1c\]:hover { background-color: #f3f3f4 !important; color: #1a1c1c !important; }
    [data-theme="dark"] .venta-reciente:hover { background: rgba(23,55,200,0.08) !important; }
    [data-theme="dark"] .bg-amber-50 { background-color: rgba(245,158,11,0.08) !important; }
    [data-theme="dark"] .border-amber-200 { border-color: rgba(245,158,11,0.2) !important; }
    [data-theme="dark"] .bg-blue-50 { background-color: rgba(23,55,200,0.08) !important; }
    [data-theme="dark"] .restock-card { background-color: #1e2022 !important; }
    [data-theme="dark"] .restock-pregunta { background-color: #141618 !important; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

@php
$plan       = $plan ?? (Auth::user()->plan ?? 'gratis');
$esBusiness = $esBusiness ?? ($plan === 'business');
$esPro      = $plan === 'pro' || $plan === 'business';
@endphp

<main class="pt-24 pb-20 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto">

    @if($plan === 'gratis')
    <div class="mb-8 rounded-xl bg-[#1737c8]/5 border border-[#1737c8]/20 px-6 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[#1737c8] text-xl">workspace_premium</span>
            <div>
                <p class="text-sm font-bold text-[#1a1c1c]">Plan Gratis — analítica del día únicamente</p>
                <p class="text-xs text-[#747688]">Actualiza al Plan Pro para ver semana, mes y acceder al asistente IA.</p>
            </div>
        </div>
        <a href="/#precios" class="shrink-0 bg-[#1737c8] text-white px-5 py-2 text-xs font-black uppercase tracking-widest rounded-lg hover:opacity-90 transition-all">Ver planes →</a>
    </div>
    @endif

    <header class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex items-center gap-4 flex-wrap">
            <div>
                <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Vista general</span>
                <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">
                    {{ $esBusiness ? 'Vista general Business' : ($esPro ? 'Vista general Pro' : 'Rendimiento diario') }}
                </h1>
            </div>
            @if($esBusiness && count($almacenes) > 0)
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 mt-2 md:mt-0" id="form-almacen">
                <input type="hidden" name="periodo" value="{{ $periodo }}"/>
                <select name="almacen_id" onchange="document.getElementById('form-almacen').submit()"
                        class="rounded-lg border border-[#c4c5da]/40 px-3 py-2 text-sm font-bold focus:outline-none focus:border-[#1737c8] bg-white cursor-pointer">
                    <option value="">🏪 Todos los almacenes</option>
                    @foreach($almacenes as $a)
                    <option value="{{ $a->id }}" {{ $almacenId == $a->id ? 'selected' : '' }}>
                        {{ $a->tipo === 'fisico' ? '🏪' : '📦' }} {{ $a->nombre }}
                    </option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('sales') }}">
                <button class="rounded-xl bg-[#1a1c1c] text-white px-4 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>ABRIR REGISTRO
                </button>
            </a>
            <a href="{{ route('resumen') }}">
                <button class="rounded-xl bg-[#1737c8] text-white px-4 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">summarize</span>RESUMEN DIARIO
                </button>
            </a>
        </div>
    </header>

    @if($esBusiness)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="kpi-card rounded-xl bg-white border border-[#c4c5da]/20 p-6">
            <div class="flex items-center gap-2 mb-3"><span class="text-lg">💰</span><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Ingreso bruto del mes</p></div>
            <p class="text-3xl font-black text-[#1a1c1c]">${{ number_format($ingresoBruto, 2) }}</p>
            <p class="text-[10px] text-[#747688] mt-1">Todo lo cobrado</p>
        </div>
        <div class="kpi-card rounded-xl bg-white border border-[#c4c5da]/20 p-6">
            <div class="flex items-center gap-2 mb-3"><span class="text-lg">📉</span><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Costos y gastos del mes</p></div>
            <p class="text-3xl font-black text-red-500">-${{ number_format($costosGastos, 2) }}</p>
            <p class="text-[10px] text-[#747688] mt-1">Proveedor + operativos</p>
        </div>
        <div class="kpi-card rounded-xl p-6 {{ $utilidadReal >= 0 ? 'bg-green-500' : 'bg-red-500' }}">
            <div class="flex items-center gap-2 mb-3"><span class="text-lg">{{ $utilidadReal >= 0 ? '🟢' : '🔴' }}</span><p class="text-[10px] font-black uppercase tracking-widest text-white/70">Utilidad real del mes</p></div>
            <p class="text-3xl font-black text-white">${{ number_format($utilidadReal, 2) }}</p>
            <p class="text-[10px] text-white/70 mt-1">Lana libre en tu bolsa</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6 section-reveal">
        <div class="kpi-card kpi-anim kpi-anim-1 rounded-xl bg-[#1737c8] p-4 md:p-6 flex items-center justify-between">
            <div><p class="text-[10px] font-black uppercase tracking-widest text-white/60 mb-1">Ventas de hoy</p><p class="text-2xl md:text-3xl font-black text-white">${{ number_format($ventasHoy, 2) }}</p></div>
            <span class="material-symbols-outlined text-4xl text-white/20">payments</span>
        </div>
        <div class="kpi-card kpi-anim kpi-anim-2 rounded-xl bg-white border border-[#c4c5da]/20 p-4 md:p-6 flex items-center justify-between">
            <div><p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Transacciones</p><p class="text-3xl font-black text-[#1a1c1c]">{{ $numVentasHoy }}</p></div>
            <span class="material-symbols-outlined text-4xl text-[#c4c5da]">receipt_long</span>
        </div>
        <div class="kpi-card kpi-anim kpi-anim-3 rounded-xl bg-white border border-[#c4c5da]/20 p-4 md:p-6 flex items-center justify-between">
            <div><p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Ticket promedio</p><p class="text-3xl font-black text-[#1a1c1c]">${{ number_format($ticketPromedio, 2) }}</p></div>
            <span class="material-symbols-outlined text-4xl text-[#c4c5da]">avg_pace</span>
        </div>
    </div>

    @if($esPro)
    <div class="section-reveal grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div class="pro-kpi rounded-xl bg-white border border-[#c4c5da]/20 p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Ventas esta semana</p>
                <p class="text-2xl font-black text-[#1a1c1c]">${{ number_format($ventasSemana ?? 0, 2) }}</p>
                @php $diffSemana = ($ventasSemana ?? 0) - ($ventasSemanaPasada ?? 0); @endphp
                <p class="text-[10px] mt-1 font-bold {{ $diffSemana >= 0 ? 'text-green-500' : 'text-red-400' }}">
                    {{ $diffSemana >= 0 ? '▲' : '▼' }} ${{ number_format(abs($diffSemana), 0) }} vs semana pasada
                </p>
            </div>
            <span class="material-symbols-outlined text-3xl text-[#1737c8]/20">calendar_today</span>
        </div>
        <div class="pro-kpi rounded-xl bg-white border border-[#c4c5da]/20 p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Mejor día de la semana</p>
                <p class="text-2xl font-black text-[#1a1c1c]">{{ $mejorDiaSemana['dia'] ?? '—' }}</p>
                <p class="text-[10px] text-[#747688] mt-1">${{ number_format($mejorDiaSemana['total'] ?? 0, 0) }} en ventas</p>
            </div>
            <span class="material-symbols-outlined text-3xl text-amber-300">emoji_events</span>
        </div>
        <div class="pro-kpi rounded-xl bg-white border border-[#c4c5da]/20 p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Clientes atendidos hoy</p>
                <p class="text-2xl font-black text-[#1a1c1c]">{{ $clientesHoy ?? 0 }}</p>
                <p class="text-[10px] text-[#747688] mt-1">{{ $clientesNuevosHoy ?? 0 }} nuevo(s) hoy</p>
            </div>
            <span class="material-symbols-outlined text-3xl text-[#c4c5da]">group</span>
        </div>
    </div>

    <div class="section-reveal grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
        <div class="md:col-span-4 rounded-xl bg-white border border-[#c4c5da]/20 overflow-hidden">
            <div class="px-5 py-4 border-b border-[#c4c5da]/10 flex items-center justify-between">
                <div><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Producto estrella</p><p class="text-sm font-bold text-[#1a1c1c]">El más vendido del mes</p></div>
                <span class="estrella-badge w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-base">⭐</span>
            </div>
            @if(isset($productoEstrella) && $productoEstrella)
            <div class="p-5">
                <div class="aspect-square rounded-xl overflow-hidden bg-[#f3f3f4] mb-4 relative">
                    @if(!empty($productoEstrella['imagen']))
                    <img src="{{ $productoEstrella['imagen'] }}" alt="{{ $productoEstrella['nombre'] }}" class="w-full h-full object-cover"/>
                    @else
                    <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-5xl text-[#c4c5da]">image</span></div>
                    @endif
                    <div class="absolute bottom-2 right-2 bg-[#1737c8] text-white text-[10px] font-black px-2 py-1 rounded-lg">#1 del mes</div>
                </div>
                <p class="font-black text-sm uppercase tracking-tight">{{ $productoEstrella['nombre'] }}</p>
                <p class="text-[10px] text-[#747688] mt-0.5">{{ $productoEstrella['categoria'] ?? '' }}</p>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <div class="bg-[#f3f3f4] rounded-lg p-2 text-center">
                        <p class="text-[9px] font-black uppercase tracking-widest text-[#747688]">Unidades</p>
                        <p class="text-lg font-black text-[#1737c8]">{{ $productoEstrella['ventas'] ?? 0 }}</p>
                    </div>
                    <div class="bg-[#f3f3f4] rounded-lg p-2 text-center">
                        <p class="text-[9px] font-black uppercase tracking-widest text-[#747688]">Ingresos</p>
                        <p class="text-lg font-black text-[#1a1c1c]">${{ number_format($productoEstrella['monto'] ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="p-8 text-center">
                <span class="material-symbols-outlined text-3xl text-[#c4c5da] block mb-2">inventory_2</span>
                <p class="text-sm text-[#747688]">Sin ventas registradas aún</p>
            </div>
            @endif
        </div>

        <div class="md:col-span-8 rounded-xl bg-white border border-[#c4c5da]/20 overflow-hidden">
            <div class="px-5 py-4 border-b border-[#c4c5da]/10 flex items-center justify-between">
                <div><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Actividad reciente</p><p class="text-sm font-bold text-[#1a1c1c]">Últimas ventas del día</p></div>
                <a href="{{ route('sales') }}" class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] hover:opacity-70 transition-opacity">Ver todas →</a>
            </div>
            <div class="divide-y divide-[#c4c5da]/10">
                @forelse($ventasRecientes ?? [] as $vr)
                <div class="venta-reciente flex items-center gap-4 px-5 py-3.5 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                        <span class="text-[#1737c8] font-black text-xs">{{ strtoupper(substr($vr['cliente'] ?? 'C', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-[#1a1c1c] truncate">{{ $vr['cliente'] ?? 'Cliente' }}</p>
                        <p class="text-[10px] text-[#747688]">{{ $vr['num_productos'] ?? 0 }} producto(s) · {{ $vr['hora'] ?? '' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black text-[#1737c8]">${{ number_format($vr['total'] ?? 0, 2) }}</p>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full uppercase {{ ($vr['metodo'] ?? '') === 'efectivo' ? 'bg-green-100 text-green-700' : (($vr['metodo'] ?? '') === 'tarjeta' ? 'bg-blue-50 text-blue-600' : 'bg-[#f3f3f4] text-[#747688]') }}">
                            {{ $vr['metodo'] ?? 'efectivo' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <span class="material-symbols-outlined text-3xl text-[#c4c5da] block mb-2">receipt_long</span>
                    <p class="text-sm text-[#747688]">Sin ventas registradas hoy</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    @if(in_array($plan, ['pro', 'business']))
    <section id="ia-section" class="mb-8">
        <div class="rounded-xl bg-white border border-[#c4c5da]/20 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#c4c5da]/10 bg-[#f9f9f9]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#1737c8] flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Asistente IA</p>
                        <p class="text-sm font-bold text-[#1a1c1c]">Análisis inteligente de tu negocio</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($esBusiness)
                    <a href="{{ route('chat.ia.index') }}" class="rounded-lg flex items-center gap-1.5 px-3 py-2 bg-[#1737c8]/10 text-[#1737c8] text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">chat</span>Chat IA
                    </a>
                    @endif
                    <button onclick="cargarIA()" id="btn-ia-refresh" class="rounded-lg flex items-center gap-2 px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                        <span class="material-symbols-outlined text-sm">refresh</span>Actualizar
                    </button>
                </div>
            </div>
            <div id="ia-contenido" class="p-6">
                <div id="ia-inicial" class="text-center py-8">
                    <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">auto_awesome</span>
                    <p class="text-sm text-[#747688] mb-4">Obtén análisis inteligente de tus ventas e inventario</p>
                    <button onclick="cargarIA()" class="rounded-xl bg-[#1737c8] text-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-2 mx-auto">
                        <span class="material-symbols-outlined text-sm">auto_awesome</span>Analizar ahora
                    </button>
                </div>
                <div id="ia-loading" class="hidden text-center py-8">
                    <div class="inline-flex items-center gap-3 text-[#747688]">
                        <svg class="animate-spin w-5 h-5 text-[#1737c8]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-sm font-bold">Analizando tu negocio...</span>
                    </div>
                </div>
                <div id="ia-resultados" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-3"><span class="material-symbols-outlined text-red-500 text-sm">warning</span><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Alertas</p></div>
                        <div id="ia-alertas" class="space-y-2"></div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3"><span class="material-symbols-outlined text-green-500 text-sm">trending_up</span><p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tendencias</p></div>
                        <div id="ia-tendencias" class="space-y-2"></div>
                    </div>
                    <div class="md:col-span-2 rounded-xl bg-[#1737c8]/5 border border-[#1737c8]/20 px-5 py-4">
                        <div class="flex items-center gap-2 mb-2"><span class="material-symbols-outlined text-[#1737c8] text-sm">insights</span><p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8]">Predicción</p></div>
                        <p id="ia-prediccion" class="text-sm text-[#1a1c1c] font-medium"></p>
                    </div>
                    <div class="md:col-span-2 rounded-xl bg-green-50 border border-green-200 px-5 py-4">
                        <div class="flex items-center gap-2 mb-2"><span class="material-symbols-outlined text-green-600 text-sm">lightbulb</span><p class="text-[10px] font-black uppercase tracking-widest text-green-700">Recomendación</p></div>
                        <p id="ia-recomendacion" class="text-sm text-green-800 font-medium"></p>
                    </div>
                </div>
                <div id="ia-error" class="hidden text-center py-6">
                    <span class="material-symbols-outlined text-amber-500 text-3xl block mb-2">error_outline</span>
                    <p class="text-sm text-[#747688]" id="ia-error-msg">No se pudo cargar el análisis</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        <section class="section-reveal md:col-span-8 rounded-xl bg-white border border-[#c4c5da]/20 p-8 relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Analítica de ventas</h2>
                    <p class="text-xs text-[#747688] font-semibold uppercase tracking-widest mt-1">{{ $esBusiness ? 'Utilidad neta en tiempo real' : 'Ventas netas en tiempo real' }}</p>
                </div>
                <div class="flex bg-[#f3f3f4] rounded-lg p-1 gap-1">
                    <a href="{{ route('dashboard', array_filter(['periodo' => 'dia', 'almacen_id' => $almacenId])) }}">
                        <button class="rounded-md px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'dia' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Día</button>
                    </a>
                    @if($plan === 'gratis')
                    <button onclick="abrirModalUpgrade('semana')" class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-[#c4c5da] flex items-center gap-1 hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[11px]">lock</span>Sem</button>
                    <button onclick="abrirModalUpgrade('mes')" class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-[#c4c5da] flex items-center gap-1 hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[11px]">lock</span>Mes</button>
                    @else
                    <a href="{{ route('dashboard', array_filter(['periodo' => 'semana', 'almacen_id' => $almacenId])) }}">
                        <button class="rounded-md px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'semana' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Semana</button>
                    </a>
                    <a href="{{ route('dashboard', array_filter(['periodo' => 'mes', 'almacen_id' => $almacenId])) }}">
                        <button class="rounded-md px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'mes' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Mes</button>
                    </a>
                    @endif
                </div>
            </div>
            <div class="relative h-[280px]"><canvas id="ventasChart"></canvas></div>
        </section>

        <section class="section-reveal md:col-span-4 rounded-xl bg-white border border-[#c4c5da]/20 p-8">
            <h2 class="text-xl font-bold tracking-tight mb-6">Lo más vendido</h2>
            <div class="space-y-5">
                @forelse($topProductos as $i => $producto)
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-[#c4c5da] w-6 shrink-0">{{ $i + 1 }}</span>
                    <div class="w-10 h-10 rounded-lg bg-[#f3f3f4] shrink-0 overflow-hidden">
                        @if(!empty($producto['imagen']))<img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-full h-full object-cover"/>
                        @else<div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-lg">image</span></div>@endif
                    </div>
                    <div class="flex-1 min-w-0"><p class="text-xs font-bold uppercase truncate">{{ $producto['nombre'] }}</p><p class="text-[10px] text-[#747688]">{{ $producto['ventas'] }} ventas</p></div>
                    <div class="shrink-0 text-right">
                        <span class="text-sm font-black text-[#1737c8]">{{ $producto['porcentaje'] }}%</span>
                        <div class="w-16 h-1 rounded-full bg-[#f3f3f4] mt-1"><div class="h-full rounded-full bg-[#1737c8]" style="width: {{ $producto['porcentaje'] }}%"></div></div>
                    </div>
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-[#f3f3f4] w-6">{{ $i + 1 }}</span>
                    <div class="w-10 h-10 rounded-lg bg-[#f3f3f4] shrink-0"></div>
                    <div class="flex-1"><div class="h-2 bg-[#f3f3f4] w-24 rounded mb-1"></div><div class="h-2 bg-[#f3f3f4] w-16 rounded"></div></div>
                    <div class="h-3 bg-[#f3f3f4] w-8 rounded"></div>
                </div>
                @endfor
                @endforelse
            </div>
            @if($plan === 'gratis')
            <button onclick="abrirModalUpgrade('ia')" class="w-full mt-6 py-3 rounded-xl border border-dashed border-[#1737c8]/30 text-[10px] font-black uppercase tracking-widest text-[#1737c8]/50 hover:border-[#1737c8] hover:text-[#1737c8] transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">lock</span>Recomendaciones IA — Pro
            </button>
            @endif
            <a href="{{ route('reporte') }}" class="block w-full mt-4">
                <button type="button" class="w-full py-3 rounded-xl border-2 border-[#1a1c1c] text-[10px] font-black tracking-widest uppercase hover:bg-[#1a1c1c] hover:text-white transition-all">Ver reporte completo</button>
            </a>
        </section>

        {{-- RESTOCK SEMÁFORO --}}
        <section class="section-reveal md:col-span-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Restock requerido</h2>
                    <p class="text-xs text-[#747688] font-semibold uppercase tracking-widest mt-1">Stock actual por talla — semáforo de reposición</p>
                </div>
                <button onclick="abrirModalStock()" class="rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/30 hover:bg-[#1a1c1c] hover:text-white transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>Ver todo
                </button>
            </div>

            @if($lowStock->isEmpty())
            <div class="rounded-xl bg-white border border-[#c4c5da]/20 px-8 py-12 text-center">
                <span class="material-symbols-outlined text-4xl text-green-400 block mb-3">verified</span>
                <p class="text-sm font-bold text-[#1a1c1c]">Todo bajo control</p>
                <p class="text-xs text-[#747688] mt-1">Todos los productos tienen stock suficiente</p>
            </div>
            @else
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4" id="restock-container">
                @foreach($lowStock as $item)
                @php
                    $colorBorde = match($item['urgencia']) { 'critico' => 'border-red-400/60', 'advertencia' => 'border-amber-400/50', default => 'border-blue-300/40' };
                    $colorBarra = match($item['urgencia']) { 'critico' => '#ef4444', 'advertencia' => '#f59e0b', default => '#60a5fa' };
                    $colorBadge = match($item['urgencia']) { 'critico' => 'background:#fef2f2;color:#dc2626', 'advertencia' => 'background:#fffbeb;color:#d97706', default => 'background:#eff6ff;color:#2563eb' };
                    $iconoUrgencia = match($item['urgencia']) { 'critico' => 'error', 'advertencia' => 'warning', default => 'info' };
                    $pct = max(4, min(($item['stock'] / 8) * 100, 100));
                @endphp
                <div class="restock-card min-w-[240px] md:min-w-[270px] rounded-2xl bg-white border {{ $colorBorde }} p-5 flex flex-col gap-3 shrink-0"
                     id="restock-{{ $item['id'] }}-{{ $item['talla'] }}"
                     data-product-id="{{ $item['id'] }}" data-talla="{{ $item['talla'] }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-sm uppercase leading-tight truncate text-[#1a1c1c]">{{ $item['nombre'] }}</p>
                            <p class="text-[10px] text-[#1737c8] font-black mt-0.5 uppercase tracking-wider">Talla {{ $item['talla'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-lg px-2.5 py-1 text-[10px] font-black uppercase" style="{{ $colorBadge }}">{{ $item['stock'] }} pcs</span>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[9px] font-black uppercase tracking-widest text-[#747688]">Nivel de stock</span>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]" style="color:{{ $colorBarra }}">{{ $iconoUrgencia }}</span>
                                <span class="text-[10px] font-black" style="color:{{ $colorBarra }}">{{ $item['mensaje'] }}</span>
                            </div>
                        </div>
                        <div class="w-full rounded-full h-2 bg-[#f3f3f4] overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%; background:{{ $colorBarra }}"></div>
                        </div>
                    </div>
                    <div class="restock-pregunta rounded-xl bg-[#f3f3f4] px-3 py-2.5">
                        <p class="text-[11px] text-[#1a1c1c] font-semibold leading-snug">
                            {{ $item['mensaje'] }}.
                            <span class="text-[#747688] font-normal">¿Quieres reponer?</span>
                        </p>
                    </div>
                    <div class="flex gap-2 mt-auto">
                        <form method="POST" action="{{ route('reponer') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="producto" value="{{ $item['id'] }}">
                            <button type="submit" class="w-full rounded-xl py-2.5 bg-[#1737c8] text-white text-[10px] font-black tracking-widest uppercase hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[13px]">add_circle</span>Reponer
                            </button>
                        </form>
                        <button type="button" onclick="descartarRestock({{ $item['id'] }}, '{{ $item['talla'] }}')"
                                class="rounded-xl px-3 py-2.5 border border-[#c4c5da]/40 text-[10px] font-black uppercase text-[#747688] hover:border-[#1a1c1c] hover:text-[#1a1c1c] transition-all flex items-center gap-1"
                                title="Estoy bien por ahora, no mostrar 7 días">
                            <span class="material-symbols-outlined text-[13px]">thumb_up</span>OK
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </section>

        @if($plan === 'gratis')
        <section class="section-reveal md:col-span-12">
            <div class="rounded-xl bg-[#1a1c1c] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-2">Plan Pro</p>
                    <h3 class="text-xl font-black text-white mb-1">Desbloquea todo el potencial de Quivex</h3>
                    <p class="text-sm text-white/50">Analítica semanal y mensual, asistente IA, reportes avanzados y más.</p>
                </div>
                <a href="/#precios" class="shrink-0 rounded-xl bg-[#1737c8] text-white px-8 py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">Actualizar a Pro →</a>
            </div>
        </section>
        @endif

    </div>
</main>

{{-- MODAL STOCK --}}
<div id="modal-stock" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalStock()">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Inventario</p><h2 class="text-2xl font-bold tracking-tight">Restock requerido</h2></div>
            <button onclick="cerrarModalStock()" class="p-2 rounded-lg hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="overflow-y-auto flex-1">
            <div class="grid grid-cols-12 px-8 py-3 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-5">Producto</div>
                <div class="col-span-2 text-center">Talla</div>
                <div class="col-span-2 text-center">Stock</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>
            @forelse($lowStock as $item)
            @php $badgeColor = match($item['urgencia']) { 'critico' => 'bg-red-100 text-red-600', 'advertencia' => 'bg-amber-100 text-amber-700', default => 'bg-blue-50 text-blue-600' }; @endphp
            <div class="grid grid-cols-12 px-8 py-4 items-center border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9]">
                <div class="col-span-5"><h3 class="font-bold text-sm uppercase truncate">{{ $item['nombre'] }}</h3><p class="text-[10px] text-[#747688]">{{ $item['mensaje'] }}</p></div>
                <div class="col-span-2 text-center"><span class="text-[10px] font-black text-[#1737c8] uppercase">{{ $item['talla'] ?? '—' }}</span></div>
                <div class="col-span-2 text-center"><span class="rounded-lg px-2 py-1 text-[10px] font-black uppercase {{ $badgeColor }}">{{ $item['stock'] }} pcs</span></div>
                <div class="col-span-3 flex justify-end">
                    <form method="POST" action="{{ route('reponer') }}">@csrf<input type="hidden" name="producto" value="{{ $item['id'] }}">
                        <button class="rounded-lg px-4 py-2 bg-[#1737c8] text-white text-[10px] font-black uppercase hover:opacity-90">REPONER</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-8 py-16 text-center text-[#747688] text-sm">Sin productos que necesiten restock</div>
            @endforelse
        </div>
        <div class="px-8 py-4 border-t border-[#c4c5da]/20">
            <button onclick="cerrarModalStock()" class="w-full rounded-xl py-3 border border-[#c4c5da]/40 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">CERRAR</button>
        </div>
    </div>
</div>

{{-- MODAL UPGRADE --}}
<div id="modal-upgrade" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalUpgrade()">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Función Pro</p><h2 class="text-xl font-bold tracking-tight" id="modal-upgrade-titulo">Desbloquea esta función</h2></div>
            <button onclick="cerrarModalUpgrade()" class="p-2 rounded-lg hover:bg-[#f3f3f4]"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="px-8 py-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-[#1737c8]/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[#1737c8] text-2xl">workspace_premium</span></div>
                <p class="text-sm text-[#747688]" id="modal-upgrade-desc">Actualiza al Plan Pro para desbloquear esta función.</p>
            </div>
            <div class="space-y-3 mb-8">
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Analítica semanal y mensual</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Asistente IA integrado</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Reportes avanzados</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Usuarios y productos ilimitados</div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="/#precios" class="flex-1 rounded-xl bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 text-center">Ver planes</a>
                <button onclick="cerrarModalUpgrade()" class="flex-1 rounded-xl border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">Ahora no</button>
            </div>
        </div>
    </div>
</div>



<script>
const labels     = @json($labels);
const valores    = @json($valores);
const esBusiness = {{ $esBusiness ? 'true' : 'false' }};

const ctx      = document.getElementById('ventasChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 280);
if (esBusiness) { gradient.addColorStop(0, 'rgba(34,197,94,0.2)'); gradient.addColorStop(1, 'rgba(34,197,94,0)'); }
else { gradient.addColorStop(0, 'rgba(23,55,200,0.15)'); gradient.addColorStop(1, 'rgba(23,55,200,0)'); }
new Chart(ctx, {
    type: 'line',
    data: { labels, datasets: [{ label: esBusiness ? 'Utilidad neta ($)' : 'Ventas ($)', data: valores, borderColor: esBusiness ? '#22c55e' : '#1737c8', borderWidth: 2.5, backgroundColor: gradient, fill: true, tension: 0.4, pointBackgroundColor: esBusiness ? '#22c55e' : '#1737c8', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 }] },
    options: {
        responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1a1c1c', titleColor: '#fff', bodyColor: 'rgba(255,255,255,0.7)', padding: 12, borderRadius: 10, callbacks: { label: ctx => (esBusiness ? ' Utilidad: $' : ' $') + parseFloat(ctx.raw).toLocaleString('es-MX', {minimumFractionDigits:2}) } } },
        scales: { x: { grid:{display:false}, border:{display:false}, ticks:{font:{size:10,weight:'700',family:'Inter'},color:'#747688'} }, y: { grid:{color:'rgba(196,197,218,0.15)'}, border:{display:false,dash:[4,4]}, ticks:{font:{size:10,weight:'700',family:'Inter'},color:'#747688',callback:v=>'$'+(v>=1000?(v/1000).toFixed(1)+'k':v)} } }
    }
});

function abrirModalStock()  { document.getElementById('modal-stock').classList.add('activo');   document.body.style.overflow='hidden'; }
function cerrarModalStock() { document.getElementById('modal-stock').classList.remove('activo'); document.body.style.overflow=''; }
const upgradeTextos = { semana:{titulo:'Analítica semanal',desc:'Con el Plan Pro puedes ver el rendimiento de tus ventas por semana.'}, mes:{titulo:'Analítica mensual',desc:'Con el Plan Pro puedes comparar meses y planear tu inventario.'}, ia:{titulo:'Asistente IA',desc:'Con el Plan Pro tienes un asistente inteligente con recomendaciones personalizadas.'} };
function abrirModalUpgrade(tipo) { const t=upgradeTextos[tipo]??{titulo:'Función Pro',desc:'Actualiza al Plan Pro.'}; document.getElementById('modal-upgrade-titulo').textContent=t.titulo; document.getElementById('modal-upgrade-desc').textContent=t.desc; document.getElementById('modal-upgrade').classList.add('activo'); document.body.style.overflow='hidden'; }
function cerrarModalUpgrade() { document.getElementById('modal-upgrade').classList.remove('activo'); document.body.style.overflow=''; }

function animarNumero(el) {
    const texto=el.textContent.trim(), num=parseFloat(texto.replace(/[$,]/g,''));
    if (isNaN(num)||num===0) return;
    const dur=800,start=performance.now(),prefix=texto.includes('$')?'$':'',isInt=Number.isInteger(num);
    function update(now) { const p=Math.min((now-start)/dur,1),ease=1-Math.pow(1-p,3),cur=num*ease; el.textContent=prefix+(isInt?Math.round(cur).toLocaleString('es-MX'):cur.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})); if(p<1)requestAnimationFrame(update); }
    requestAnimationFrame(update);
}
document.querySelectorAll('.kpi-card .text-3xl, .pro-kpi .text-2xl').forEach(el => {
    const obs=new IntersectionObserver(entries => { if(entries[0].isIntersecting){animarNumero(el);obs.disconnect();} },{threshold:0.5});
    obs.observe(el);
});
document.querySelectorAll('.kpi-card').forEach(card => {
    card.addEventListener('mousemove', e => { const r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-0.5,y=(e.clientY-r.top)/r.height-0.5; card.style.transform=`perspective(400px) rotateX(${-y*6}deg) rotateY(${x*6}deg) translateY(-3px) scale(1.01)`; });
    card.addEventListener('mouseleave', () => { card.style.transform=''; });
});

async function cargarIA() {
    document.getElementById('ia-inicial').classList.add('hidden');
    document.getElementById('ia-resultados').classList.add('hidden');
    document.getElementById('ia-error').classList.add('hidden');
    document.getElementById('ia-loading').classList.remove('hidden');
    const btn=document.getElementById('btn-ia-refresh'); btn.disabled=true;
    try {
        const response=await fetch('{{ route("dashboard.ia") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}});
        const data=await response.json();
        if(data.error) throw new Error(data.error);
        document.getElementById('ia-alertas').innerHTML=(data.alertas??[]).length>0?data.alertas.map(a=>`<div class="flex items-start gap-2 rounded-lg bg-red-50 border border-red-100 px-3 py-2"><span class="material-symbols-outlined text-red-400 text-sm mt-0.5 shrink-0">circle</span><p class="text-xs text-red-700 font-medium">${a}</p></div>`).join(''):'<p class="text-xs text-[#747688]">Sin alertas por ahora ✓</p>';
        document.getElementById('ia-tendencias').innerHTML=(data.tendencias??[]).length>0?data.tendencias.map(t=>`<div class="flex items-start gap-2 rounded-lg bg-green-50 border border-green-100 px-3 py-2"><span class="material-symbols-outlined text-green-500 text-sm mt-0.5 shrink-0">arrow_upward</span><p class="text-xs text-green-700 font-medium">${t}</p></div>`).join(''):'<p class="text-xs text-[#747688]">Sin tendencias detectadas</p>';
        document.getElementById('ia-prediccion').textContent=data.prediccion??'—';
        document.getElementById('ia-recomendacion').textContent=data.recomendacion??'—';
        document.getElementById('ia-loading').classList.add('hidden');
        document.getElementById('ia-resultados').classList.remove('hidden');
    } catch(e) {
        document.getElementById('ia-loading').classList.add('hidden');
        document.getElementById('ia-error-msg').textContent=e.message||'Error al cargar el análisis';
        document.getElementById('ia-error').classList.remove('hidden');
    }
    btn.disabled=false;
}

@if(session('reponer_producto'))
setTimeout(() => showToast('Redirigiendo a inventario', 'Ve a reponer el producto seleccionado 📦', 'blue', 4000), 500);
@endif

async function descartarRestock(productId, talla) {
    const cardId = `restock-${productId}-${talla}`;
    const card   = document.getElementById(cardId);
    if (!card) return;
    try {
        const res = await fetch('{{ route("restock.descartar") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: productId, talla: talla, dias: 7 })
        });
        if (!res.ok) throw new Error('Error al guardar');
        card.classList.add('dismissing');
        showToast('¡Entendido!', 'No te molestamos con este producto por 7 días 👍', 'green', 4000);
        setTimeout(() => {
            card.remove();
            const container = document.getElementById('restock-container');
            if (container && container.querySelectorAll('.restock-card').length === 0) {
                container.closest('section').querySelector('.flex.gap-4').outerHTML = `
                    <div class="rounded-xl bg-white border border-[#c4c5da]/20 px-8 py-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-green-400 block mb-3">verified</span>
                        <p class="text-sm font-bold text-[#1a1c1c]">Todo bajo control</p>
                        <p class="text-xs text-[#747688] mt-1">Todos los productos tienen stock suficiente</p>
                    </div>`;
            }
        }, 300);
    } catch(e) {
        showToast('Error', 'No se pudo guardar, intenta de nuevo', 'red', 4000);
    }
}
</script>

@include('partials._sidebar')
</body>
</html>