{{-- SIDEBAR OVERLAY --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300"
     onclick="cerrarSidebar()"></div>

{{-- SIDEBAR --}}
<div id="sidebar" class="fixed top-0 bottom-0 w-[280px] bg-white z-50 flex flex-col border-r border-[#c4c5da]/20 overflow-y-auto" style="left: -300px;">

    {{-- CABECERA --}}
    <div class="flex items-center justify-between p-5 border-b border-[#c4c5da]/15">
        <div class="bg-[#f3f3f4] border border-[#c4c5da]/30 px-4 py-3 flex items-center gap-3 flex-1 min-w-0">
            <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0">
                <span class="text-white font-black text-sm">Q</span>
            </div>
            <div class="min-w-0">
                <p class="font-black text-sm tracking-tighter text-[#1a1c1c]">Quivex</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-[#747688] mt-0.5 truncate">
                    {{ Str::limit(Auth::user()->store_name ?? 'Mi tienda', 22) }}
                </p>
            </div>
        </div>
        <button onclick="cerrarSidebar()" class="p-2 hover:bg-[#f3f3f4] transition-colors ml-3 shrink-0">
            <span class="material-symbols-outlined text-[#747688] text-lg">close</span>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <div class="flex-1 p-4 space-y-1">

        <p class="text-[9px] font-black uppercase tracking-widest text-[#c4c5da] px-2 pt-2 pb-3">Navegación</p>

        <a href="{{ route('sales') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('sales') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>Nueva venta
        </a>

        <a href="{{ route('catalog') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('catalog') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">storefront</span>Catálogo
        </a>

        <a href="{{ route('inventario') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('inventario') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">inventory_2</span>Inventario
        </a>

        @if(Auth::user()->role === 'admin')
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest text-[#1737c8] px-2 pt-2 pb-1">Administración</p>

        <a href="{{ route('dashboard') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('dashboard') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">grid_view</span>Dashboard
        </a>

        <a href="{{ route('clientes') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('clientes') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">group</span>Clientes
        </a>

        <a href="{{ route('reporte') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('reporte') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">bar_chart</span>Reportes
        </a>

        <a href="{{ route('usuarios') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('usuarios') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>Usuarios
        </a>

        {{-- PRO --}}
        @php $plan = Auth::user()->plan ?? 'gratis'; @endphp
        @if(in_array($plan, ['pro', 'business']))
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest text-amber-500 px-2 pt-2 pb-1">⭐ Pro</p>

        <a href="{{ route('reporte') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('reporte') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">analytics</span>Reportes avanzados
        </a>
        @endif

        {{-- BUSINESS --}}
        @if($plan === 'business')
        <div class="border-t border-[#c4c5da]/20 my-2"></div>
        <p class="text-[9px] font-black uppercase tracking-widest px-2 pt-2 pb-1" style="color:#7c3aed;">⚡ Business</p>

        <a href="{{ route('almacenes.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('almacenes.*') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">store</span>Almacenes
        </a>

        <a href="{{ route('gastos.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('gastos.*') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">payments</span>Gastos
        </a>

        <a href="{{ route('rentabilidad.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('rentabilidad.*') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">trending_up</span>Rentabilidad
        </a>

        <a href="{{ route('chat.ia.index') }}" onclick="cerrarSidebar()"
           class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-widest {{ request()->routeIs('chat.ia.*') ? 'bg-[#f3f3f4] text-[#1737c8]' : 'text-[#1a1c1c] hover:bg-[#f3f3f4] hover:text-[#1737c8]' }} transition-all">
            <span class="material-symbols-outlined text-[16px]">auto_awesome</span>Chat IA
        </a>
        @endif

        @endif

    </div>

    {{-- PIE --}}
    <div class="border-t border-[#c4c5da]/15 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-[#1737c8] rounded-full flex items-center justify-center shrink-0">
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
        <a href="{{ route('logout') }}"
           class="w-full flex items-center gap-2 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>Cerrar sesión
        </a>
    </div>

</div>

{{-- BOTTOM NAV MÓVIL --}}
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-16 px-2 bg-white/90 backdrop-blur-xl border-t border-[#c4c5da]/20">
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('dashboard') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">grid_view</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Inicio</span>
    </a>
    <a href="{{ route('sales') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('sales') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Ventas</span>
    </a>
    <a href="{{ route('catalog') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('catalog') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">storefront</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Catálogo</span>
    </a>
    <a href="{{ route('inventario') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('inventario') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Stock</span>
    </a>
    <a href="{{ route('clientes') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('clientes') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">group</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Clientes</span>
    </a>
    @else
    <a href="{{ route('sales') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('sales') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Ventas</span>
    </a>
    <a href="{{ route('catalog') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('catalog') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">storefront</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Catálogo</span>
    </a>
    <a href="{{ route('inventario') }}" class="flex flex-col items-center pt-2 min-w-0 flex-1 {{ request()->routeIs('inventario') ? 'text-[#1737c8] border-t-2 border-[#1737c8]' : 'text-[#1a1c1c]/40' }}">
        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
        <span class="text-[9px] uppercase tracking-widest font-bold mt-0.5">Stock</span>
    </a>
    @endif
</nav>

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