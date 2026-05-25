{{-- NAV SUPERIOR --}}
<nav class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20">
    <div class="flex items-center gap-4">
        <button onclick="abrirSidebar()" class="hover:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-[#1737c8]">menu</span>
        </button>
        <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('sales') }}"
           class="font-black tracking-tighter text-xl text-[#1a1c1c] hover:opacity-70 transition-opacity">
            Qui<span class="text-[#1737c8]">vex</span>
        </a>
    </div>

    <div class="relative group">
        <button class="flex items-center gap-2 hover:opacity-80 transition-all">
            <span class="material-symbols-outlined text-[#1737c8]">account_circle</span>
        </button>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-[#c4c5da]/20 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Sesión activa</p>
                <p class="text-sm font-bold text-[#1a1c1c] mt-1">{{ Auth::user()->name ?? 'Usuario' }}</p>
            </div>
            <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                <p class="text-[10px] text-[#747688] font-semibold">{{ Auth::user()->email ?? '' }}</p>
                <p class="text-[9px] font-black uppercase tracking-widest mt-1 {{ Auth::user()->role === 'admin' ? 'text-[#1737c8]' : 'text-[#747688]' }}">
                    {{ Auth::user()->role === 'admin' ? 'Administrador' : 'Vendedor' }}
                </p>
            </div>
            <a href="{{ route('logout') }}"
               class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 transition-colors">
                <span class="material-symbols-outlined text-sm">logout</span>
                Cerrar sesión
            </a>
        </div>
    </div>
</nav>