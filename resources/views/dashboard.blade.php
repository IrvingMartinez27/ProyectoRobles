<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Dashboard</title>
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
    .kpi-card { transition: all 0.2s; }
    .kpi-card:hover { transform: translateY(-2px); }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

@php $plan = $plan ?? (Auth::user()->plan ?? 'gratis'); @endphp

<main class="pt-24 pb-20 md:pb-12 px-6 max-w-7xl mx-auto">

    {{-- BANNER PLAN GRATIS --}}
    @if($plan === 'gratis')
    <div class="mb-8 bg-[#1737c8]/5 border border-[#1737c8]/20 px-6 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[#1737c8] text-xl">workspace_premium</span>
            <div>
                <p class="text-sm font-bold text-[#1a1c1c]">Plan Gratis — analítica del día únicamente</p>
                <p class="text-xs text-[#747688]">Actualiza al Plan Pro para ver semana, mes y acceder al asistente IA.</p>
            </div>
        </div>
        <a href="/#precios" class="shrink-0 bg-[#1737c8] text-white px-5 py-2 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">Ver planes →</a>
    </div>
    @endif

    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Descripción general</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Rendimiento diario</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('sales') }}">
                <button class="bg-[#1a1c1c] text-white px-5 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>ABRIR REGISTRO
                </button>
            </a>
            <a href="{{ route('resumen') }}">
                <button class="bg-[#1737c8] text-white px-5 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">summarize</span>RESUMEN DIARIO
                </button>
            </a>
        </div>
    </header>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="kpi-card bg-[#1737c8] p-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-white/60 mb-1">Ventas de hoy</p>
                <p class="text-3xl font-black text-white">${{ number_format($ventasHoy, 2) }}</p>
            </div>
            <span class="material-symbols-outlined text-4xl text-white/20">payments</span>
        </div>
        <div class="kpi-card bg-white border border-[#c4c5da]/20 p-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Transacciones</p>
                <p class="text-3xl font-black text-[#1a1c1c]">{{ $numVentasHoy }}</p>
            </div>
            <span class="material-symbols-outlined text-4xl text-[#c4c5da]">receipt_long</span>
        </div>
        <div class="kpi-card bg-white border border-[#c4c5da]/20 p-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Ticket promedio</p>
                <p class="text-3xl font-black text-[#1a1c1c]">${{ number_format($ticketPromedio, 2) }}</p>
            </div>
            <span class="material-symbols-outlined text-4xl text-[#c4c5da]">avg_pace</span>
        </div>
    </div>

    @if(in_array($plan ?? 'gratis', ['pro', 'business']))
<section id="ia-section" class="mb-8">
    <div class="bg-white border border-[#c4c5da]/20 overflow-hidden">
 
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#c4c5da]/10 bg-[#f9f9f9]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Asistente IA</p>
                    <p class="text-sm font-bold text-[#1a1c1c]">Análisis inteligente de tu negocio</p>
                </div>
            </div>
            <button onclick="cargarIA()" id="btn-ia-refresh"
                    class="flex items-center gap-2 px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                <span class="material-symbols-outlined text-sm">refresh</span>Actualizar
            </button>
        </div>
 
        {{-- Contenido IA --}}
        <div id="ia-contenido" class="p-6">
 
            {{-- Estado inicial --}}
            <div id="ia-inicial" class="text-center py-8">
                <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">auto_awesome</span>
                <p class="text-sm text-[#747688] mb-4">Obtén análisis inteligente de tus ventas e inventario</p>
                <button onclick="cargarIA()"
                        class="bg-[#1737c8] text-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-2 mx-auto">
                    <span class="material-symbols-outlined text-sm">auto_awesome</span>Analizar ahora
                </button>
            </div>
 
            {{-- Loading --}}
            <div id="ia-loading" class="hidden text-center py-8">
                <div class="inline-flex items-center gap-3 text-[#747688]">
                    <svg class="animate-spin w-5 h-5 text-[#1737c8]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm font-bold">Analizando tu negocio...</span>
                </div>
            </div>
 
            {{-- Resultados --}}
            <div id="ia-resultados" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
 
                {{-- Alertas --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-red-500 text-sm">warning</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Alertas</p>
                    </div>
                    <div id="ia-alertas" class="space-y-2"></div>
                </div>
 
                {{-- Tendencias --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tendencias</p>
                    </div>
                    <div id="ia-tendencias" class="space-y-2"></div>
                </div>
 
                {{-- Predicción --}}
                <div class="md:col-span-2 bg-[#1737c8]/5 border border-[#1737c8]/20 px-5 py-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-[#1737c8] text-sm">insights</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8]">Predicción</p>
                    </div>
                    <p id="ia-prediccion" class="text-sm text-[#1a1c1c] font-medium"></p>
                </div>
 
                {{-- Recomendación --}}
                <div class="md:col-span-2 bg-green-50 border border-green-200 px-5 py-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-green-600 text-sm">lightbulb</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-700">Recomendación</p>
                    </div>
                    <p id="ia-recomendacion" class="text-sm text-green-800 font-medium"></p>
                </div>
 
            </div>
 
            {{-- Error --}}
            <div id="ia-error" class="hidden text-center py-6">
                <span class="material-symbols-outlined text-amber-500 text-3xl block mb-2">error_outline</span>
                <p class="text-sm text-[#747688]" id="ia-error-msg">No se pudo cargar el análisis</p>
            </div>
 
        </div>
    </div>
</section>
 
<script>
async function cargarIA() {
    document.getElementById('ia-inicial').classList.add('hidden');
    document.getElementById('ia-resultados').classList.add('hidden');
    document.getElementById('ia-error').classList.add('hidden');
    document.getElementById('ia-loading').classList.remove('hidden');
 
    const btn = document.getElementById('btn-ia-refresh');
    btn.disabled = true;
 
    try {
        const response = await fetch('{{ route("dashboard.ia") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
 
        const data = await response.json();
 
        if (data.error) throw new Error(data.error);
 
        // Alertas
        const alertasEl = document.getElementById('ia-alertas');
        alertasEl.innerHTML = (data.alertas ?? []).length > 0
            ? data.alertas.map(a => `
                <div class="flex items-start gap-2 bg-red-50 border border-red-100 px-3 py-2">
                    <span class="material-symbols-outlined text-red-400 text-sm mt-0.5 shrink-0">circle</span>
                    <p class="text-xs text-red-700 font-medium">${a}</p>
                </div>`).join('')
            : '<p class="text-xs text-[#747688]">Sin alertas por ahora ✓</p>';
 
        // Tendencias
        const tendenciasEl = document.getElementById('ia-tendencias');
        tendenciasEl.innerHTML = (data.tendencias ?? []).length > 0
            ? data.tendencias.map(t => `
                <div class="flex items-start gap-2 bg-green-50 border border-green-100 px-3 py-2">
                    <span class="material-symbols-outlined text-green-500 text-sm mt-0.5 shrink-0">arrow_upward</span>
                    <p class="text-xs text-green-700 font-medium">${t}</p>
                </div>`).join('')
            : '<p class="text-xs text-[#747688]">Sin tendencias detectadas</p>';
 
        document.getElementById('ia-prediccion').textContent    = data.prediccion ?? '—';
        document.getElementById('ia-recomendacion').textContent = data.recomendacion ?? '—';
 
        document.getElementById('ia-loading').classList.add('hidden');
        document.getElementById('ia-resultados').classList.remove('hidden');
 
    } catch (e) {
        document.getElementById('ia-loading').classList.add('hidden');
        document.getElementById('ia-error-msg').textContent = e.message || 'Error al cargar el análisis';
        document.getElementById('ia-error').classList.remove('hidden');
    }
 
    btn.disabled = false;
}
</script>
@endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        {{-- GRÁFICA --}}
        <section class="md:col-span-8 bg-white border border-[#c4c5da]/20 p-8 relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Analítica de ventas</h2>
                    <p class="text-xs text-[#747688] font-semibold uppercase tracking-widest mt-1">Ventas netas en tiempo real</p>
                </div>
                <div class="flex bg-[#f3f3f4] p-1 gap-1">
                    <a href="{{ route('dashboard', ['periodo' => 'dia']) }}">
                        <button class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'dia' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Día</button>
                    </a>
                    @if($plan === 'gratis')
                        <button onclick="abrirModalUpgrade('semana')" class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-[#c4c5da] flex items-center gap-1 hover:text-[#1737c8] transition-all">
                            <span class="material-symbols-outlined text-[11px]">lock</span>Sem
                        </button>
                        <button onclick="abrirModalUpgrade('mes')" class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-[#c4c5da] flex items-center gap-1 hover:text-[#1737c8] transition-all">
                            <span class="material-symbols-outlined text-[11px]">lock</span>Mes
                        </button>
                    @else
                        <a href="{{ route('dashboard', ['periodo' => 'semana']) }}">
                            <button class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'semana' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Semana</button>
                        </a>
                        <a href="{{ route('dashboard', ['periodo' => 'mes']) }}">
                            <button class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest transition-all {{ $periodo === 'mes' ? 'bg-[#1a1c1c] text-white' : 'text-[#747688] hover:text-[#1a1c1c]' }}">Mes</button>
                        </a>
                    @endif
                </div>
            </div>
            <div class="relative h-[280px]">
                <canvas id="ventasChart"></canvas>
            </div>
        </section>

        {{-- TOP PRODUCTOS --}}
        <section class="md:col-span-4 bg-white border border-[#c4c5da]/20 p-8">
            <h2 class="text-xl font-bold tracking-tight mb-6">Lo más vendido</h2>
            <div class="space-y-5">
                @forelse($topProductos as $i => $producto)
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-[#c4c5da] w-6 shrink-0">{{ $i + 1 }}</span>
                    <div class="w-10 h-10 bg-[#f3f3f4] shrink-0 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#c4c5da] text-lg">image</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase truncate">{{ $producto['nombre'] }}</p>
                        <p class="text-[10px] text-[#747688]">{{ $producto['ventas'] }} ventas</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="text-sm font-black text-[#1737c8]">{{ $producto['porcentaje'] }}%</span>
                        <div class="w-16 h-1 bg-[#f3f3f4] mt-1">
                            <div class="h-full bg-[#1737c8]" style="width: {{ $producto['porcentaje'] }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-[#f3f3f4] w-6">{{ $i + 1 }}</span>
                    <div class="w-10 h-10 bg-[#f3f3f4] shrink-0"></div>
                    <div class="flex-1"><div class="h-2 bg-[#f3f3f4] w-24 rounded mb-1"></div><div class="h-2 bg-[#f3f3f4] w-16 rounded"></div></div>
                    <div class="h-3 bg-[#f3f3f4] w-8 rounded"></div>
                </div>
                @endfor
                @endforelse
            </div>

            @if($plan === 'gratis')
            <button onclick="abrirModalUpgrade('ia')" class="w-full mt-6 py-3 border border-dashed border-[#1737c8]/30 text-[10px] font-black uppercase tracking-widest text-[#1737c8]/50 hover:border-[#1737c8] hover:text-[#1737c8] transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">lock</span>Recomendaciones IA — Pro
            </button>
            @endif

            <a href="{{ route('reporte') }}">
                <button class="w-full mt-4 py-3 border-b-2 border-[#1a1c1c] text-[10px] font-black tracking-widest uppercase hover:bg-[#1a1c1c] hover:text-white transition-all">
                    Ver reporte completo
                </button>
            </a>
        </section>

        {{-- RESTOCK POR TALLAS --}}
        <section class="md:col-span-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Restock requerido</h2>
                    <p class="text-xs text-[#747688] font-semibold uppercase tracking-widest mt-1">Tallas por debajo del mínimo</p>
                </div>
                <button onclick="abrirModalStock()" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/30 hover:bg-[#1a1c1c] hover:text-white transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>Ver todo
                </button>
            </div>
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                @forelse($lowStock as $item)
                <div class="min-w-[240px] bg-white border border-[#c4c5da]/20 p-5 flex flex-col gap-4 shrink-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold text-sm uppercase leading-tight">{{ $item['nombre'] }}</p>
                            <p class="text-[10px] text-[#1737c8] font-black mt-0.5 uppercase tracking-wider">Talla: {{ $item['talla'] ?? '—' }}</p>
                            <p class="text-[10px] text-[#747688] mt-0.5 uppercase tracking-wider">#{{ $item['id'] }}</p>
                        </div>
                        <span class="bg-red-100 text-red-700 px-2 py-1 text-[10px] font-black tracking-widest uppercase shrink-0">{{ $item['stock'] }} pcs</span>
                    </div>
                    <div class="w-full bg-[#f3f3f4] h-1.5">
                        <div class="h-full bg-red-400" style="width: {{ min(($item['stock'] / 5) * 100, 100) }}%"></div>
                    </div>
                    <form method="POST" action="{{ route('reponer') }}">
                        @csrf
                        <input type="hidden" name="producto" value="{{ $item['id'] }}">
                        <button class="w-full py-2.5 bg-[#1737c8] text-white text-[10px] font-black tracking-widest uppercase hover:opacity-90 transition-all">Reponer stock</button>
                    </form>
                </div>
                @empty
                <div class="bg-white border border-[#c4c5da]/20 px-8 py-10 text-center w-full">
                    <span class="material-symbols-outlined text-3xl text-green-400 block mb-2">check_circle</span>
                    <p class="text-sm font-bold text-[#1a1c1c]">Todo el stock está bien</p>
                    <p class="text-xs text-[#747688] mt-1">Ninguna talla está por debajo del mínimo</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- BANNER UPGRADE --}}
        @if($plan === 'gratis')
        <section class="md:col-span-12">
            <div class="bg-[#1a1c1c] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-2">Plan Pro</p>
                    <h3 class="text-xl font-black text-white mb-1">Desbloquea todo el potencial de Quivex</h3>
                    <p class="text-sm text-white/50">Analítica semanal y mensual, asistente IA, reportes avanzados y más.</p>
                </div>
                <a href="/#precios" class="shrink-0 bg-[#1737c8] text-white px-8 py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
                    Actualizar a Pro →
                </a>
            </div>
        </section>
        @endif

    </div>
</main>

{{-- MODAL STOCK --}}
<div id="modal-stock" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarModalStock()">
    <div class="bg-white w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Inventario</p><h2 class="text-2xl font-bold tracking-tight">Todo el stock bajo</h2></div>
            <button onclick="cerrarModalStock()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="overflow-y-auto flex-1">
            <div class="grid grid-cols-12 px-8 py-3 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-5">Producto</div>
                <div class="col-span-2 text-center">Talla</div>
                <div class="col-span-2 text-center">Piezas</div>
                <div class="col-span-3 text-right">Acción</div>
            </div>
            @forelse($lowStock as $item)
            <div class="grid grid-cols-12 px-8 py-4 items-center border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9]">
                <div class="col-span-5">
                    <h3 class="font-bold text-sm uppercase">{{ $item['nombre'] }}</h3>
                    <p class="text-[10px] text-[#747688]">#{{ $item['id'] }}</p>
                </div>
                <div class="col-span-2 text-center">
                    <span class="text-[10px] font-black text-[#1737c8] uppercase">{{ $item['talla'] ?? '—' }}</span>
                </div>
                <div class="col-span-2 text-center">
                    <span class="bg-red-100 text-red-700 px-2 py-1 text-[10px] font-black uppercase">{{ $item['stock'] }} pcs</span>
                </div>
                <div class="col-span-3 flex justify-end">
                    <form method="POST" action="{{ route('reponer') }}">
                        @csrf
                        <input type="hidden" name="producto" value="{{ $item['id'] }}">
                        <button class="px-4 py-2 bg-[#1737c8] text-white text-[10px] font-black uppercase hover:opacity-90">REPONER</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-8 py-16 text-center text-[#747688] text-sm">Sin productos con bajo stock</div>
            @endforelse
        </div>
        <div class="px-8 py-4 border-t border-[#c4c5da]/20">
            <button onclick="cerrarModalStock()" class="w-full py-3 border border-[#c4c5da]/40 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">CERRAR</button>
        </div>
    </div>
</div>

{{-- MODAL UPGRADE --}}
<div id="modal-upgrade" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4" onclick="if(event.target===this) cerrarModalUpgrade()">
    <div class="bg-white w-full max-w-md">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Función Pro</p><h2 class="text-xl font-bold tracking-tight" id="modal-upgrade-titulo">Desbloquea esta función</h2></div>
            <button onclick="cerrarModalUpgrade()" class="p-2 hover:bg-[#f3f3f4]"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="px-8 py-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-[#1737c8]/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#1737c8] text-2xl">workspace_premium</span>
                </div>
                <p class="text-sm text-[#747688]" id="modal-upgrade-desc">Actualiza al Plan Pro para desbloquear esta función.</p>
            </div>
            <div class="space-y-3 mb-8">
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Analítica semanal y mensual</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Asistente IA integrado</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Reportes avanzados con PDF</div>
                <div class="flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>Usuarios y productos ilimitados</div>
            </div>
            <div class="flex gap-3">
                <a href="/#precios" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 text-center">Ver planes</a>
                <button onclick="cerrarModalUpgrade()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4]">Ahora no</button>
            </div>
        </div>
    </div>
</div>

{{-- BOTTOM NAV --}}
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></div></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
    <a href="/clientes" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">group</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span></a>
</nav>

<script>
const labels  = @json($labels);
const valores = @json($valores);

const ctx = document.getElementById('ventasChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 280);
gradient.addColorStop(0, 'rgba(23, 55, 200, 0.15)');
gradient.addColorStop(1, 'rgba(23, 55, 200, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Ventas ($)',
            data: valores,
            borderColor: '#1737c8',
            borderWidth: 2.5,
            backgroundColor: gradient,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1737c8',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1c1c',
                titleColor: '#ffffff',
                bodyColor: 'rgba(255,255,255,0.7)',
                padding: 12,
                callbacks: {
                    label: ctx => ' $' + parseFloat(ctx.raw).toLocaleString('es-MX', { minimumFractionDigits: 2 })
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { font: { size: 10, weight: '700', family: 'Inter' }, color: '#747688' }
            },
            y: {
                grid: { color: 'rgba(196,197,218,0.15)', drawBorder: false },
                border: { display: false, dash: [4, 4] },
                ticks: {
                    font: { size: 10, weight: '700', family: 'Inter' },
                    color: '#747688',
                    callback: v => '$' + (v >= 1000 ? (v/1000).toFixed(1) + 'k' : v)
                }
            }
        }
    }
});

function abrirModalStock() { document.getElementById('modal-stock').classList.add('activo'); document.body.style.overflow = 'hidden'; }
function cerrarModalStock() { document.getElementById('modal-stock').classList.remove('activo'); document.body.style.overflow = ''; }

const upgradeTextos = {
    semana: { titulo: 'Analítica semanal', desc: 'Con el Plan Pro puedes ver el rendimiento de tus ventas por semana y detectar tendencias.' },
    mes:    { titulo: 'Analítica mensual',  desc: 'Con el Plan Pro puedes comparar meses y planear tu inventario con datos reales.' },
    ia:     { titulo: 'Asistente IA',       desc: 'Con el Plan Pro tienes un asistente inteligente que analiza tus ventas y te da recomendaciones personalizadas.' },
};
function abrirModalUpgrade(tipo) {
    const t = upgradeTextos[tipo] ?? { titulo: 'Función Pro', desc: 'Actualiza al Plan Pro.' };
    document.getElementById('modal-upgrade-titulo').textContent = t.titulo;
    document.getElementById('modal-upgrade-desc').textContent = t.desc;
    document.getElementById('modal-upgrade').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalUpgrade() { document.getElementById('modal-upgrade').classList.remove('activo'); document.body.style.overflow = ''; }
</script>

@include('partials._sidebar')

</body>
</html>