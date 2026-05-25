<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Catálogo</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }
    .producto-card { transition: all 0.2s; }
    .producto-card:hover .producto-imagen { transform: scale(1.05); }
    .producto-imagen { transition: transform 0.7s ease; }
    .btn-agregar { transition: all 0.2s; }
    .btn-agregar:hover { transform: scale(1.05); }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

@include('partials._nav')

<div class="px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Catálogo</span>
    </div>
</div>

@if(session('success'))
<div class="mx-6 mb-4 bg-green-100 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
</div>
@endif

<main class="max-w-[1600px] mx-auto px-6 pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- PRODUCTOS -->
        <section class="lg:col-span-8">
            <div class="mb-8 pt-6">
                <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-2">Gestión de inventario</p>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tighter text-[#1a1c1c]">Catálogo de productos</h1>
            </div>
            <div class="flex gap-4 mb-8 overflow-x-auto pb-2">
                <button class="filtro-btn activo px-6 py-2 bg-[#1a1c1c] text-white text-xs font-bold uppercase tracking-widest" onclick="filtrar('todos', this)">Todos</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('ropa', this)">Ropa</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('calzado', this)">Calzado</button>
                <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]" onclick="filtrar('accesorios', this)">Accesorios</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-6" id="grid-productos">
                @forelse($productos ?? [] as $producto)
                <div class="producto-card group flex flex-col space-y-4" data-categoria="{{ $producto['categoria'] }}">
                    <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative">
                        @if(isset($producto['imagen']) && $producto['imagen'])
                            <img class="producto-imagen w-full h-full object-cover" src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                        @endif
                        @if(isset($producto['stock']))
                            @if($producto['stock'] === 'disponible')<div class="absolute top-4 left-4 bg-[#1737c8] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">En stock</div>
                            @elseif($producto['stock'] === 'poco')<div class="absolute top-4 left-4 bg-[#1a1c1c] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Poco stock</div>
                            @elseif($producto['stock'] === 'agotado')<div class="absolute top-4 left-4 bg-red-700 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Sin stock</div>
                            @endif
                        @endif
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="agregarAlCarrito({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio']]) }})"
                                class="btn-agregar absolute bottom-4 right-4 bg-[#1737c8] text-white w-10 h-10 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all">
                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                        </button>
                        @endif
                    </div>
                    <div class="flex flex-col space-y-1">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold tracking-tight">{{ $producto['nombre'] }}</h3>
                            <span class="text-[#1737c8] font-bold text-sm">${{ number_format($producto['precio'], 2) }}</span>
                        </div>
                        <p class="text-[#5e5e5e]/60 font-mono text-[10px] tracking-widest uppercase">ID: {{ $producto['id'] }}</p>
                        <div class="pt-3 border-t border-[#c4c5da]/20">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#1a1c1c]/40 mb-2">Inventario</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                <span class="bg-[#eeeeee] px-2 py-1 text-[10px] font-bold">{{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span></span>
                                @endforeach
                            </div>
                        </div>
                        @if(!isset($producto['stock']) || $producto['stock'] !== 'agotado')
                        <button onclick="agregarAlCarrito({{ json_encode(['id' => $producto['id'], 'nombre' => $producto['nombre'], 'precio' => $producto['precio']]) }})"
                                class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#1a1c1c] text-[10px] font-black uppercase tracking-widest hover:bg-[#1737c8] hover:text-white transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Agregar al carrito
                        </button>
                        @else
                        <div class="w-full mt-2 py-2.5 bg-[#f3f3f4] text-[#c4c5da] text-[10px] font-black uppercase tracking-widest text-center">Sin stock</div>
                        @endif
                    </div>
                </div>
                @empty
                @for($i = 0; $i < 6; $i++)
                <div class="producto-card group flex flex-col space-y-4">
                    <div class="aspect-[4/5] bg-[#f3f3f4] flex items-center justify-center"><span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span></div>
                    <div class="flex justify-between items-start"><div class="h-4 bg-[#f3f3f4] w-32 rounded"></div><div class="h-4 bg-[#f3f3f4] w-16 rounded"></div></div>
                    <div class="h-3 bg-[#f3f3f4] w-24 rounded"></div>
                    <div class="w-full h-9 bg-[#f3f3f4] rounded mt-2"></div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>

        <!-- CARRITO -->
        <aside class="lg:col-span-4 lg:sticky lg:top-20 h-fit pt-6">
            <div class="bg-[#f3f3f4] border border-[#c4c5da]/20">
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20 bg-white">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8]">shopping_cart</span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Carrito de venta</p>
                            <p class="text-sm font-black text-[#1a1c1c]" id="carrito-count">0 productos</p>
                        </div>
                    </div>
                    <button onclick="limpiarCarrito()" class="text-[10px] font-black uppercase tracking-widest text-[#747688] hover:text-red-600 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete_sweep</span>Limpiar
                    </button>
                </div>
                <div id="carrito-items" class="max-h-64 overflow-y-auto">
                    <div id="carrito-vacio" class="px-6 py-12 text-center text-[#747688] text-sm">
                        <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>Agrega productos del catálogo
                    </div>
                </div>
                <div class="px-6 py-4 bg-[#1737c8] flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Total</span>
                    <span class="text-2xl font-black text-white" id="carrito-total">$0.00</span>
                </div>
                <form method="POST" action="{{ route('catalog.store') }}" id="form-carrito" class="px-6 py-5 space-y-4 bg-white border-t border-[#c4c5da]/20">
                    @csrf
                    <input type="hidden" name="productos_carrito" id="productos-carrito-input"/>
                    <input type="hidden" name="total_carrito" id="total-carrito-input"/>
                    <div class="flex flex-col gap-1 relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Cliente</label>
                        <input type="text" id="cliente-carrito-search" name="cliente_nombre" placeholder="Nombre del cliente..." autocomplete="off" oninput="buscarClienteCarrito(this.value)" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <input type="hidden" name="cliente_id" id="cliente-carrito-id"/>
                        <p class="text-[9px] text-[#747688] font-semibold mt-1">Si el cliente no existe se registrará automáticamente</p>
                        <div id="carrito-sugerencias" class="absolute top-[68px] left-0 right-0 bg-white border border-[#c4c5da]/40 shadow-lg z-50 hidden max-h-40 overflow-y-auto"></div>
                        <div id="clientes-data-carrito" class="hidden">
                            @foreach($clientes ?? [] as $cliente)
                            <span data-id="{{ $cliente->id ?? $cliente['id'] }}" data-nombre="{{ $cliente->name ?? $cliente['nombre'] }}"></span>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" onclick="prepararFormCarrito(event)" class="w-full py-4 bg-[#1a1c1c] text-white text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">point_of_sale</span>Registrar venta
                    </button>
                </form>
            </div>
        </aside>
    </div>
</main>

<!-- BOTTOM NAV móvil -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    @endif
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
</nav>

<script>
let carrito = [];
function agregarAlCarrito(producto) {
    const existente = carrito.find(p => p.id === producto.id);
    if (existente) { existente.cantidad += 1; } else { carrito.push({ ...producto, cantidad: 1 }); }
    actualizarCarrito();
}
function actualizarCarrito() {
    const itemsEl = document.getElementById('carrito-items');
    const countEl = document.getElementById('carrito-count');
    const totalEl = document.getElementById('carrito-total');
    if (carrito.length === 0) { itemsEl.innerHTML = '<div class="px-6 py-12 text-center text-[#747688] text-sm"><span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-2">shopping_cart</span>Agrega productos del catálogo</div>'; countEl.textContent = '0 productos'; totalEl.textContent = '$0.00'; return; }
    let total = 0;
    itemsEl.innerHTML = carrito.map((p, i) => { const s = p.precio * p.cantidad; total += s; return `<div class="flex items-center gap-3 px-4 py-3 border-b border-[#c4c5da]/10 bg-white"><div class="flex-1 min-w-0"><p class="font-bold text-xs uppercase truncate">${p.nombre}</p><p class="text-[10px] text-[#747688]">$${parseFloat(p.precio).toFixed(2)} c/u</p></div><div class="flex items-center gap-1"><button onclick="cambiarCantidad(${i},-1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">−</button><span class="w-8 text-center text-sm font-black">${p.cantidad}</span><button onclick="cambiarCantidad(${i},1)" class="w-6 h-6 bg-[#f3f3f4] flex items-center justify-center text-xs font-black">+</button></div><p class="text-xs font-black text-[#1737c8]">$${s.toFixed(2)}</p><button onclick="quitarDelCarrito(${i})" class="text-[#747688] hover:text-red-600 ml-1"><span class="material-symbols-outlined text-sm">close</span></button></div>`; }).join('');
    countEl.textContent = carrito.length + (carrito.length === 1 ? ' producto' : ' productos');
    totalEl.textContent = '$' + total.toFixed(2);
}
function cambiarCantidad(i, d) { carrito[i].cantidad += d; if (carrito[i].cantidad <= 0) carrito.splice(i,1); actualizarCarrito(); }
function quitarDelCarrito(i) { carrito.splice(i,1); actualizarCarrito(); }
function limpiarCarrito() { carrito = []; actualizarCarrito(); }
function prepararFormCarrito(e) {
    if (carrito.length === 0) { e.preventDefault(); alert('Agrega al menos un producto al carrito'); return; }
    if (!document.getElementById('cliente-carrito-search').value.trim()) { e.preventDefault(); alert('Escribe el nombre del cliente'); return; }
    const total = carrito.reduce((s,p) => s+p.precio*p.cantidad, 0);
    document.getElementById('productos-carrito-input').value = JSON.stringify(carrito);
    document.getElementById('total-carrito-input').value = total.toFixed(2);
}
function buscarClienteCarrito(query) {
    const sugerencias = document.getElementById('carrito-sugerencias');
    const q = query.toLowerCase().trim();
    if (q.length < 1) { sugerencias.classList.add('hidden'); return; }
    const c = Array.from(document.querySelectorAll('#clientes-data-carrito span')).filter(c => c.dataset.nombre.toLowerCase().includes(q));
    sugerencias.innerHTML = c.length === 0 ? '<div class="px-4 py-3 text-sm text-[#747688]">Sin coincidencias</div>' : c.map(c => `<div class="px-4 py-3 text-sm font-bold cursor-pointer hover:bg-[#f3f3f4] uppercase" onclick="seleccionarClienteCarrito('${c.dataset.id}','${c.dataset.nombre}')">${c.dataset.nombre}</div>`).join('');
    sugerencias.classList.remove('hidden');
}
function seleccionarClienteCarrito(id, nombre) { document.getElementById('cliente-carrito-search').value = nombre; document.getElementById('cliente-carrito-id').value = id; document.getElementById('carrito-sugerencias').classList.add('hidden'); }
document.addEventListener('click', e => { if (!e.target.closest('#cliente-carrito-search') && !e.target.closest('#carrito-sugerencias')) document.getElementById('carrito-sugerencias')?.classList.add('hidden'); });
function filtrar(categoria, btn) { document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo')); btn.classList.add('activo'); document.querySelectorAll('.producto-card').forEach(c => { c.style.display = (categoria === 'todos' || c.dataset.categoria === categoria) ? '' : 'none'; }); }
</script>

@include('partials._sidebar')

</body>
</html>