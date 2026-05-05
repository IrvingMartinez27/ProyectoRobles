<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Robles Sport - Ventas</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(196,197,218,0.3); }
    .receipt-texture {
        background-image: radial-gradient(rgba(26,28,28,0.03) 1px, transparent 0);
        background-size: 8px 8px;
    }
    .venta-row { cursor: pointer; transition: all 0.2s; }
    .venta-row:hover { background-color: #f3f3f4; }
    .venta-row.selected { background-color: rgba(0,53,197,0.05); box-shadow: inset 0 0 0 1px rgba(0,53,197,0.2); }
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

<main class="min-h-screen pt-24 pb-20 md:pb-8 px-4 md:px-8 max-w-[1600px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LISTA DE VENTAS -->
        <section class="lg:col-span-7 xl:col-span-8">

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-[#747688] mb-1 font-semibold">Descripción general</p>
                    <h2 class="text-4xl font-extrabold tracking-tight text-[#1a1c1c]">Ventas del día</h2>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
                    <input id="buscador"
                           class="pl-10 pr-4 py-2 bg-transparent border-b border-[#c4c5da]/40 focus:border-[#0035c5] focus:ring-0 transition-all text-sm outline-none w-full md:w-64"
                           placeholder="Buscar por ID o nombre"
                           type="text"
                           oninput="filtrarVentas(this.value)"/>
                </div>
            </div>

            <!-- CABECERA TABLA -->
            <div class="grid grid-cols-12 px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
                <div class="col-span-6 md:col-span-5">Cliente y transacción</div>
                <div class="hidden md:block col-span-2 text-right">Monto</div>
                <div class="hidden md:block col-span-2 text-center">Estado</div>
                <div class="col-span-6 md:col-span-3 text-right">Acciones</div>
            </div>

            <!-- FILAS DINÁMICAS -->
            <div id="lista-ventas" class="space-y-1 mt-1">
                @forelse($ventas ?? [] as $venta)
                <div class="venta-row grid grid-cols-12 px-6 py-6 items-center bg-white border-b border-[#c4c5da]/10"
                     data-nombre="{{ strtolower($venta['cliente']) }}"
                     data-id="{{ strtolower($venta['id']) }}"
                     onclick="seleccionarVenta({{ json_encode($venta) }}, this)">

                    <div class="col-span-6 md:col-span-5 flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#0035c5]/5 flex items-center justify-center">
                            <span class="text-[#0035c5] font-bold text-xs">
                                {{ strtoupper(substr($venta['cliente'], 0, 1)) }}{{ strtoupper(substr(strstr($venta['cliente'], ' '), 1, 1)) }}
                            </span>
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
                        <span class="text-[9px] font-bold px-2 py-0.5 uppercase tracking-tight
                            {{ $venta['estado'] === 'completada' ? 'bg-green-100 text-green-700' : 'bg-[#0035c5]/10 text-[#0035c5]' }}">
                            {{ $venta['estado'] === 'completada' ? 'Completada' : 'Seleccionada' }}
                        </span>
                    </div>

                    <div class="col-span-6 md:col-span-3 flex justify-end gap-2">
                        <button onclick="event.stopPropagation(); imprimirTicket({{ json_encode($venta) }})"
                                class="p-2 bg-[#1a1c1c] text-white hover:bg-[#0035c5] transition-colors">
                            <span class="material-symbols-outlined text-sm">print</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarEmail({{ json_encode($venta) }})"
                                class="p-2 border border-[#c4c5da]/30 hover:border-[#1a1c1c] transition-colors">
                            <span class="material-symbols-outlined text-sm">mail</span>
                        </button>
                        <button onclick="event.stopPropagation(); enviarSMS({{ json_encode($venta) }})"
                                class="p-2 border border-[#c4c5da]/30 hover:border-[#1a1c1c] transition-colors">
                            <span class="material-symbols-outlined text-sm">sms</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-6 py-16 text-center text-[#747688] text-sm">
                    Sin ventas registradas hoy
                </div>
                @endforelse
            </div>

        </section>

        <!-- TICKET -->
        <aside class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-28 h-fit">
            <div class="bg-[#f3f3f4] p-1 relative overflow-hidden">

                <!-- Perforación -->
                <div class="flex justify-between absolute top-0 left-0 right-0 px-2">
                    @for($i = 0; $i < 8; $i++)
                    <div class="w-2 h-2 rounded-full bg-white -mt-1"></div>
                    @endfor
                </div>

                <div class="bg-white p-8 receipt-texture border-x border-[#c4c5da]/10 shadow-sm" id="ticket-contenido">

                    <div class="text-center mb-10">
                        <h3 class="font-black tracking-tighter text-2xl text-[#1a1c1c]" id="ticket-empresa">ROBLES SPORT</h3>
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

                    <div class="space-y-4 mb-8" id="ticket-productos">
                        <p class="text-xs text-[#747688] text-center">Sin productos</p>
                    </div>

                    <div class="space-y-1.5 border-t border-dashed border-[#c4c5da]/40 pt-4">
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase tracking-wider font-bold">
                            <span>Subtotal</span>
                            <span id="ticket-subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-[10px] text-[#747688] uppercase tracking-wider font-bold">
                            <span>IVA (0.0%)</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-black tracking-tight text-[#1a1c1c] pt-2">
                            <span>TOTAL</span>
                            <span id="ticket-total">$0.00</span>
                        </div>
                    </div>

                    <div class="mt-12 text-center">
                        <div class="flex justify-center gap-1 mb-4 opacity-30">
                            @foreach([1,0.5,1.5,1,0.5,1.5,0.5,2,1,0.5,1.5] as $w)
                            <div class="h-8 bg-[#1a1c1c]" style="width: {{ $w * 4 }}px"></div>
                            @endforeach
                        </div>
                        <p class="text-[9px] text-[#747688] uppercase tracking-widest italic">Gracias por elegir Robles Sport</p>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="mt-1 flex gap-1">
                    <button onclick="imprimirTicketActual()"
                            class="flex-1 py-4 bg-[#0035c5] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-2 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-lg">print</span>
                        Imprimir ticket
                    </button>
                    <button onclick="enviarDigital()"
                            class="flex-1 py-4 bg-[#1a1c1c] text-white text-[10px] font-black tracking-widest uppercase flex items-center justify-center gap-2 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-lg">send</span>
                        Enviar digital
                    </button>
                </div>

            </div>
        </aside>

    </div>
</main>

<!-- BOTTOM NAV (móvil) -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span>
    </a>
    <a href="/sales" class="flex flex-col items-center text-[#0035c5] border-t-2 border-[#0035c5] pt-2">
        <span class="material-symbols-outlined">receipt_long</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span>
    </a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span>
    </a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span>
    </a>
    <a href="#" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Clientes</span>
    </a>
</nav>

<script>
// aqui guardo la venta que esta seleccionada ahorita
let ventaActual = null;

// esto se ejecuta cuando le das click a una fila, carga el ticket del lado derecho
function seleccionarVenta(venta, el) {
    ventaActual = venta;

    // quito el selected de todas las filas y se lo pongo a la que clickearon
    document.querySelectorAll('.venta-row').forEach(r => r.classList.remove('selected'));
    el.classList.add('selected');

    // actualizo los datos del ticket con los de la venta seleccionada
    document.getElementById('ticket-id').textContent = '#ID-' + venta.id;
    document.getElementById('ticket-fecha').textContent = venta.fecha ?? '—';
    document.getElementById('ticket-subtotal').textContent = '$' + parseFloat(venta.total).toFixed(2);
    document.getElementById('ticket-total').textContent = '$' + parseFloat(venta.total).toFixed(2);

    // aqui meto los productos de la venta en el ticket
    // cuando conectes la bd asegurate de mandar venta.productos con nombre, detalle y precio
    const productosEl = document.getElementById('ticket-productos');
    if (venta.productos && venta.productos.length > 0) {
        productosEl.innerHTML = venta.productos.map(p => `
            <div class="flex justify-between items-start">
                <div class="flex-1 pr-4">
                    <h5 class="text-xs font-bold text-[#1a1c1c]">${p.nombre}</h5>
                    <p class="text-[9px] text-[#747688]">${p.detalle ?? ''}</p>
                </div>
                <span class="text-xs font-bold">$${parseFloat(p.precio).toFixed(2)}</span>
            </div>
        `).join('');
    } else {
        productosEl.innerHTML = '<p class="text-xs text-[#747688] text-center">Sin detalle de productos</p>';
    }
}

// esto filtra las filas cuando escribes en el buscador, busca por nombre o por id
function filtrarVentas(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.venta-row').forEach(row => {
        const nombre = row.dataset.nombre ?? '';
        const id = row.dataset.id ?? '';
        row.style.display = (nombre.includes(q) || id.includes(q)) ? '' : 'none';
    });
}

// esto es cuando le das al boton de imprimir en la fila directamente
function imprimirTicket(venta) {
    seleccionarVenta(venta, document.querySelector(`[data-id="${venta.id.toString().toLowerCase()}"]`));
    setTimeout(() => window.print(), 300);
}

// esto es cuando le das al boton de imprimir en el ticket del lado derecho
function imprimirTicketActual() {
    if (!ventaActual) { alert('Selecciona una venta primero'); return; }
    window.print();
}

// aqui conectas la logica para enviar el ticket por email
// crea una ruta POST tipo /ventas/email y mandas el id de la venta
function enviarEmail(venta) {
    alert('Enviando por email la venta #ID-' + venta.id);
}

// aqui conectas la logica para enviar por SMS o whatsapp
// crea una ruta POST tipo /ventas/sms y mandas el id de la venta
function enviarSMS(venta) {
    alert('Enviando por SMS la venta #ID-' + venta.id);
}

// este es el boton de "enviar digital" del ticket, usa la funcion de email
function enviarDigital() {
    if (!ventaActual) { alert('Selecciona una venta primero'); return; }
    enviarEmail(ventaActual);
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
                <a href="#" class="block pl-14 pr-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-[#5e5e5e] hover:text-[#0035c5] hover:bg-[#f9f9f9] transition-all">— Ver clientes</a>
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