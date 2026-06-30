<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Quivex - Almacenes</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    #modal-almacen {
        display: none;
        position: fixed; inset: 0; z-index: 50;
        align-items: center; justify-content: center;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        padding: 16px;
    }
    #modal-almacen.activo { display: flex; }

    @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    @keyframes popIn  { 0% { opacity:0; transform:scale(0.9); } 60% { transform:scale(1.02); } 100% { opacity:1; transform:scale(1); } }
    @keyframes toastIn  { from { opacity:0; transform:translateX(120%); } to { opacity:1; transform:translateX(0); } }
    @keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(120%); } }

    .card-anim { opacity:0; animation: popIn 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .card-anim:nth-child(1) { animation-delay:0.05s; }
    .card-anim:nth-child(2) { animation-delay:0.12s; }
    .card-anim:nth-child(3) { animation-delay:0.19s; }
    .card-anim:nth-child(4) { animation-delay:0.26s; }

    .almacen-card { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; cursor: pointer; }
    .almacen-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(23,55,200,0.12); border-color: #1737c8; }

    .modal-box { animation: popIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards; }

    .tipo-btn {
        border: 2px solid rgba(196,197,218,0.4);
        border-radius: 16px;
        padding: 20px 12px;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
        cursor: pointer; transition: all 0.2s ease;
        background: transparent; color: #747688;
    }
    .tipo-btn:hover { border-color: #1737c8; color: #1737c8; }
    .tipo-btn.active-fisico { background: #1737c8; color: #fff; border-color: #1737c8; }
    .tipo-btn.active-virtual { background: #f59e0b; color: #fff; border-color: #f59e0b; }

    #toast-container {
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        display: flex; flex-direction: column; gap: 10px; pointer-events: none;
    }
    .toast {
        pointer-events: all; min-width: 280px; max-width: 340px;
        border-radius: 14px; padding: 14px 16px;
        display: flex; align-items: flex-start; gap: 12px;
        background: #1a1c1c; color: #fff;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        animation: toastIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
    }
    .toast.removing { animation: toastOut 0.3s ease forwards; }
    .toast-close { margin-left:auto; opacity:0.5; cursor:pointer; flex-shrink:0; font-size:16px; line-height:1; }
    .toast-close:hover { opacity:1; }

    /* dark mode */
    [data-theme="dark"] body { background:#0f1012 !important; color:#f3f3f4 !important; }
    [data-theme="dark"] .bg-white { background:#1e2022 !important; }
    [data-theme="dark"] .card-bg { background:#1e2022 !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .text-gray { color:#9496a8 !important; }
    [data-theme="dark"] .banner-bg { background:#1a1c1e !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .modal-box { background:#1e2022 !important; }
    [data-theme="dark"] .input-field { background:#141618 !important; color:#f3f3f4 !important; border-color:rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] .tipo-btn { border-color:rgba(255,255,255,0.1); color:#9496a8; }
    [data-theme="dark"] .tipo-btn:hover { border-color:#1737c8; color:#1737c8; }
    [data-theme="dark"] .btn-cancel { border-color:rgba(255,255,255,0.1) !important; color:#9496a8 !important; }
    [data-theme="dark"] .btn-cancel:hover { background:#141618 !important; }
    [data-theme="dark"] .modal-border { border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .empty-card { background:#1e2022 !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .add-card { background:#1e2022 !important; border-color:rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] .breadcrumb-text { color:#9496a8 !important; }
    [data-theme="dark"] .title-text { color:#f3f3f4 !important; }
    [data-theme="dark"] .subtitle-text { color:#9496a8 !important; }
    [data-theme="dark"] .dot-inactive { background:rgba(255,255,255,0.15) !important; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest breadcrumb-text text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors relative z-20">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="title-text text-[#1a1c1c]">Almacenes</span>
    </div>
</div>

<main class="pb-24 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8" style="opacity:0;animation:fadeUp 0.5s ease 0.05s forwards;">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Business</p>
            <h1 class="text-3xl font-black tracking-tight title-text text-[#1a1c1c]">Almacenes y sucursales</h1>
            <p class="text-sm subtitle-text text-[#747688] mt-1">Máximo 3 — locales físicos o almacenes virtuales</p>
        </div>
        @if(count($almacenes) < 3)
        <button id="btn-nuevo-almacen"
                class="bg-[#1737c8] text-white px-5 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center gap-2 self-start md:self-auto shadow-lg"
                style="box-shadow:0 8px 24px rgba(23,55,200,0.25)">
            <span class="material-symbols-outlined text-sm">add</span>Nuevo almacén
        </button>
        @endif
    </div>

    {{-- BANNER EJEMPLOS --}}
    <div class="banner-bg rounded-2xl border border-[#c4c5da]/20 bg-white px-6 py-5 mb-8"
         style="opacity:0;animation:fadeUp 0.5s ease 0.1s forwards;">
        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-4">Ejemplos de uso</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#1737c8] text-sm">store</span>
                </div>
                <div>
                    <p class="text-xs font-black text-[#1a1c1c] title-text mb-0.5">🏪 Tienda física</p>
                    <p class="text-[10px] text-[#747688] subtitle-text">Sucursal Norte · Showroom Centro · Bodega</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-amber-600 text-sm">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs font-black text-[#1a1c1c] title-text mb-0.5">📦 Revendedor</p>
                    <p class="text-[10px] text-[#747688] subtitle-text">Almacén Casa · Cajuela del Carro · Mochila Primo</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTADOR --}}
    <div class="flex items-center gap-3 mb-6" style="opacity:0;animation:fadeUp 0.5s ease 0.15s forwards;">
        <div class="flex gap-1.5">
            @for($i = 0; $i < 3; $i++)
            <div class="w-8 h-2 rounded-full transition-all duration-500 {{ $i < count($almacenes) ? 'bg-[#1737c8]' : 'dot-inactive bg-[#c4c5da]/30' }}"></div>
            @endfor
        </div>
        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] subtitle-text">
            {{ count($almacenes) }}/3 almacenes
        </p>
    </div>

    {{-- CARDS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        @forelse($almacenes as $almacen)
        <div class="almacen-card card-anim card-bg bg-white rounded-2xl border border-[#c4c5da]/20 p-6 relative overflow-hidden group">
            
            {{-- Enlace extendido que vuelve clickeable a toda la card --}}
            <a href="{{ route('almacenes.show', $almacen->id) }}" class="absolute inset-0 z-10 focus:outline-none"></a>

            <div class="absolute top-0 right-0 w-20 h-20 rounded-full -translate-y-6 translate-x-6 opacity-[0.06]"
                 style="background:{{ $almacen->tipo === 'fisico' ? '#1737c8' : '#f59e0b' }}"></div>
            
            <div class="flex items-start justify-between mb-5 relative">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $almacen->tipo === 'fisico' ? 'bg-[#1737c8]/10' : 'bg-amber-100' }}">
                    <span class="material-symbols-outlined text-xl {{ $almacen->tipo === 'fisico' ? 'text-[#1737c8]' : 'text-amber-600' }}">
                        {{ $almacen->tipo === 'fisico' ? 'store' : 'inventory_2' }}
                    </span>
                </div>
                
                {{-- Formulario de eliminación asegurado por encima del link principal --}}
                <form method="POST" action="{{ route('almacenes.destroy', $almacen->id) }}"
                      onsubmit="return confirm('¿Desactivar este almacén?')" class="relative z-20">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-xl flex items-center justify-center text-[#c4c5da] hover:text-red-500 hover:bg-red-50 transition-all focus:outline-none focus:ring-2 focus:ring-red-500">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </form>
            </div>
            
            <div class="relative z-0">
                <p class="font-black text-lg uppercase tracking-tight title-text text-[#1a1c1c] mb-2">{{ $almacen->nombre }}</p>
                <span class="text-[9px] font-black px-3 py-1 rounded-full inline-block uppercase tracking-wider
                    {{ $almacen->tipo === 'fisico' ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'bg-amber-100 text-amber-700' }}">
                    {{ $almacen->tipo === 'fisico' ? '🏪 Físico' : '📦 Virtual' }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-1 sm:col-span-2 md:col-span-3 empty-card bg-white rounded-2xl border border-[#c4c5da]/20 px-6 py-16 text-center card-anim">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">store</span>
            <p class="text-sm font-bold text-[#747688] subtitle-text mb-4">Sin almacenes registrados</p>
            <button id="btn-crear-primero"
                    class="bg-[#1737c8] text-white px-6 py-3 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
                Crear primer almacén
            </button>
        </div>
        @endforelse

        @if(count($almacenes) < 3)
        <button id="btn-add-card"
                class="add-card card-anim bg-white rounded-2xl border-2 border-dashed border-[#c4c5da]/30 p-6 flex flex-col items-center justify-center gap-3 hover:border-[#1737c8] transition-all min-h-[160px] text-[#c4c5da] hover:text-[#1737c8] group focus:outline-none">
            <div class="w-10 h-10 rounded-xl bg-[#f3f3f4] group-hover:bg-[#1737c8]/10 flex items-center justify-center transition-all">
                <span class="material-symbols-outlined">add</span>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-black uppercase tracking-widest">Agregar almacén</p>
                <p class="text-[9px] mt-0.5 opacity-60">{{ 3 - count($almacenes) }} disponible(s)</p>
            </div>
        </button>
        @endif
    </div>

</main>

{{-- MODAL --}}
<div id="modal-almacen" onclick="if(event.target===this) cerrarModal()">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md overflow-hidden mx-4 md:mx-0" onclick="event.stopPropagation()">
        <div class="modal-border flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Business</p>
                <h2 class="text-xl font-black tracking-tight title-text text-[#1a1c1c]">Nuevo almacén</h2>
            </div>
            <button onclick="cerrarModal()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all focus:outline-none">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('almacenes.store') }}" class="px-6 py-6 space-y-5">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre del almacén</label>
                <input type="text" name="nombre"
                       placeholder="Ej. Sucursal Norte, Cajuela del Carro"
                       class="input-field border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] transition-all w-full"/>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tipo</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="btn-tipo-fisico" onclick="seleccionarTipo('fisico')" class="tipo-btn active-fisico focus:outline-none">
                        <span class="material-symbols-outlined text-xl">store</span>
                        <span>Físico</span>
                        <span style="font-size:9px;font-weight:400;text-transform:none;letter-spacing:0;opacity:0.7;">Local, showroom, bodega</span>
                    </button>
                    <button type="button" id="btn-tipo-virtual" onclick="seleccionarTipo('virtual')" class="tipo-btn focus:outline-none">
                        <span class="material-symbols-outlined text-xl">inventory_2</span>
                        <span>Virtual</span>
                        <span style="font-size:9px;font-weight:400;text-transform:none;letter-spacing:0;opacity:0.7;">Cajuela, mochila, casa</span>
                    </button>
                </div>
                <input type="hidden" name="tipo" id="tipo-hidden" value="fisico"/>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-[#1737c8] text-white py-3.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all focus:outline-none"
                        style="box-shadow:0 4px 16px rgba(23,55,200,0.25)">
                    Crear almacén
                </button>
                <button type="button" onclick="cerrarModal()"
                        class="btn-cancel px-5 border border-[#c4c5da]/40 rounded-xl py-3.5 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all text-[#747688] focus:outline-none">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials._sidebar')

<script>
// ── MODAL ────────────────────────────────────────────────────
function abrirModal() {
    document.getElementById('modal-almacen').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModal() {
    document.getElementById('modal-almacen').classList.remove('activo');
    document.body.style.overflow = '';
}

// Todos los botones que abren el modal
document.addEventListener('DOMContentLoaded', function() {
    ['btn-nuevo-almacen','btn-crear-primero','btn-add-card'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', abrirModal);
    });
});

// ── TIPO ─────────────────────────────────────────────────────
function seleccionarTipo(tipo) {
    var btnF = document.getElementById('btn-tipo-fisico');
    var btnV = document.getElementById('btn-tipo-virtual');
    btnF.className = 'tipo-btn focus:outline-none' + (tipo === 'fisico' ? ' active-fisico' : '');
    btnV.className = 'tipo-btn focus:outline-none' + (tipo === 'virtual' ? ' active-virtual' : '');
    document.getElementById('tipo-hidden').value = tipo;
}

// showToast y removeToast vienen del _nav.blade.php

// Flash sessions
@php $flashSuccess = session('success'); $flashError = session('error'); @endphp
@if($flashSuccess)
document.addEventListener('DOMContentLoaded', function() {
    showToast('¡Listo!', @json($flashSuccess), 'green', 5000);
});
@endif
@if($flashError)
document.addEventListener('DOMContentLoaded', function() {
    showToast('Error', @json($flashError), 'red', 5000);
});
@endif
</script>
</body>
</html>