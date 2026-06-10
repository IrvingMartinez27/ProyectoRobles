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
    body { font-family:'Inter',sans-serif; background:#f9f9f9; color:#1a1c1c; min-height:100dvh; }
    .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }

    #modal-usuario { display:none; }
    #modal-usuario.activo { display:flex; }

    @keyframes fadeInUp  { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    @keyframes popIn     { 0%{opacity:0;transform:scale(0.92)} 70%{transform:scale(1.02)} 100%{opacity:1;transform:scale(1)} }
    @keyframes slideRow  { from{opacity:0;transform:translateX(-6px)} to{opacity:1;transform:translateX(0)} }
    @keyframes modalIn   { from{opacity:0;transform:scale(0.95) translateY(10px)} to{opacity:1;transform:scale(1) translateY(0)} }

    .anim-header { animation:fadeInUp 0.5s ease both; }
    .anim-1 { animation-delay:0.05s; }
    .anim-2 { animation-delay:0.10s; }
    .anim-3 { animation-delay:0.15s; }

    .user-row { animation:slideRow 0.35s ease both; transition:background 0.15s; }
    .user-row:nth-child(1){animation-delay:0.04s} .user-row:nth-child(2){animation-delay:0.08s}
    .user-row:nth-child(3){animation-delay:0.12s} .user-row:nth-child(4){animation-delay:0.16s}
    .user-row:nth-child(n+5){animation-delay:0.20s}
    .user-row:hover { background:rgba(23,55,200,0.03)!important; }

    .user-card { animation:popIn 0.4s cubic-bezier(0.34,1.56,0.64,1) both; transition:transform .2s ease, box-shadow .2s ease; }
    .user-card:nth-child(1){animation-delay:0.04s} .user-card:nth-child(2){animation-delay:0.09s}
    .user-card:nth-child(3){animation-delay:0.14s} .user-card:nth-child(n+4){animation-delay:0.19s}
    .user-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(23,55,200,.08); }

    .modal-content { animation:modalIn 0.25s ease both; }

    /* DARK MODE */
    [data-theme="dark"] body                     { background:#0f1012!important;color:#f3f3f4!important; }
    [data-theme="dark"] .bg-white                { background:#1e2022!important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\]         { background:#0f1012!important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\]         { background:#141618!important; }
    [data-theme="dark"] .text-\[\#1a1c1c\]       { color:#f3f3f4!important; }
    [data-theme="dark"] .text-\[\#747688\]        { color:#9496a8!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20  { border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10  { border-color:rgba(255,255,255,.05)!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/30  { border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40  { border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] .user-row:hover           { background:rgba(23,55,200,.08)!important; }
    [data-theme="dark"] .hover\:bg-\[\#f3f3f4\]:hover { background:#141618!important; }
    [data-theme="dark"] .hover\:bg-red-50:hover   { background:rgba(239,68,68,.08)!important; }
    [data-theme="dark"] .thead-row                { background:#141618!important;border-color:rgba(255,255,255,.05)!important; }
    [data-theme="dark"] .thead-row span           { color:#9496a8!important; }
    [data-theme="dark"] input,[data-theme="dark"] select { background:#141618!important;color:#f3f3f4!important;border-color:rgba(255,255,255,.1)!important; }
    [data-theme="dark"] input::placeholder        { color:#9496a8!important; }
    [data-theme="dark"] .bg-green-50              { background:rgba(34,197,94,.08)!important; }
    [data-theme="dark"] .bg-green-100             { background:rgba(34,197,94,.12)!important; }
    [data-theme="dark"] .bg-red-50                { background:rgba(239,68,68,.08)!important; }
    [data-theme="dark"] .bg-red-100               { background:rgba(239,68,68,.12)!important; }
    [data-theme="dark"] #modal-usuario .bg-white  { background:#1e2022!important; }
    [data-theme="dark"] .user-card                { background:#1e2022!important;border-color:rgba(255,255,255,.08)!important; }
    [data-theme="dark"] .you-card                 { background:rgba(23,55,200,.1)!important;border-color:rgba(23,55,200,.25)!important; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

<main class="pt-24 pb-28 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <header class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 anim-header anim-1">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Administración</span>
            <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Usuarios</h1>
        </div>
        @if($plan !== 'gratis')
        <button onclick="abrirModal()"
                class="bg-[#1737c8] text-white px-5 py-3 text-sm font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all flex items-center gap-2 w-fit"
                style="box-shadow:0 4px 16px rgba(23,55,200,.25)">
            <span class="material-symbols-outlined text-sm">person_add</span>Agregar usuario
        </button>
        @else
        <button onclick="document.getElementById('banner-gratis').scrollIntoView({behavior:'smooth'})"
                class="bg-[#f3f3f4] text-[#747688] px-5 py-3 text-sm font-black uppercase tracking-widest rounded-xl flex items-center gap-2 w-fit cursor-not-allowed">
            <span class="material-symbols-outlined text-sm">lock</span>Agregar — Pro
        </button>
        @endif
    </header>

    {{-- BANNER GRATIS --}}
    @if($plan === 'gratis')
    <div id="banner-gratis" class="mb-6 bg-[#1737c8] rounded-2xl px-6 py-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 anim-header anim-2">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-white">workspace_premium</span>
            </div>
            <div>
                <p class="text-sm font-black text-white">Plan Gratis — solo tú como usuario</p>
                <p class="text-xs text-white/60 mt-0.5">Con el Plan Pro puedes agregar vendedores ilimitados.</p>
            </div>
        </div>
        <a href="/pago/planes" class="shrink-0 bg-white text-[#1737c8] px-5 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
            Ver Plan Pro →
        </a>
    </div>
    @endif

    {{-- TABLA DESKTOP --}}
    <section class="hidden md:block bg-white rounded-2xl border border-[#c4c5da]/20 overflow-hidden anim-header anim-3">
        {{-- Thead --}}
        <div class="thead-row grid grid-cols-12 px-8 py-4 bg-[#f3f3f4] border-b border-[#c4c5da]/20">
            <span class="col-span-1 text-[10px] font-black uppercase tracking-widest text-[#747688]">#</span>
            <span class="col-span-3 text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre</span>
            <span class="col-span-4 text-[10px] font-black uppercase tracking-widest text-[#747688]">Correo</span>
            <span class="col-span-2 text-[10px] font-black uppercase tracking-widest text-[#747688]">Rol</span>
            <span class="col-span-1 text-[10px] font-black uppercase tracking-widest text-[#747688]">Estado</span>
            <span class="col-span-1 text-right text-[10px] font-black uppercase tracking-widest text-[#747688]">Acción</span>
        </div>

        {{-- Tú --}}
        <div class="user-row grid grid-cols-12 px-8 py-5 items-center border-b border-[#c4c5da]/10 bg-[#1737c8]/5">
            <div class="col-span-1">
                <div class="w-9 h-9 bg-[#1737c8] rounded-xl flex items-center justify-center">
                    <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="col-span-3">
                <p class="font-bold text-sm text-[#1a1c1c]">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-[#747688] font-semibold uppercase tracking-wider">Tú</p>
            </div>
            <div class="col-span-4"><p class="text-sm text-[#747688] truncate">{{ Auth::user()->email }}</p></div>
            <div class="col-span-2">
                <span class="bg-[#1737c8] text-white px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Admin</span>
            </div>
            <div class="col-span-1">
                <span class="bg-green-100 text-green-700 px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Activo</span>
            </div>
            <div class="col-span-1 flex justify-end">
                <span class="text-[#c4c5da] text-[10px] font-bold">—</span>
            </div>
        </div>

        @forelse($usuarios as $usuario)
        <div class="user-row grid grid-cols-12 px-8 py-5 items-center border-b border-[#c4c5da]/10">
            <div class="col-span-1">
                <div class="w-9 h-9 bg-[#f3f3f4] border border-[#c4c5da]/30 rounded-xl flex items-center justify-center">
                    <span class="text-[#1a1c1c] font-black text-xs">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="col-span-3"><p class="font-bold text-sm text-[#1a1c1c]">{{ $usuario->name }}</p></div>
            <div class="col-span-4"><p class="text-sm text-[#747688] truncate">{{ $usuario->email }}</p></div>
            <div class="col-span-2">
                @if($usuario->role === 'admin')
                <span class="bg-[#1737c8] text-white px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Admin</span>
                @else
                <span class="bg-[#1a1c1c] text-white px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Vendedor</span>
                @endif
            </div>
            <div class="col-span-1">
                @if($usuario->estado)
                <span class="bg-green-100 text-green-700 px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Activo</span>
                @else
                <span class="bg-red-100 text-red-700 px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full">Inactivo</span>
                @endif
            </div>
            <div class="col-span-1 flex justify-end">
                <form method="POST" action="/usuarios/{{ $usuario->id }}"
                      onsubmit="return confirm('¿Eliminar a {{ addslashes($usuario->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#747688] hover:text-red-600 hover:bg-red-50 transition-all">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-8 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">group_off</span>
            <p class="text-sm text-[#747688] font-semibold">Aún no has agregado usuarios.</p>
            @if($plan !== 'gratis')
            <p class="text-xs text-[#c4c5da] mt-1">Haz clic en "Agregar usuario" para crear vendedores.</p>
            @endif
        </div>
        @endforelse
    </section>

    {{-- CARDS MÓVIL --}}
    <section class="md:hidden flex flex-col gap-3 anim-header anim-3">

        {{-- Tú --}}
        <div class="you-card user-card bg-white rounded-2xl border border-[#1737c8]/20 p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-11 h-11 bg-[#1737c8] rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-white font-black text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-black text-sm text-[#1a1c1c]">{{ Auth::user()->name }}</p>
                        <span class="text-[9px] font-black px-2 py-0.5 bg-[#1737c8] text-white rounded-full uppercase">Tú</span>
                    </div>
                    <p class="text-[11px] text-[#747688] truncate mt-0.5">{{ Auth::user()->email }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Admin</span>
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Activo</span>
                    </div>
                </div>
            </div>
            <span class="text-[#c4c5da] text-xs font-bold shrink-0">—</span>
        </div>

        @forelse($usuarios as $usuario)
        <div class="user-card bg-white rounded-2xl border border-[#c4c5da]/20 p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-11 h-11 bg-[#f3f3f4] border border-[#c4c5da]/30 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-[#1a1c1c] font-black text-sm">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-sm text-[#1a1c1c]">{{ $usuario->name }}</p>
                    <p class="text-[11px] text-[#747688] truncate mt-0.5">{{ $usuario->email }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @if($usuario->role === 'admin')
                        <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Admin</span>
                        @else
                        <span class="bg-[#1a1c1c] text-white px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Vendedor</span>
                        @endif
                        @if($usuario->estado)
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Activo</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 text-[9px] font-black uppercase rounded-full">Inactivo</span>
                        @endif
                    </div>
                </div>
            </div>
            <form method="POST" action="/usuarios/{{ $usuario->id }}"
                  onsubmit="return confirm('¿Eliminar a {{ addslashes($usuario->name) }}?')"
                  class="shrink-0">
                @csrf @method('DELETE')
                <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#747688] hover:text-red-600 hover:bg-red-50 transition-all">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-[#c4c5da]/20 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c4c5da] block mb-3">group_off</span>
            <p class="text-sm text-[#747688] font-semibold">Aún no has agregado usuarios.</p>
        </div>
        @endforelse
    </section>

    {{-- LEYENDA --}}
    <div class="mt-6 flex gap-4 flex-wrap anim-header anim-3">
        <div class="flex items-center gap-2">
            <span class="bg-[#1737c8] text-white px-2 py-0.5 text-[10px] font-black uppercase rounded-full">Admin</span>
            <span class="text-xs text-[#747688]">Acceso completo</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="bg-[#1a1c1c] text-white px-2 py-0.5 text-[10px] font-black uppercase rounded-full">Vendedor</span>
            <span class="text-xs text-[#747688]">Solo ventas, catálogo e inventario</span>
        </div>
    </div>

</main>

{{-- MODAL CREAR USUARIO --}}
@if($plan !== 'gratis')
<div id="modal-usuario" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     onclick="if(event.target===this) cerrarModal()">
    <div class="modal-content bg-white rounded-2xl w-full max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#c4c5da]/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-[#1737c8] mb-0.5">Nuevo usuario</p>
                <h2 class="text-xl font-black tracking-tight text-[#1a1c1c]">Agregar al equipo</h2>
            </div>
            <button onclick="cerrarModal()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-[#f3f3f4] transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('usuarios.store') }}" class="px-6 py-6 flex flex-col gap-4">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                @foreach($errors->all() as $error)
                <p class="text-xs text-red-600 font-medium">{{ $error }}</p>
                @endforeach
            </div>
            @endif
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Nombre completo</label>
                <input name="name" value="{{ old('name') }}" type="text" placeholder="Nombre del usuario" required
                       class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] transition-all"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Correo electrónico</label>
                <input name="email" value="{{ old('email') }}" type="email" placeholder="correo@ejemplo.com" required
                       class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] transition-all"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Contraseña</label>
                <input name="password" type="password" placeholder="Mínimo 8 caracteres" required
                       class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] transition-all"/>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Rol</label>
                <select name="role" required
                        class="border border-[#c4c5da]/40 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9] transition-all">
                    <option value="vendedor" {{ old('role') == 'vendedor' ? 'selected' : '' }}>Vendedor — ventas, catálogo e inventario</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin — acceso completo</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="cerrarModal()"
                        class="flex-1 py-3.5 border border-[#c4c5da]/40 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f3f3f4] transition-all text-[#747688]">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-3.5 bg-[#1737c8] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all"
                        style="box-shadow:0 4px 16px rgba(23,55,200,.25)">
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@include('partials._sidebar')

<script>
function abrirModal() {
    document.getElementById('modal-usuario')?.classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModal() {
    document.getElementById('modal-usuario')?.classList.remove('activo');
    document.body.style.overflow = '';
}

@if($errors->any() && $plan !== 'gratis')
window.addEventListener('DOMContentLoaded', () => abrirModal());
@endif

// Flash toasts
@php $flashS = session('success'); $flashE = session('error'); @endphp
@if($flashS)
document.addEventListener('DOMContentLoaded', () => showToast('¡Listo!', @json($flashS), 'green', 5000));
@endif
@if($flashE)
document.addEventListener('DOMContentLoaded', () => showToast('Error', @json($flashE), 'red', 5000));
@endif
</script>
</body>
</html>