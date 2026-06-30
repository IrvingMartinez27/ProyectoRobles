<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Reportes</title>
@vite(['resources/css/app.css'])
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family:'Inter',sans-serif; background:#f9f9f9; color:#1a1c1c; min-height:100dvh; transition:background 0.3s,color 0.3s; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }

    /* ANIMACIONES */
    @keyframes fadeInUp  { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
    @keyframes popIn     { 0%{opacity:0;transform:scale(0.90)} 65%{transform:scale(1.025)} 100%{opacity:1;transform:scale(1)} }
    @keyframes countUp   { from{opacity:0;transform:scale(0.85)} to{opacity:1;transform:scale(1)} }
    @keyframes slideRow  { from{opacity:0;transform:translateX(-8px)} to{opacity:1;transform:translateX(0)} }
    @keyframes barGrow   { from{width:0} to{width:var(--w)} }
    @keyframes shimmer   { 0%{background-position:-200% 0} 100%{background-position:200% 0} }

    .anim-1  { animation:fadeInUp 0.5s ease both 0.04s; }
    .anim-2  { animation:fadeInUp 0.5s ease both 0.09s; }
    .anim-3  { animation:fadeInUp 0.5s ease both 0.14s; }
    .anim-4  { animation:fadeInUp 0.5s ease both 0.19s; }
    .anim-5  { animation:fadeInUp 0.5s ease both 0.24s; }
    .anim-6  { animation:fadeInUp 0.5s ease both 0.29s; }

    .card-pop { animation:popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both; }
    .card-pop:nth-child(1){animation-delay:0.04s} .card-pop:nth-child(2){animation-delay:0.09s}
    .card-pop:nth-child(3){animation-delay:0.14s} .card-pop:nth-child(4){animation-delay:0.19s}

    .venta-row { animation:slideRow 0.3s ease both; transition:background 0.15s; }
    .venta-row:nth-child(1){animation-delay:0.03s} .venta-row:nth-child(2){animation-delay:0.06s}
    .venta-row:nth-child(3){animation-delay:0.09s} .venta-row:nth-child(4){animation-delay:0.12s}
    .venta-row:nth-child(n+5){animation-delay:0.15s}
    .venta-row:hover { background:rgba(23,55,200,0.08)!important; }

    .hero-num { animation:countUp 0.7s cubic-bezier(0.34,1.56,0.64,1) both 0.2s; }

    .bar-fill { width:0; animation:barGrow 1s cubic-bezier(0.34,1.56,0.64,1) forwards; }

    .card-hover { transition:transform 0.2s ease, box-shadow 0.2s ease; }
    .card-hover:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(23,55,200,0.15); }

    .periodo-btn { transition:all 0.2s ease; }
    .periodo-btn.activo { background:#f3f3f4!important; color:#0f1012!important; }
    .periodo-btn:not(.activo):hover { background:rgba(255,255,255,0.06); }



    /* TOP PROD LIGHT */
    .top-prod-1 { background:rgba(23,55,200,0.06); border-color:rgba(23,55,200,0.15); }
    .top-prod-2 { background:rgba(148,150,168,0.06); border-color:rgba(148,150,168,0.12); }
    .top-prod-3 { background:rgba(245,158,11,0.06); border-color:rgba(245,158,11,0.12); }
    /* DARK MODE */
    [data-theme="dark"] body                        { background:#0f1012!important; color:#f3f3f4!important; }
    [data-theme="dark"] .bg-card                    { background:#1e2022!important; border-color:rgba(255,255,255,0.06)!important; }
    [data-theme="dark"] .bg-card-deep               { background:#141618!important; }
    [data-theme="dark"] .txt-primary                { color:#f3f3f4!important; }
    [data-theme="dark"] .txt-muted                  { color:#9496a8!important; }
    [data-theme="dark"] .border-subtle              { border-color:rgba(255,255,255,0.06)!important; }
    [data-theme="dark"] .divide-subtle > * + *      { border-color:rgba(255,255,255,0.04)!important; }
    [data-theme="dark"] .periodo-btn                { border-color:rgba(255,255,255,0.08)!important; color:#9496a8!important; }
    [data-theme="dark"] .periodo-btn.activo         { background:#f3f3f4!important; color:#0f1012!important; }
    [data-theme="dark"] .periodo-btn:not(.activo):hover { background:rgba(255,255,255,0.06)!important; }
    [data-theme="dark"] input[type="date"]          { background:#141618!important; color:#f3f3f4!important; border-color:rgba(255,255,255,0.08)!important; }
    [data-theme="dark"] .venta-row:hover            { background:rgba(23,55,200,0.08)!important; }
    [data-theme="dark"] .badge-efectivo             { background:rgba(34,197,94,0.12)!important; color:#22c55e!important; }
    [data-theme="dark"] .badge-tarjeta              { background:rgba(23,55,200,0.15)!important; color:#6b8ef5!important; }
    [data-theme="dark"] .badge-transferencia        { background:rgba(245,158,11,0.12)!important; color:#f59e0b!important; }
    [data-theme="dark"] .badge-mayoreo              { background:rgba(245,158,11,0.12)!important; color:#f59e0b!important; }
    [data-theme="dark"] .badge-menudeo              { background:rgba(148,150,168,0.12)!important; color:#9496a8!important; }
    [data-theme="dark"] .kpi-icon-blue              { background:rgba(23,55,200,0.15)!important; }
    [data-theme="dark"] .avatar-cell                { background:rgba(23,55,200,0.15)!important; }
    [data-theme="dark"] .piezas-badge               { background:#141618!important; color:#9496a8!important; }
    [data-theme="dark"] .pag-btn                    { background:#141618!important; color:#9496a8!important; }
    [data-theme="dark"] .pag-btn:hover              { color:#f3f3f4!important; }
    [data-theme="dark"] .pag-btn-disabled           { background:#141618!important; color:rgba(148,150,168,0.3)!important; }
    [data-theme="dark"] .bar-bg                     { background:#141618!important; }
    [data-theme="dark"] .top-prod-1                 { background:rgba(23,55,200,0.1)!important; border-color:rgba(23,55,200,0.2)!important; }
    [data-theme="dark"] .top-prod-2                 { background:rgba(148,150,168,0.08)!important; border-color:rgba(148,150,168,0.1)!important; }
    [data-theme="dark"] .top-prod-3                 { background:rgba(245,158,11,0.08)!important; border-color:rgba(245,158,11,0.15)!important; }
    /* SCROLLBAR */
    ::-webkit-scrollbar { width:4px; height:4px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:#1737c8; border-radius:2px; }

    /* TABLA */
    thead th { position:sticky; top:0; }
    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; }
    .badge-efectivo  { background:rgba(34,197,94,0.12);  color:#22c55e; }
    .badge-tarjeta   { background:rgba(23,55,200,0.15);  color:#6b8ef5; }
    .badge-transferencia { background:rgba(245,158,11,0.12); color:#f59e0b; }
    .badge-mayoreo   { background:rgba(245,158,11,0.12); color:#f59e0b; }
    .badge-menudeo   { background:rgba(148,150,168,0.12); color:#9496a8; }

    /* INPUT DATE */
    input[type="date"] { color-scheme:dark; }
</style>
</head>
<body>

@include('partials._nav')

{{-- BREADCRUMB --}}
<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3 anim-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]/30">chevron_right</span>
        <span class="text-[#f3f3f4]">Reportes</span>
    </div>
</div>

<main class="pb-28 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <header class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 anim-1">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-2">Analytics</p>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight text-[#1a1c1c] txt-primary">Reportes</h1>
            <p class="text-sm text-[#747688] txt-muted mt-1">Analítica completa de tu negocio</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @if($esPro)
            <a href="{{ route('reporte.pdf', request()->query()) }}"
               class="card-hover flex items-center gap-2 px-4 py-2.5 bg-[#1737c8] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all"
               style="box-shadow:0 4px 16px rgba(23,55,200,0.3)">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>PDF
            </a>
            <a href="{{ route('reporte.excel', request()->query()) }}"
               class="card-hover flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all"
               style="box-shadow:0 4px 16px rgba(34,197,94,0.2)">
                <span class="material-symbols-outlined text-sm">table_chart</span>Excel
            </a>
            @endif
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-4 py-2.5 border border-[#c4c5da]/10 text-[#9496a8] text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#1e2022] transition-all">
                <span class="material-symbols-outlined text-sm">arrow_back</span>Volver
            </a>
        </div>
    </header>

    {{-- BANNER GRATIS --}}
    @if(!$esPro)
    <div class="mb-6 bg-[#1737c8]/10 border border-[#1737c8]/20 rounded-2xl px-5 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 anim-2">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[#1737c8]">lock</span>
            <div>
                <p class="text-sm font-black text-[#1a1c1c] txt-primary">Estás viendo el reporte de hoy</p>
                <p class="text-xs text-[#747688] txt-muted">Actualiza al Plan Pro para filtrar por semana, mes y exportar.</p>
            </div>
        </div>
        <a href="{{ route('planes') }}" class="shrink-0 bg-[#1737c8] text-white px-5 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90">Ver planes →</a>
    </div>
    @endif

    {{-- FILTROS PRO --}}
    @if($esPro)
    <section class="anim-2 bg-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5 mb-8">
        <div class="flex flex-wrap gap-2 items-center mb-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#9496a8] mr-1">Período:</p>
            @foreach([['hoy','Hoy'],['semana','Semana'],['mes','Este mes'],['mes_pasado','Mes pasado']] as [$val,$label])
            <a href="{{ route('reporte', ['periodo' => $val]) }}"
               class="periodo-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl border border-[#c4c5da]/30 text-[#747688] {{ request('periodo') === $val ? 'activo' : '' }}">
                {{ $label }}
            </a>
            @endforeach
            <a href="{{ route('reporte') }}"
               class="periodo-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl border border-[#c4c5da]/30 text-[#747688] {{ !request('periodo') && !request('desde') ? 'activo' : '' }}">
                Todo
            </a>
        </div>
        <form method="GET" action="{{ route('reporte') }}" class="flex flex-col md:flex-row gap-3 items-end flex-wrap border-t border-[#c4c5da]/10 pt-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black tracking-widest uppercase text-[#9496a8]">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}"
                       class="border border-[#c4c5da]/30 rounded-xl px-4 py-2.5 text-sm font-medium bg-[#f9f9f9] text-[#1a1c1c] txt-primary bg-card-deep focus:outline-none focus:border-[#1737c8] transition-all"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black tracking-widest uppercase text-[#9496a8]">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}"
                       class="border border-[#c4c5da]/30 rounded-xl px-4 py-2.5 text-sm font-medium bg-[#f9f9f9] text-[#1a1c1c] txt-primary bg-card-deep focus:outline-none focus:border-[#1737c8] transition-all"/>
            </div>
            <button type="submit"
                    class="bg-[#1737c8] text-white px-5 py-2.5 text-[10px] font-black tracking-widest uppercase rounded-xl hover:opacity-90 flex items-center gap-2 transition-all">
                <span class="material-symbols-outlined text-sm">filter_list</span>Filtrar
            </button>
        </form>
    </section>
    @endif

    {{-- HERO CARD — estilo Nu --}}
    <div class="anim-3 mb-6">
        <div class="card-hover rounded-3xl p-8 md:p-10 relative overflow-hidden"
             style="background:linear-gradient(135deg,#1737c8 0%,#0f2a9e 50%,#0a1f7a 100%);">
            {{-- Decoración fondo --}}
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10"
                 style="background:radial-gradient(circle,#fff 0%,transparent 70%);transform:translate(30%,-30%);"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-5"
                 style="background:radial-gradient(circle,#fff 0%,transparent 70%);transform:translate(-30%,30%);"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">
                        {{ !$esPro ? 'Total vendido hoy' : 'Total del período' }}
                    </p>
                    <div class="hero-num">
                        <h2 class="text-5xl md:text-6xl font-black text-white tracking-tight">${{ $totalPeriodo }}</h2>
                    </div>
                    <div class="flex items-center gap-3 mt-3 flex-wrap">
                        <div class="flex items-center gap-1.5 bg-green-500/20 border border-green-500/30 rounded-full px-3 py-1">
                            <span class="material-symbols-outlined text-green-400 text-sm">trending_up</span>
                            <span class="text-xs font-black text-green-400">{{ $totalRegistros }} transacciones</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-white/10 rounded-full px-3 py-1">
                            <span class="material-symbols-outlined text-white/60 text-sm">avg_pace</span>
                            <span class="text-xs font-black text-white/70">Ticket prom. ${{ $ticketPromedio }}</span>
                        </div>
                    </div>
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40 mb-1">Fecha</p>
                    <p class="text-lg font-black text-white">{{ now()->format('d M Y') }}</p>
                    <p class="text-xs text-white/50 mt-0.5">{{ now()->format('H:i') }} hrs</p>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card-pop card-hover bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl kpi-icon-blue bg-[#1737c8]/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#1737c8] text-sm">payments</span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted mb-1">Total ventas</p>
            <h3 class="text-2xl font-black text-[#1a1c1c] txt-primary">${{ $totalPeriodo }}</h3>
        </div>
        <div class="card-pop card-hover bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-green-500/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-green-400 text-sm">receipt_long</span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted mb-1">Transacciones</p>
            <h3 class="text-2xl font-black text-[#1a1c1c] txt-primary">{{ $totalRegistros }}</h3>
        </div>
        <div class="card-pop card-hover bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-amber-400 text-sm">avg_pace</span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted mb-1">Ticket promedio</p>
            <h3 class="text-2xl font-black text-[#1a1c1c] txt-primary">${{ $ticketPromedio }}</h3>
        </div>
        <div class="card-pop card-hover bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-purple-400 text-sm">calendar_today</span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted mb-1">Fecha</p>
            <h3 class="text-lg font-black text-[#1a1c1c] txt-primary">{{ now()->format('d/m/Y') }}</h3>
        </div>
    </div>

    @if($esPro)
    {{-- DESGLOSE CON BARRAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        {{-- MÉTODOS DE PAGO --}}
        <div class="card-hover anim-4 bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-[#1737c8] text-sm">credit_card</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] txt-muted">Por método de pago</p>
            </div>
            @php
                $totalMetodos = collect($porMetodo)->sum(fn($d) => is_array($d) ? floatval(str_replace(',','',$d['monto'])) : 0);
            @endphp
            @forelse($porMetodo as $metodo => $datos)
            @php
                $monto = is_array($datos) ? floatval(str_replace(',','',$datos['monto'])) : 0;
                $pct   = $totalMetodos > 0 ? round(($monto / $totalMetodos) * 100) : 0;
                $colors = ['efectivo'=>['bar'=>'#22c55e','bg'=>'rgba(34,197,94,0.12)','text'=>'#22c55e'],
                           'tarjeta' =>['bar'=>'#1737c8','bg'=>'rgba(23,55,200,0.15)','text'=>'#6b8ef5'],
                           'transferencia'=>['bar'=>'#f59e0b','bg'=>'rgba(245,158,11,0.12)','text'=>'#f59e0b']];
                $c = $colors[$metodo] ?? ['bar'=>'#9496a8','bg'=>'rgba(148,150,168,0.1)','text'=>'#9496a8'];
            @endphp
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:{{ $c['bar'] }}"></span>
                        <span class="text-sm font-black text-[#1a1c1c] txt-primary uppercase">{{ $metodo }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black" style="color:{{ $c['text'] }}">${{ $datos['monto'] }}</span>
                        <span class="text-[9px] text-[#9496a8] ml-2">{{ $datos['cantidad'] }} ventas</span>
                    </div>
                </div>
                <div class="h-2 rounded-full bar-bg bg-[#f3f3f4] overflow-hidden">
                    <div class="h-full rounded-full bar-fill transition-all"
                         style="--w:{{ $pct }}%; background:{{ $c['bar'] }}; width:0"
                         data-width="{{ $pct }}%"></div>
                </div>
                <p class="text-[9px] text-[#747688] txt-muted mt-1 text-right">{{ $pct }}% del total</p>
            </div>
            @empty
            <p class="text-sm text-[#747688] txt-muted">Sin datos</p>
            @endforelse
        </div>

        {{-- TIPOS DE VENTA --}}
        <div class="card-hover anim-4 bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-amber-400 text-sm">sell</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] txt-muted">Por tipo de venta</p>
            </div>
            @php
                $totalTipos = $porTipo->sum(fn($d) => floatval(str_replace(',','',$d['monto'])));
            @endphp
            @forelse($porTipo as $tipo => $datos)
            @php
                $monto = floatval(str_replace(',','',$datos['monto']));
                $pct   = $totalTipos > 0 ? round(($monto / $totalTipos) * 100) : 0;
                $isMayoreo = $tipo === 'mayoreo';
            @endphp
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:{{ $isMayoreo ? '#f59e0b' : '#1737c8' }}"></span>
                        <span class="text-sm font-black text-[#1a1c1c] txt-primary uppercase">{{ $tipo }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black {{ $isMayoreo ? 'text-amber-400' : 'text-[#6b8ef5]' }}">${{ $datos['monto'] }}</span>
                        <span class="text-[9px] text-[#9496a8] ml-2">{{ $datos['cantidad'] }} ventas</span>
                    </div>
                </div>
                <div class="h-2 rounded-full bar-bg bg-[#f3f3f4] overflow-hidden">
                    <div class="h-full rounded-full bar-fill"
                         style="--w:{{ $pct }}%; background:{{ $isMayoreo ? '#f59e0b' : '#1737c8' }}; width:0"
                         data-width="{{ $pct }}%"></div>
                </div>
                <p class="text-[9px] text-[#747688] txt-muted mt-1 text-right">{{ $pct }}% del total</p>
            </div>
            @empty
            <p class="text-sm text-[#747688] txt-muted">Sin datos</p>
            @endforelse
        </div>
    </div>

    {{-- TOP PRODUCTOS --}}
    @if(isset($topProductos) && $topProductos->count() > 0)
    <div class="card-hover anim-5 bg-card bg-white border border-[#c4c5da]/20 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400 text-sm">emoji_events</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] txt-muted">Top productos del período</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($topProductos->take(3) as $i => $prod)
            @php
                $medals = ['🥇','🥈','🥉'];
                $bgColors = ['rgba(23,55,200,0.1)','rgba(148,150,168,0.08)','rgba(245,158,11,0.08)'];
                $borderColors = ['rgba(23,55,200,0.2)','rgba(148,150,168,0.1)','rgba(245,158,11,0.15)'];
            @endphp
            <div class="rounded-2xl p-4 border" class="top-prod-{{ $i + 1 }} rounded-2xl p-4 border">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-2xl">{{ $medals[$i] }}</span>
                    <span class="text-[9px] font-black text-[#9496a8] uppercase">#{{ $i + 1 }}</span>
                </div>
                <p class="font-black text-sm text-[#1a1c1c] txt-primary uppercase truncate mb-1">{{ $prod['nombre'] }}</p>
                <p class="text-xs text-[#747688] txt-muted">{{ $prod['unidades'] }} unidades</p>
                <p class="text-lg font-black text-[#1737c8] mt-2">${{ number_format($prod['monto'], 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    {{-- TABLA DETALLE --}}
    <section class="anim-6 bg-card bg-white border border-[#c4c5da]/20 rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-[#c4c5da]/20 border-subtle flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#1737c8] text-sm">receipt_long</span>
                <h2 class="text-base font-black text-[#1a1c1c] txt-primary">{{ !$esPro ? 'Ventas de hoy' : 'Detalle de ventas' }}</h2>
            </div>
            <span class="text-[9px] font-black text-[#747688] txt-muted bg-[#f3f3f4] bg-card-deep px-3 py-1.5 rounded-full">
                {{ $ventas->total() ?? count($ventas) }} registros
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-card-deep bg-[#f3f3f4] border-b border-[#c4c5da]/20 border-subtle">
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">#</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Cliente</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Piezas</th>
                        @if($esPro)
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Método</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Tipo</th>
                        @endif
                        <th class="px-5 py-3.5 text-left text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Fecha</th>
                        <th class="px-5 py-3.5 text-right text-[9px] font-black uppercase tracking-widest text-[#747688] txt-muted">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#c4c5da]/05">
                    @forelse($ventas ?? [] as $venta)
                    <tr class="venta-row">
                        <td class="px-5 py-4 text-[#9496a8] font-mono text-xs">#{{ $venta['id'] }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl avatar-cell bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                                    <span class="text-[#6b8ef5] font-black text-xs">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}</span>
                                </div>
                                <span class="font-bold text-[#1a1c1c] txt-primary text-sm whitespace-nowrap">{{ $venta['cliente'] }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="piezas-badge bg-[#f3f3f4] text-[#747688] text-[10px] font-black px-2.5 py-1 rounded-lg">
                                {{ $venta['num_productos'] }} art.
                            </span>
                        </td>
                        @if($esPro)
                        <td class="px-5 py-4">
                            <span class="badge badge-{{ $venta['metodo_pago'] }}">
                                {{ $venta['metodo_pago'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="badge badge-{{ $venta['tipo_venta'] ?? 'menudeo' }}">
                                {{ $venta['tipo_venta'] ?? 'menudeo' }}
                            </span>
                        </td>
                        @endif
                        <td class="px-5 py-4 text-[#9496a8] text-xs whitespace-nowrap">{{ $venta['fecha'] }}</td>
                        <td class="px-5 py-4 text-right font-black text-[#1737c8] whitespace-nowrap">${{ $venta['total'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $esPro ? 7 : 5 }}" class="px-5 py-16 text-center">
                            <span class="material-symbols-outlined text-4xl text-[#c4c5da]/20 block mb-3">receipt_long</span>
                            <p class="text-sm text-[#747688] txt-muted">Sin ventas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if(method_exists($ventas, 'links'))
        <div class="px-6 py-4 border-t border-[#c4c5da]/20 border-subtle flex items-center justify-between">
            <p class="text-xs text-[#747688] txt-muted">
                Mostrando {{ $ventas->firstItem() }}–{{ $ventas->lastItem() }} de {{ $ventas->total() }}
            </p>
            <div class="flex gap-2">
                @if($ventas->onFirstPage())
                <span class="pag-btn-disabled px-3 py-1.5 rounded-xl bg-[#f3f3f4] text-[#747688]/40 text-xs font-black cursor-not-allowed">← Ant.</span>
                @else
                <a href="{{ $ventas->previousPageUrl() }}" class="pag-btn px-3 py-1.5 rounded-xl bg-[#f3f3f4] text-[#747688] text-xs font-black hover:text-[#1a1c1c] transition-all">← Ant.</a>
                @endif
                @if($ventas->hasMorePages())
                <a href="{{ $ventas->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-[#1737c8] text-white text-xs font-black hover:opacity-90 transition-all">Sig. →</a>
                @else
                <span class="pag-btn-disabled px-3 py-1.5 rounded-xl bg-[#f3f3f4] text-[#747688]/40 text-xs font-black cursor-not-allowed">Sig. →</span>
                @endif
            </div>
        </div>
        @endif
    </section>

</main>

@include('partials._sidebar')

<script>
// Animar barras al cargar
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.bar-fill').forEach(bar => {
            const w = bar.getAttribute('data-width');
            bar.style.width = w;
        });
    }, 400);
});

// Flash toasts
@php $flashS = session('success'); $flashE = session('error'); @endphp
@if($flashS)
document.addEventListener('DOMContentLoaded', () => showToast('¡Listo!', @json($flashS), 'green', 5000));
@endif
@if($flashE)
document.addEventListener('DOMContentLoaded', () => showToast('Error', @json($flashE), 'red', 5000));
@endif
</script>
</body>
</html>