<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Almacenes</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-almacen { display: none; }
    #modal-almacen.activo { display: flex; }
</style>
</head>
<body class="bg-[#f9f9f9] text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Almacenes</span>
    </div>
</div>

@if(session('success'))
<div class="mx-4 md:mx-6 mb-4 bg-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mx-4 md:mx-6 mb-4 bg-red-100 text-red-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
</div>
@endif

<main class="pb-24 px-4 md:px-6 max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Business</p>
            <h1 class="text-3xl font-black tracking-tight">Almacenes y sucursales</h1>
            <p class="text-sm text-[#747688] mt-1">Máximo 3 — locales físicos o almacenes virtuales</p>
        </div>
        @if(count($almacenes) < 3)
        <button onclick="abrirModalAlmacen()" class="bg-[#1737c8] text-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-90 flex items-center gap-1.5 self-start">
            <span class="material-symbols-outlined text-sm">add</span>Nuevo almacén
        </button>
        @endif
    </div>

    {{-- EJEMPLOS --}}
    <div class="bg-[#f3f3f4] border border-[#c4c5da]/20 px-6 py-4 mb-6">
        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-3">Ejemplos de uso</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-black text-[#1a1c1c] mb-1">🏪 Tienda física</p>
                <p class="text-[10px] text-[#747688]">Sucursal Norte · Showroom Centro · Bodega</p>
            </div>
            <div>
                <p class="text-xs font-black text-[#1a1c1c] mb-1">📦 Revendedor</p>
                <p class="text-[10px] text-[#747688]">Almacén Casa · Cajuela del Carro · Mochila Primo</p>
            </div>
        </div>
    </div>

    {{-- LISTA DE ALMACENES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($almacenes as $almacen)
        <div class="bg-white border border-[#c4c5da]/20 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center shrink-0
                    {{ $almacen->tipo === 'fisico' ? 'bg-[#1737c8]/10' : 'bg-amber-100' }}">
                    <span class="material-symbols-outlined text-xl {{ $almacen->tipo === 'fisico' ? 'text-[#1737c8]' : 'text-amber-600' }}">
                        {{ $almacen->tipo === 'fisico' ? 'store' : 'inventory_2' }}
                    </span>
                </div>
                <form method="POST" action="{{ route('almacenes.destroy', $almacen->id) }}" onsubmit="return confirm('¿Desactivar este almacén?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-[#c4c5da] hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </form>
            </div>
            <p class="font-black text-lg uppercase tracking-tight">{{ $almacen->nombre }}</p>
            <span class="text-[9px] font-black px-2 py-0.5 mt-1 inline-block
                {{ $almacen->tipo === 'fisico' ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'bg-amber-100 text-amber-700' }} uppercase">
                {{ $almacen->tipo === 'fisico' ? 'Físico' : 'Virtual' }}
            </span>
        </div>
        @empty
        <div class="md:col-span-3 bg-white border border-[#c4c5da]/20 px-6 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">store</span>
            <p class="text-sm font-bold text-[#747688]">Sin almacenes registrados</p>
            <button onclick="abrirModalAlmacen()" class="mt-4 bg-[#1737c8] text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:opacity-90">
                Crear primer almacén
            </button>
        </div>
        @endforelse

        @if(count($almacenes) < 3)
        <button onclick="abrirModalAlmacen()" class="bg-white border-2 border-dashed border-[#c4c5da]/40 p-6 flex flex-col items-center justify-center gap-2 hover:border-[#1737c8] hover:text-[#1737c8] transition-all text-[#c4c5da] min-h-[140px]">
            <span class="material-symbols-outlined text-2xl">add</span>
            <p class="text-[10px] font-black uppercase tracking-widest">Agregar almacén</p>
            <p class="text-[9px]">{{ 3 - count($almacenes) }} disponible(s)</p>
        </button>
        @endif
    </div>

</main>

{{-- MODAL NUEVO ALMACÉN --}}
<div id="modal-almacen" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalAlmacen()">
    <div class="bg-white w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Business</p>
                <h2 class="text-xl font-bold tracking-tight">Nuevo almacén</h2>
            </div>
            <button onclick="cerrarModalAlmacen()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('almacenes.store') }}" class="px-6 py-5 space-y-4">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre</label>
                <input type="text" name="nombre" placeholder="Ej. Sucursal Norte, Cajuela del Carro"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tipo</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="seleccionarTipo('fisico', this)"
                            class="tipo-btn py-4 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:border-[#1737c8] hover:text-[#1737c8] transition-all flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined">store</span>Físico
                        <span class="text-[8px] text-[#747688] normal-case font-normal">Local, showroom, bodega</span>
                    </button>
                    <button type="button" onclick="seleccionarTipo('virtual', this)"
                            class="tipo-btn py-4 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:border-amber-500 hover:text-amber-600 transition-all flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined">inventory_2</span>Virtual
                        <span class="text-[8px] text-[#747688] normal-case font-normal">Cajuela, mochila, casa</span>
                    </button>
                </div>
                <input type="hidden" name="tipo" id="tipo-hidden" value="fisico"/>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90">
                    Crear almacén
                </button>
                <button type="button" onclick="cerrarModalAlmacen()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials._sidebar')

<script>
function abrirModalAlmacen() { document.getElementById('modal-almacen').classList.add('activo'); document.body.style.overflow = 'hidden'; }
function cerrarModalAlmacen() { document.getElementById('modal-almacen').classList.remove('activo'); document.body.style.overflow = ''; }

function seleccionarTipo(tipo, btn) {
    document.querySelectorAll('.tipo-btn').forEach(b => {
        b.classList.remove('bg-[#1737c8]', 'bg-amber-500', 'text-white', 'border-[#1737c8]', 'border-amber-500');
    });
    if (tipo === 'fisico') {
        btn.classList.add('bg-[#1737c8]', 'text-white', 'border-[#1737c8]');
    } else {
        btn.classList.add('bg-amber-500', 'text-white', 'border-amber-500');
    }
    document.getElementById('tipo-hidden').value = tipo;
}

// Seleccionar físico por defecto
document.addEventListener('DOMContentLoaded', () => {
    const btnFisico = document.querySelector('.tipo-btn');
    if (btnFisico) seleccionarTipo('fisico', btnFisico);
});
</script>
</body>
</html>