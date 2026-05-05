<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Robles Sport - Catálogo</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    /* modal */
    #modal-nuevo-producto { display: none; }
    #modal-nuevo-producto.activo { display: flex; }

    /* filtros activos */
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }
    .filtro-btn { transition: all 0.2s; }

    /* cards */
    .producto-card { transition: all 0.2s; }
    .producto-card:hover .producto-imagen { transform: scale(1.05); }
    .producto-imagen { transition: transform 0.7s ease; }

    /* imagen placeholder cuando no hay img */
    .img-placeholder {
        width: 100%;
        height: 100%;
        background: #f3f3f4;
        display: flex;
        align-items: center;
        justify-content: center;
    }
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
    <section class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="max-w-2xl">
            <p class="text-[#0035c5] font-bold tracking-widest text-[10px] uppercase mb-4">Gestión de inventario</p>
            <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-[#1a1c1c] mb-6">Catálogo de productos</h1>
            <p class="text-[#5e5e5e] text-lg leading-relaxed max-w-xl">
                Administra la disponibilidad, información y existencias de los productos de Robles Sport.
            </p>
        </div>
        <div>
            {}
            <button onclick="abrirModal()"
                    class="bg-[#1a1c1c] text-white px-8 py-4 font-bold tracking-tight hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                Nuevo producto
            </button>
        </div>
    </section>

    <!-- FILTROS -->
    {{-- estos botones filtran las tarjetas por categoria, el JS lo maneja abajo --}}
    <div class="flex gap-4 mb-12 overflow-x-auto pb-2">
        <button class="filtro-btn activo px-6 py-2 bg-[#1a1c1c] text-white text-xs font-bold uppercase tracking-widest"
                onclick="filtrar('todos', this)">Todos</button>
        <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                onclick="filtrar('ropa', this)">Ropa</button>
        <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                onclick="filtrar('calzado', this)">Calzado</button>
        <button class="filtro-btn px-6 py-2 bg-[#e8e8e8] text-[#1a1c1c] text-xs font-bold uppercase tracking-widest hover:bg-[#e2e2e2]"
                onclick="filtrar('accesorios', this)">Accesorios</button>
    </div>

    <!-- GRID DE PRODUCTOS -->
    {{-- cuando conectes la bd asegurate de mandar $productos con: id, nombre, precio, categoria, stock, tallas, imagen, estado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-16 gap-x-8" id="grid-productos">

        @forelse($productos ?? [] as $producto)
        <div class="producto-card group flex flex-col space-y-6" data-categoria="{{ $producto['categoria'] }}">

            {{-- recuadro de imagen, si no hay imagen muestra un placeholder gris --}}
            <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative">
                @if(isset($producto['imagen']) && $producto['imagen'])
                    <img class="producto-imagen w-full h-full object-cover"
                         src="{{ $producto['imagen'] }}"
                         alt="{{ $producto['nombre'] }}"/>
                @else
                    <div class="img-placeholder">
                        <span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span>
                    </div>
                @endif

                {{-- badge de estado: en stock, poco stock, sin stock --}}
                @if($producto['stock'] === 'disponible')
                    <div class="absolute top-4 left-4 bg-[#0035c5] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">En stock</div>
                @elseif($producto['stock'] === 'poco')
                    <div class="absolute top-4 left-4 bg-[#1a1c1c] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Poco stock</div>
                @elseif($producto['stock'] === 'agotado')
                    <div class="absolute top-4 left-4 bg-[#8d1c00] text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Sin stock</div>
                @endif
            </div>

            <div class="flex flex-col space-y-2">
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold tracking-tight">{{ $producto['nombre'] }}</h3>
                    <span class="text-[#0035c5] font-bold text-sm">${{ number_format($producto['precio'], 2) }}</span>
                </div>
                <p class="text-[#5e5e5e]/60 font-mono text-[10px] tracking-widest uppercase">ID: {{ $producto['id'] }}</p>

                {{-- desglose de tallas/existencias --}}
                <div class="pt-4 border-t border-[#c4c5da]/20 mt-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#1a1c1c]/40 mb-3">Desglose de inventario</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                        <span class="bg-[#eeeeee] text-[#1a1c1c] px-3 py-1 text-[11px] font-bold">
                            {{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#0035c5]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- si no hay productos de la bd muestra los recuadros vacios de placeholder --}}
        @for($i = 0; $i < 6; $i++)
        <div class="producto-card group flex flex-col space-y-6">
            <div class="aspect-[4/5] bg-[#f3f3f4] overflow-hidden relative flex items-center justify-center">
                <span class="material-symbols-outlined text-[#c4c5da] text-6xl">image</span>
            </div>
            <div class="flex flex-col space-y-2">
                <div class="flex justify-between items-start">
                    <div class="h-4 bg-[#f3f3f4] w-32 rounded"></div>
                    <div class="h-4 bg-[#f3f3f4] w-16 rounded"></div>
                </div>
                <div class="h-3 bg-[#f3f3f4] w-24 rounded mt-1"></div>
                <div class="pt-4 border-t border-[#c4c5da]/20 mt-2">
                    <div class="h-3 bg-[#f3f3f4] w-32 rounded mb-3"></div>
                    <div class="flex gap-2">
                        <div class="h-6 bg-[#f3f3f4] w-12 rounded"></div>
                        <div class="h-6 bg-[#f3f3f4] w-12 rounded"></div>
                        <div class="h-6 bg-[#f3f3f4] w-12 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
        @endforelse

    </div>
</main>

<!-- MODAL NUEVO PRODUCTO -->
{{-- este modal se abre al dar click en "nuevo producto" --}}
{{-- cuando conectes la bd la ruta del form debe ser route('productos.store') o como la llames --}}
<div id="modal-nuevo-producto"
     class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">

        <!-- cabecera del modal -->
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#0035c5] mb-1">Catálogo</p>
                <h2 class="text-2xl font-bold tracking-tight">Nuevo producto</h2>
            </div>
            <button onclick="cerrarModal()"
                    class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- formulario -->
        {{-- aqui conectas la ruta para guardar el producto, algo como action="{{ route('productos.store') }}" --}}
        <form method="POST" action="#" enctype="multipart/form-data" class="px-8 py-6 space-y-6">
            @csrf

            <!-- nombre -->
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre del producto</label>
                <input type="text"
                       name="nombre"
                       placeholder="Ej. Tenis Nike Air Max"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#0035c5] transition-colors bg-[#f9f9f9]"/>
            </div>

            <!-- precio y categoria en la misma fila -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Precio</label>
                    <input type="number"
                           name="precio"
                           placeholder="0.00"
                           step="0.01"
                           class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#0035c5] transition-colors bg-[#f9f9f9]"/>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Categoría</label>
                    {{-- aqui conectas las categorias desde la bd si quieres que sean dinamicas --}}
                    <select name="categoria"
                            class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#0035c5] transition-colors bg-[#f9f9f9]">
                        <option value="ropa">Ropa</option>
                        <option value="calzado">Calzado</option>
                        <option value="accesorios">Accesorios</option>
                    </select>
                </div>
            </div>

            <!-- imagen -->
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Imagen del producto</label>
                <div class="border border-dashed border-[#c4c5da]/60 p-8 text-center bg-[#f9f9f9] cursor-pointer hover:border-[#0035c5] transition-colors"
                     onclick="document.getElementById('input-imagen').click()">
                    <span class="material-symbols-outlined text-[#c4c5da] text-4xl mb-2 block">upload</span>
                    <p class="text-xs text-[#747688] font-semibold">Haz click para subir una imagen</p>
                    <p class="text-[10px] text-[#747688]/60 mt-1">PNG, JPG hasta 5MB</p>
                    <input type="file" id="input-imagen" name="imagen" accept="image/*" class="hidden"
                           onchange="previsualizarImagen(this)"/>
                </div>
                <img id="preview-imagen" src="" alt="Preview" class="hidden mt-2 w-full max-h-48 object-contain border border-[#c4c5da]/20"/>
            </div>

            <!-- tallas/existencias -->
            <div class="flex flex-col gap-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tallas y existencias</label>
                <div id="tallas-container" class="space-y-2">
                    {{-- esto agrega filas de talla + cantidad, el JS lo maneja --}}
                    <div class="flex gap-3 items-center talla-fila">
                        <input type="text" name="tallas[]" placeholder="Talla (ej. S, M, 42)" 
                               class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9]"/>
                        <input type="number" name="cantidades[]" placeholder="Cantidad" min="0"
                               class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9]"/>
                        <button type="button" onclick="eliminarTalla(this)"
                                class="p-2 hover:bg-[#f3f3f4] transition-colors text-[#747688]">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="agregarTalla()"
                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-[#0035c5] hover:opacity-70 transition-opacity mt-1">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Agregar talla
                </button>
            </div>

            <!-- estado de stock -->
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Estado de stock</label>
                <select name="stock"
                        class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#0035c5] transition-colors bg-[#f9f9f9]">
                    <option value="disponible">En stock</option>
                    <option value="poco">Poco stock</option>
                    <option value="agotado">Sin stock</option>
                </select>
            </div>

            <!-- botones del form -->
            <div class="flex gap-3 pt-4 border-t border-[#c4c5da]/20">
                <button type="submit"
                        class="flex-1 bg-[#0035c5] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
                    GUARDAR PRODUCTO
                </button>
                <button type="button" onclick="cerrarModal()"
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
    <a href="/catalog" class="flex flex-col items-center text-[#0035c5] border-t-2 border-[#0035c5] pt-2">
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
// abre el modal de nuevo producto
function abrirModal() {
    document.getElementById('modal-nuevo-producto').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

// cierra el modal
function cerrarModal() {
    document.getElementById('modal-nuevo-producto').classList.remove('activo');
    document.body.style.overflow = '';
}

// cierra el modal si le das click fuera de el
document.getElementById('modal-nuevo-producto').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// agrega una nueva fila de talla + cantidad al formulario
function agregarTalla() {
    const container = document.getElementById('tallas-container');
    const fila = document.createElement('div');
    fila.className = 'flex gap-3 items-center talla-fila';
    fila.innerHTML = `
        <input type="text" name="tallas[]" placeholder="Talla (ej. S, M, 42)"
               class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9]"/>
        <input type="number" name="cantidades[]" placeholder="Cantidad" min="0"
               class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#0035c5] bg-[#f9f9f9]"/>
        <button type="button" onclick="eliminarTalla(this)"
                class="p-2 hover:bg-[#f3f3f4] transition-colors text-[#747688]">
            <span class="material-symbols-outlined text-sm">delete</span>
        </button>
    `;
    container.appendChild(fila);
}

// elimina una fila de talla
function eliminarTalla(btn) {
    const fila = btn.closest('.talla-fila');
    if (document.querySelectorAll('.talla-fila').length > 1) {
        fila.remove();
    }
}

// muestra preview de la imagen antes de subir
function previsualizarImagen(input) {
    const preview = document.getElementById('preview-imagen');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// filtra los productos por categoria al dar click en los botones
function filtrar(categoria, btn) {
    // actualizo el boton activo
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');

    // muestro u oculto las tarjetas segun la categoria
    document.querySelectorAll('.producto-card').forEach(card => {
        if (categoria === 'todos' || card.dataset.categoria === categoria) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
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