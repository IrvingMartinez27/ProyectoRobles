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
    #sidebar-overlay { transition: all 0.3s; }
    #sidebar { transition: left 0.3s ease; }
    .fila-cliente:hover td { background-color: #f9f9f9; }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

<!-- NAV -->
<nav class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <button onclick="abrirSidebar()" class="hover:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-[#1737c8]">menu</span>
        </button>
        <a href="{{ route('dashboard') }}" class="font-black tracking-tighter text-xl text-[#1a1c1c] hover:opacity-70 transition-opacity">Qui<span class="text-[#1737c8]">vex</span></a>
    </div>
    <div class="relative group">
        <button class="flex items-center gap-2 hover:opacity-80 transition-all">
            <span class="material-symbols-outlined text-[#1737c8]">account_circle</span>
        </button>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-[#c4c5da]/20 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Sesión activa</p>
                <p class="text-sm font-bold text-[#1a1c1c] mt-1">{{ Auth::user()->name ?? 'Usuario' }}</p>
            </div>
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] text-[#747688] font-semibold">{{ Auth::user()->email ?? '' }}</p>
            </div>
            <a href="{{ route('logout') }}" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 transition-colors">
                <span class="material-symbols-outlined text-sm">logout</span> Cerrar sesión
            </a>
        </div>
    </div>
</nav>

<div class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span> Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Clientes</span>
    </div>
</div>

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
            <div class="bg-[#f3f3f4] p-8">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-4">Cliente más frecuente</p>
                @if(isset($topCompradores[0]))
                <p class="text-xl font-black text-[#1a1c1c] uppercase tracking-tight">{{ $topCompradores[0]['nombre'] ?? $topCompradores[0]['name'] ?? '' }}</p>
                <p class="text-[10px] text-[#747688] mt-2 uppercase tracking-wider">{{ $topCompradores[0]['num_compras'] ?? 0 }} compras — ${{ number_format($topCompradores[0]['total_gastado'] ?? 0, 2) }}</p>
                @else
                <p class="text-sm text-[#747688]">Sin datos aún</p>
                @endif
            </div>
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
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Dirección</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Compras</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Total gastado</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Última compra</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Acción</th>
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
                        <td class="px-6 py-4"><p class="text-sm text-[#747688]">{{ $cliente['direccion'] ?? '—' }}</p></td>
                        <td class="px-6 py-4 text-center"><span class="text-lg font-black">{{ $cliente['num_compras'] }}</span></td>
                        <td class="px-6 py-4 text-center"><span class="text-sm font-black text-[#1737c8]">${{ number_format($cliente['total_gastado'], 2) }}</span></td>
                        <td class="px-6 py-4"><p class="text-sm text-[#747688]">{{ $cliente['ultima_compra'] }}</p></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="abrirHistorial({{ json_encode($cliente) }})" class="px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1 ml-auto">
                                <span class="material-symbols-outlined text-sm">history</span> Historial
                            </button>
                        </td>
                    </tr>
                    @empty
                    @for($i = 0; $i < 5; $i++)
                    <tr class="border-b border-[#c4c5da]/10">
                        <td class="px-6 py-4"><div class="flex items-center gap-3"><div class="w-9 h-9 bg-[#f3f3f4] shrink-0"></div><div class="h-3 bg-[#f3f3f4] w-28 rounded"></div></div></td>
                        <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] w-24 rounded"></div></td>
                        <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] w-36 rounded"></div></td>
                        <td class="px-6 py-4 text-center"><div class="h-4 bg-[#f3f3f4] w-6 rounded mx-auto"></div></td>
                        <td class="px-6 py-4 text-center"><div class="h-3 bg-[#f3f3f4] w-16 rounded mx-auto"></div></td>
                        <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] w-20 rounded"></div></td>
                        <td class="px-6 py-4 text-right"><div class="h-8 bg-[#f3f3f4] w-20 rounded ml-auto"></div></td>
                    </tr>
                    @endfor
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
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">GUARDAR CLIENTE</button>
                <button type="button" onclick="cerrarModalCliente()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">CANCELAR</button>
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
            </div>
            <button onclick="cerrarHistorial()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="overflow-y-auto flex-1">
            <div class="grid grid-cols-12 px-8 py-3 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-3"># Venta</div><div class="col-span-3">Fecha</div><div class="col-span-3">Productos</div><div class="col-span-3 text-right">Total</div>
            </div>
            <div id="historial-lista"><div class="px-8 py-16 text-center text-[#747688] text-sm">Sin compras registradas</div></div>
        </div>
        <div class="px-8 py-4 border-t border-[#c4c5da]/20 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total gastado</p>
                <p class="text-xl font-black text-[#1737c8]" id="historial-total">$0.00</p>
            </div>
            <button onclick="cerrarHistorial()" class="px-6 py-3 border border-[#c4c5da]/40 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">CERRAR</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300" onclick="cerrarSidebar()"></div>
<div id="sidebar" class="fixed top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 overflow-y-auto" style="left: -300px;">
    <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
        <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 px-4 py-3 flex items-center gap-3 flex-1">
            <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0"><span class="text-white font-black text-sm">Q</span></div>
            <div>
                <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Quivex</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5">{{ Auth::user()->store_name ?? 'Mi tienda' }}</p>
            </div>
        </div>
        <button onclick="cerrarSidebar()" class="p-2 hover:bg-[#f3f3f4] transition-colors ml-3"><span class="material-symbols-outlined text-[#747688] text-lg">close</span></button>
    </div>
    <div class="flex-1 p-4 space-y-1">
        <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-2 pt-2 pb-3">Navegación</p>
        <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[16px]">grid_view</span>Ir al inicio</a>
        <a href="{{ route('sales') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[16px]">add_circle</span>Nueva venta</a>
        <a href="{{ route('catalog') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[16px]">storefront</span>Catálogo</a>
        <a href="{{ route('inventario') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[16px]">inventory_2</span>Inventario</a>
        <a href="{{ route('clientes') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1737c8] bg-[#1737c8]/5 transition-all"><span class="material-symbols-outlined text-[16px]">group</span>Clientes</a>
        @if(Auth::user()->role === 'admin')
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <a href="{{ route('usuarios') }}" onclick="cerrarSidebar()" class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all"><span class="material-symbols-outlined text-[16px]">manage_accounts</span>Usuarios</a>
        @endif
    </div>
    <div class="border-t border-[#c4c5da]/15 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 bg-[#1737c8] rounded-full flex items-center justify-center shrink-0"><span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span></div>
            <div>
                <p class="text-sm font-bold text-[#1a1c1c]">{{ Auth::user()->name ?? 'Usuario' }}</p>
                <p class="text-[10px] text-[#747688]">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span> Cerrar sesión
        </a>
    </div>
</div>

<script>
function abrirSidebar() { document.getElementById('sidebar').style.left='0'; document.getElementById('sidebar-overlay').classList.remove('opacity-0','invisible'); document.getElementById('sidebar-overlay').classList.add('opacity-100','visible'); document.body.style.overflow='hidden'; }
function cerrarSidebar() { document.getElementById('sidebar').style.left='-300px'; document.getElementById('sidebar-overlay').classList.add('opacity-0','invisible'); document.getElementById('sidebar-overlay').classList.remove('opacity-100','visible'); document.body.style.overflow=''; }
function abrirModalCliente() { document.getElementById('modal-nuevo-cliente').classList.add('activo'); document.body.style.overflow='hidden'; }
function cerrarModalCliente() { document.getElementById('modal-nuevo-cliente').classList.remove('activo'); document.body.style.overflow=''; }
function abrirHistorial(cliente) {
    document.getElementById('historial-nombre').textContent = cliente.nombre ?? '';
    document.getElementById('historial-total').textContent = '$' + parseFloat(cliente.total_gastado ?? 0).toFixed(2);
    const lista = document.getElementById('historial-lista');
    lista.innerHTML = (cliente.compras && cliente.compras.length > 0)
        ? cliente.compras.map(c => `<div class="grid grid-cols-12 px-8 py-4 border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9]"><div class="col-span-3 font-mono text-[10px] text-[#747688]">#${c.id}</div><div class="col-span-3 text-sm text-[#747688]">${c.fecha}</div><div class="col-span-3 text-sm">${c.num_productos ?? 0} artículo(s)</div><div class="col-span-3 text-right font-black text-[#1737c8]">$${parseFloat(c.total).toFixed(2)}</div></div>`).join('')
        : '<div class="px-8 py-16 text-center text-[#747688] text-sm">Sin compras registradas</div>';
    document.getElementById('modal-historial').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarHistorial() { document.getElementById('modal-historial').classList.remove('activo'); document.body.style.overflow=''; }
function buscarCliente(query) { const q = query.toLowerCase(); document.querySelectorAll('.fila-cliente').forEach(f => { f.style.display = (f.dataset.nombre ?? '').includes(q) ? '' : 'none'; }); }
@if($errors->any()) document.addEventListener('DOMContentLoaded', () => abrirModalCliente()); @endif
</script>
</body>
</html>