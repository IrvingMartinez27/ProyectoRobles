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
<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <span class="material-symbols-outlined text-[#0035c5]">menu</span>
        <span class="font-black tracking-tighter text-xl text-[#1a1c1c]">Robles Sport</span>
    </div>
    <div class="hidden md:flex items-center gap-8">
        <a href="/dashboard" class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Inicio</a>
        <a href="/sales"     class="text-[#0035c5] font-bold px-2 py-1">Ventas</a>
        <a href="/catalog"   class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Catálogo</a>
        <a href="#"          class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Inventario</a>
        <a href="#"          class="text-[#1a1c1c] hover:bg-[#f3f3f4] transition-colors px-2 py-1">Clientes</a>
    </div>
    <span class="material-symbols-outlined text-[#0035c5]">account_circle</span>
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
    <a href="#" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
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

</body>
</html>