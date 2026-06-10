{{-- SIDEBAR OVERLAY --}}
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-all duration-300 ease-in-out"
     style="opacity:0;visibility:hidden;pointer-events:none;"
     onclick="cerrarSidebar()"></div>

{{-- SIDEBAR --}}
<div id="sidebar"
     class="fixed top-0 bottom-0 left-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 overflow-y-auto"
     style="transform:translateX(-100%);transition:transform 0.35s cubic-bezier(0.32,0.72,0,1);will-change:transform;">

    {{-- CABECERA --}}
    <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
        <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 rounded-xl px-4 py-3 flex items-center gap-3 flex-1 min-w-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                <img src="/images/quivex-logo.png" alt="Quivex"
                     class="w-8 h-8 object-contain"
                     id="sidebar-logo"
                     onerror="this.style.display='none';document.getElementById('sidebar-logo-fallback').style.display='flex'"/>
                <div id="sidebar-logo-fallback"
                     class="w-8 h-8 rounded-lg bg-[#1737c8] items-center justify-center hidden">
                    <span class="text-white font-black text-sm">Q</span>
                </div>
            </div>
            <div class="min-w-0">
                <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Quivex</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5 truncate">
                    {{ Str::limit(Auth::user()->store_name ?? 'Mi tienda', 22) }}
                </p>
            </div>
        </div>
        <button onclick="cerrarSidebar()" class="p-2 rounded-lg hover:bg-[#f3f3f4] transition-colors ml-3 shrink-0">
            <span class="material-symbols-outlined text-[#747688] text-lg">close</span>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <div class="flex-1 p-4 space-y-0.5">

        <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-2 pt-2 pb-3">Navegación</p>

        <a href="{{ route('sales') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('sales') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>Nueva venta
        </a>

        <a href="{{ route('catalog') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('catalog') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">storefront</span>Catálogo
        </a>

        <a href="{{ route('inventario') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('inventario') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">inventory_2</span>Inventario
        </a>

        @if(Auth::user()->role === 'admin')
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest text-[#1737c8] px-2 pt-2 pb-1">Administración</p>

        <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">grid_view</span>Vista general
        </a>

        <a href="{{ route('clientes') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('clientes') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">group</span>Clientes
        </a>

        <a href="{{ route('reporte') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('reporte') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">bar_chart</span>Reportes
        </a>

        <a href="{{ route('usuarios') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('usuarios') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>Usuarios
        </a>

        {{-- PRO --}}
        @php $plan = Auth::user()->plan ?? 'gratis'; @endphp
        @if(in_array($plan, ['pro', 'business']))
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest text-amber-500 px-2 pt-2 pb-1">⭐ Pro</p>

        <a href="{{ route('reporte') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('reporte') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">analytics</span>Reportes avanzados
        </a>
        @endif

        {{-- BUSINESS --}}
        @if($plan === 'business')
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest px-2 pt-2 pb-1" style="color:#7c3aed;">⚡ Business</p>

        <a href="{{ route('almacenes.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('almacenes.*') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">store</span>Almacenes
        </a>

        <a href="{{ route('para.entrega') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('para.entrega') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">local_shipping</span>Para Entrega
        </a>

        <a href="{{ route('gastos.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('gastos.*') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">payments</span>Gastos
        </a>

        <a href="{{ route('rentabilidad.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('rentabilidad.*') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">trending_up</span>Rentabilidad
        </a>

        <a href="{{ route('chat.ia.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-150 {{ request()->routeIs('chat.ia.*') ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }}">
            <span class="material-symbols-outlined text-[16px]">auto_awesome</span>Chat IA
        </a>
        @endif

        @endif

    </div>

    {{-- PIE --}}
    <div class="border-t border-[#c4c5da]/15 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-[#1737c8] flex items-center justify-center shrink-0">
                <span class="text-white font-black text-xs">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-[#1a1c1c] truncate">
                    {{ Str::of(Auth::user()->name ?? 'Usuario')->explode(' ')->first() }}
                    {{ optional(Str::of(Auth::user()->name ?? '')->explode(' '))->get(1, '') }}
                </p>
                <p class="text-[10px] text-[#747688] truncate">
                    {{ Auth::user()->role === 'admin' ? 'Administrador' : 'Vendedor' }}
                    @if(isset(Auth::user()->plan))
                    · <span class="{{ Auth::user()->plan === 'gratis' ? 'text-[#747688]' : (Auth::user()->plan === 'business' ? 'font-bold uppercase' : 'text-[#1737c8] font-bold uppercase') }}"
                            @if(Auth::user()->plan === 'business') style="color:#7c3aed;" @endif>
                        {{ Auth::user()->plan }}
                      </span>
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('logout') }}" id="btn-logout"
           class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>Cerrar sesión
        </a>
    </div>

</div>

{{-- DARK MODE sidebar --}}
<style>
    [data-theme="dark"] #sidebar { background-color: #1a1c1e !important; border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] #sidebar .text-\[\#1a1c1c\] { color: #f3f3f4 !important; }
    [data-theme="dark"] #sidebar .text-\[\#747688\] { color: #9496a8 !important; }
    [data-theme="dark"] #sidebar .bg-\[\#f3f3f4\] { background-color: #141618 !important; }
    [data-theme="dark"] #sidebar .border-\[\#c4c5da\]\/20 { border-color: rgba(255,255,255,0.06) !important; }
    [data-theme="dark"] #sidebar .border-\[\#c4c5da\]\/15 { border-color: rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] #sidebar .hover\:bg-\[\#f3f3f4\]:hover { background-color: rgba(255,255,255,0.06) !important; }
    [data-theme="dark"] #sidebar .bg-red-50 { background-color: rgba(239,68,68,0.08) !important; }
    [data-theme="dark"] #sidebar .border-red-100 { border-color: rgba(239,68,68,0.2) !important; }
    [data-theme="dark"] #sidebar-logo { filter: invert(1) brightness(1.2); }
    [data-theme="dark"] #sidebar-overlay { background: rgba(0,0,0,0.7) !important; }
    [data-theme="dark"] nav.fixed.bottom-0 { background: rgba(15,16,18,0.95) !important; border-color: rgba(255,255,255,0.06) !important; }
    [data-theme="dark"] nav.fixed.bottom-0 a { color: rgba(243,243,244,0.4) !important; }
    [data-theme="dark"] nav.fixed.bottom-0 a.text-\[\#1737c8\] { color: #1737c8 !important; }
</style>

{{-- BOTTOM NAV MÓVIL --}}
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-2 bg-white/90 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('dashboard') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">grid_view</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Inicio</span>
    </a>
    <a href="{{ route('sales') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('sales') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Ventas</span>
    </a>
    <a href="{{ route('inventario') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('inventario') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Stock</span>
    </a>
    @if((Auth::user()->plan ?? 'gratis') === 'business')
    <a href="{{ route('para.entrega') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('para.entrega') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">local_shipping</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Entrega</span>
    </a>
    @endif
    <a href="{{ route('clientes') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('clientes') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">group</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Clientes</span>
    </a>
    @else
    <a href="{{ route('sales') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('sales') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Ventas</span>
    </a>
    <a href="{{ route('catalog') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('catalog') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">storefront</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Catálogo</span>
    </a>
    <a href="{{ route('inventario') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 transition-colors {{ request()->routeIs('inventario') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Stock</span>
    </a>
    @endif
</nav>

<script>
function abrirSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.style.transform     = 'translateX(0)';
    overlay.style.opacity       = '1';
    overlay.style.visibility    = 'visible';
    overlay.style.pointerEvents = 'auto';
    document.body.style.overflow = 'hidden';
}
function cerrarSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.style.transform     = 'translateX(-100%)';
    overlay.style.opacity       = '0';
    overlay.style.visibility    = 'hidden';
    overlay.style.pointerEvents = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarSidebar(); });
</script>