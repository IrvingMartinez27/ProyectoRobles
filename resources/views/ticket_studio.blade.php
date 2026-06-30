<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Ticket Studio</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family:'Inter',sans-serif; background:#f9f9f9; color:#1a1c1c; min-height:100dvh; transition:background 0.3s,color 0.3s; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }

    @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    @keyframes popIn    { 0%{opacity:0;transform:scale(0.88)} 65%{transform:scale(1.02)} 100%{opacity:1;transform:scale(1)} }

    .anim-1 { animation:fadeInUp 0.45s ease both 0.04s; }
    .anim-2 { animation:fadeInUp 0.45s ease both 0.10s; }
    .anim-3 { animation:fadeInUp 0.45s ease both 0.16s; }

    /* ── TEMPLATE CARDS ──────────────────────────────────── */
    .tpl-card { transition:all 0.2s ease; cursor:pointer; }
    .tpl-card:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(23,55,200,0.08); }
    .tpl-card.selected { border-color:#1737c8 !important; box-shadow:0 0 0 3px rgba(23,55,200,0.18); }

    /* ── TICKET PAPER — SIEMPRE BLANCO ───────────────────── */
    .ticket-paper {
        background: #ffffff !important;
        color: #1a1c1c !important;
        font-size: 11px;
        width: 240px;
        min-height: 500px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.18);
        border-radius: 3px;
        overflow: hidden;
        position: relative;
        transition: font-family 0.2s ease, box-shadow 0.3s ease;
    }

    /* ── MARCA DE AGUA ───────────────────────────────────── */
    .ticket-watermark {
        position:absolute; inset:0;
        display:flex; align-items:center; justify-content:center;
        pointer-events:none; z-index:0; overflow:hidden;
    }
    .ticket-watermark img {
        width:180px;
        opacity:0.04;
        transform:rotate(-30deg);
        filter:grayscale(100%);
        user-select:none;
    }
    .ticket-content { position:relative; z-index:1; }

    /* ══════════════════════════════════════════════════════
       DISEÑO 1 — MINIMALISTA
       Todo centrado, limpio, total en caja sutil
    ══════════════════════════════════════════════════════ */
    .tpl-minimalista .tk-header {
        padding: 20px 16px 14px;
        text-align: center;
        border-bottom: 1.5px solid var(--accent,#1737c8);
    }
    .tpl-minimalista .tk-logo-wrap {
        display: flex; justify-content: center; margin-bottom: 8px;
    }
    .tpl-minimalista .tk-logo-wrap img { max-height: 32px; max-width: 90px; object-fit: contain; }
    .tpl-minimalista .tk-store   { font-size:13px; font-weight:900; color:var(--accent,#1737c8); letter-spacing:-0.3px; }
    .tpl-minimalista .tk-folio   { font-size:8px; color:#9496a8; margin-top:3px; text-transform:uppercase; letter-spacing:.1em; }
    .tpl-minimalista .tk-section { padding:9px 16px; border-bottom:1px dashed #e5e5f0; }
    .tpl-minimalista .tk-sectitle{ font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.15em; color:#9496a8; margin-bottom:5px; }
    .tpl-minimalista .tk-total   {
        margin:12px 16px; border-radius:8px;
        padding:12px 14px;
        background:#f9f9fb;
        border:1.5px solid var(--accent,#1737c8);
        text-align:center;
    }
    .tpl-minimalista .tk-total-l { font-size:8px; font-weight:700; color:#9496a8; text-transform:uppercase; letter-spacing:.1em; }
    .tpl-minimalista .tk-total-v { font-size:22px; font-weight:900; color:var(--accent,#1737c8); line-height:1.1; }
    .tpl-minimalista .tk-footer  { padding:10px 16px 14px; text-align:center; }
    .tpl-minimalista .tk-puntos  { background:var(--accent,#1737c8); color:#fff; display:inline-block; border-radius:20px; padding:3px 10px; font-size:8px; font-weight:700; margin-top:6px; }

    /* ══════════════════════════════════════════════════════
       DISEÑO 2 — DESTACADO
       Header sólido con color acento, total negro agresivo
    ══════════════════════════════════════════════════════ */
    .tpl-destacado .tk-header  {
        background: var(--accent,#1737c8);
        padding: 18px 16px 14px;
        position: relative; overflow: hidden;
    }
    .tpl-destacado .tk-header::after {
        content:''; position:absolute; bottom:-20px; right:-20px;
        width:80px; height:80px; border-radius:50%;
        background:rgba(255,255,255,0.08);
    }
    .tpl-destacado .tk-logo-wrap { margin-bottom:8px; }
    .tpl-destacado .tk-logo-wrap img {
        max-height:28px; max-width:80px; object-fit:contain;
        filter:brightness(0) invert(1); opacity:0.9;
    }
    .tpl-destacado .tk-store   { font-size:14px; font-weight:900; color:#fff; }
    .tpl-destacado .tk-folio   { font-size:8px; color:rgba(255,255,255,.6); margin-top:2px; }
    .tpl-destacado .tk-section { padding:9px 16px; border-bottom:1px solid #f0f0f0; }
    .tpl-destacado .tk-sectitle{ font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.15em; color:var(--accent,#1737c8); margin-bottom:5px; }
    .tpl-destacado .tk-total   { background:#1a1c1c; padding:14px 16px; }
    .tpl-destacado .tk-total-l { font-size:8px; font-weight:700; color:rgba(255,255,255,.4); text-transform:uppercase; letter-spacing:.1em; }
    .tpl-destacado .tk-total-v { font-size:22px; font-weight:900; color:var(--accent,#1737c8); }
    .tpl-destacado .tk-footer  { padding:10px 16px 14px; background:#fafafa; border-top:1px solid #f0f0f0; }
    .tpl-destacado .tk-puntos  { background:rgba(255,255,255,.15); color:#fff; display:inline-block; border-radius:4px; padding:3px 8px; font-size:8px; font-weight:700; margin-top:6px; }

    /* ══════════════════════════════════════════════════════
       DISEÑO 3 — CLÁSICO
       Izquierda, logo al lado, líneas punteadas térmicas
    ══════════════════════════════════════════════════════ */
    .tpl-clasico .tk-header {
        padding: 14px 14px 10px;
        border-bottom: 2px dashed #1a1c1c;
    }
    .tpl-clasico .tk-logo-row {
        display:table; width:100%;
    }
    .tpl-clasico .tk-logo-cell {
        display:table-cell; vertical-align:middle; width:36px;
    }
    .tpl-clasico .tk-logo-wrap img { max-height:30px; max-width:32px; object-fit:contain; }
    .tpl-clasico .tk-logo-text { display:table-cell; vertical-align:middle; padding-left:8px; }
    .tpl-clasico .tk-store     { font-size:12px; font-weight:900; color:#1a1c1c; text-transform:uppercase; letter-spacing:.04em; }
    .tpl-clasico .tk-folio     { font-size:8px; color:#9496a8; margin-top:1px; }
    .tpl-clasico .tk-section   { padding:8px 14px; border-bottom:1px dashed #c4c5da; }
    .tpl-clasico .tk-sectitle  { font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:.12em; color:#1a1c1c; margin-bottom:4px; padding-bottom:3px; border-bottom:1px dotted #c4c5da; }
    .tpl-clasico .tk-total     { padding:10px 14px; border-top:2px dashed #1a1c1c; border-bottom:2px dashed #1a1c1c; background:#f9f9f9; }
    .tpl-clasico .tk-total-l   { font-size:8px; font-weight:700; color:#9496a8; text-transform:uppercase; }
    .tpl-clasico .tk-total-v   { font-size:20px; font-weight:900; color:#1a1c1c; font-family:'Courier New',monospace !important; }
    .tpl-clasico .tk-footer    { padding:8px 14px 12px; border-top:1px dashed #c4c5da; }
    .tpl-clasico .tk-puntos    { background:#f3f3f4; color:var(--accent,#1737c8); display:inline-block; border-radius:3px; padding:2px 7px; font-size:8px; font-weight:700; margin-top:4px; border:1px dashed var(--accent,#1737c8); }

    /* ── COMUNES TICKET ──────────────────────────────────── */
    .tk-prod-name   { font-size:10px; font-weight:700; color:#1a1c1c; }
    .tk-prod-det    { font-size:8px; color:#9496a8; margin-top:1px; }
    .tk-prod-precio { font-size:10px; font-weight:900; color:#1a1c1c; text-align:right; }
    .tk-cliente     { font-size:11px; font-weight:700; color:#1a1c1c; }
    .tk-cliente-det { font-size:8px; color:#9496a8; margin-top:1px; }
    .tk-lbl         { font-size:8px; color:#9496a8; padding:2px 0; }
    .tk-val         { font-size:9px; font-weight:700; color:#1a1c1c; text-align:right; padding:2px 0; }
    .tk-footer-msg  { font-size:9px; color:#1a1c1c; font-weight:600; margin-bottom:4px; }
    .tk-footer-sub  { font-size:7px; color:#9496a8; }
    .tk-powered     { font-size:7px; color:#c4c5da; margin-top:4px; letter-spacing:.1em; text-transform:uppercase; }
    .tk-qr-label    { font-size:7px; color:#9496a8; margin-top:3px; }
    .tk-qr-wrap     { padding:10px 0 6px; text-align:center; }

    /* ── DARK MODE controles ─────────────────────────────── */
    [data-theme="dark"] body                      { background:#0f1012!important; color:#f3f3f4!important; }
    [data-theme="dark"] .ctrl-card                { background:#1e2022!important; border-color:rgba(255,255,255,0.07)!important; }
    [data-theme="dark"] .ctrl-label               { color:#9496a8!important; }
    [data-theme="dark"] .ctrl-input               { background:#141618!important; color:#f3f3f4!important; border-color:rgba(255,255,255,0.09)!important; }
    [data-theme="dark"] .ctrl-input:focus         { border-color:#1737c8!important; }
    [data-theme="dark"] .tpl-card                 { background:#1e2022!important; border-color:rgba(255,255,255,0.07)!important; }
    [data-theme="dark"] .tpl-card.selected        { border-color:#1737c8!important; }
    [data-theme="dark"] .tpl-mini-bg              { background:rgba(255,255,255,0.05)!important; }
    [data-theme="dark"] .tpl-mini-line            { background:rgba(255,255,255,0.09)!important; }
    [data-theme="dark"] .preview-stage            { background:#141618!important; }
    [data-theme="dark"] .txt-primary              { color:#f3f3f4!important; }
    [data-theme="dark"] .txt-muted                { color:#9496a8!important; }
    [data-theme="dark"] .btn-save                 { box-shadow:0 4px 20px rgba(23,55,200,0.4)!important; }
    [data-theme="dark"] .live-badge               { background:rgba(23,55,200,0.2)!important; color:#6b8ef5!important; }
    [data-theme="dark"] .upload-label             { border-color:rgba(23,55,200,0.35)!important; color:#6b8ef5!important; }
    [data-theme="dark"] .preset-dot               { border-color:#0f1012!important; }
</style>
</head>
<body>

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="anim-1 flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest txt-muted text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="hover:text-[#1737c8] transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]/40">chevron_right</span>
        <span class="txt-primary text-[#1a1c1c]">Ticket Studio</span>
    </div>
</div>

<main class="pb-28 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto">

    <header class="anim-1 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8]">Business</p>
                <span class="text-[#c4c5da]/40">·</span>
                <p class="text-[10px] font-black uppercase tracking-widest txt-muted text-[#747688]">Exclusivo</p>
            </div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight txt-primary text-[#1a1c1c]">Ticket Studio</h1>
            <p class="text-sm txt-muted text-[#747688] mt-1">Diseña el comprobante de tu tienda</p>
        </div>
        <a href="{{ route('sales') }}"
           class="self-start flex items-center gap-2 px-4 py-2.5 border border-[#c4c5da]/30 rounded-xl text-[10px] font-black uppercase tracking-widest txt-muted text-[#747688] hover:border-[#1737c8] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-sm">arrow_back</span>Volver
        </a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- ══ CONTROLES ══ --}}
        <div class="lg:col-span-7 space-y-4 anim-2">
            <form method="POST" action="{{ route('ticket.config') }}" enctype="multipart/form-data">
                @csrf

                {{-- LOGO --}}
                <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-[#1737c8]/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1737c8] text-sm">image</span>
                        </div>
                        <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Logo de tu tienda</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div id="logo-preview-wrap"
                             class="w-16 h-16 rounded-xl bg-[#f3f3f4] border border-[#c4c5da]/20 flex items-center justify-center overflow-hidden shrink-0">
                            @if($tenant->ticket_logo)
                                <img src="{{ asset('storage/'.$tenant->ticket_logo) }}" class="w-full h-full object-contain p-1"/>
                            @else
                                <img src="{{ url('images/quivex-logo.png') }}" class="w-full h-full object-contain p-2 opacity-30"/>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="upload-label flex items-center gap-2 px-4 py-2.5 border border-dashed border-[#1737c8]/30 rounded-xl text-[10px] font-black uppercase tracking-widest text-[#1737c8] hover:bg-[#1737c8]/5 cursor-pointer transition-all">
                                <span class="material-symbols-outlined text-sm">upload</span>Subir logo
                                <input type="file" name="ticket_logo" id="logo-input" accept="image/*" class="hidden" onchange="previewLogo(this)"/>
                            </label>
                            <p class="text-[9px] txt-muted text-[#9496a8] mt-1.5">PNG o JPG transparente · Máx 1MB</p>
                        </div>
                    </div>
                </div>

                {{-- COLOR + FUENTE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-[#1737c8]/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[#1737c8] text-sm">palette</span>
                            </div>
                            <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Color de acento</p>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <input type="color" name="ticket_color" id="color-input"
                                   value="{{ $tenant->ticket_color ?? '#1737c8' }}"
                                   class="w-11 h-11 rounded-xl border border-[#c4c5da]/20 cursor-pointer p-0.5 shrink-0"
                                   oninput="actualizarColor()"/>
                            <div>
                                <p class="text-sm font-black txt-primary text-[#1a1c1c]" id="color-hex">{{ $tenant->ticket_color ?? '#1737c8' }}</p>
                                <p class="text-[9px] txt-muted text-[#9496a8]">Títulos y acento</p>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['#1737c8','#1a1c1c','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899'] as $p)
                            <button type="button" onclick="setColor('{{ $p }}')"
                                    class="preset-dot w-6 h-6 rounded-full border-2 border-white shadow-sm hover:scale-110 transition-transform"
                                    style="background:{{ $p }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-[#1737c8]/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[#1737c8] text-sm">text_fields</span>
                            </div>
                            <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Fuente del ticket</p>
                        </div>
                        <select name="ticket_font" id="font-select"
                                class="ctrl-input w-full border border-[#c4c5da]/20 rounded-xl px-4 py-3 text-sm font-bold bg-[#f9f9f9] text-[#1a1c1c] focus:outline-none focus:border-[#1737c8] transition-all cursor-pointer"
                                onchange="actualizarFuente()">
                            <option value="Inter" {{ ($tenant->ticket_font ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter — Moderna</option>
                            <option value="Roboto Mono" {{ ($tenant->ticket_font ?? '') === 'Roboto Mono' ? 'selected' : '' }}>Roboto Mono — Térmica</option>
                            <option value="Courier New" {{ ($tenant->ticket_font ?? '') === 'Courier New' ? 'selected' : '' }}>Courier New — Clásica</option>
                        </select>
                        <p class="text-[9px] txt-muted text-[#9496a8] mt-2">Cambia en tiempo real</p>
                    </div>
                </div>

                {{-- TEMPLATES --}}
                <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-[#1737c8]/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1737c8] text-sm">style</span>
                        </div>
                        <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Diseño del ticket</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3">

                        {{-- MINIMALISTA --}}
                        <div class="tpl-card rounded-xl border-2 p-3 {{ ($tenant->ticket_template ?? 'minimalista') === 'minimalista' ? 'selected border-[#1737c8]' : 'border-[#c4c5da]/20' }}"
                             onclick="selTemplate('minimalista')">
                            <div class="mb-2 space-y-1.5 bg-white rounded p-1.5 border border-[#c4c5da]/20">
                                <div class="h-1.5 rounded tpl-mini-bg bg-[#f3f3f4]" style="width:50%;margin:0 auto;"></div>
                                <div style="height:1px;border-top:1.5px solid #1737c8;" id="mini-min-border"></div>
                                <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]" style="width:90%;"></div>
                                <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]" style="width:75%;"></div>
                                <div class="rounded p-1 mt-1" style="border:1.5px solid #1737c8;text-align:center;" id="mini-min-box">
                                    <div style="font-size:7px;font-weight:900;color:#1737c8;">$8,200</div>
                                </div>
                            </div>
                            <p class="text-[10px] font-black uppercase text-center txt-primary text-[#1a1c1c]">Minimal</p>
                            <p class="text-[8px] txt-muted text-[#9496a8] text-center">Limpio · centrado</p>
                        </div>

                        {{-- DESTACADO --}}
                        <div class="tpl-card rounded-xl border-2 p-3 {{ ($tenant->ticket_template ?? 'minimalista') === 'destacado' ? 'selected border-[#1737c8]' : 'border-[#c4c5da]/20' }}"
                             onclick="selTemplate('destacado')">
                            <div class="mb-2 space-y-1.5 bg-white rounded overflow-hidden border border-[#c4c5da]/20">
                                <div class="p-1.5" style="background:#1737c8;" id="mini-dest-header">
                                    <div style="height:5px;background:rgba(255,255,255,0.3);border-radius:2px;width:60%;"></div>
                                </div>
                                <div class="px-1.5 space-y-1">
                                    <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]" style="width:90%;"></div>
                                    <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]" style="width:70%;"></div>
                                </div>
                                <div class="p-1.5" style="background:#1a1c1c;">
                                    <div style="font-size:7px;font-weight:900;color:#1737c8;" id="mini-dest-total">$8,200</div>
                                </div>
                            </div>
                            <p class="text-[10px] font-black uppercase text-center txt-primary text-[#1a1c1c]">Destacado</p>
                            <p class="text-[8px] txt-muted text-[#9496a8] text-center">Header sólido</p>
                        </div>

                        {{-- CLÁSICO --}}
                        <div class="tpl-card rounded-xl border-2 p-3 {{ ($tenant->ticket_template ?? 'minimalista') === 'clasico' ? 'selected border-[#1737c8]' : 'border-[#c4c5da]/20' }}"
                             onclick="selTemplate('clasico')">
                            <div class="mb-2 space-y-1 bg-white rounded p-1.5 border border-[#c4c5da]/20">
                                <div class="h-1.5 rounded tpl-mini-line bg-[#e5e5f0]" style="width:65%;"></div>
                                <div style="border-top:1.5px dashed #c4c5da;margin:2px 0;"></div>
                                <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]"></div>
                                <div class="h-1 rounded tpl-mini-line bg-[#e5e5f0]" style="width:80%;"></div>
                                <div style="border-top:1.5px dashed #1a1c1c;margin:2px 0;"></div>
                                <div style="font-size:7px;font-weight:900;color:#1a1c1c;font-family:'Courier New';">$8,200.00</div>
                                <div style="border-bottom:1.5px dashed #1a1c1c;"></div>
                            </div>
                            <p class="text-[10px] font-black uppercase text-center txt-primary text-[#1a1c1c]">Clásico</p>
                            <p class="text-[8px] txt-muted text-[#9496a8] text-center">Térmico · punteado</p>
                        </div>
                    </div>
                    <input type="hidden" name="ticket_template" id="template-input" value="{{ $tenant->ticket_template ?? 'minimalista' }}"/>
                </div>

                {{-- WHATSAPP --}}
                <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-500 text-sm">chat</span>
                        </div>
                        <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">WhatsApp del negocio</p>
                    </div>
                    <input type="text" name="ticket_whatsapp" id="whatsapp-input"
                           value="{{ $tenant->ticket_whatsapp ?? '' }}"
                           placeholder="5212345678901 (con código de país)"
                           class="ctrl-input w-full border border-[#c4c5da]/20 rounded-xl px-4 py-3 text-sm bg-[#f9f9f9] text-[#1a1c1c] focus:outline-none focus:border-[#1737c8] transition-all"/>
                    <p class="text-[9px] txt-muted text-[#9496a8] mt-1.5">El QR del ticket abrirá tu WhatsApp</p>
                </div>

                {{-- MENSAJE --}}
                <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-[#1737c8]/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1737c8] text-sm">comment</span>
                        </div>
                        <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Mensaje de pie de página</p>
                    </div>
                    <textarea name="ticket_mensaje" id="mensaje-input" rows="2"
                              placeholder="¡Gracias por tu compra! Síguenos como @TuTienda"
                              maxlength="200"
                              class="ctrl-input w-full border border-[#c4c5da]/20 rounded-xl px-4 py-3 text-sm bg-[#f9f9f9] text-[#1a1c1c] focus:outline-none focus:border-[#1737c8] resize-none transition-all"
                              oninput="actualizarMensaje()">{{ $tenant->ticket_mensaje ?? '' }}</textarea>
                    <div class="flex justify-between mt-1.5">
                        <p class="text-[9px] txt-muted text-[#9496a8]">Máx 200 caracteres</p>
                        <p class="text-[9px] txt-muted text-[#9496a8]" id="char-count">{{ strlen($tenant->ticket_mensaje ?? '') }}/200</p>
                    </div>
                </div>

                <button type="submit"
                        class="btn-save w-full bg-[#1737c8] text-white py-4 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:opacity-90 transition-all flex items-center justify-center gap-2"
                        style="box-shadow:0 4px 20px rgba(23,55,200,0.3)">
                    <span class="material-symbols-outlined text-sm">save</span>Guardar configuración
                </button>
            </form>
        </div>

        {{-- ══ PREVIEW ══ --}}
        <div class="lg:col-span-5 anim-3 lg:sticky lg:top-24 lg:self-start">
            <div class="ctrl-card bg-white rounded-2xl border border-[#c4c5da]/20 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#1737c8] text-sm">preview</span>
                        <p class="ctrl-label text-[10px] font-black uppercase tracking-widest text-[#747688]">Preview</p>
                    </div>
                    <span class="live-badge text-[9px] bg-[#1737c8]/10 text-[#1737c8] px-2.5 py-1 rounded-full font-black">● Live</span>
                </div>

                <div class="preview-stage bg-[#f3f3f4] rounded-xl p-5 flex items-start justify-center min-h-[560px]">

                    {{-- TICKET PAPER --}}
                    <div id="ticket-paper" class="ticket-paper tpl-{{ $tenant->ticket_template ?? 'minimalista' }}">

                        {{-- MARCA DE AGUA --}}
                        <div class="ticket-watermark">
                            <img src="{{ url('images/quivex-logo.png') }}" alt="wm" id="wm-img"/>
                        </div>

                        <div class="ticket-content">

                            {{-- ── HEADER ── --}}
                            <div class="tk-header" id="tk-header">

                                {{-- Minimalista / Clásico (sin bg) --}}
                                <div id="tk-plain-header">
                                    {{-- Clásico: logo + texto en fila --}}
                                    <div class="tk-logo-row" id="tk-clasico-row" style="display:none;">
                                        <div class="tk-logo-cell">
                                            <div class="tk-logo-wrap">
                                                <img src="{{ url('images/quivex-logo.png') }}" id="tk-logo-clasico" alt="logo"/>
                                            </div>
                                        </div>
                                        <div class="tk-logo-text">
                                            <div class="tk-store" id="tk-store-clasico">{{ $tenant->store_name ?? 'Mi Tienda' }}</div>
                                            <div class="tk-folio">QVX-0001 · {{ now()->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    {{-- Minimalista: centrado --}}
                                    <div id="tk-minimal-block">
                                        <div class="tk-logo-wrap">
                                            <img src="{{ url('images/quivex-logo.png') }}" id="tk-logo-minimal" alt="logo"/>
                                        </div>
                                        <div class="tk-store" id="tk-store-minimal">{{ $tenant->store_name ?? 'Mi Tienda' }}</div>
                                        <div class="tk-folio">QVX-0001 · {{ now()->format('d/m/Y') }}</div>
                                    </div>
                                </div>

                                {{-- Destacado: fondo sólido --}}
                                <div id="tk-dest-header" style="display:none;">
                                    <div class="tk-logo-wrap">
                                        <img src="{{ url('images/quivex-logo.png') }}" id="tk-logo-dest" alt="logo"/>
                                    </div>
                                    <div class="tk-store" id="tk-store-dest">{{ $tenant->store_name ?? 'Mi Tienda' }}</div>
                                    <div class="tk-folio">QVX-0001 · {{ now()->format('d/m/Y') }}</div>
                                </div>

                            </div>

                            {{-- CLIENTE --}}
                            <div class="tk-section">
                                <div class="tk-sectitle">Cliente</div>
                                <div class="tk-cliente">Ana García</div>
                                <div class="tk-cliente-det">Efectivo · Menudeo</div>
                            </div>

                            {{-- PRODUCTOS --}}
                            <div class="tk-section">
                                <div class="tk-sectitle">Productos</div>
                                <table width="100%" style="border-collapse:collapse;">
                                    <tr>
                                        <td width="70%" valign="top">
                                            <div class="tk-prod-name">Jordan 1 Retro</div>
                                            <div class="tk-prod-det">x1 · Talla 28 MX</div>
                                        </td>
                                        <td align="right" valign="top">
                                            <div class="tk-prod-precio">$3,200.00</div>
                                        </td>
                                    </tr>
                                    <tr><td colspan="2" style="height:5px;"></td></tr>
                                    <tr>
                                        <td width="70%" valign="top">
                                            <div class="tk-prod-name">Air Force One</div>
                                            <div class="tk-prod-det">x2 · Talla 27 MX</div>
                                        </td>
                                        <td align="right" valign="top">
                                            <div class="tk-prod-precio">$5,000.00</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            {{-- SUBTOTAL --}}
                            <div class="tk-section">
                                <table width="100%" style="border-collapse:collapse;">
                                    <tr>
                                        <td class="tk-lbl">Subtotal</td>
                                        <td class="tk-val">$8,200.00</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- TOTAL --}}
                            <div id="tk-total-wrap">
                                <div class="tk-total">
                                    <div class="tk-total-l">Total</div>
                                    <div class="tk-total-v">$8,200.00</div>
                                    <div class="tk-puntos" id="tk-puntos">+164 puntos</div>
                                </div>
                            </div>

                            {{-- QR simulado --}}
                            <div class="tk-qr-wrap">
                                <div style="width:56px;height:56px;margin:0 auto;background:#f3f3f4;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                    <span style="font-size:26px;color:#c4c5da;">▦</span>
                                </div>
                                <div class="tk-qr-label">Escanea para contactarnos</div>
                            </div>

                            {{-- FOOTER --}}
                            <div class="tk-footer">
                                <div class="tk-footer-msg" id="tk-footer-msg">{{ $tenant->ticket_mensaje ?: '¡Gracias por tu compra!' }}</div>
                                <div class="tk-footer-sub">{{ now()->format('d/m/Y H:i') }}</div>
                                <div class="tk-powered">Generado por Quivex</div>
                            </div>

                        </div>{{-- /content --}}
                    </div>{{-- /paper --}}
                </div>

                <p class="text-[9px] txt-muted text-[#9496a8] text-center mt-3">Vista aproximada del ticket impreso</p>
            </div>
        </div>

    </div>
</main>

@include('partials._sidebar')

<script>
var currentTemplate = '{{ $tenant->ticket_template ?? "minimalista" }}';
var currentColor    = '{{ $tenant->ticket_color    ?? "#1737c8" }}';

document.addEventListener('DOMContentLoaded', function() {
    aplicarTemplate(currentTemplate, false);
    aplicarColor(currentColor);
    actualizarMiniaturas(currentColor);
    actualizarFuente();
});

// ── COLOR ─────────────────────────────────────────────────
function actualizarColor() {
    currentColor = document.getElementById('color-input').value;
    document.getElementById('color-hex').textContent = currentColor;
    aplicarColor(currentColor);
    actualizarMiniaturas(currentColor);
}

function setColor(hex) {
    document.getElementById('color-input').value = hex;
    actualizarColor();
}

function aplicarColor(c) {
    var paper = document.getElementById('ticket-paper');
    paper.style.setProperty('--accent', c);

    var destHeader = document.getElementById('tk-dest-header');
    if (destHeader && currentTemplate === 'destacado') {
        // La clase CSS ya usa --accent, pero forzamos en header directo
        paper.querySelector('.tk-header').style.background = (currentTemplate === 'destacado') ? c : '';
    }
}

function actualizarMiniaturas(c) {
    var els = {
        'mini-min-border': function(el){ el.style.borderTopColor = c; },
        'mini-min-box':    function(el){ el.style.borderColor = c; el.querySelector('div').style.color = c; },
        'mini-dest-header':function(el){ el.style.background = c; },
        'mini-dest-total': function(el){ el.style.color = c; },
    };
    Object.keys(els).forEach(function(id){
        var el = document.getElementById(id);
        if(el) els[id](el);
    });
}

// ── TEMPLATE ─────────────────────────────────────────────
function selTemplate(tpl) {
    currentTemplate = tpl;
    document.getElementById('template-input').value = tpl;

    document.querySelectorAll('.tpl-card').forEach(function(c){
        c.classList.remove('selected','border-[#1737c8]');
        c.classList.add('border-[#c4c5da]/20');
    });
    var card = document.querySelector('[onclick="selTemplate(\'' + tpl + '\')"]');
    if (card) {
        card.classList.add('selected','border-[#1737c8]');
        card.classList.remove('border-[#c4c5da]/20');
    }

    aplicarTemplate(tpl, true);
    aplicarColor(currentColor);
}

function aplicarTemplate(tpl, animate) {
    var paper     = document.getElementById('ticket-paper');
    var plainHdr  = document.getElementById('tk-plain-header');
    var destHdr   = document.getElementById('tk-dest-header');
    var clasicoRow= document.getElementById('tk-clasico-row');
    var minBlock  = document.getElementById('tk-minimal-block');
    var hdr       = paper.querySelector('.tk-header');

    // Limpiar clases
    paper.classList.remove('tpl-minimalista','tpl-destacado','tpl-clasico');
    paper.classList.add('tpl-' + tpl);

    if (tpl === 'minimalista') {
        plainHdr.style.display  = '';
        destHdr.style.display   = 'none';
        clasicoRow.style.display= 'none';
        minBlock.style.display  = '';
        hdr.style.background    = '';
        hdr.style.textAlign     = 'center';
    } else if (tpl === 'destacado') {
        plainHdr.style.display  = 'none';
        destHdr.style.display   = '';
        clasicoRow.style.display= 'none';
        minBlock.style.display  = '';
        hdr.style.background    = currentColor;
        hdr.style.textAlign     = 'left';
    } else if (tpl === 'clasico') {
        plainHdr.style.display  = '';
        destHdr.style.display   = 'none';
        clasicoRow.style.display= '';
        minBlock.style.display  = 'none';
        hdr.style.background    = '';
        hdr.style.textAlign     = 'left';
    }

    if (animate) {
        paper.style.opacity = '0.5';
        paper.style.transform = 'scale(0.96)';
        setTimeout(function(){
            paper.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            paper.style.opacity = '1';
            paper.style.transform = 'scale(1)';
        }, 50);
    }
}

// ── FUENTE ────────────────────────────────────────────────
function actualizarFuente() {
    var font = document.getElementById('font-select').value;
    document.getElementById('ticket-paper').style.fontFamily = "'" + font + "', Arial, sans-serif";
}

// ── LOGO PREVIEW ─────────────────────────────────────────
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // Actualizar todos los logos del ticket
            ['tk-logo-minimal','tk-logo-dest','tk-logo-clasico'].forEach(function(id){
                var img = document.getElementById(id);
                if (img) img.src = e.target.result;
            });
            // Marca de agua
            var wm = document.getElementById('wm-img');
            if (wm) wm.src = e.target.result;
            // Preview en control
            document.getElementById('logo-preview-wrap').innerHTML =
                '<img src="' + e.target.result + '" class="w-full h-full object-contain p-1"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── MENSAJE ───────────────────────────────────────────────
function actualizarMensaje() {
    var msg = document.getElementById('mensaje-input').value;
    var el  = document.getElementById('tk-footer-msg');
    if (el) el.textContent = msg || '¡Gracias por tu compra!';
    document.getElementById('char-count').textContent = msg.length + '/200';
}

// ── TOASTS ───────────────────────────────────────────────
@php $flashS = session('success'); $flashE = session('error'); @endphp
@if($flashS)
document.addEventListener('DOMContentLoaded', function(){ showToast('¡Listo!', @json($flashS), 'green', 5000); });
@endif
@if($flashE)
document.addEventListener('DOMContentLoaded', function(){ showToast('Error', @json($flashE), 'red', 5000); });
@endif
</script>
</body>
</html>