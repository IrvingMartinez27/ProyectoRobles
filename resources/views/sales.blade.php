<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Ventas</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(196,197,218,0.3); }
    .receipt-texture { background-image: radial-gradient(rgba(26,28,28,0.03) 1px, transparent 0); background-size: 8px 8px; }
    .venta-row { cursor: pointer; transition: all 0.2s; }
    .venta-row:hover { background-color: #f3f3f4; }
    .venta-row.selected { background-color: rgba(23,55,200,0.05); box-shadow: inset 0 0 0 1px rgba(23,55,200,0.2); }
    @media print {
        nav, #lista-ventas, #modal-nueva-venta, #sidebar, #sidebar-overlay, header { display: none !important; }
        body { background: white !important; }
        aside { display: block !important; position: static !important; width: 100% !important; max-width: 400px !important; margin: 0 auto !important; }
        aside .mt-1 { display: none !important; }
    }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span> Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Ventas</span>
    </div>
</div>

<main class="min-h-screen pb-20 md:pb-8 px-4 md:px-8 max-w-[1600px] mx-auto">
    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-6 py-3 text-sm font-bold mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LISTA DE VENTAS -->
        <section class="lg:col-span-7 xl:col-span-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-[#747688] mb-1 font-semibold">Descripción general</p>
                    <h2 class="text-4xl font-extrabold tracking-tight text-[#1a1c1c]">Ventas del día</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                        <input id="buscador" class="pl-10 pr-4 py-2 bg-transparent border-b border-[#c4c5da]/40 focus:border-[#1737c8] focus:ring-0 transition-all text-sm outline-none w-full md:w-64" placeholder="Buscar por ID o nombre" type="text" oninput="filtrarVentas(this.value)"/>
                    </div>
                    <button onclick="abrirModalVenta()" class="bg-[#1737c8] text-white px-5 py-2 text-[11px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-sm">add</span>Nueva venta
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-12 px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-6 md:col-span-5">Cliente y transacción</div>
                <div class="hidden md:block col-span-2 text-right">Monto</div>
                <div class="hidden md:block col-span-2 text-center">Estado</div>
                <div class="col-span-6 md:col-span-3 text-right">Acciones</div>
            </div>

            <div id="lista-ventas" class="space-y-1 mt-1">
                @forelse($ventas ?? [] as $venta)
                <div class="venta-row grid grid-cols-12 px-6 py-6 items-center bg-white border-b border-[#c4c5da]/10"
                     data-nombre="{{ strtolower($venta['cliente']) }}" data-id="{{ $venta['id'] }}"
                     onclick="seleccionarVenta({{ json_encode($venta) }}, this)">
                    <div class="col-span-6 md:col-span-5 flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#1737c8]/5 flex items-center justify-center">
                            <span class="text-[#1737c8] font-bold text-xs">{{ strtoupper(substr($venta['cliente'], 0, 1)) }}{{ strtoupper(substr(strstr($venta['cliente'], ' '), 1, 1)) }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm leading-tight">{{ $venta['cliente'] }}</h4>
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider mt-0.5">#ID-{{ $venta['id'] }}</p>
                        </div>
                    </div>
                    <div class="hidden md:block col-span-2 text-right">
                        <span class="text-sm font-semibold">${{ number_format($venta['total'], 2) }}</span>
                    </div>
                    <div class="hidden md:block col-span-2 text-center">
                        <span class="text-[9px] font-bold px-2 py-0.5 uppercase tracking-tight {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-[#1737c8]/10 text-[#1737c8]' }}">
                            {{ $venta['estado'] === 'completada' ? 'Completada' : 'Pendiente' }}
                        </span>
                    </div>
                    <div class="col-span-6 md:col-span-3 flex justify-end gap-2">
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})" class="p-2 bg-[#1a1c1c] text-white hover:bg-[#1737c8] transition-colors">
                            <span class="material-symbols-outlined text-sm">print</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarEmail({{ json_encode($venta) }})" class="p-2 border border-[#c4c5da]/30 hover:border-[#1a1c1c] transition-colors">
                            <span class="material-symbols-outlined text-sm">mail</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarSMS({{ json_encode($venta) }})" class="p-2 border border-[#c4c5da]/30 hover:border-[#1a1c1c] transition-colors">
                            <span class="material-symbols-outlined text-sm">sms</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-6 py-16 text-center text-[#747688] text-sm">Sin ventas registradas hoy</div>
                @endforelse
            </div>
        </section>

        <!-- TICKET -->
        <aside class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-28 h-fit">
            <div class="bg-[#f3f3f4] p-1 relative overflow-hidden">
                <div class="flex justify-between absolute top-0 left-0 right-0 px-2">
                    @for($i = 0; $i < 8; $i++)<div class="w-2 h-2 rounded-full bg-white -mt-1"></div>@endfor
                </div>
                <div class="bg-white p-8 receipt-texture border-x border-[#c4c5da]/10 shadow-sm" id="ticket-contenido">
                    <div class="text-center mb-10">
                        <h3 class="font-black tracking-tighter text-2xl text-[#1a1c1c]">{{ Auth::user()->store_name ?? 'MI TIENDA' }}</h3>
                        <p class="text-[10px] text-[#747688] uppercase tracking-[0.3em] mt-1">Tienda deportiva</p>
                    </div>
                    <div class="flex justify-between items-end border-b border-dashed border-[#c4c5da]/40 pb-4 mb-6">
                        <div>
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider">Transacción</p>
                            <p class="text-sm font-bold tracking-tight" id="ticket-id">Selecciona una venta</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-[#747688] uppercase tracking-wider">Fecha</p>
                            <p class="text-sm font-bold tracking-tight" id="ticket-fecha">—</p>
                        </div>
                    </div>
                    <div class="space-y-4 mb-8" id="ticket-productos"><p class="text-xs text-[#747688] text-center">Sin productos</p></div>
                    <div class="space-y-1.5 border-t border-dashed border-[#c4c5da]/40 pt-4">
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase tracking-wider font-bold"><span>Subtotal</span><span id="ticket-subtotal">$0.00</span></div>
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase tracking-wider font-bold"><span>IVA (0%)</span><span>$0.00</span></div>
                        <div class="flex justify-between text-lg font-black tracking-tight text-[#1a1c1c] pt-2"><span>TOTAL</span><span id="ticket-total">$0.00</span></div>
                    </div>
                    <div class="mt-12 text-center">
                        <div class="flex justify-center gap-1 mb-4 opacity-30">
                            @foreach([1,0.5,1.5,1,0.5,1.5,0.5,2,1,0.5,1.5] as $w)
                            <div class="h-8 bg-[#1a1c1c]" style="width: {{ $w * 4 }}px"></div>
                            @endforeach
                        </div>
                        <p class="text-[9px] text-[#747688] uppercase tracking-widest italic">Gracias por tu compra</p>
                    </div>
                </div>
                <div class="mt-1 flex gap-1">
                    <button onclick="imprimirTicketActual()" class="flex-1 py-4 bg-[#1737c8] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-2 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-lg">print</span>Imprimir ticket
                    </button>
                    <button onclick="enviarDigital()" class="flex-1 py-4 bg-[#1a1c1c] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-2 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-lg">send</span>Enviar digital
                    </button>
                </div>
            </div>
        </aside>
    </div>
</main>

<!-- BOTTOM NAV móvil -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    @endif
    <a href="/sales" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
</nav>

<!-- MODAL NUEVA VENTA -->
<div id="modal-nueva-venta" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" style="display:none;" onclick="if(event.target===this) cerrarModalVenta()">
    <div class="bg-white w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Ventas</p><h2 class="text-2xl font-bold tracking-tight">Nueva venta</h2></div>
            <button onclick="cerrarModalVenta()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('ventas.store') }}" id="form-nueva-venta" class="px-8 py-6 space-y-6">
            @csrf
            <div class="flex flex-col gap-1 relative">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                <input type="text" id="cliente-search" name="cliente_nombre" placeholder="Escribe el nombre del cliente..." autocomplete="off" oninput="buscarClienteVenta(this.value)" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                <input type="hidden" name="cliente_id" id="cliente-id-hidden"/>
                <div id="clientes-sugerencias" class="absolute top-full left-0 right-0 bg-white border border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-48 overflow-y-auto"></div>
                <div id="clientes-data" class="hidden">
                    @foreach($clientes ?? [] as $cliente)
                    <span data-id="{{ $cliente->id ?? $cliente['id'] }}" data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"></span>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Productos</label>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button type="button" onclick="filtrarCategoria('ropa', this)" class="cat-btn px-3 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Ropa</button>
                    <button type="button" onclick="filtrarCategoria('calzado', this)" class="cat-btn px-3 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Calzado</button>
                    <button type="button" onclick="filtrarCategoria('accesorios', this)" class="cat-btn px-3 py-2 text-[10px] font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1737c8] hover:text-[#1737c8] transition-all">Accesorios</button>
                </div>
                <div id="lista-productos-categoria" class="hidden space-y-1 max-h-40 overflow-y-auto border border-[#c4c5da]/20 bg-[#f9f9f9]"></div>
                <div id="productos-container" class="space-y-2 mt-2"></div>
                <div id="productos-hidden"></div>
                <div class="grid grid-cols-12 gap-3 text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-0.5" id="cabecera-productos" style="display:none;">
                    <div class="col-span-5">Producto</div><div class="col-span-3">Cantidad</div><div class="col-span-3">Precio</div>
                </div>
                <div id="productos-data" class="hidden">
                    @foreach($productos ?? [] as $prod)
                    <span data-id="{{ $prod['id'] ?? '' }}" data-nombre="{{ $prod['nombre'] ?? '' }}" data-precio="{{ $prod['precio'] ?? 0 }}" data-categoria="{{ $prod['categoria'] ?? '' }}"></span>
                    @endforeach
                </div>
            </div>
            <div class="bg-[#1737c8] px-6 py-4 flex justify-between items-center">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total calculado</span>
                <span class="text-2xl font-black text-white" id="total-calculado">$0.00</span>
            </div>
            <div class="flex gap-3 pt-2 border-t border-[#c4c5da]/20">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">point_of_sale</span>REGISTRAR VENTA
                </button>
                <button type="button" onclick="cerrarModalVenta()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">CANCELAR</button>
            </div>
        </form>
    </div>
</div>

<script>
let ventaActual = null;
function seleccionarVenta(venta, el) {
    ventaActual = venta;
    document.querySelectorAll('.venta-row').forEach(r => r.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('ticket-id').textContent = '#ID-' + venta.id;
    document.getElementById('ticket-fecha').textContent = venta.fecha ?? '—';
    document.getElementById('ticket-subtotal').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('ticket-total').textContent = '$' + parseFloat(venta.total).toFixed(2);
    const productosEl = document.getElementById('ticket-productos');
    productosEl.innerHTML = (venta.productos && venta.productos.length > 0)
        ? venta.productos.map(p => `<div class="flex justify-between items-start"><div class="flex-1 pr-4"><h5 class="text-xs font-bold text-[#1a1c1c]">${p.nombre}</h5><p class="text-[9px] text-[#747688]">${p.detalle ?? ''}</p></div><span class="text-xs font-bold">$${parseFloat(p.precio).toFixed(2)}</span></div>`).join('')
        : '<p class="text-xs text-[#747688] text-center">Sin detalle de productos</p>';
}
function filtrarVentas(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.venta-row').forEach(row => {
        row.style.display = ((row.dataset.nombre ?? '').includes(q) || (row.dataset.id ?? '').includes(q)) ? '' : 'none';
    });
}
function imprimirTicket(venta) {
    const fila = document.querySelector(`.venta-row[data-id="${venta.id}"]`);
    if (fila) seleccionarVenta(venta, fila);
    setTimeout(() => window.print(), 400);
}
function imprimirTicketActual() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } window.print(); }
function enviarEmail(venta) { alert('Enviando por email la venta #ID-' + venta.id); }
function enviarSMS(venta) { alert('Enviando por SMS la venta #ID-' + venta.id); }
function enviarDigital() { if (!ventaActual) { alert('Selecciona una venta primero'); return; } enviarEmail(ventaActual); }
function abrirModalVenta() { document.getElementById('modal-nueva-venta').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
function cerrarModalVenta() {
    document.getElementById('modal-nueva-venta').style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('cliente-search').value = '';
    document.getElementById('cliente-id-hidden').value = '';
    document.getElementById('clientes-sugerencias').classList.add('hidden');
    document.getElementById('lista-productos-categoria').classList.add('hidden');
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]', 'text-white', 'border-[#1a1c1c]'));
    productosVenta = [];
    renderizarProductosVenta();
}
function buscarClienteVenta(query) {
    const sugerencias = document.getElementById('clientes-sugerencias');
    const clientes = document.querySelectorAll('#clientes-data span');
    const q = query.toLowerCase().trim();
    if (q.length < 1) { sugerencias.classList.add('hidden'); return; }
    const coincidencias = Array.from(clientes).filter(c => c.dataset.nombre.toLowerCase().includes(q));
    sugerencias.innerHTML = coincidencias.length === 0
        ? '<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias</div>'
        : coincidencias.map(c => `<div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] uppercase" onclick="seleccionarCliente('${c.dataset.id}','${c.dataset.nombre}')">${c.dataset.nombre}</div>`).join('');
    sugerencias.classList.remove('hidden');
}
function seleccionarCliente(id, nombre) {
    document.getElementById('cliente-search').value = nombre;
    document.getElementById('cliente-id-hidden').value = id;
    document.getElementById('clientes-sugerencias').classList.add('hidden');
}
document.addEventListener('click', e => { if (!e.target.closest('#cliente-search') && !e.target.closest('#clientes-sugerencias')) document.getElementById('clientes-sugerencias')?.classList.add('hidden'); });
let productosVenta = [];
function filtrarCategoria(categoria, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('bg-[#1a1c1c]','text-white','border-[#1a1c1c]'));
    btn.classList.add('bg-[#1a1c1c]','text-white','border-[#1a1c1c]');
    const productos = document.querySelectorAll('#productos-data span');
    const lista = document.getElementById('lista-productos-categoria');
    const filtrados = Array.from(productos).filter(p => p.dataset.categoria === categoria);
    lista.innerHTML = filtrados.length === 0
        ? '<div class="px-4 py-3 text-sm text-[#747688]">Sin productos</div>'
        : filtrados.map(p => `<div class="flex items-center justify-between px-4 py-2.5 hover:bg-white cursor-pointer border-b border-[#c4c5da]/10" onclick="agregarProductoVenta('${p.dataset.id}','${p.dataset.nombre}',${p.dataset.precio})"><div><p class="text-xs font-bold uppercase">${p.dataset.nombre}</p><p class="text-[10px] text-[#747688]">$${parseFloat(p.dataset.precio).toFixed(2)}</p></div><span class="material-symbols-outlined text-[#1737c8] text-sm">add_circle</span></div>`).join('');
    lista.classList.remove('hidden');
}
function agregarProductoVenta(id, nombre, precio) {
    const existe = productosVenta.find(p => p.id === id);
    if (existe) { existe.cantidad += 1; } else { productosVenta.push({ id, nombre, precio: parseFloat(precio), cantidad: 1 }); }
    renderizarProductosVenta();
}
function renderizarProductosVenta() {
    const container = document.getElementById('productos-container');
    const cabecera = document.getElementById('cabecera-productos');
    if (productosVenta.length === 0) { container.innerHTML = ''; cabecera.style.display = 'none'; actualizarHiddens(); calcularTotal(); return; }
    cabecera.style.display = 'grid';
    container.innerHTML = productosVenta.map((p, i) => `<div class="grid grid-cols-12 gap-3 items-center py-2 border-b border-[#c4c5da]/10"><div class="col-span-5"><p class="text-xs font-bold uppercase truncate">${p.nombre}</p></div><div class="col-span-3"><div class="flex items-center gap-1"><button type="button" onclick="cambiarCantidadVenta(${i},-1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">−</button><span class="w-8 text-center text-sm font-black">${p.cantidad}</span><button type="button" onclick="cambiarCantidadVenta(${i},1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">+</button></div></div><div class="col-span-3"><p class="text-xs font-black text-[#1737c8]">$${(p.precio*p.cantidad).toFixed(2)}</p></div><div class="col-span-1"><button type="button" onclick="quitarProductoVenta(${i})" class="text-[#747688] hover:text-red-600"><span class="material-symbols-outlined text-sm">close</span></button></div></div>`).join('');
    actualizarHiddens(); calcularTotal();
}
function cambiarCantidadVenta(i, d) { productosVenta[i].cantidad += d; if (productosVenta[i].cantidad <= 0) productosVenta.splice(i,1); renderizarProductosVenta(); }
function quitarProductoVenta(i) { productosVenta.splice(i,1); renderizarProductosVenta(); }
function actualizarHiddens() { document.getElementById('productos-hidden').innerHTML = productosVenta.map(p => `<input type="hidden" name="productos[]" value="${p.nombre}"/><input type="hidden" name="cantidades[]" value="${p.cantidad}"/><input type="hidden" name="precios[]" value="${p.precio}"/>`).join(''); }
function calcularTotal() { document.getElementById('total-calculado').textContent = '$' + productosVenta.reduce((s,p) => s+p.precio*p.cantidad, 0).toFixed(2); }
</script>

@include('partials._sidebar')

</body>
</html>