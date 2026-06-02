<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Gastos</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-gasto { display: none; }
    #modal-gasto.activo { display: flex; }
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
        <a href="{{ route('rentabilidad.index') }}" class="hover:text-[#1737c8] transition-colors">Rentabilidad</a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Gastos</span>
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
            <h1 class="text-3xl font-black tracking-tight">Gastos operativos</h1>
            <p class="text-sm text-[#747688] mt-1">Registra renta, envíos, publicidad y más</p>
        </div>
        <div class="flex gap-2">
            <form method="GET" action="{{ route('gastos.index') }}" class="flex gap-2">
                <input type="month" name="mes" value="{{ $mes }}"
                       class="border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-white"/>
                <button type="submit" class="bg-[#f3f3f4] text-[#1a1c1c] px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] hover:text-white transition-all">
                    Filtrar
                </button>
            </form>
            <button onclick="abrirModalGasto()" class="bg-[#1737c8] text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:opacity-90 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span>Nuevo gasto
            </button>
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Total gastos</p>
            <p class="text-xl font-black text-red-500">-${{ number_format($totalGastos, 0) }}</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Fijos</p>
            <p class="text-xl font-black text-blue-600">${{ number_format($porCategoria['fijo'] ?? 0, 0) }}</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Variables</p>
            <p class="text-xl font-black text-amber-600">${{ number_format($porCategoria['variable'] ?? 0, 0) }}</p>
        </div>
        <div class="bg-white border border-[#c4c5da]/20 p-4 text-center">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Hormiga</p>
            <p class="text-xl font-black text-red-400">${{ number_format($porCategoria['hormiga'] ?? 0, 0) }}</p>
        </div>
    </div>

    {{-- LISTA DE GASTOS --}}
    <div class="bg-white border border-[#c4c5da]/20">
        <div class="px-6 py-4 border-b border-[#c4c5da]/10">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">{{ count($gastos) }} gasto(s) registrados</p>
        </div>
        @forelse($gastos as $gasto)
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9]">
            <div class="flex items-center gap-4">
                <div class="w-9 h-9 flex items-center justify-center shrink-0
                    {{ $gasto->categoria === 'fijo' ? 'bg-blue-100' : ($gasto->categoria === 'variable' ? 'bg-amber-100' : 'bg-red-100') }}">
                    <span class="material-symbols-outlined text-sm
                        {{ $gasto->categoria === 'fijo' ? 'text-blue-600' : ($gasto->categoria === 'variable' ? 'text-amber-600' : 'text-red-500') }}">
                        {{ $gasto->categoria === 'fijo' ? 'home' : ($gasto->categoria === 'variable' ? 'local_shipping' : 'coffee') }}
                    </span>
                </div>
                <div>
                    <p class="font-bold text-sm uppercase">{{ $gasto->concepto }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[9px] font-black px-2 py-0.5 uppercase
                            {{ $gasto->categoria === 'fijo' ? 'bg-blue-100 text-blue-700' : ($gasto->categoria === 'variable' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                            {{ $gasto->categoria }}
                        </span>
                        @if($gasto->almacen)
                        <span class="text-[9px] text-[#747688]">{{ $gasto->almacen->nombre }}</span>
                        @endif
                        <span class="text-[9px] text-[#747688]">{{ $gasto->fecha->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <p class="font-black text-sm text-red-500">-${{ number_format($gasto->monto, 2) }}</p>
                <form method="POST" action="{{ route('gastos.destroy', $gasto->id) }}" onsubmit="return confirm('¿Eliminar este gasto?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-[#c4c5da] hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">receipt_long</span>
            <p class="text-sm font-bold text-[#747688]">Sin gastos registrados este mes</p>
            <button onclick="abrirModalGasto()" class="mt-4 bg-[#1737c8] text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:opacity-90">
                Registrar primer gasto
            </button>
        </div>
        @endforelse
    </div>
</main>

{{-- MODAL NUEVO GASTO --}}
<div id="modal-gasto" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalGasto()">
    <div class="bg-white w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Gastos operativos</p>
                <h2 class="text-xl font-bold tracking-tight">Nuevo gasto</h2>
            </div>
            <button onclick="cerrarModalGasto()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('gastos.store') }}" class="px-6 py-5 space-y-4">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Concepto</label>
                <input type="text" name="concepto" placeholder="Ej. Renta local, Guías DHL, Meta Ads"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Categoría</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="seleccionarCategoria('fijo', this)"
                            class="cat-btn py-3 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:border-blue-500 hover:text-blue-600 transition-all flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-sm">home</span>Fijo
                    </button>
                    <button type="button" onclick="seleccionarCategoria('variable', this)"
                            class="cat-btn py-3 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:border-amber-500 hover:text-amber-600 transition-all flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-sm">local_shipping</span>Variable
                    </button>
                    <button type="button" onclick="seleccionarCategoria('hormiga', this)"
                            class="cat-btn py-3 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:border-red-500 hover:text-red-500 transition-all flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-sm">coffee</span>Hormiga
                    </button>
                </div>
                <input type="hidden" name="categoria" id="categoria-hidden"/>
                <p class="text-[9px] text-[#747688]">Fijo: renta, luz · Variable: envíos, publicidad · Hormiga: pequeños gastos diarios</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Monto</label>
                    <input type="number" name="monto" placeholder="0.00" min="0" step="0.01"
                           class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Fecha</label>
                    <input type="date" name="fecha" value="{{ now()->format('Y-m-d') }}"
                           class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                </div>
            </div>
            @if(count($almacenes) > 0)
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Almacén (opcional)</label>
                <select name="almacen_id" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]">
                    <option value="">Global (todos los almacenes)</option>
                    @foreach($almacenes as $a)
                    <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90">
                    Guardar gasto
                </button>
                <button type="button" onclick="cerrarModalGasto()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials._sidebar')

<script>
function abrirModalGasto() { document.getElementById('modal-gasto').classList.add('activo'); document.body.style.overflow = 'hidden'; }
function cerrarModalGasto() { document.getElementById('modal-gasto').classList.remove('activo'); document.body.style.overflow = ''; }

function seleccionarCategoria(cat, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => {
        b.classList.remove('bg-blue-500', 'bg-amber-500', 'bg-red-500', 'text-white', 'border-blue-500', 'border-amber-500', 'border-red-500');
    });
    const colores = { fijo: 'bg-blue-500', variable: 'bg-amber-500', hormiga: 'bg-red-500' };
    const borders = { fijo: 'border-blue-500', variable: 'border-amber-500', hormiga: 'border-red-500' };
    btn.classList.add(colores[cat], borders[cat], 'text-white');
    document.getElementById('categoria-hidden').value = cat;
}
</script>
</body>
</html>