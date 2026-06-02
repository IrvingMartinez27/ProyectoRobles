<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Inventario</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-editar { display: none; }
    #modal-editar.activo { display: flex; }
    .filtro-btn.activo { background: #1a1c1c; color: #ffffff; }
    .fila-poco { border-left: 3px solid #f59e0b; }
    .fila-poco td { background-color: #fffbeb; }
    .fila-agotado { border-left: 3px solid #ef4444; }
    .fila-agotado td { background-color: #fef2f2; }
    .fila-ok td { background-color: #ffffff; }
    .fila-producto:hover td { filter: brightness(0.97); }
    .tooltip-wrap { position: relative; display: inline-block; cursor: default; }
    .tooltip-box { display: none; position: absolute; bottom: calc(100% + 8px); left: 0; z-index: 99; background: #1a1c1c; color: #fff; padding: 10px 14px; font-size: 11px; font-weight: 600; white-space: nowrap; min-width: 190px; border-radius: 2px; }
    .tooltip-box::after { content: ''; position: absolute; top: 100%; left: 14px; border: 5px solid transparent; border-top-color: #1a1c1c; }
    .tooltip-wrap:hover .tooltip-box { display: block; }
    .tooltip-titulo { font-size: 10px; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 6px; }
    .tooltip-fila { display: flex; justify-content: space-between; gap: 16px; font-size: 11px; margin-bottom: 3px; color: rgba(255,255,255,0.75); }
    .tooltip-fila span:last-child { font-weight: 700; color: #fff; }
    .tooltip-fila span.bajo { color: #fca5a5 !important; }
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; margin-right: 6px; }
    .dot-ok { background: #22c55e; }
    .dot-poco { background: #f59e0b; }
    .dot-agotado { background: #ef4444; }
    #zona-imagen { min-height: 120px; }
    #zona-imagen.con-imagen { min-height: 180px; }
</style>
</head>
<body class="bg-white text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        @endif
        <span class="text-[#1a1c1c]">Inventario</span>
    </div>
</div>

<main class="max-w-[1440px] mx-auto px-6 py-12 pb-24">
    <section class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[#1737c8] font-bold tracking-widest text-[10px] uppercase mb-4">Gestión de stock</p>
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter text-[#1a1c1c] mb-4">Inventario</h1>
            <p class="text-[#5e5e5e] text-lg leading-relaxed max-w-xl">Consulta, edita y agrega productos al inventario.</p>
        </div>
        <div class="flex gap-4">
            <button onclick="abrirModalProducto()" class="bg-[#1737c8] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>NUEVO PRODUCTO
            </button>
            <div class="bg-[#f3f3f4] px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-1">Total productos</p>
                <p class="text-3xl font-black text-[#1a1c1c]">{{ $productos->count() }}</p>
            </div>
            <div class="bg-red-50 px-6 py-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-red-400 mb-1">Stock bajo</p>
                <p class="text-3xl font-black text-red-600">{{ $productos->filter(fn($p) => $p['stock_total'] < 10)->count() }}</p>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between items-start md:items-center">
        <div class="flex gap-3 overflow-x-auto pb-1">
            <button class="filtro-btn activo px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#1a1c1c] transition-all" onclick="filtrar('todos', this)">Todos</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all" onclick="filtrar('ropa', this)">Ropa</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all" onclick="filtrar('calzado', this)">Calzado</button>
            <button class="filtro-btn px-5 py-2 text-xs font-black uppercase tracking-widest border border-[#c4c5da]/40 hover:border-[#1a1c1c] transition-all" onclick="filtrar('accesorios', this)">Accesorios</button>
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#747688] text-sm">search</span>
            <input type="text" placeholder="Buscar producto..." oninput="buscar(this.value)" class="pl-10 pr-4 py-2 border-b border-[#c4c5da]/40 focus:border-[#1737c8] focus:outline-none transition-colors text-sm w-64 bg-transparent"/>
        </div>
    </div>

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
                <tr class="fila-producto border-b border-[#c4c5da]/10 transition-colors {{ $producto['stock_total'] == 0 ? 'fila-agotado' : ($producto['stock_total'] < 10 ? 'fila-poco' : 'fila-ok') }}"
                    data-categoria="{{ $producto['categoria'] }}" data-nombre="{{ strtolower($producto['nombre']) }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if(!empty($producto['imagen']))
                                <img src="{{ $producto['imagen'] }}"                                     alt="{{ $producto['nombre'] }}"
                                     class="w-10 h-10 object-cover rounded flex-shrink-0 border border-[#c4c5da]/20"/>
                            @else
                                <div class="w-10 h-10 bg-[#f3f3f4] flex items-center justify-center flex-shrink-0 rounded">
                                    <span class="material-symbols-outlined text-[#c4c5da] text-sm">image</span>
                                </div>
                            @endif
                            <div class="flex items-center">
                                <span class="dot {{ $producto['stock_total'] == 0 ? 'dot-agotado' : ($producto['stock_total'] < 10 ? 'dot-poco' : 'dot-ok') }}"></span>
                                @if($producto['stock_total'] < 10)
                                <div class="tooltip-wrap">
                                    <p class="font-bold text-sm uppercase">{{ $producto['nombre'] }}</p>
                                    <div class="tooltip-box {{ $producto['stock_total'] == 0 ? 'tooltip-agotado' : 'tooltip-poco' }}">
                                        <div class="tooltip-titulo">{{ $producto['stock_total'] == 0 ? '✕ Sin stock' : '⚠ Stock bajo' }}</div>
                                        <div class="tooltip-fila"><span>Total piezas</span><span class="{{ $producto['stock_total'] == 0 ? 'bajo' : '' }}">{{ $producto['stock_total'] }}</span></div>
                                        @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                                        <div class="tooltip-fila"><span>Talla {{ $talla }}</span><span class="{{ $cantidad <= 2 ? 'bajo' : '' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }} piezas</span></div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <p class="font-bold text-sm uppercase">{{ $producto['nombre'] }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4"><p class="font-mono text-[10px] text-[#747688] uppercase tracking-wider">{{ $producto['id'] }}</p></td>
                    <td class="px-6 py-4"><span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-[#f3f3f4]">{{ ucfirst($producto['categoria']) }}</span></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($producto['tallas'] ?? [] as $talla => $cantidad)
                            <span class="bg-[#f3f3f4] px-2 py-1 text-[10px] font-bold">{{ $talla }}: <span class="{{ $cantidad <= 2 ? 'text-red-600' : 'text-[#1737c8]' }}">{{ str_pad($cantidad, 2, '0', STR_PAD_LEFT) }}</span></span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center"><span class="text-lg font-black {{ $producto['stock_total'] < 10 ? 'text-red-600' : 'text-[#1a1c1c]' }}">{{ $producto['stock_total'] }}</span></td>
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
                        <button onclick="abrirEditar({{ json_encode($producto) }})" class="px-4 py-2 border border-[#c4c5da]/40 text-[10px] font-black uppercase tracking-widest hover:bg-[#1a1c1c] hover:text-white hover:border-[#1a1c1c] transition-all flex items-center gap-1 ml-auto">
                            <span class="material-symbols-outlined text-sm">edit</span>Editar stock
                        </button>
                    </td>
                </tr>
                @empty
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
<div id="modal-editar" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this) cerrarEditar()">
    <div class="bg-white w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Inventario</p><h2 class="text-xl font-bold tracking-tight" id="modal-nombre">Editar stock</h2></div>
            <button onclick="cerrarEditar()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('inventario.update') }}" id="form-editar" class="px-8 py-6 space-y-6">
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
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">GUARDAR CAMBIOS</button>
                <button type="button" onclick="cerrarEditar()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">CANCELAR</button>
            </div>
            <div class="pt-2">
                <button type="button" onclick="abrirConfirmarEliminar()" class="w-full border border-red-200 text-red-500 py-3 text-xs font-black uppercase tracking-widest hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">delete</span>Eliminar producto
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL NUEVO PRODUCTO -->
<div id="modal-nuevo-producto" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm" style="display:none;" onclick="if(event.target===this) cerrarModalProducto()">
    <div class="bg-white w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-8 py-6 border-b border-[#c4c5da]/20">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Inventario</p><h2 class="text-2xl font-bold tracking-tight">Nuevo producto</h2></div>
            <button onclick="cerrarModalProducto()" class="p-2 hover:bg-[#f3f3f4] transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('productos.store') }}" id="form-nuevo-producto" class="px-8 py-6 space-y-6" enctype="multipart/form-data">
            @csrf

            {{-- IMAGEN --}}
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">
                    Imagen del producto <span class="normal-case font-normal text-[#c4c5da]">(opcional)</span>
                </label>
                <div id="zona-imagen"
                     onclick="document.getElementById('input-imagen').click()"
                     class="border-2 border-dashed border-[#c4c5da]/40 hover:border-[#1737c8] transition-colors cursor-pointer flex flex-col items-center justify-center py-8 gap-2 relative bg-[#f9f9f9] overflow-hidden">
                    <img id="preview-imagen" src="" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover"/>
                    <div id="zona-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                        <span class="material-symbols-outlined text-3xl text-[#c4c5da]">add_photo_alternate</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Haz clic para subir imagen</p>
                        <p class="text-[9px] text-[#c4c5da]">JPG, PNG o WEBP · Máx. 2MB</p>
                    </div>
                    <input type="file" id="input-imagen" name="imagen" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previsualizarImagen(this)"/>
                </div>
                <button type="button" id="btn-quitar-imagen" onclick="quitarImagen()" class="hidden text-[9px] font-black uppercase tracking-widest text-red-500 hover:opacity-70 transition-opacity text-left">
                    ✕ Quitar imagen
                </button>
            </div>

            {{-- NOMBRE --}}
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre del producto</label>
                <input type="text" name="nombre" id="input-nombre-producto" placeholder="Ej. Tenis Nike Air Max"
                       oninput="verificarDuplicado(this.value)"
                       class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                <p id="alerta-duplicado" class="text-[10px] text-yellow-600 font-black uppercase tracking-widest hidden">
                    ⚠ Este producto ya existe — se agregarán las piezas al stock actual
                </p>
                <input type="hidden" name="producto_existente" id="producto-existente" value="0"/>
            </div>

            {{-- PRECIO Y CATEGORÍA --}}
            {{-- PRECIO, COSTO Y CATEGORÍA --}}
<div class="grid grid-cols-2 gap-4">
    <div class="flex flex-col gap-1">
        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Precio de venta</label>
        <input type="number" name="precio" placeholder="0.00" step="0.01"
               class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">
            Costo proveedor
            @if(!$esBusiness)
            <span class="text-[9px] font-black bg-[#1737c8] text-white px-1.5 py-0.5 rounded-full ml-1">Business</span>
            @endif
        </label>
        <input type="number" name="costo" placeholder="0.00" step="0.01"
               {{ !$esBusiness ? 'disabled' : '' }}
               class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] {{ !$esBusiness ? 'opacity-40 cursor-not-allowed' : '' }}"/>
        <p class="text-[9px] text-[#747688]">Lo que pagaste al proveedor</p>
    </div>
</div>
<div class="flex flex-col gap-1">
    <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Categoría</label>
    <select name="categoria" class="border border-[#c4c5da]/40 px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]">
        <option value="ropa">Ropa</option>
        <option value="calzado">Calzado</option>
        <option value="accesorios">Accesorios</option>
    </select>
</div>

            {{-- TALLAS --}}
            <div class="flex flex-col gap-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Tallas y existencias</label>
                <p class="text-[9px] text-[#747688] font-semibold -mt-2">El estado de stock se calcula automáticamente</p>
                <div id="tallas-container" class="space-y-2">
                    <div class="flex gap-3 items-center talla-fila">
                        <input type="text" name="tallas[]" placeholder="Talla (ej. S, M, 42)"
                               class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <input type="number" name="cantidades[]" placeholder="Cantidad" min="0"
                               class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
                        <button type="button" onclick="eliminarTalla(this)" class="p-2 hover:bg-[#f3f3f4] text-[#747688]">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="agregarTalla()" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-[#1737c8] hover:opacity-70 transition-opacity">
                    <span class="material-symbols-outlined text-sm">add</span>Agregar talla
                </button>
            </div>

            {{-- BOTONES --}}
            <div class="flex gap-3 pt-4 border-t border-[#c4c5da]/20">
                <button type="submit" class="flex-1 bg-[#1737c8] text-white py-4 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">GUARDAR PRODUCTO</button>
                <button type="button" onclick="cerrarModalProducto()" class="flex-1 border border-[#c4c5da]/40 py-4 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">CANCELAR</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CONFIRMAR ELIMINAR -->
<div id="modal-confirmar-eliminar" class="fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm" style="display:none;">
    <div class="bg-white w-full max-w-sm mx-4">
        <div class="px-8 py-6 border-b border-[#c4c5da]/20">
            <p class="text-[10px] font-bold uppercase tracking-widest text-red-500 mb-1">Confirmar acción</p>
            <h2 class="text-xl font-bold tracking-tight">¿Eliminar producto?</h2>
        </div>
        <div class="px-8 py-6">
            <p class="text-sm text-[#5e5e5e] mb-1">Vas a eliminar:</p>
            <p class="font-black text-lg uppercase tracking-tight mb-4" id="confirmar-nombre-producto">—</p>
            <p class="text-xs text-[#747688]">Esta acción no se puede deshacer. Se eliminará el producto y todo su inventario.</p>
        </div>
        <div class="flex gap-3 px-8 pb-6">
            <form id="form-eliminar-producto" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest hover:bg-red-700 transition-all">
                    SÍ, ELIMINAR
                </button>
            </form>
            <button type="button" onclick="cerrarConfirmarEliminar()" class="flex-1 border border-[#c4c5da]/40 py-3 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                CANCELAR
            </button>
        </div>
    </div>
</div>

<!-- BOTTOM NAV móvil -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="/dashboard" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">dashboard</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Inicio</span></a>
    @endif
    <a href="/sales" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">receipt_long</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Ventas</span></a>
    <a href="/catalog" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2"><span class="material-symbols-outlined">shopping_bag</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Catálogo</span></a>
    <a href="/inventario" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2"><span class="material-symbols-outlined">inventory_2</span><span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span></a>
</nav>

<script>
// ── EDITAR STOCK ──────────────────────────────────────────────────────────
function abrirEditar(producto) {
    document.getElementById('modal-nombre').textContent = producto.nombre;
    document.getElementById('modal-producto-id').value = producto.id;
    const tallasEl = document.getElementById('modal-tallas');
    tallasEl.innerHTML = '';
    Object.entries(producto.tallas ?? {}).forEach(([talla, cantidad]) => {
        const fila = document.createElement('div');
        fila.className = 'flex gap-3 items-center';
        fila.innerHTML = `
            <div class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm font-bold bg-[#f9f9f9] text-[#747688] uppercase tracking-wider">${talla}</div>
            <input type="number" name="tallas[${talla}]" value="${cantidad}" min="0" oninput="calcularTotal()"
                   class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] font-bold"/>`;
        tallasEl.appendChild(fila);
    });
    calcularTotal();
    document.getElementById('modal-editar').classList.add('activo');
    document.body.style.overflow = 'hidden';
}

function calcularTotal() {
    const inputs = document.querySelectorAll('#modal-tallas input[type="number"]');
    let total = 0;
    inputs.forEach(i => total += parseInt(i.value) || 0);
    document.getElementById('modal-total').textContent = total;
}

function cerrarEditar() {
    document.getElementById('modal-editar').classList.remove('activo');
    document.body.style.overflow = '';
}

// ── ELIMINAR PRODUCTO ─────────────────────────────────────────────────────
function abrirConfirmarEliminar() {
    const id     = document.getElementById('modal-producto-id').value;
    const nombre = document.getElementById('modal-nombre').textContent;
    document.getElementById('confirmar-nombre-producto').textContent = nombre;
    document.getElementById('form-eliminar-producto').action = '/productos/' + id;
    document.getElementById('modal-confirmar-eliminar').style.display = 'flex';
}

function cerrarConfirmarEliminar() {
    document.getElementById('modal-confirmar-eliminar').style.display = 'none';
}

// ── FILTROS Y BÚSQUEDA ────────────────────────────────────────────────────
function filtrar(categoria, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    document.querySelectorAll('.fila-producto').forEach(f => {
        f.style.display = (categoria === 'todos' || f.dataset.categoria === categoria) ? '' : 'none';
    });
}

function buscar(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.fila-producto').forEach(f => {
        f.style.display = f.dataset.nombre.includes(q) ? '' : 'none';
    });
}

// ── MODAL NUEVO PRODUCTO ──────────────────────────────────────────────────
function abrirModalProducto() {
    document.getElementById('modal-nuevo-producto').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModalProducto() {
    document.getElementById('modal-nuevo-producto').style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('form-nuevo-producto').reset();
    document.getElementById('alerta-duplicado').classList.add('hidden');
    quitarImagen();
}

function verificarDuplicado(nombre) {
    const productos = document.querySelectorAll('.fila-producto');
    const alerta = document.getElementById('alerta-duplicado');
    const q = nombre.toLowerCase().trim();
    const existe = Array.from(productos).some(f => f.dataset.nombre && f.dataset.nombre.toLowerCase() === q);
    existe && q.length > 0 ? alerta.classList.remove('hidden') : alerta.classList.add('hidden');
    document.getElementById('producto-existente').value = existe && q.length > 0 ? '1' : '0';
}

function agregarTalla() {
    const container = document.getElementById('tallas-container');
    const fila = document.createElement('div');
    fila.className = 'flex gap-3 items-center talla-fila';
    fila.innerHTML = `
        <input type="text" name="tallas[]" placeholder="Talla"
               class="flex-1 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
        <input type="number" name="cantidades[]" placeholder="Cantidad" min="0"
               class="w-28 border border-[#c4c5da]/40 px-3 py-2 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
        <button type="button" onclick="eliminarTalla(this)" class="p-2 hover:bg-[#f3f3f4] text-[#747688]">
            <span class="material-symbols-outlined text-sm">delete</span>
        </button>`;
    container.appendChild(fila);
}

function eliminarTalla(btn) {
    const fila = btn.closest('.talla-fila');
    if (document.querySelectorAll('.talla-fila').length > 1) fila.remove();
}

// ── IMAGEN ────────────────────────────────────────────────────────────────
function previsualizarImagen(input) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen no puede pesar más de 2MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const preview     = document.getElementById('preview-imagen');
        const placeholder = document.getElementById('zona-placeholder');
        const btnQuitar   = document.getElementById('btn-quitar-imagen');
        const zona        = document.getElementById('zona-imagen');

        preview.src = e.target.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
        btnQuitar.classList.remove('hidden');
        zona.classList.add('con-imagen');
    };
    reader.readAsDataURL(file);
}

function quitarImagen() {
    const input       = document.getElementById('input-imagen');
    const preview     = document.getElementById('preview-imagen');
    const placeholder = document.getElementById('zona-placeholder');
    const btnQuitar   = document.getElementById('btn-quitar-imagen');
    const zona        = document.getElementById('zona-imagen');

    input.value = '';
    preview.src = '';
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    btnQuitar.classList.add('hidden');
    zona.classList.remove('con-imagen');
}
</script>

@include('partials._sidebar')

</body>
</html>