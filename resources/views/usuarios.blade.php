<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Usuarios</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    #modal-usuario { display: none; }
    #modal-usuario.activo { display: flex; }
</style>
</head>

<body class="bg-[#f9f9f9]">

@include('partials._nav')

<main class="pt-24 pb-24 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <header class="mb-8 md:mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Administración</span>
            <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Usuarios</h1>
        </div>
        <button onclick="abrirModal()"
                class="bg-[#1737c8] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2 w-fit">
            <span class="material-symbols-outlined text-sm">person_add</span>
            AGREGAR USUARIO
        </button>
    </header>

    <!-- ALERTAS -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 px-5 py-4 flex items-center gap-3 rounded">
            <span class="material-symbols-outlined text-green-600 text-lg">check_circle</span>
            <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 px-5 py-4 flex items-center gap-3 rounded">
            <span class="material-symbols-outlined text-red-600 text-lg">error</span>
            <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- ── TABLA (md+) ── -->
    <section class="hidden md:block bg-white border border-[#c4c5da]/20 overflow-hidden">

        <div class="grid grid-cols-12 px-8 py-4 text-[10px] font-black uppercase tracking-widest text-[#747688] bg-[#f3f3f4] border-b border-[#c4c5da]/20">
            <div class="col-span-1">#</div>
            <div class="col-span-3">Nombre</div>
            <div class="col-span-4">Correo</div>
            <div class="col-span-2">Rol</div>
            <div class="col-span-1">Estado</div>
            <div class="col-span-1 text-right">Acción</div>
        </div>

        <!-- Tú (admin actual) -->
        <div class="grid grid-cols-12 px-8 py-5 items-center border-b border-[#c4c5da]/10 bg-[#1737c8]/5">
            <div class="col-span-1">
                <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center">
                    <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="col-span-3">
                <p class="font-bold text-sm text-[#1a1c1c]">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-[#747688] font-semibold uppercase tracking-wider">Tú</p>
            </div>
            <div class="col-span-4">
                <p class="text-sm text-[#747688] truncate">{{ Auth::user()->email }}</p>
            </div>
            <div class="col-span-2">
                <span class="bg-[#1737c8] text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest">Admin</span>
            </div>
            <div class="col-span-1">
                <span class="bg-green-100 text-green-700 px-3 py-1 text-[10px] font-black uppercase tracking-widest">Activo</span>
            </div>
            <div class="col-span-1 flex justify-end">
                <span class="text-[#c4c5da] text-[10px] font-bold">—</span>
            </div>
        </div>

        @forelse($usuarios as $usuario)
        <div class="grid grid-cols-12 px-8 py-5 items-center border-b border-[#c4c5da]/10 hover:bg-[#f9f9f9] transition-colors">
            <div class="col-span-1">
                <div class="w-8 h-8 bg-[#f3f3f4] border border-[#c4c5da]/30 flex items-center justify-center">
                    <span class="text-[#1a1c1c] font-black text-xs">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="col-span-3">
                <p class="font-bold text-sm text-[#1a1c1c]">{{ $usuario->name }}</p>
            </div>
            <div class="col-span-4">
                <p class="text-sm text-[#747688] truncate">{{ $usuario->email }}</p>
            </div>
            <div class="col-span-2">
                @if($usuario->role === 'admin')
                    <span class="bg-[#1737c8] text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest">Admin</span>
                @else
                    <span class="bg-[#1a1c1c] text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest">Vendedor</span>
                @endif
            </div>
            <div class="col-span-1">
                @if($usuario->estado)
                    <span class="bg-green-100 text-green-700 px-3 py-1 text-[10px] font-black uppercase tracking-widest">Activo</span>
                @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 text-[10px] font-black uppercase tracking-widest">Inactivo</span>
                @endif
            </div>
            <div class="col-span-1 flex justify-end">
                <form method="POST" action="/usuarios/{{ $usuario->id }}"
                      onsubmit="return confirm('¿Eliminar a {{ $usuario->name }}? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-[#747688] hover:text-red-600 hover:bg-red-50 transition-all">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-8 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">group_off</span>
            <p class="text-sm text-[#747688] font-semibold">Aún no has agregado usuarios.</p>
            <p class="text-xs text-[#c4c5da] mt-1">Haz clic en "Agregar usuario" para crear vendedores.</p>
        </div>
        @endforelse
    </section>

    <!-- ── TARJETAS (móvil) ── -->
    <section class="md:hidden flex flex-col gap-4">

        <!-- Tú (admin actual) -->
        <div class="bg-white border border-[#1737c8]/20 p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 bg-[#1737c8] flex items-center justify-center shrink-0 rounded-sm">
                    <span class="text-white font-black text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-bold text-sm text-[#1a1c1c]">{{ Auth::user()->name }}</p>
                        <span class="text-[9px] font-black px-2 py-0.5 bg-[#1737c8] text-white uppercase tracking-widest">Tú</span>
                    </div>
                    <p class="text-[11px] text-[#747688] truncate mt-0.5">{{ Auth::user()->email }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Admin</span>
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Activo</span>
                    </div>
                </div>
            </div>
            <span class="text-[#c4c5da] text-xs font-bold shrink-0">—</span>
        </div>

        @forelse($usuarios as $usuario)
        <div class="bg-white border border-[#c4c5da]/20 p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 bg-[#f3f3f4] border border-[#c4c5da]/30 flex items-center justify-center shrink-0 rounded-sm">
                    <span class="text-[#1a1c1c] font-black text-sm">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-[#1a1c1c]">{{ $usuario->name }}</p>
                    <p class="text-[11px] text-[#747688] truncate mt-0.5">{{ $usuario->email }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @if($usuario->role === 'admin')
                            <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Admin</span>
                        @else
                            <span class="bg-[#1a1c1c] text-white px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Vendedor</span>
                        @endif
                        @if($usuario->estado)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Activo</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Inactivo</span>
                        @endif
                    </div>
                </div>
            </div>
            <form method="POST" action="/usuarios/{{ $usuario->id }}"
                  onsubmit="return confirm('¿Eliminar a {{ $usuario->name }}?')"
                  class="shrink-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 text-[#747688] hover:text-red-600 hover:bg-red-50 transition-all rounded">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
            </form>
        </div>
        @empty
        <div class="bg-white border border-[#c4c5da]/20 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">group_off</span>
            <p class="text-sm text-[#747688] font-semibold">Aún no has agregado usuarios.</p>
            <p class="text-xs text-[#c4c5da] mt-1">Toca "Agregar usuario" para crear vendedores.</p>
        </div>
        @endforelse
    </section>

    <!-- INFO -->
    <div class="mt-6 flex gap-4 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[10px] font-black uppercase tracking-widest">Admin</span>
            <span class="text-xs text-[#747688]">Acceso completo al sistema</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="bg-[#1a1c1c] text-white px-2 py-0.5 text-[10px] font-black uppercase tracking-widest">Vendedor</span>
            <span class="text-xs text-[#747688]">Solo ventas, catálogo e inventario</span>
        </div>
    </div>

</main>

<!-- MODAL CREAR USUARIO -->
<div id="modal-usuario"
     class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     onclick="if(event.target===this) cerrarModal()">

    <div class="bg-white w-full max-w-md">

        <div class="flex items-center justify-between px-6 md:px-8 py-5 md:py-6 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-1">Nuevo usuario</p>
                <h2 class="text-xl font-bold tracking-tight">Agregar al equipo</h2>
            </div>
            <button onclick="cerrarModal()" class="p-2 hover:bg-[#f3f3f4] transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('usuarios.store') }}" class="px-6 md:px-8 py-6 flex flex-col gap-5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label class="text-[10px] font-bold tracking-widest text-[#747688] uppercase mb-2 block">Nombre completo</label>
                <input name="name" value="{{ old('name') }}" type="text" placeholder="Nombre del usuario" required
                       class="w-full border border-[#c4c5da]/40 px-4 py-3 text-sm outline-none focus:border-[#1737c8] focus:ring-2 focus:ring-[#1737c8]/20 transition-all">
            </div>

            <div>
                <label class="text-[10px] font-bold tracking-widest text-[#747688] uppercase mb-2 block">Correo electrónico</label>
                <input name="email" value="{{ old('email') }}" type="email" placeholder="correo@ejemplo.com" required
                       class="w-full border border-[#c4c5da]/40 px-4 py-3 text-sm outline-none focus:border-[#1737c8] focus:ring-2 focus:ring-[#1737c8]/20 transition-all">
            </div>

            <div>
                <label class="text-[10px] font-bold tracking-widest text-[#747688] uppercase mb-2 block">Contraseña</label>
                <input name="password" type="password" placeholder="Mínimo 8 caracteres" required
                       class="w-full border border-[#c4c5da]/40 px-4 py-3 text-sm outline-none focus:border-[#1737c8] focus:ring-2 focus:ring-[#1737c8]/20 transition-all">
            </div>

            <div>
                <label class="text-[10px] font-bold tracking-widest text-[#747688] uppercase mb-2 block">Rol</label>
                <select name="role" required
                        class="w-full border border-[#c4c5da]/40 px-4 py-3 text-sm outline-none bg-white focus:border-[#1737c8] focus:ring-2 focus:ring-[#1737c8]/20 transition-all">
                    <option value="vendedor" {{ old('role') == 'vendedor' ? 'selected' : '' }}>Vendedor — ventas, catálogo e inventario</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin — acceso completo</option>
                </select>
            </div>

            <div class="flex gap-3 mt-2">
                <button type="button" onclick="cerrarModal()"
                        class="flex-1 py-3 border border-[#c4c5da]/40 text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-3 bg-[#1737c8] text-white text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all">
                    Crear usuario
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
    <a href="/inventario" class="flex flex-col items-center text-[#1a1c1c]/50 pt-2">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Stock</span>
    </a>
    <a href="/usuarios" class="flex flex-col items-center text-[#1737c8] border-t-2 border-[#1737c8] pt-2">
        <span class="material-symbols-outlined">group</span>
        <span class="text-[10px] uppercase tracking-widest font-semibold mt-1">Equipo</span>
    </a>
</nav>

<script>
function abrirModal() {
    document.getElementById('modal-usuario').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModal() {
    document.getElementById('modal-usuario').classList.remove('activo');
    document.body.style.overflow = '';
}
@if ($errors->any())
    window.addEventListener('DOMContentLoaded', () => abrirModal());
@endif
</script>

@include('partials._sidebar')

</body>
</html>