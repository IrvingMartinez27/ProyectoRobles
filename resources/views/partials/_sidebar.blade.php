{{-- SIDEBAR OVERLAY --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300"
     onclick="cerrarSidebar()"></div>

{{-- SIDEBAR --}}
<div id="sidebar" class="fixed top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 overflow-y-auto" style="left: -300px;">

    {{-- CABECERA --}}
    <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
        <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 px-4 py-3 flex items-center gap-3 flex-1">
            <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0">
                <span class="text-white font-black text-sm">Q</span>
            </div>
            <div>
                <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Quivex</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5">{{ Auth::user()->store_name ?? 'Mi tienda' }}</p>
            </div>
        </div>
        <button onclick="cerrarSidebar()" class="p-2 hover:bg-[#f3f3f4] transition-colors ml-3">
            <span class="material-symbols-outlined text-[#747688] text-lg">close</span>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <div class="flex-1 p-4 space-y-1">

        {{-- VISIBLE PARA TODOS --}}
        <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-2 pt-2 pb-3">Navegación</p>

        <a href="{{ route('sales') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>
            Nueva venta
        </a>

        <a href="{{ route('catalog') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">storefront</span>
            Catálogo
        </a>

        <a href="{{ route('inventario') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">inventory_2</span>
            Inventario
        </a>

        {{-- SOLO ADMIN --}}
        @if(Auth::user()->role === 'admin')

        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest text-[#1737c8] px-2 pt-2 pb-1">Administración</p>

        <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">grid_view</span>
            Dashboard
        </a>

        <a href="{{ route('clientes') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">group</span>
            Clientes
        </a>

        <a href="{{ route('reporte') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">bar_chart</span>
            Reportes
        </a>

        <a href="{{ route('usuarios') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8] transition-all">
            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
            Usuarios
        </a>

        @endif

    </div>

    {{-- PIE — usuario + logout --}}
    <div class="border-t border-[#c4c5da]/15 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 bg-[#1737c8] rounded-full flex items-center justify-center shrink-0">
                <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            </div>
            <div>
                <p class="text-sm font-bold text-[#1a1c1c]">{{ Auth::user()->name ?? 'Usuario' }}</p>
                <p class="text-[10px] text-[#747688]">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>
        <a href="{{ route('logout') }}"
           class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>
            Cerrar sesión
        </a>
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
</script>