<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Clientes</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-nuevo-cliente { display: none; }
    #modal-nuevo-cliente.activo { display: flex; }
    #modal-historial { display: none; }
    #modal-historial.activo { display: flex; }
    #modal-canjear { display: none; }
    #modal-canjear.activo { display: flex; }
    .fila-cliente:hover td { background-color: #f9f9f9; }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-20 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Clientes</span>
    </div>
</div>

@if(session('success'))
<div class="mx-6 mb-4 bg-green-100 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mx-6 mb-4 bg-red-100 text-red-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
</div>
@endif

<main class="max-w-[1440px] mx-auto px-6 py-12 pb-24">
    <section class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-4">Gestión de clientes</p>
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter text-[#1a1c1c] mb-4">Clientes</h1>
            <p class="text-[#5e5e5e] text-lg leading-relaxed max-w-xl">Administra y consulta la información de todos los clientes.</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-[#f3f3f4] px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Total clientes</p>
                <p class="text-3xl font-black text-[#1a1c1c]">{{ $clientes->count() }}</p>
            </div>
            <div class="bg-[#1737c8] px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1">Nuevos este mes</p>
                <p class="text-3xl font-black text-white">{{ $nuevosEsteMes ?? '0' }}</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
        <section class="md:col-span-8 bg-[#f3f3f4] p-8">
            <h2 class="text-xl font-bold tracking-tight mb-8">Top compradores</h2>
            <div class="space-y-4">
                @forelse($topCompradores ?? [] as $index => $tc)
                <div class="flex items-center gap-4 bg-white p-4 border border-[#c4c5da]/10">
                    <span class="text-2xl font-black text-[#c4c5da] w-8 shrink-0">{{ $index + 1 }}</span>
                    <div class="w-10 h-10 bg-[#1737c8] flex items-center justify-center shrink-0">
                        <span class="text-white font-black text-xs">{{ strtoupper(substr($tc['nombre'] ?? $tc['name'] ?? '?', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-sm uppercase">{{ $tc['nombre'] ?? $tc['name'] ?? '' }}</p>
                            @if(($tc['num_compras'] ?? 0) >= 5)
                            <span class="text-[9px] font-black px-2 py-0.5 bg-[#1737c8] text-white uppercase tracking-widest">Frecuente</span>
                            @endif
                            @if($esPro && ($tc['puntos'] ?? 0) > 0)
                            <span class="text-[9px] font-black px-2 py-0.5 bg-amber-100 text-amber-700 uppercase tracking-widest flex items-center gap-1">
                                <span class="material-symbols-outlined text-[11px]">stars</span>{{ $tc['puntos'] }} pts
                            </span>
                            @endif
                        </div>
                        <p class="text-[10px] text-[#747688] uppercase tracking-wider">{{ $tc['num_compras'] ?? 0 }} compras</p>
                    </div>
                    <span class="text-sm font-black text-[#1737c8]">${{ number_format($tc['total_gastado'] ?? 0, 2) }}</span>
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-4 bg-white p-4 border border-[#c4c5da]/10">
                    <span class="text-2xl font-black text-[#c4c5da] w-8">{{ $i + 1 }}</span>
                    <div class="w-10 h-10 bg-[#f3f3f4] shrink-0"></div>
                    <div class="flex-1"><div class="h-3 bg-[#f3f3f4] w-32 rounded mb-2"></div><div class="h-2 bg-[#f3f3f4] w-20 rounded"></div></div>
                    <div class="h-3 bg-[#f3f3f4] w-16 rounded"></div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>
        <section class="md:col-span-4 flex flex-col gap-6">
            <button onclick="abrirModalCliente()" class="bg-[#1a1c1c] text-white p-8 flex flex-col items-start gap-3 hover:opacity-90 transition-all text-left">
                <span class="material-symbols-outlined text-3xl">person_add</span>
                <div>
                    <p class="font-black text-lg uppercase tracking-tight">Nuevo cliente</p>
                    <p class="text-[10px] text-white/60 uppercase tracking-widest mt-1">Agregar al registro</p>
                </div>
            </button>

            {{-- PROGRAMA DE LEALTAD --}}
           @if($esPro)
<div class="bg-amber-50 border border-amber-200 p-6 space-y-4">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-amber-500">stars</span>
        <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Programa de lealtad</p>
    </div>
 
    {{-- TOGGLE ACTIVAR/DESACTIVAR --}}
    <form method="POST" action="{{ route('lealtad.toggle') }}">
        @csrf
        <div class="flex items-center justify-between bg-white border border-amber-200 px-4 py-3">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">
                    {{ $lealtadActivo ? 'Activo' : 'Inactivo' }}
                </p>
                <p class="text-[9px] text-[#747688] mt-0.5">
                    {{ $lealtadActivo ? 'Los clientes acumulan puntos' : 'Sin acumulación de puntos' }}
                </p>
            </div>
            <button type="submit"
                    class="px-4 py-2 text-[10px] font-black uppercase tracking-widest transition-all
                        {{ $lealtadActivo
                            ? 'bg-amber-500 text-white hover:bg-amber-600'
                            : 'bg-[#f3f3f4] text-[#747688] hover:bg-amber-100 hover:text-amber-700' }}">
                {{ $lealtadActivo ? 'Desactivar' : 'Activar' }}
            </button>
        </div>
    </form>
 
    @if($lealtadActivo)
    <p class="text-sm text-amber-700 mb-1">1 punto por cada <span class="font-black">$50</span> gastados</p>
    <p class="text-sm text-amber-700">1 punto = <span class="font-black">$1</span> de descuento</p>
    <div class="pt-4 border-t border-amber-200">
        <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">Total puntos activos</p>
        <p class="text-2xl font-black text-amber-700">{{ collect($clientes)->sum('puntos') }}</p>
    </div>
    @endif
</div>
@else
<a href="{{ route('planes') }}" class="bg-[#f3f3f4] border border-[#c4c5da]/40 p-6 flex flex-col gap-3 hover:border-[#1737c8] transition-all group">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[#c4c5da]">stars</span>
        <p class="text-[10px] font-black uppercase tracking-widest text-[#c4c5da]">Programa de lealtad</p>
        <span class="text-[9px] font-black bg-[#1737c8] text-white px-2 py-0.5 rounded-full">PRO</span>
    </div>
    <p class="text-sm text-[#747688]">Acumula y canjea puntos con tus clientes. Actualiza a Pro.</p>
    <span class="material-symbols-outlined text-[#c4c5da] group-hover:text-[#1737c8] transition-colors">lock</span>
</a>
@endif
        </section>
    </div>

    <section>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold tracking-tight">Todos los clientes</h2>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                <input type="text" placeholder="Buscar por nombre..." oninput="buscarCliente(this.value)" class="pl-10 pr-4 py-2 border-b border-[#c4c5da]/40 focus:border-[#1737c8] focus:outline-none transition-colors text-sm w-72 bg-transparent"/>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Nombre</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Teléfono</th>
                        @if($esPro)
                        <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-amber-600">
                            <span class="flex items-center gap-1 justify-center"><span class="material-symbols-outlined text-sm">stars</span>Puntos</span>
                        </th>
                        @endif
                        <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Compras</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Total gastado</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Última compra</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr class="fila-cliente border-b border-[#c4c5da]/10 transition-colors" data-nombre="{{ strtolower($cliente['nombre']) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                                    <span class="text-[#1737c8] font-black text-xs">{{ strtoupper(substr($cliente['nombre'], 0, 1)) }}</span>
                                </div>
                                <p class="font-bold text-sm uppercase">{{ $cliente['nombre'] }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4"><p class="text-sm text-[#747688]">{{ $cliente['telefono'] ?? '—' }}</p></td>
                        @if($esPro)
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-lg font-black text-amber-600">{{ $cliente['puntos'] ?? 0 }}</span>
                                @if(($cliente['puntos'] ?? 0) > 0)
                                <button onclick="abrirModalCanjear({{ json_encode($cliente) }})"
                                        class="ml-1 text-[9px] font-black px-2 py-0.5 bg-amber-100 text-amber-700 hover:bg-amber-200 transition-all uppercase">
                                    Canjear
                                </button>
                                @endif
                            </div>
                        </td>
                        @endif
                        <td class="px-6 py-4 text-center"><span class="text-lg font-black">{{ $cliente['num_compras'] }}</span></td>
                        <td class="px-6 py-4 text-center"><span class="text-sm font-black text-[#1737c8]">${{ number_format($cliente['total_gastado'], 2) }}</span></td>
                        <td class="px-6 py-4"><p class="text-sm text-[#747688]">{{ $cliente['ultima_compra'] }}</p></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="abrirHistorial({{ json_encode($cliente) }})" class="px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1 ml-auto">
                                <span class="material-symbols-outlined text-sm">history</span>Historial
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="{{ $esPro ? 7 : 6 }}" class="px-6 py-16 text-center text-[#747688]">Sin clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- MODAL NUEVO CLIENTE -->
<div id="modal-nuevo-cliente" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalCliente()">
    <div class="bg-white w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Clientes</p>
                <h2 class="text-2xl font-bold tracking-tight">Nuevo cliente</h2>
            </div>
            <button onclick="cerrarModalCliente()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('clientes.store') }}" class="px-8 py-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre</label>
                    <input type="text" name="name" placeholder="Ej. Juan" value="{{ old('name') }}" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                    @error('name')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Apellido</label>
                    <input type="text" name="apellido" placeholder="Ej. Pérez" value="{{ old('apellido') }}" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Dirección</label>
                <input type="text" name="direccion" placeholder="Ej. Calle 123, Col. Centro" value="{{ old('direccion') }}" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Teléfono</label>
                <input type="tel" name="telefono" placeholder="Ej. 55 1234 5678" value="{{ old('telefono') }}" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                @error('telefono')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-4 border-t border-[#c4c5da]/20">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">Guardar cliente</button>
                <button type="button" onclick="cerrarModalCliente()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HISTORIAL -->
<div id="modal-historial" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarHistorial()">
    <div class="bg-white w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Historial de compras</p>
                <h2 class="text-xl font-bold tracking-tight" id="historial-nombre">Cliente</h2>
                @if($esPro)
                <p class="text-sm text-amber-600 font-black mt-1" id="historial-puntos"></p>
                @endif
            </div>
            <button onclick="cerrarHistorial()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="overflow-y-auto flex-1">
            <div class="grid grid-cols-12 px-8 py-3 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-2"># Venta</div>
                <div class="col-span-3">Fecha</div>
                <div class="col-span-3">Productos</div>
                @if($esPro)<div class="col-span-2 text-center">Puntos</div>@endif
                <div class="{{ $esPro ? 'col-span-2' : 'col-span-4' }} text-right">Total</div>
            </div>
            <div id="historial-lista"><div class="px-8 py-16 text-center text-[#747688] text-sm">Sin compras registradas</div></div>
        </div>
        <div class="px-8 py-4 border-t border-[#c4c5da]/20 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total gastado</p>
                <p class="text-xl font-black text-[#1737c8]" id="historial-total">$0.00</p>
            </div>
            <button onclick="cerrarHistorial()" class="px-6 py-3 border border-[#c4c5da]/40 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">Cerrar</button>
        </div>
    </div>
</div>

{{-- MODAL CANJEAR PUNTOS (solo Pro) --}}
@if($esPro)
<div id="modal-canjear" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalCanjear()">
    <div class="bg-white w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-amber-600 mb-1">Programa de lealtad</p>
                <h2 class="text-xl font-bold tracking-tight" id="canjear-nombre">—</h2>
            </div>
            <button onclick="cerrarModalCanjear()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="bg-amber-50 border border-amber-200 px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-700">Puntos disponibles</span>
                <span class="text-2xl font-black text-amber-600" id="canjear-puntos-disponibles">0</span>
            </div>
            <div class="bg-[#f3f3f4] px-4 py-3 text-sm text-[#747688]">
                <p>1 punto = <span class="font-black text-[#1a1c1c]">$1 de descuento</span></p>
            </div>
            <form method="POST" id="form-canjear" class="space-y-3">
                @csrf
                @method('POST')
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Puntos a canjear</label>
                    <input type="number" name="puntos_canjear" id="canjear-input" placeholder="0" min="1"
                           oninput="calcularDescuento()"
                           class="border border-[#c4c5da]/40 px-4 py-3 text-lg font-black focus:outline-none focus:border-amber-500 bg-[#f9f9f9]"/>
                </div>
                <div class="bg-amber-50 px-4 py-3 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-amber-700">Descuento</span>
                    <span class="text-xl font-black text-amber-600" id="canjear-descuento">$0.00</span>
                </div>
                <p id="canjear-error" class="text-xs text-red-500 font-bold hidden">No puedes canjear más puntos de los disponibles.</p>
            </form>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button type="button" onclick="confirmarCanje()" class="flex-1 py-4 bg-amber-500 text-white text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">redeem</span>Confirmar canje
            </button>
            <button onclick="cerrarModalCanjear()" class="px-4 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

@include('partials._sidebar')

<script>
const ES_PRO_CLIENTES = {{ $esPro ? 'true' : 'false' }};
let clienteActualCanje = null;

function abrirModalCliente() { document.getElementById('modal-nuevo-cliente').classList.add('activo'); document.body.style.overflow='hidden'; }
function cerrarModalCliente() { document.getElementById('modal-nuevo-cliente').classList.remove('activo'); document.body.style.overflow=''; }

function abrirHistorial(cliente) {
    document.getElementById('historial-nombre').textContent = cliente.nombre ?? '';
    document.getElementById('historial-total').textContent = '$' + parseFloat(cliente.total_gastado ?? 0).toFixed(2);
    if (ES_PRO_CLIENTES) {
        document.getElementById('historial-puntos').textContent = `⭐ ${cliente.puntos ?? 0} puntos acumulados`;
    }
    const lista = document.getElementById('historial-lista');
    lista.innerHTML = (cliente.compras && cliente.compras.length > 0)
        ? cliente.compras.map(c => `
            <div class="grid grid-cols-12 px-8 py-4 border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9]">
                <div class="col-span-2 font-mono text-[10px] text-[#747688]">#${c.id}</div>
                <div class="col-span-3 text-sm text-[#747688]">${c.fecha}</div>
                <div class="col-span-3 text-sm">${c.num_productos ?? 0} artículo(s)</div>
                ${ES_PRO_CLIENTES ? `<div class="col-span-2 text-center text-sm font-black text-amber-600">+${c.puntos_ganados ?? 0} pts</div>` : ''}
                <div class="${ES_PRO_CLIENTES ? 'col-span-2' : 'col-span-4'} text-right font-black text-[#1737c8]">$${parseFloat(c.total).toFixed(2)}</div>
            </div>`).join('')
        : '<div class="px-8 py-16 text-center text-[#747688] text-sm">Sin compras registradas</div>';
    document.getElementById('modal-historial').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarHistorial() { document.getElementById('modal-historial').classList.remove('activo'); document.body.style.overflow=''; }

function abrirModalCanjear(cliente) {
    clienteActualCanje = cliente;
    document.getElementById('canjear-nombre').textContent = cliente.nombre;
    document.getElementById('canjear-puntos-disponibles').textContent = cliente.puntos ?? 0;
    document.getElementById('canjear-input').value = '';
    document.getElementById('canjear-descuento').textContent = '$0.00';
    document.getElementById('canjear-error').classList.add('hidden');
    document.getElementById('form-canjear').action = `/clientes/${cliente.id}/canjear`;
    document.getElementById('modal-canjear').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalCanjear() { document.getElementById('modal-canjear').classList.remove('activo'); document.body.style.overflow=''; clienteActualCanje = null; }

function calcularDescuento() {
    const puntos = parseInt(document.getElementById('canjear-input').value) || 0;
    const disponibles = clienteActualCanje?.puntos ?? 0;
    const errorEl = document.getElementById('canjear-error');
    document.getElementById('canjear-descuento').textContent = '$' + puntos.toFixed(2);
    if (puntos > disponibles) {
        errorEl.classList.remove('hidden');
    } else {
        errorEl.classList.add('hidden');
    }
}

function confirmarCanje() {
    const puntos = parseInt(document.getElementById('canjear-input').value) || 0;
    const disponibles = clienteActualCanje?.puntos ?? 0;
    if (puntos <= 0) { alert('Ingresa los puntos a canjear'); return; }
    if (puntos > disponibles) { document.getElementById('canjear-error').classList.remove('hidden'); return; }
    
    // Crear form dinámico con POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/clientes/${clienteActualCanje.id}/canjear`;
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    
    const puntosInput = document.createElement('input');
    puntosInput.type = 'hidden';
    puntosInput.name = 'puntos_canjear';
    puntosInput.value = puntos;
    
    form.appendChild(csrf);
    form.appendChild(puntosInput);
    document.body.appendChild(form);
    form.submit();
}

function buscarCliente(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.fila-cliente').forEach(f => {
        f.style.display = (f.dataset.nombre ?? '').includes(q) ? '' : 'none';
    });
}

@if($errors->any()) document.addEventListener('DOMContentLoaded', () => abrirModalCliente()); @endif
</script>
</body>
</html>