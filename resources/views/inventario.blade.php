<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Robles Sport - Inventario</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-editar { display: none; }
    #modal-editar.activo { display: flex; }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }

    /* filas con alerta */
    .fila-poco { border-left: 3px solid #f59e0b; }
    .fila-poco td { background-color: #fffbeb; }
    .fila-agotado { border-left: 3px solid #ef4444; }
    .fila-agotado td { background-color: #fef2f2; }
    .fila-ok td { background-color: #ffffff; }
    .fila-producto:hover td { filter: brightness(0.97); }

    /* tooltip */
    .tooltip-wrap { position: relative; display: inline-block; cursor: default; }
    .tooltip-box {
        display: none;
        position: absolute;
        bottom: calc(100% + 8px);
        left: 0;
        z-index: 99;
        background: #1a1c1c;
        color: #fff;
        padding: 10px 14px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        min-width: 190px;
        border-radius: 2px;
    }
    .tooltip-box::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 14px;
        border: 5px solid transparent;
        border-top-color: #1a1c1c;
    }
    .tooltip-wrap:hover .tooltip-box { display: block; }
    .tooltip-titulo { font-size: 10px; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 6px; }
    .tooltip-fila { display: flex; justify-content: space-between; gap: 16px; font-size: 11px; margin-bottom: 3px; color: rgba(255,255,255,0.75); }
    .tooltip-fila span:last-child { font-weight: 700; color: #fff; }
    .tooltip-fila span.bajo { color: #fca5a5 !important; }
    .tooltip-poco .tooltip-titulo { color: #fcd34d; }
    .tooltip-agotado .tooltip-titulo { color: #fca5a5; }

    /* dot indicador */
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; margin-right: 6px; }
    .dot-ok { background: #22c55e; }
    .dot-poco { background: #f59e0b; }
    .dot-agotado { background: #ef4444; }
</style>
</head>

<body class="bg-white text-[#1a1c1c]">

<!-- NAV -->
<nav class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <button onclick="abrirSidebar()" class="hover:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-[#0035c5]">menu</span>
        </button>
        <span class="font-black tracking-tighter text-xl text-[#1a1c1c]">Robles Sport</span>
    </div>

    <div class="relative group">
        <button class="flex items-center gap-2 hover:opacity-80 transition-all">
            <span class="material-symbols-outlined text-[#0035c5]">account_circle</span>
        </button>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-[#c4c5da]/20 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Sesión activa</p>
                <p class="text-sm font-bold text-[#1a1c1c] mt-1">{{ Auth::user()->name ?? 'Usuario' }}</p>
            </div>
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] text-[#747688] font-semibold">{{ Auth::user()->email ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-[1440px] mx-auto px-6 py-12 pb-24">

    <!-- HEADER -->
    <section class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[#0035c5] font-bold tracking-widest text-[10px] uppercase mb-4">Gestión de stock</p>
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter text-[#1a1c1c] mb-4">Inventario</h1>
            <p class="text-[#5e5e5e] text-lg leading-relaxed max-w-xl">
                Consulta y edita el stock de todos los productos de Robles Sport.
            </p>
        </div>
        <div class="flex gap-4">
            <div class="bg-[#f3f3f4] px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Total productos</p>
                <p class="text-3xl font-black text-[#1a1c1c]">{{ count($productos ?? []) }}</p>
            </div>
            <div class="bg-red-50 px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-red-400 mb-1">Stock bajo</p>
                <p class="text-3xl font-black text-red-600">{{ count(array_filter($productos ?? [], fn($p) => $p['stock_total'] < 10)) }}</p>
            </div>
        </div>
    </section>

    <!-- FILTROS + BUSCADOR -->
    <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between items-start md:items-center">
        <div class="flex gap-3 overflow-x-auto pb-1">
            <button class="filtro-btn activo px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#1a1c1c] transition-all"
                    onclick="filtrar('todos', this)">Todos</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all"
                    onclick="filtrar('ropa', this)">Ropa</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all"
                    onclick="filtrar('calzado', this)">Calzado</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all"
                    onclick="filtrar('accesorios', this)">Accesorios</button>
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
            <input type="text"
                   placeholder="Buscar producto..."
                   oninput="buscar(this.value)"
                   class="pl-10 pr-4 py-2 border-b border-[#c4c5da]/40 focus:border-[#0035c5] focus:outline-none transition-colors text-sm w-64 bg-transparent"/>
        </div>
    </div>

    <!-- TABLA -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabla-inventario">
            <thead>
                <tr class="bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Producto</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">ID</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Categoría</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Tallas / Existencias</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Stock total</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Estado</th>
                    <th class="text-right px-6 py-4 text-[10px] font-black tracking-widest uppercase text-[#747688]">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos ?? [] as $producto)

                {{-- la fila cambia de color segun el stock --}}
                <tr class="fila-producto border-b border-[#c4c5da]/10 transition-colors
                    {{ $producto['stock_total'] == 0 ? 'fila-agotado' : ($producto['stock_total'] < 10 ? 'fila-poco' : 'fila-ok') }}"
                    data-categoria="{{ $producto['categoria'] }}"
                    data-nombre="{{ strtolower($producto['nombre']) }}">

                    <td class="px-6 py-4">
                        {{-- dot de color + nombre con tooltip si tiene stock bajo --}}
                        <div class="flex items-center">
                            <span class="dot {{ $producto['stock_total'] == 0 ? 'dot-agotado' : ($producto['stock_total'] < 10 ? 'dot-poco' : 'dot-ok') }}"></span>

                            @if($producto['stock_total'] < 10)
                            {{-- tooltip solo aparece si tiene stock bajo o agotado --}}
                            <div class="tooltip-wrap">
                                <p class="font-bold text-sm uppercase">{{ $producto['nombre'] }}</p>
                                <div class="tooltip-box {{ $producto['stock_total'] == 0 ? 'tooltip-agotado' : 'tooltip-poco' }}">
                                    <div class="tooltip-titulo">
                                        {{ $producto['stock_total'] == 0 ? '✕ Sin stock' : '⚠ Stock bajo' }}
                                    </div>
                                    <div class="tooltip-fila">
                                        <span>Total piezas</span>
                                        <span class="{{ $producto['stock_total'] == 0 ? 'bajo' : '' }}">{{ $producto['stock_total'] }}</span>
                                    </div>
                                    {{-- muestra cada talla con su cantidad, en rojo si es <= 2 --}}
                                    @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                    <div class="tooltip-fila">
                                        <span>Talla {{ $talla }}</span>
                                        <span class="{{ $cantidad <= 2 ? 'bajo' : '' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }} piezas</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <p class="font-bold text-sm uppercase">{{ $producto['nombre'] }}</p>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-mono text-[10px] text-[#747688] uppercase tracking-wider">{{ $producto['id'] }}</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-[#f3f3f4]">
                            {{ ucfirst($producto['categoria']) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                            <span class="bg-[#f3f3f4] px-2 py-1 text-[10px] font-bold">
                                {{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#0035c5]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span>
                            </span>
                            @endforeach
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-black {{ $producto['stock_total'] < 10 ? 'text-red-600' : 'text-[#1a1c1c]' }}">
                            {{ $producto['stock_total'] }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($producto['stock_total'] == 0)
                            <span class="text-[9px] font-black px-2 py-1 bg-red-100 text-red-700 uppercase tracking-widest">Sin stock</span>
                        @elseif($producto['stock_total'] < 10)
                            <span class="text-[9px] font-black px-2 py-1 bg-yellow-100 text-yellow-700 uppercase tracking-widest">Poco stock</span>
                        @else
                            <span class="text-[9px] font-black px-2 py-1 bg-green-100 text-green-700 uppercase tracking-widest">En stock</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        {}
                        <button onclick="abrirEditar({{ json_encode($producto) }})"
                                class="px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1 ml-auto">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            Editar stock
                        </button>
                    </td>

                </tr>
                @empty
                {{-- placeholders mientras no hay productos en la bd --}}
                @for($i = 0; $i < 6; $i++)
                <tr class="border-b border-[#c4c5da]/10">
                    <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] w-32 rounded"></div></td>
                    <td class="px-6 py-4"><div class="h-3 bg-[#f3f3f4] w-24 rounded"></div></td>
                    <td class="px-6 py-4"><div class="h-6 bg-[#f3f3f4] w-16 rounded"></div></td>
                    <td class="px-6 py-4"><div class="h-6 bg-[#f3f3f4] w-40 rounded"></div></td>
                    <td class="px-6 py-4 text-center"><div class="h-4 bg-[#f3f3f4] w-8 rounded mx-auto"></div></td>
                    <td class="px-6 py-4 text-center"><div class="h-6 bg-[#f3f3f4] w-16 rounded mx-auto"></div></td>
                    <td class="px-6 py-4 text-right"><div class="h-8 bg-[#f3f3f4] w-24 rounded ml-auto"></div></td>
                </tr>
                @endfor
                @endforelse
            </tbody>
        </table>
    </div>

</main>

<!-- MODAL EDITAR STOCK -->
{{-- este modal se abre al dar click en editar stock de cualquier producto --}}
{{-- aqui conectas la ruta para actualizar el stock, algo como action="{{ route('inventario.update') }}" --}}
<div id="modal-editar"
     class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm"
     onclick="if(event.target===this) cerrarEditar()">

    <div class="bg-white w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#0035c5] mb-1">Inventario</p>
                <h2 class="text-xl font-bold tracking-tight" id="modal-nombre">Editar stock</h2>
            </div>
            <button onclick="cerrarEditar()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="#" id="form-editar" class="px-8 py-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="producto_id" id="modal-producto-id"/>

            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688] block mb-3">Tallas y existencias</label>
                <div id="modal-tallas" class="space-y-3"></div>
            </div>

            <div class="bg-[#f3f3f4] px-4 py-3 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Stock total calculado</span>
                <span class="text-lg font-black text-[#1a1c1c]" id="modal-total">0</span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-[#0035c5] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
                    GUARDAR CAMBIOS
                </button>
                <button type="button" onclick="cerrarEditar()"
                        class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                    CANCELAR
                </button>
            </div>
        </form>
    </div>
</div>

<!-- BOTTOM NAV (móvil) -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span>
    </a>
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">receipt_long</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span>
    </a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span>
    </a>
    <a href="/inventario" class="flex flex-col items-center text-[#0035c5] border-t-2 border-[#0035c5] pt-2">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span>
    </a>
    <a href="/clientes" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span>
    </a>
</nav>

<script>
// abre el modal de editar stock con los datos del producto clickeado
function abrirEditar(producto) {
    document.getElementById('modal-nombre').textContent = producto.nombre;
    document.getElementById('modal-producto-id').value = producto.id;

    const tallasEl = document.getElementById('modal-tallas');
    tallasEl.innerHTML = '';

    const tallas = producto.tallas ?? {};
    Object.entries(tallas).forEach(([talla, cantidad]) => {
        const fila = document.createElement('div');
        fila.className = 'flex gap-3 items-center';
        fila.innerHTML = `
            <div class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm font-bold bg-[#f9f9f9] text-[#747688] uppercase tracking-wider">
                ${talla}
            </div>
            <input type="number"
                   name="tallas[${talla}]"
                   value="${cantidad}"
                   min="0"
                   oninput="calcularTotal()"
                   class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9] font-bold"/>
        `;
        tallasEl.appendChild(fila);
    });

    calcularTotal();
    document.getElementById('modal-editar').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

// calcula el stock total sumando todas las cantidades
function calcularTotal() {
    const inputs = document.querySelectorAll('#modal-tallas input[type="number"]');
    let total = 0;
    inputs.forEach(input => total += parseInt(input.value) || 0);
    document.getElementById('modal-total').textContent = total;
}

// cierra el modal
function cerrarEditar() {
    document.getElementById('modal-editar').classList.remove('activo');
    document.body.style.overflow = '';
}

// filtra las filas por categoria
function filtrar(categoria, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    document.querySelectorAll('.fila-producto').forEach(fila => {
        fila.style.display = (categoria === 'todos' || fila.dataset.categoria === categoria) ? '' : 'none';
    });
}

// busca productos por nombre en tiempo real
function buscar(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.fila-producto').forEach(fila => {
        fila.style.display = fila.dataset.nombre.includes(q) ? '' : 'none';
    });
}
</script>

    <!-- SIDEBAR OVERLAY -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300"
         onclick="cerrarSidebar()"></div>

    <!-- SIDEBAR -->
    <div id="sidebar" class="fixed left-[-300px] top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 transition-all duration-300 overflow-y-auto">

        <!-- LOGO BOX -->
        <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
            <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 px-4 py-3 flex items-center gap-3 flex-1">
                <div class="w-8 h-8 bg-[#0035c5] flex items-center justify-center shrink-0">
                    <span class="text-white font-black text-sm">R</span>
                </div>
                <div>
                    <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Robles Sport</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5">Panel de administración</p>
                </div>
            </div>
            <button onclick="cerrarSidebar()" class="p-2 hover:bg-[#f3f3f4] transition-colors ml-3">
                <span class="material-symbols-outlined text-[#747688] text-lg">close</span>
            </button>
        </div>

        <!-- NAVEGACIÓN -->
        <div class="flex-1">
            <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-5 pt-4 pb-2">Navegación</p>

            <!-- INICIO -->
            <button onclick="toggleMenu('inicio')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Inicio
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-inicio">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-inicio">
                <a href="{{ route('dashboard') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver rendimiento diario</a>
                <a href="{{ route('resumen') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Resumen diario</a>
                <a href="{{ route('reporte') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Reporte completo</a>
            </div>

            <!-- VENTAS -->
            <button onclick="toggleMenu('ventas')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Ventas
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-ventas">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-ventas">
                <a href="{{ route('sales') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Abrir registro</a>
                <a href="{{ route('sales') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver ventas del día</a>
            </div>

            <!-- CATÁLOGO -->
            <button onclick="toggleMenu('catalogo')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                    Catálogo
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-catalogo">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-catalogo">
                <a href="{{ route('catalog') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver productos</a>
                <a href="{{ route('catalog') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Nuevo producto</a>
            </div>

            <!-- INVENTARIO -->
            <button onclick="toggleMenu('inventario')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    Inventario
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-inventario">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-inventario">
                <a href="{{ route('inventario') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver inventario</a>
                <a href="{{ route('inventario') }}" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver stock bajo</a>
            </div>

            <!-- CLIENTES -->
            <button onclick="toggleMenu('clientes')" class="w-full flex items-center justify-between px-5 py-3 text-[11px] font-black uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    Clientes
                </div>
                <span class="material-symbols-outlined text-[16px] text-[#c4c5da] transition-transform duration-300" id="chevron-clientes">expand_more</span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" id="menu-clientes">
                <a href="/clientes" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver clientes</a>
                <a href="#" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Agregar cliente</a>
            </div>
        </div>

        <!-- FOOTER CON USUARIO -->
        <div class="border-t border-[#c4c5da]/15 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-[#0035c5] rounded-full flex items-center justify-center shrink-0">
                    <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-[#1a1c1c]">{{ Auth::user()->name ?? 'Usuario' }}</p>
                    <p class="text-[10px] text-[#747688]">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <script>
    function abrirSidebar() {
        document.getElementById('sidebar').style.left = '0';
        document.getElementById('sidebar-overlay').classList.remove('opacity-0', 'invisible');
        document.getElementById('sidebar-overlay').classList.add('opacity-100', 'visible');
        document.body.style.overflow = 'hidden';
    }
    function cerrarSidebar() {
        document.getElementById('sidebar').style.left = '-300px';
        document.getElementById('sidebar-overlay').classList.add('opacity-0', 'invisible');
        document.getElementById('sidebar-overlay').classList.remove('opacity-100', 'visible');
        document.body.style.overflow = '';
    }
    function toggleMenu(id) {
        const menu = document.getElementById('menu-' + id);
        const chevron = document.getElementById('chevron-' + id);
        const isOpen = menu.style.maxHeight && menu.style.maxHeight !== '0px';
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.maxHeight = '0px');
        document.querySelectorAll('[id^="chevron-"]').forEach(c => c.style.transform = 'rotate(0deg)');
        if (!isOpen) {
            menu.style.maxHeight = menu.scrollHeight + 'px';
            chevron.style.transform = 'rotate(180deg)';
        }
    }
    </script>

</body>
</html>