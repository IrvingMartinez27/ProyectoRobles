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
    /* Transiciones globales para un Dark Mode y UI fluida */
    body, main, div, header, nav, aside, button, input, select { 
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease; 
    }

    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; scroll-behavior: smooth; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    /* Modales con desenfoque y animación suave */
    .modal-backdrop { display: none; opacity: 0; transition: opacity 0.3s ease; }
    .modal-backdrop.activo { display: flex; opacity: 1; }

    /* ANIMACIONES */
    @keyframes fadeInUp { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
    @keyframes popIn { 0%{opacity:0; transform:scale(0.95) translateY(10px);} 100%{opacity:1; transform:scale(1) translateY(0);} }
    @keyframes slideInRow { from{opacity:0; transform:translateX(-10px);} to{opacity:1; transform:translateX(0);} }
    @keyframes modalScale { from { opacity: 0; transform: scale(0.95) translateY(15px); } to { opacity: 1; transform: scale(1) translateY(0); } }

    .anim-header { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .anim-header-1 { animation-delay: 0.05s; }
    .anim-header-2 { animation-delay: 0.12s; }
    .anim-header-3 { animation-delay: 0.19s; }

    .stat-card { animation: popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .stat-card-1 { animation-delay: 0.08s; }
    .stat-card-2 { animation-delay: 0.16s; }

    .top-card { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease; }
    .top-card:nth-child(1){animation-delay:0.05s;}
    .top-card:nth-child(2){animation-delay:0.10s;}
    .top-card:nth-child(3){animation-delay:0.15s;}
    .top-card:nth-child(4){animation-delay:0.20s;}
    .top-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(23,55,200,0.08); border-color: #1737c8; }

    .fila-cliente { animation: slideInRow 0.4s ease both; transition: background 0.2s ease; }
    .fila-cliente:nth-child(1){animation-delay:0.04s;}
    .fila-cliente:nth-child(2){animation-delay:0.08s;}
    .fila-cliente:nth-child(3){animation-delay:0.12s;}
    .fila-cliente:nth-child(4){animation-delay:0.16s;}
    .fila-cliente:nth-child(5){animation-delay:0.20s;}
    .fila-cliente:nth-child(n+6){animation-delay:0.24s;}

    /* HOVER FILAS — solo fondo, sin afectar texto */
    .fila-cliente:hover { background: rgba(23,55,200,0.04); }
    .fila-cliente:hover td { background: transparent !important; }

    .modal-content { animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(196,197,218,0.5); border-radius: 10px; }

    /* DARK MODE */
    [data-theme="dark"] body { background-color: #0f1012 !important; color: #f3f3f4 !important; }
    [data-theme="dark"] main { background-color: #0f1012 !important; }
    [data-theme="dark"] .bg-white { background-color: #1e2022 !important; border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\] { background-color: #0f1012 !important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background-color: #141618 !important; }
    [data-theme="dark"] .text-\[\#1a1c1c\] { color: #f3f3f4 !important; }
    [data-theme="dark"] .text-\[\#747688\] { color: #9496a8 !important; }
    [data-theme="dark"] .text-\[\#5e5e5e\] { color: #9496a8 !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color: rgba(255,255,255,0.12) !important; }
    [data-theme="dark"] thead tr { background-color: #141618 !important; }
    [data-theme="dark"] thead th { color: #9496a8 !important; border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .fila-cliente:hover { background: rgba(23,55,200,0.08) !important; }
    [data-theme="dark"] .top-card { background-color: #1e2022 !important; border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] input { background-color: #141618 !important; color: #f3f3f4 !important; border-color: rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] input::placeholder { color: #5e5e5e !important; }
    [data-theme="dark"] .hover\:bg-\[\#f3f3f4\]:hover { background-color: #141618 !important; }
    [data-theme="dark"] .hover\:bg-\[\#f9f9f9\]:hover { background-color: #1a1c1e !important; }
    [data-theme="dark"] .bg-green-100 { background-color: rgba(34,197,94,0.12) !important; }
    [data-theme="dark"] .bg-red-100 { background-color: rgba(239,68,68,0.12) !important; }
    [data-theme="dark"] .bg-amber-50 { background-color: rgba(245,158,11,0.08) !important; }
    [data-theme="dark"] .bg-amber-100 { background-color: rgba(245,158,11,0.15) !important; }
    [data-theme="dark"] .border-amber-200 { border-color: rgba(245,158,11,0.2) !important; }
    [data-theme="dark"] #modal-nuevo-cliente .bg-white,
    [data-theme="dark"] #modal-historial .bg-white { background-color: #1e2022 !important; }
    [data-theme="dark"] nav.fixed.bottom-0 { background: rgba(15,16,18,0.92) !important; border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>
</head>
<body>

@include('partials._nav')

<div class="pt-24 px-6 max-w-[1440px] mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3 anim-header">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Clientes</span>
    </div>
</div>

@if(session('success'))
<div class="mx-6 max-w-[1440px] xl:mx-auto mb-4 bg-green-100 text-green-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 anim-header shadow-sm">
    <span class="material-symbols-outlined text-lg">check_circle</span>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mx-6 max-w-[1440px] xl:mx-auto mb-4 bg-red-100 text-red-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 anim-header shadow-sm">
    <span class="material-symbols-outlined text-lg">error</span>{{ session('error') }}
</div>
@endif

<main class="max-w-[1440px] mx-auto px-6 py-8 pb-28">

    {{-- HEADER --}}
    <section class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="anim-header anim-header-1">
            <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-4">Gestión de clientes</p>
            <h1 class="text-5xl md:text-6xl font-black tracking-tighter text-[#1a1c1c] mb-4">Clientes</h1>
            <p class="text-[#747688] text-base leading-relaxed max-w-xl">Administra y consulta la información de todos los clientes de la tienda.</p>
        </div>
        <div class="flex gap-4 anim-header anim-header-2">
            <div class="stat-card stat-card-1 bg-[#f3f3f4] rounded-3xl px-8 py-6 text-center border border-[#c4c5da]/20 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Total clientes</p>
                <p class="text-3xl font-black text-[#1a1c1c]">{{ $clientes->count() }}</p>
            </div>
            <div class="stat-card stat-card-2 bg-[#1737c8] rounded-3xl px-8 py-6 text-center shadow-lg shadow-[#1737c8]/20 flex flex-col justify-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1">Nuevos este mes</p>
                <p class="text-3xl font-black text-white">{{ $nuevosEsteMes ?? '0' }}</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">

        {{-- TOP COMPRADORES --}}
        <section class="md:col-span-8 bg-[#f3f3f4] rounded-3xl p-8 anim-header anim-header-3 border border-[#c4c5da]/20 shadow-sm">
            <h2 class="text-xl font-black tracking-tight mb-8 text-[#1a1c1c]">Top compradores</h2>
            <div class="space-y-4">
                @forelse($topCompradores ?? [] as $index => $tc)
                <div class="top-card flex items-center gap-4 bg-white p-4 border border-[#c4c5da]/20 rounded-2xl">
                    <span class="text-2xl font-black text-[#c4c5da] w-8 shrink-0 text-center">{{ $index + 1 }}</span>
                    <div class="w-12 h-12 rounded-xl bg-[#1737c8] flex items-center justify-center shrink-0 shadow-md">
                        <span class="text-white font-black text-sm">{{ strtoupper(substr($tc['nombre'] ?? $tc['name'] ?? '?', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-0.5">
                            <p class="font-black text-sm uppercase text-[#1a1c1c] truncate">{{ $tc['nombre'] ?? $tc['name'] ?? '' }}</p>
                            @if(($tc['num_compras'] ?? 0) >= 5)
                            <span class="text-[9px] font-black px-2.5 py-1 rounded-md bg-[#1737c8] text-white uppercase tracking-widest shadow-sm">Frecuente</span>
                            @endif
                            @if($esPro && ($tc['puntos'] ?? 0) > 0)
                            <span class="text-[9px] font-black px-2.5 py-1 rounded-md bg-amber-100 text-amber-700 uppercase tracking-widest flex items-center gap-1">
                                <span class="material-symbols-outlined text-[11px]">stars</span>{{ $tc['puntos'] }} pts
                            </span>
                            @endif
                        </div>
                        <p class="text-[10px] font-bold text-[#747688] uppercase tracking-wider">{{ $tc['num_compras'] ?? 0 }} compras</p>
                    </div>
                    <span class="text-lg font-black text-[#1737c8]">${{ number_format($tc['total_gastado'] ?? 0, 2) }}</span>
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-4 bg-white p-4 border border-[#c4c5da]/20 rounded-2xl">
                    <span class="text-2xl font-black text-[#c4c5da] w-8 text-center">{{ $i + 1 }}</span>
                    <div class="w-12 h-12 rounded-xl bg-[#f3f3f4] shrink-0"></div>
                    <div class="flex-1"><div class="h-3 bg-[#f3f3f4] rounded w-32 mb-2"></div><div class="h-2 bg-[#f3f3f4] rounded w-20"></div></div>
                    <div class="h-4 bg-[#f3f3f4] rounded w-16"></div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>

        {{-- ACCIONES --}}
        <section class="md:col-span-4 flex flex-col gap-6 anim-header anim-header-3">
            <button onclick="abrirModalCliente()" class="bg-[#1a1c1c] text-white rounded-3xl p-8 flex flex-col items-start gap-4 hover:opacity-90 transition-all text-left shadow-lg hover:-translate-y-1 hover:shadow-xl">
                <span class="material-symbols-outlined text-4xl">person_add</span>
                <div>
                    <p class="font-black text-2xl uppercase tracking-tight leading-none mb-1">Nuevo<br>cliente</p>
                    <p class="text-[10px] text-white/60 uppercase tracking-widest font-bold">Agregar al registro</p>
                </div>
            </button>

            @if($esPro)
            <div class="bg-amber-50 rounded-3xl border border-amber-200 p-8 space-y-5 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-lg">stars</span>
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Programa de lealtad</p>
                </div>
                <form method="POST" action="{{ route('lealtad.toggle') }}">
                    @csrf
                    <div class="flex items-center justify-between bg-white rounded-2xl border border-amber-200 px-5 py-4 shadow-sm">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#1a1c1c]">{{ $lealtadActivo ? 'Activo' : 'Inactivo' }}</p>
                            <p class="text-[9px] font-bold text-[#747688] mt-0.5">{{ $lealtadActivo ? 'Los clientes acumulan puntos' : 'Sin acumulación de puntos' }}</p>
                        </div>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm hover:shadow-md {{ $lealtadActivo ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-[#f3f3f4] text-[#747688] hover:bg-amber-100 hover:text-amber-700' }}">
                            {{ $lealtadActivo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </div>
                </form>
                @if($lealtadActivo)
                <div class="space-y-1">
                    <p class="text-sm font-medium text-amber-700">1 punto por cada <span class="font-black">$50</span> gastados</p>
                    <p class="text-sm font-medium text-amber-700">1 punto = <span class="font-black">$1</span> de descuento</p>
                </div>
                <div class="pt-5 border-t border-amber-200">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">Total puntos activos</p>
                    <p class="text-3xl font-black text-amber-700">{{ collect($clientes)->sum('puntos') }}</p>
                </div>
                <div class="bg-amber-100/50 rounded-2xl border border-amber-300 px-5 py-4 text-xs font-medium text-amber-800 flex items-start gap-2">
                    <span class="material-symbols-outlined text-sm shrink-0 mt-0.5">info</span>
                    <span>Los puntos se canjean al registrar una venta desde <a href="{{ route('sales') }}" class="font-black underline hover:text-amber-600 transition-colors">Nueva Venta</a>.</span>
                </div>
                @endif
            </div>
            @else
            <a href="{{ route('planes') }}" class="bg-[#f3f3f4] rounded-3xl border border-[#c4c5da]/40 p-8 flex flex-col gap-4 hover:border-[#1737c8] transition-all group shadow-sm hover:shadow-md">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#c4c5da] group-hover:text-[#1737c8] transition-colors">stars</span>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#c4c5da] group-hover:text-[#1a1c1c] transition-colors">Programa de lealtad</p>
                    <span class="text-[9px] font-black bg-[#1737c8] text-white px-2.5 py-0.5 rounded-md shadow-sm">PRO</span>
                </div>
                <p class="text-sm text-[#747688] font-medium leading-relaxed">Acumula y canjea puntos con tus clientes. Actualiza a Pro para desbloquear.</p>
                <span class="material-symbols-outlined text-[#c4c5da] group-hover:text-[#1737c8] transition-colors mt-auto self-end">lock</span>
            </a>
            @endif
        </section>
    </div>

    {{-- TABLA CLIENTES --}}
    <section class="anim-header anim-header-3 bg-white border border-[#c4c5da]/30 rounded-3xl shadow-sm overflow-hidden pb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-8 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <h2 class="text-2xl font-black tracking-tight text-[#1a1c1c]">Todos los clientes</h2>
            <div class="relative w-full md:w-auto">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                <input type="text" placeholder="Buscar por nombre..." oninput="buscarCliente(this.value)"
                       class="pl-11 pr-4 py-3 border border-[#c4c5da]/40 rounded-2xl focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 focus:outline-none transition-all text-sm w-full md:w-80 bg-white shadow-sm"/>
            </div>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f9f9f9] border-b border-[#c4c5da]/20">
                        <th class="text-left px-8 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Nombre</th>
                        <th class="text-left px-6 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Teléfono</th>
                        @if($esPro)
                        <th class="text-center px-6 py-5 text-[10px] font-black tracking-widest uppercase text-amber-600 whitespace-nowrap">
                            <span class="flex items-center gap-1 justify-center"><span class="material-symbols-outlined text-sm">stars</span>Puntos</span>
                        </th>
                        @endif
                        <th class="text-center px-6 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Compras</th>
                        <th class="text-center px-6 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Total gastado</th>
                        <th class="text-left px-6 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Última compra</th>
                        <th class="text-right px-8 py-5 text-[10px] font-black tracking-widest uppercase text-[#747688] whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr class="fila-cliente border-b border-[#c4c5da]/10" data-nombre="{{ strtolower($cliente['nombre']) }}">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                                    <span class="text-[#1737c8] font-black text-sm">{{ strtoupper(substr($cliente['nombre'], 0, 1)) }}</span>
                                </div>
                                <p class="font-black text-sm uppercase text-[#1a1c1c]">{{ $cliente['nombre'] }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap"><p class="text-sm font-medium text-[#747688]">{{ $cliente['telefono'] ?? '—' }}</p></td>
                        @if($esPro)
                        <td class="px-6 py-5 text-center whitespace-nowrap">
                            <span class="text-lg font-black {{ ($cliente['puntos'] ?? 0) > 0 ? 'text-amber-600' : 'text-[#c4c5da]' }}">
                                {{ $cliente['puntos'] ?? 0 }}
                            </span>
                        </td>
                        @endif
                        <td class="px-6 py-5 text-center whitespace-nowrap"><span class="text-lg font-black text-[#1a1c1c]">{{ $cliente['num_compras'] }}</span></td>
                        <td class="px-6 py-5 text-center whitespace-nowrap"><span class="text-base font-black text-[#1737c8]">${{ number_format($cliente['total_gastado'], 2) }}</span></td>
                        <td class="px-6 py-5 whitespace-nowrap"><p class="text-sm font-medium text-[#747688]">{{ $cliente['ultima_compra'] }}</p></td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <button onclick="abrirHistorial({{ json_encode($cliente) }})"
                                    class="px-5 py-2.5 border border-[#c4c5da]/40 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1.5 ml-auto shadow-sm hover:shadow-md">
                                <span class="material-symbols-outlined text-sm">history</span>Historial
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="{{ $esPro ? 7 : 6 }}" class="px-8 py-20 text-center text-[#747688] font-medium text-base">Sin clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>

<div id="modal-nuevo-cliente" class="modal-backdrop fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-md" onclick="if(event.target===this) cerrarModalCliente()">
    <div class="modal-content bg-white rounded-3xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Registro</p>
                <h2 class="text-2xl font-black tracking-tight text-[#1a1c1c]">Nuevo cliente</h2>
            </div>
            <button onclick="cerrarModalCliente()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-[#c4c5da]/30 hover:bg-[#f3f3f4] hover:text-red-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('clientes.store') }}" class="px-8 py-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Nombre</label>
                    <input type="text" name="name" placeholder="Ej. Juan" value="{{ old('name') }}"
                           class="border border-[#c4c5da]/40 px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all shadow-sm"/>
                    @error('name')<p class="text-[10px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Apellido</label>
                    <input type="text" name="apellido" placeholder="Ej. Pérez" value="{{ old('apellido') }}"
                           class="border border-[#c4c5da]/40 px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all shadow-sm"/>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Dirección</label>
                <input type="text" name="direccion" placeholder="Ej. Calle 123, Col. Centro" value="{{ old('direccion') }}"
                       class="border border-[#c4c5da]/40 px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all shadow-sm"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] ml-1">Teléfono</label>
                <input type="tel" name="telefono" placeholder="Ej. 55 1234 5678" value="{{ old('telefono') }}"
                       class="border border-[#c4c5da]/40 px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-white rounded-2xl transition-all shadow-sm"/>
                @error('telefono')<p class="text-[10px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-4 pt-6 border-t border-[#c4c5da]/20">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1">Guardar cliente</button>
                <button type="button" onclick="cerrarModalCliente()" class="px-6 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all rounded-2xl shadow-sm">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-historial" class="modal-backdrop fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-md" onclick="if(event.target===this) cerrarHistorial()">
    <div class="modal-content bg-white rounded-3xl w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20 bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-1">Historial de compras</p>
                <h2 class="text-2xl font-black tracking-tight" id="historial-nombre">Cliente</h2>
                @if($esPro)
                <p class="text-xs text-amber-600 font-black mt-1.5 flex items-center gap-1" id="historial-puntos"></p>
                @endif
            </div>
            <button onclick="cerrarHistorial()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-[#c4c5da]/30 hover:bg-[#f3f3f4] hover:text-red-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <div class="overflow-y-auto flex-1 custom-scrollbar">
            <div class="grid grid-cols-12 px-8 py-4 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-white border-b border-[#c4c5da]/20 sticky top-0 z-10 shadow-sm">
                <div class="col-span-2"># Venta</div>
                <div class="col-span-3">Fecha</div>
                <div class="col-span-3">Productos</div>
                @if($esPro)<div class="col-span-2 text-center">Puntos</div>@endif
                <div class="{{ $esPro ? 'col-span-2' : 'col-span-4' }} text-right">Total</div>
            </div>
            <div id="historial-lista"><div class="px-8 py-20 text-center text-[#747688] font-medium text-base">Sin compras registradas</div></div>
        </div>
        <div class="px-8 py-6 border-t border-[#c4c5da]/20 flex justify-between items-center bg-[#f9f9f9]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Total gastado</p>
                <p class="text-3xl font-black text-[#1737c8] tracking-tight mt-0.5" id="historial-total">$0.00</p>
            </div>
            <button onclick="cerrarHistorial()" class="px-8 py-3.5 bg-white border border-[#c4c5da]/40 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all shadow-sm hover:shadow-md">Cerrar</button>
        </div>
    </div>
</div>

<nav class="md:hidden fixed bottom-0 w-full z-40 flex justify-around items-center h-16 px-4 bg-white/90 backdrop-blur-xl border-t border-[#c4c5da]/20 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]">
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">dashboard</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Inicio</span></a>
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">receipt_long</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2 transition-colors hover:text-[#1737c8]"><span class="material-symbols-outlined">inventory_2</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Stock</span></a>
    <a href="/clientes" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">group</span><span class="text-[9px] uppercase tracking-widest font-black mt-1">Clientes</span></a>
</nav>

@include('partials._sidebar')

<script>
const ES_PRO_CLIENTES = {{ $esPro ? 'true' : 'false' }};

function abrirModalCliente() {
    document.getElementById('modal-nuevo-cliente').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalCliente() {
    document.getElementById('modal-nuevo-cliente').classList.remove('activo');
    document.body.style.overflow = '';
}

function abrirHistorial(cliente) {
    document.getElementById('historial-nombre').textContent = cliente.nombre ?? '';
    document.getElementById('historial-total').textContent = '$' + parseFloat(cliente.total_gastado ?? 0).toFixed(2);
    const puntosEl = document.getElementById('historial-puntos');
    if (puntosEl) puntosEl.innerHTML = `<span class="material-symbols-outlined text-[14px]">stars</span> ${cliente.puntos ?? 0} puntos acumulados`;
    
    const lista = document.getElementById('historial-lista');
    lista.innerHTML = (cliente.compras && cliente.compras.length > 0)
        ? cliente.compras.map(c => `
            <div class="grid grid-cols-12 px-8 py-5 border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9] transition-colors items-center">
                <div class="col-span-2 font-mono text-[11px] font-bold text-[#747688]">#${c.id}</div>
                <div class="col-span-3 text-sm font-medium text-[#747688]">${c.fecha}</div>
                <div class="col-span-3 text-sm font-bold text-[#1a1c1c]">${c.num_productos ?? 0} artículo(s)</div>
                ${ES_PRO_CLIENTES ? `<div class="col-span-2 text-center text-sm font-black text-amber-600">+${c.puntos_ganados ?? 0} pts</div>` : ''}
                <div class="${ES_PRO_CLIENTES ? 'col-span-2' : 'col-span-4'} text-right font-black text-lg text-[#1737c8]">$${parseFloat(c.total).toFixed(2)}</div>
            </div>`).join('')
        : '<div class="px-8 py-20 text-center text-[#747688] font-medium text-base">Sin compras registradas</div>';
        
    document.getElementById('modal-historial').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarHistorial() {
    document.getElementById('modal-historial').classList.remove('activo');
    document.body.style.overflow = '';
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