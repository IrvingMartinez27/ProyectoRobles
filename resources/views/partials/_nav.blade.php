{{-- DARK MODE GLOBAL - aplica antes de renderizar --}}
<script>
(function() {
    const t = localStorage.getItem('qvx-app-theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
})();
</script>

<style>
/* ── DARK MODE GLOBAL ────────────────────────────────────────── */
[data-theme="dark"] {
    --bg: #0f1012; --bg2: #1a1c1e; --bg3: #141618; --card: #1e2022;
    --text: #f3f3f4; --text2: #9496a8; --border: rgba(255,255,255,0.08);
    --nav-bg: rgba(15,16,18,0.92);
}
[data-theme="light"] {
    --bg: #f9f9f9; --bg2: #ffffff; --bg3: #f3f3f4; --card: #ffffff;
    --text: #1a1c1c; --text2: #747688; --border: rgba(196,197,218,0.2);
    --nav-bg: rgba(255,255,255,0.85);
}
[data-theme="dark"] body { background-color: var(--bg) !important; color: var(--text) !important; }
[data-theme="dark"] main { background-color: var(--bg) !important; }
[data-theme="dark"] .bg-white { background-color: var(--card) !important; }
[data-theme="dark"] .bg-\[\#f9f9f9\] { background-color: var(--bg) !important; }
[data-theme="dark"] .bg-\[\#f3f3f4\] { background-color: var(--bg3) !important; }
[data-theme="dark"] .bg-white\/80 { background: var(--nav-bg) !important; }
[data-theme="dark"] .text-\[\#1a1c1c\] { color: var(--text) !important; }
[data-theme="dark"] .text-\[\#747688\] { color: var(--text2) !important; }
[data-theme="dark"] .text-\[\#c4c5da\] { color: rgba(255,255,255,0.2) !important; }
[data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color: var(--border) !important; }
[data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color: rgba(255,255,255,0.05) !important; }
[data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color: rgba(255,255,255,0.1) !important; }
[data-theme="dark"] .border-\[\#c4c5da\]\/30 { border-color: rgba(255,255,255,0.08) !important; }
[data-theme="dark"] .border-\[\#c4c5da\]\/15 { border-color: rgba(255,255,255,0.06) !important; }
[data-theme="dark"] nav.fixed { background: var(--nav-bg) !important; border-color: var(--border) !important; }
[data-theme="dark"] .hover\:bg-\[\#f3f3f4\]:hover { background-color: var(--bg3) !important; }
[data-theme="dark"] .hover\:bg-\[\#f9f9f9\]:hover { background-color: var(--bg2) !important; }
[data-theme="dark"] .hover\:bg-\[\#1a1c1c\]:hover { background-color: #f3f3f4 !important; color: #1a1c1c !important; }
[data-theme="dark"] input, [data-theme="dark"] select, [data-theme="dark"] textarea {
    background-color: var(--bg3) !important; color: var(--text) !important; border-color: var(--border) !important;
}
[data-theme="dark"] input::placeholder, [data-theme="dark"] textarea::placeholder { color: var(--text2) !important; }
[data-theme="dark"] .divide-\[\#c4c5da\]\/10 > * { border-color: rgba(255,255,255,0.05) !important; }
[data-theme="dark"] #modal-stock .bg-white, [data-theme="dark"] #modal-upgrade .bg-white,
[data-theme="dark"] #modal-nueva-venta .bg-white, [data-theme="dark"] #modal-usuario .bg-white,
[data-theme="dark"] #modal-efectivo .bg-white { background-color: var(--card) !important; }
[data-theme="dark"] nav.fixed.bottom-0 { background: rgba(15,16,18,0.92) !important; border-color: var(--border) !important; }
[data-theme="dark"] nav.fixed.bottom-0 a { color: var(--text2) !important; }
[data-theme="dark"] nav.fixed.bottom-0 a.text-\[\#1737c8\] { color: #1737c8 !important; }
[data-theme="dark"] #sidebar { background-color: var(--bg2) !important; border-color: var(--border) !important; }
[data-theme="dark"] .bg-\[\#1a1c1c\] { background-color: #0a0b0c !important; }
[data-theme="dark"] .bg-\[\#f9f9f9\].border-b { background-color: var(--bg3) !important; }
[data-theme="dark"] .receipt-texture { background-image: radial-gradient(rgba(255,255,255,0.02) 1px, transparent 0) !important; }
[data-theme="dark"] #notif-panel { background-color: var(--card) !important; border-color: var(--border) !important; }
[data-theme="dark"] .notif-item:hover { background-color: var(--bg3) !important; }

/* ── ANIMACIONES GLOBALES ────────────────────────────────────── */
@keyframes fadeInUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
@keyframes countUp { from { opacity:0; transform:scale(0.85); } to { opacity:1; transform:scale(1); } }
@keyframes slideInLeft { from { opacity:0; transform:translateX(-12px); } to { opacity:1; transform:translateX(0); } }
@keyframes popIn { 0% { opacity:0; transform:scale(0.8); } 70% { transform:scale(1.05); } 100% { opacity:1; transform:scale(1); } }
@keyframes toastIn { from { opacity:0; transform:translateX(100%); } to { opacity:1; transform:translateX(0); } }
@keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(100%); } }
@keyframes notifBell { 0%,100% { transform:rotate(0); } 20% { transform:rotate(-15deg); } 40% { transform:rotate(15deg); } 60% { transform:rotate(-10deg); } 80% { transform:rotate(10deg); } }

.anim-fade   { opacity:0; animation:fadeInUp 0.5s ease forwards; }
.anim-fade-1 { animation-delay:0.05s; } .anim-fade-2 { animation-delay:0.10s; }
.anim-fade-3 { animation-delay:0.15s; } .anim-fade-4 { animation-delay:0.20s; }
.anim-fade-5 { animation-delay:0.25s; } .anim-fade-6 { animation-delay:0.30s; }
.anim-pop   { opacity:0; animation:popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards; }
.anim-pop-1 { animation-delay:0.05s; } .anim-pop-2 { animation-delay:0.12s; }
.anim-pop-3 { animation-delay:0.19s; } .anim-pop-4 { animation-delay:0.26s; }
.anim-pop-5 { animation-delay:0.33s; } .anim-pop-6 { animation-delay:0.40s; }
.anim-slide   { opacity:0; animation:slideInLeft 0.4s ease forwards; }
.anim-slide-1 { animation-delay:0.05s; } .anim-slide-2 { animation-delay:0.12s; } .anim-slide-3 { animation-delay:0.19s; }
.section-reveal { opacity:0; transform:translateY(20px); transition:opacity 0.5s ease, transform 0.5s ease; }
.section-reveal.visible { opacity:1; transform:translateY(0); }
.hover-lift { transition:transform 0.2s ease, box-shadow 0.2s ease !important; }
.hover-lift:hover { transform:translateY(-2px) !important; box-shadow:0 8px 24px rgba(23,55,200,0.1) !important; }
.tilt-card { will-change:transform; transition:transform 0.15s ease, box-shadow 0.15s ease; }
.pulse-dot { animation:pulseDot 2s infinite; }
@keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.5;transform:scale(0.85);} }
.num-counter { display:inline-block; }

/* ── NAV ─────────────────────────────────────────────────────── */
.nav-plan-badge {
    display:inline-flex; align-items:center; gap:3px; padding:3px 8px;
    border-radius:999px; font-size:9px; font-weight:900;
    text-transform:uppercase; letter-spacing:0.12em; line-height:1;
}
.nav-btn-icon {
    width:36px; height:36px; border-radius:10px; display:flex;
    align-items:center; justify-content:center;
    border:1px solid rgba(196,197,218,0.3); transition:all 0.2s ease; color:#747688;
    position:relative;
}
.nav-btn-icon:hover { border-color:#1737c8; color:#1737c8; }
[data-theme="dark"] .nav-btn-icon { border-color:rgba(255,255,255,0.1); color:#9496a8; }
[data-theme="dark"] .nav-btn-icon:hover { border-color:#1737c8; color:#1737c8; background:rgba(23,55,200,0.1); }

/* Badge campana */
.notif-badge {
    position:absolute; top:-4px; right:-4px;
    min-width:16px; height:16px; border-radius:999px;
    background:#ef4444; color:#fff; font-size:9px; font-weight:900;
    display:flex; align-items:center; justify-content:center; padding:0 3px;
    border:2px solid #fff; display:none;
}
[data-theme="dark"] .notif-badge { border-color:#0f1012; }
.notif-badge.visible { display:flex; }
.bell-ring { animation:notifBell 0.6s ease; }

/* Panel notificaciones */
#notif-panel {
    /* Responsive width */
    position:absolute; right:0; top:calc(100% + 10px);
    width:calc(100vw - 2rem); max-width: 340px; 
    background:#fff; border:1px solid rgba(196,197,218,0.2);
    border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.12);
    z-index:100; overflow:hidden;
    opacity:0; visibility:hidden; transform:translateY(-8px) scale(0.97);
    transition:all 0.2s cubic-bezier(0.34,1.56,0.64,1);
    transform-origin: top right;
}
#notif-panel.open {
    opacity:1; visibility:visible; transform:translateY(0) scale(1);
}
@media (min-width: 640px) {
    #notif-panel { width: 340px; }
}


/* Toasts */
#toast-container {
    position:fixed; bottom:24px; right:24px; z-index:9999;
    display:flex; flex-direction:column; gap:10px; pointer-events:none;
}
.toast {
    pointer-events:all; min-width:280px; max-width:340px;
    border-radius:14px; padding:14px 16px;
    display:flex; align-items:flex-start; gap:12px;
    background:#1a1c1c; color:#fff;
    box-shadow:0 8px 32px rgba(0,0,0,0.2);
    animation:toastIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
}
.toast.removing { animation:toastOut 0.3s ease forwards; }
.toast-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; shrink:0; flex-shrink:0; }
.toast-close { margin-left:auto; opacity:0.5; cursor:pointer; flex-shrink:0; font-size:16px; line-height:1; padding:2px; }
.toast-close:hover { opacity:1; }
</style>

{{-- NAV SUPERIOR --}}
<nav class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-4 w-full bg-white/80 backdrop-blur-xl border-b border-[#c4c5da]/20 transition-colors duration-300">
    <div class="flex items-center gap-3">
        <button onclick="abrirSidebar()" class="nav-btn-icon" title="Menú">
            <span class="material-symbols-outlined text-sm">menu</span>
        </button>

        <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('sales') }}"
           class="font-black tracking-tighter text-xl text-[#1a1c1c] hover:opacity-70 transition-opacity">
            Qui<span class="text-[#1737c8]">vex</span>
        </a>

        @php $planBadge = Auth::user()->plan ?? 'gratis'; @endphp
        <span class="hidden md:inline-flex nav-plan-badge
            {{ $planBadge === 'business' ? 'bg-amber-100 text-amber-700' : ($planBadge === 'pro' ? 'bg-[#1737c8]/10 text-[#1737c8]' : 'bg-[#f3f3f4] text-[#747688]') }}">
            <span class="material-symbols-outlined" style="font-size:10px;">{{ $planBadge === 'business' ? 'bolt' : ($planBadge === 'pro' ? 'workspace_premium' : 'star') }}</span>
            {{ ucfirst($planBadge) }}
        </span>
    </div>

    <div class="flex items-center gap-2 relative">
        {{-- TOGGLE TEMA --}}
        <button id="app-theme-toggle" onclick="toggleAppTheme()" class="nav-btn-icon" title="Cambiar tema">
            <span class="material-symbols-outlined text-sm" id="app-theme-icon">dark_mode</span>
        </button>

        {{-- CAMPANA DE NOTIFICACIONES (Pro y Business) --}}
        @if(in_array($planBadge, ['pro', 'business']))
        <div class="static md:relative" id="notif-wrapper">
            <button class="nav-btn-icon" id="notif-btn" onclick="toggleNotifPanel()" title="Notificaciones">
                <span class="material-symbols-outlined text-sm" id="notif-bell-icon">notifications</span>
                <span class="notif-badge" id="notif-badge">0</span>
            </button>

            {{-- PANEL --}}
            <div id="notif-panel">
                <div class="flex items-center justify-between px-4 py-3 border-b border-[#c4c5da]/20">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Notificaciones</p>
                        <p class="text-sm font-bold text-[#1a1c1c]" id="notif-count-label">Sin notificaciones</p>
                    </div>
                    <button onclick="leerTodasNotifs()" id="btn-leer-todas"
                            class="text-[9px] font-black uppercase tracking-widest text-[#1737c8] hover:opacity-70 hidden">
                        Marcar todas
                    </button>
                </div>
                <div id="notif-list" class="overflow-y-auto max-h-[360px] divide-y divide-[#c4c5da]/10">
                    <div id="notif-empty" class="px-4 py-10 text-center">
                        <span class="material-symbols-outlined text-3xl text-[#c4c5da] block mb-2">notifications_off</span>
                        <p class="text-xs text-[#747688]">Todo tranquilo por aquí</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- USER MENU --}}
        <div class="relative group">
            <button class="flex items-center hover:opacity-70 transition-opacity">
                <img src="{{ asset('images/quivex-logo.png') }}" alt="Quivex" class="h-8 w-auto" />
            </button>
            <div class="absolute right-0 top-full mt-2 w-52 bg-white border rounded-xl border-[#c4c5da]/20 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#747688]">Sesión activa</p>
                    <p class="text-sm font-bold text-[#1a1c1c] mt-1 truncate">{{ Auth::user()->name ?? 'Usuario' }}</p>
                </div>
                <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                    <p class="text-[10px] text-[#747688] font-semibold truncate">{{ Auth::user()->email ?? '' }}</p>
                    <p class="text-[9px] font-black uppercase tracking-widest mt-1 {{ Auth::user()->role === 'admin' ? 'text-[#1737c8]' : 'text-[#747688]' }}">
                        {{ Auth::user()->role === 'admin' ? 'Administrador' : 'Vendedor' }}
                    </p>
                </div>
                <div class="px-4 py-3 border-b border-[#c4c5da]/20">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-2">Tema</p>
                    <div class="flex gap-2">
                        <button onclick="setAppTheme('light')" id="btn-theme-light"
                                class="flex-1 py-1.5 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 rounded-lg flex items-center justify-center gap-1 transition-all hover:border-[#1737c8]">
                            <span class="material-symbols-outlined text-[11px]">light_mode</span>Claro
                        </button>
                        <button onclick="setAppTheme('dark')" id="btn-theme-dark"
                                class="flex-1 py-1.5 text-[9px] font-black uppercase tracking-widest border border-[#c4c5da]/40 rounded-lg flex items-center justify-center gap-1 transition-all hover:border-[#1737c8]">
                            <span class="material-symbols-outlined text-[11px]">dark_mode</span>Oscuro
                        </button>
                    </div>
                </div>
                <a href="{{ route('logout') }}" id="btn-logout"
                   class="w-full flex items-center gap-2 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- TOAST CONTAINER --}}
<div id="toast-container"></div>

<script>
// ── DATOS DEL USUARIO ────────────────────────────────────────
const QVX_USER = {
    nombre:  '{{ explode(" ", Auth::user()->name ?? "")[0] }}',
    tienda:  '{{ Auth::user()->store_name ?? "" }}',
    plan:    '{{ Auth::user()->plan ?? "gratis" }}',
    esPro:   {{ in_array(Auth::user()->plan ?? 'gratis', ['pro', 'business']) ? 'true' : 'false' }},
};

// ── TEMA ─────────────────────────────────────────────────────
function setAppTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('qvx-app-theme', theme);
    const icon = document.getElementById('app-theme-icon');
    if (icon) icon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
    const bl = document.getElementById('btn-theme-light');
    const bd = document.getElementById('btn-theme-dark');
    if (bl) { bl.style.background = theme === 'light' ? '#1737c8' : ''; bl.style.color = theme === 'light' ? '#fff' : ''; bl.style.borderColor = theme === 'light' ? '#1737c8' : ''; }
    if (bd) { bd.style.background = theme === 'dark'  ? '#1737c8' : ''; bd.style.color = theme === 'dark'  ? '#fff' : ''; bd.style.borderColor = theme === 'dark'  ? '#1737c8' : ''; }
}
function toggleAppTheme() {
    const curr = document.documentElement.getAttribute('data-theme') || 'light';
    setAppTheme(curr === 'dark' ? 'light' : 'dark');
}

// ── TOAST SYSTEM ─────────────────────────────────────────────
const toastColors = {
    blue:   { bg:'#1737c8', icon:'notifications' },
    green:  { bg:'#22c55e', icon:'check_circle' },
    red:    { bg:'#ef4444', icon:'error' },
    amber:  { bg:'#f59e0b', icon:'warning' },
    purple: { bg:'#8b5cf6', icon:'auto_awesome' },
};

function showToast(titulo, mensaje, color = 'blue', duracion = 5000) {
    const container = document.getElementById('toast-container');
    const c = toastColors[color] || toastColors.blue;
    const id = 'toast-' + Date.now();

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.id = id;
    toast.innerHTML = `
        <div class="toast-icon" style="background:${c.bg}20">
            <span class="material-symbols-outlined text-sm" style="color:${c.bg}">${c.icon}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p style="font-size:12px;font-weight:800;margin-bottom:2px;">${titulo}</p>
            <p style="font-size:11px;opacity:0.7;line-height:1.4;">${mensaje}</p>
        </div>
        <span class="toast-close" onclick="removeToast('${id}')">✕</span>
    `;
    container.appendChild(toast);

    if (duracion > 0) {
        setTimeout(() => removeToast(id), duracion);
    }
}

function removeToast(id) {
    const toast = document.getElementById(id);
    if (!toast) return;
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
}

// ── BIENVENIDA / DESPEDIDA ───────────────────────────────────
function mostrarBienvenida() {
    const hora = new Date().getHours();
    let saludo, emoji, mensaje;

    if (hora >= 6 && hora < 12) {
        saludo = 'Buenos días'; emoji = '🌅';
        const frases = [
            '¡Hoy será un gran día para las ventas!',
            'Empieza el día con todo, el éxito te espera.',
            '¡A darle con todo! Tu tienda te necesita.',
        ];
        mensaje = frases[Math.floor(Math.random() * frases.length)];
    } else if (hora >= 12 && hora < 19) {
        saludo = 'Buenas tardes'; emoji = '☀️';
        const frases = [
            '¡Sigue así, vas muy bien!',
            'La tarde es tuya, aprovéchala al máximo.',
            '¡Ánimo! Las mejores ventas aún están por venir.',
        ];
        mensaje = frases[Math.floor(Math.random() * frases.length)];
    } else {
        saludo = 'Buenas noches'; emoji = '🌙';
        const frases = [
            'Terminando el día con todo, ¡así se hace!',
            'El esfuerzo de hoy es el éxito de mañana.',
            '¡Casi listo! Cierra el día con una venta más.',
        ];
        mensaje = frases[Math.floor(Math.random() * frases.length)];
    }

    const titulo = `${emoji} ${saludo}, ${QVX_USER.nombre}`;
    const desc   = QVX_USER.tienda
        ? `${QVX_USER.tienda} — ${mensaje}`
        : mensaje;

    showToast(titulo, desc, 'blue', 6000);
}

function configurarDespedida() {
    const btn = document.getElementById('btn-logout');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const hora = new Date().getHours();
        let saludo, frases;

        if (hora >= 6 && hora < 12) {
            saludo = '¡Hasta pronto!';
            frases = ['Que tengas un excelente día.', 'Vuelve pronto, tu tienda te espera.'];
        } else if (hora >= 12 && hora < 19) {
            saludo = '¡Hasta luego!';
            frases = ['Hoy fue un gran día.', '¡Descansa bien, te lo mereces!'];
        } else {
            saludo = '¡Buenas noches!';
            frases = ['Mañana será aún mejor.', '¡Descansa, mañana hay más ventas!'];
        }

        const frase = frases[Math.floor(Math.random() * frases.length)];
        showToast(`👋 ${saludo} ${QVX_USER.nombre}`, frase, 'purple', 0);

        setTimeout(() => { window.location.href = btn.href; }, 1800);
    });
}

// ── NOTIFICACIONES (campana) ─────────────────────────────────
let notifPanelOpen = false;

function toggleNotifPanel() {
    const panel = document.getElementById('notif-panel');
    if (!panel) return;
    notifPanelOpen = !notifPanelOpen;
    panel.classList.toggle('open', notifPanelOpen);
    if (notifPanelOpen) cargarNotificaciones();
}

function cerrarNotifPanel() {
    notifPanelOpen = false;
    const panel = document.getElementById('notif-panel');
    if (panel) panel.classList.remove('open');
}

async function cargarNotificaciones() {
    if (!QVX_USER.esPro) return;
    try {
        const res  = await fetch('{{ route("notificaciones.index") }}', {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        renderNotificaciones(data.notificaciones, data.total);
    } catch(e) { console.error('Error cargando notificaciones:', e); }
}

function renderNotificaciones(notifs, total) {
    const list    = document.getElementById('notif-list');
    const empty   = document.getElementById('notif-empty');
    const badge   = document.getElementById('notif-badge');
    const label   = document.getElementById('notif-count-label');
    const btnTodo = document.getElementById('btn-leer-todas');
    const bell    = document.getElementById('notif-bell-icon');

    // Badge
    if (total > 0) {
        badge.textContent = total > 9 ? '9+' : total;
        badge.classList.add('visible');
        bell.textContent = 'notifications_active';
        if (btnTodo) btnTodo.classList.remove('hidden');
        label.textContent = total === 1 ? '1 notificación' : `${total} notificaciones`;
    } else {
        badge.classList.remove('visible');
        bell.textContent = 'notifications';
        if (btnTodo) btnTodo.classList.add('hidden');
        label.textContent = 'Sin notificaciones';
    }

    if (!notifs || notifs.length === 0) {
        if (empty) empty.style.display = 'block';
        return;
    }
    if (empty) empty.style.display = 'none';

    const colorMap = {
        red:   { bg:'#fef2f2', icon:'#ef4444', border:'#fecaca' },
        amber: { bg:'#fffbeb', icon:'#f59e0b', border:'#fde68a' },
        blue:  { bg:'#eff6ff', icon:'#1737c8', border:'#bfdbfe' },
        green: { bg:'#f0fdf4', icon:'#22c55e', border:'#bbf7d0' },
    };

    // Limpiar items anteriores (no el empty)
    list.querySelectorAll('.notif-item').forEach(el => el.remove());

    notifs.forEach(n => {
        const c   = colorMap[n.color] || colorMap.blue;
        const div = document.createElement('div');
        div.className = 'notif-item flex items-start gap-3 px-4 py-3 cursor-pointer transition-colors';
        div.style.cssText = 'transition:background 0.15s ease;';
        div.onmouseenter = () => div.style.background = '#f9f9f9';
        div.onmouseleave = () => div.style.background = '';
        div.innerHTML = `
            <div style="width:34px;height:34px;border-radius:10px;background:${c.bg};border:1px solid ${c.border};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                <span class="material-symbols-outlined" style="font-size:16px;color:${c.icon}">${n.icono}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p style="font-size:12px;font-weight:800;color:#1a1c1c;margin-bottom:2px;line-height:1.3;">${n.titulo}</p>
                <p style="font-size:11px;color:#747688;line-height:1.4;">${n.mensaje}</p>
                <p style="font-size:10px;color:#c4c5da;margin-top:4px;font-weight:600;">${n.hace}</p>
            </div>
            <button onclick="leerNotif(${n.id}, this)" style="flex-shrink:0;opacity:0.4;font-size:14px;padding:2px 4px;border-radius:6px;transition:all 0.15s;" onmouseenter="this.style.opacity=1;this.style.background='#f3f3f4'" onmouseleave="this.style.opacity=0.4;this.style.background=''">✕</button>
        `;
        list.appendChild(div);
    });

    // Animar campana si hay nuevas
    if (total > 0) {
        const bellBtn = document.getElementById('notif-btn');
        if (bellBtn) { bellBtn.classList.add('bell-ring'); setTimeout(() => bellBtn.classList.remove('bell-ring'), 600); }
    }
}

async function leerNotif(id, btn) {
    try {
        await fetch(`/notificaciones/${id}/leer`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        });
        const item = btn.closest('.notif-item');
        if (item) { item.style.opacity = '0'; item.style.transform = 'translateX(10px)'; item.style.transition = 'all 0.2s ease'; setTimeout(() => { item.remove(); cargarNotificaciones(); }, 200); }
    } catch(e) { console.error(e); }
}

async function leerTodasNotifs() {
    try {
        await fetch('{{ route("notificaciones.leerTodas") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        });
        cargarNotificaciones();
        showToast('Todo al día', 'Notificaciones marcadas como leídas', 'green', 3000);
    } catch(e) { console.error(e); }
}

// Cerrar panel al click fuera
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) cerrarNotifPanel();
});

// ── INIT ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const t = localStorage.getItem('qvx-app-theme') || 'light';
    setAppTheme(t);

    // Despedida al cerrar sesión
    configurarDespedida();

    // Cargar notificaciones iniciales (solo pro/business)
    if (QVX_USER.esPro) {
        setTimeout(() => cargarNotificaciones(), 1500);
        // Refrescar cada 5 minutos
        setInterval(() => { if (!notifPanelOpen) cargarNotificaciones(); }, 5 * 60 * 1000);
    }

    // SCROLL REVEAL
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
    }, { threshold: 0.08 });
    document.querySelectorAll('.section-reveal').forEach(el => revObs.observe(el));

    // TILT 3D
    document.querySelectorAll('.tilt-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top) / r.height - 0.5;
            card.style.transform = `perspective(500px) rotateX(${-y*6}deg) rotateY(${x*6}deg) translateY(-2px)`;
            card.style.transition = 'transform 0.1s ease';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = ''; card.style.transition = 'transform 0.4s ease';
        });
    });

    // NUM COUNTER
    document.querySelectorAll('.num-counter').forEach(el => {
        const obs = new IntersectionObserver(entries => {
            if (!entries[0].isIntersecting) return;
            obs.disconnect();
            const texto = el.textContent.trim();
            const num = parseFloat(texto.replace(/[$,]/g, ''));
            if (isNaN(num) || num === 0) return;
            const prefix = texto.includes('$') ? '$' : '';
            const isInt = Number.isInteger(num);
            const dur = 900; const start = performance.now();
            function update(now) {
                const p = Math.min((now - start) / dur, 1);
                const ease = 1 - Math.pow(1-p, 3); const cur = num * ease;
                el.textContent = prefix + (isInt ? Math.round(cur).toLocaleString('es-MX') : cur.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2}));
                if (p < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }, { threshold: 0.5 });
        obs.observe(el);
    });

    // HOVER LIFT
    document.querySelectorAll('.hover-lift').forEach(el => {
        el.addEventListener('mouseenter', () => { el.style.transform = 'translateY(-2px)'; el.style.boxShadow = '0 8px 24px rgba(23,55,200,0.1)'; });
        el.addEventListener('mouseleave', () => { el.style.transform = ''; el.style.boxShadow = ''; });
    });
});
</script>