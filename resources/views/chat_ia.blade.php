<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Chat IA</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    /* Scroll oculto pero funcional para un diseño más limpio */
    #chat-container { height: calc(100dvh - 220px); overflow-y: auto; scroll-behavior: smooth; }
    #chat-container::-webkit-scrollbar { width: 6px; }
    #chat-container::-webkit-scrollbar-thumb { background-color: rgba(196,197,218,0.5); border-radius: 10px; }
    
    /* Animaciones fluidas */
    .mensaje-ia { animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { 
        from { opacity: 0; transform: translateY(12px) scale(0.98); } 
        to { opacity: 1; transform: translateY(0) scale(1); } 
    }
    
    .prose strong { font-weight: 900; }
    .prose ul { list-style: disc; padding-left: 1.2rem; }
    .prose p { margin-bottom: 0.5rem; }
    
    .typing-indicator span { display: inline-block; width: 6px; height: 6px; background: #1737c8; border-radius: 50%; animation: typing 1.4s infinite cubic-bezier(0.4, 0, 0.2, 1); }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 0%, 100% { transform: translateY(0); opacity: 0.4; } 50% { transform: translateY(-4px); opacity: 1; } }

    /* ── DARK MODE ────────────────────────────────────── */
    [data-theme="dark"] body { background:#0f1012 !important; color:#f3f3f4 !important; }
    [data-theme="dark"] .bg-white { background:#1e2022 !important; border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .bg-\[\#f3f3f4\] { background:#141618 !important; color:#f3f3f4 !important; }
    [data-theme="dark"] .bg-\[\#f9f9f9\] { background:#0f1012 !important; }
    [data-theme="dark"] .text-\[\#1a1c1c\] { color:#f3f3f4 !important; }
    [data-theme="dark"] .text-\[\#747688\] { color:#9496a8 !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/10 { border-color:rgba(255,255,255,0.05) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/20 { border-color:rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .border-\[\#c4c5da\]\/40 { border-color:rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] input { background:#141618 !important; color:#f3f3f4 !important; border-color:rgba(255,255,255,0.1) !important; }
    [data-theme="dark"] input::placeholder { color:#9496a8 !important; }
    [data-theme="dark"] .typing-indicator span { background:#9496a8 !important; }
    [data-theme="dark"] .sugerencia-btn { background:#141618 !important; color:#9496a8 !important; }
    [data-theme="dark"] .sugerencia-btn:hover { background:#1737c8 !important; color:#fff !important; }
</style>
</head>
<body class="bg-[#f9f9f9] text-[#1a1c1c]">

@include('partials._nav')

<div class="pt-20 px-4 md:px-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Chat IA Financiero</span>
    </div>
</div>

<main class="px-4 md:px-6 max-w-4xl mx-auto pb-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#1737c8] mb-0.5">Business</p>
            <h1 class="text-2xl font-black tracking-tight">Tu CFO virtual</h1>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1.5 bg-white border border-[#c4c5da]/20 px-4 py-2.5 rounded-2xl shadow-sm">
                <span class="material-symbols-outlined text-sm text-amber-500">stars</span>
                <span class="text-[10px] font-black text-[#747688]" id="consultas-restantes">{{ $consultas_restantes }} consultas</span>
            </div>
        </div>
    </div>

    {{-- CHAT --}}
    <div class="bg-white border border-[#c4c5da]/20 rounded-3xl flex flex-col shadow-sm overflow-hidden" style="height: calc(100dvh - 240px); min-height: 400px;">

        {{-- MENSAJES --}}
        <div id="chat-container" class="flex-1 p-5 space-y-6">

            {{-- Mensaje bienvenida --}}
            @if(count($mensajes) === 0)
            <div class="flex gap-3 mensaje-ia">
                <div class="w-9 h-9 rounded-xl bg-[#1737c8] flex items-center justify-center shrink-0 shadow-md">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div class="flex-1">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 ml-1">Quivex CFO</p>
                    <div class="bg-[#f3f3f4] px-5 py-4 text-sm prose max-w-none rounded-2xl rounded-tl-[4px] shadow-sm">
                        <p>Hola 👋 Soy tu director financiero virtual. Tengo acceso a tus ventas, costos e inventario de este mes.</p>
                        <p class="mt-2">Puedes preguntarme cosas como:</p>
                        <ul class="mt-1 space-y-1">
                            <li>¿Cuánto gané libre este mes?</li>
                            <li>¿Qué producto me deja más ganancia?</li>
                            <li>¿Cuánto me están comiendo los gastos?</li>
                            <li>¿Cómo voy comparado con el mes pasado?</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Historial de mensajes --}}
            @foreach($mensajes as $msg)
            @if($msg->role === 'user')
            <div class="flex gap-3 justify-end mensaje-ia">
                <div class="max-w-[85%] sm:max-w-[75%]">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 text-right mr-1">Tú</p>
                    <div class="bg-[#1737c8] text-white px-5 py-3.5 text-sm rounded-2xl rounded-tr-[4px] shadow-md">
                        {{ $msg->contenido }}
                    </div>
                </div>
            </div>
            @else
            <div class="flex gap-3 mensaje-ia">
                <div class="w-9 h-9 rounded-xl bg-[#1737c8] flex items-center justify-center shrink-0 shadow-md">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div class="flex-1 max-w-[90%] sm:max-w-[85%]">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 ml-1">Quivex CFO</p>
                    <div class="bg-[#f3f3f4] px-5 py-4 text-sm prose max-w-none rounded-2xl rounded-tl-[4px] shadow-sm" data-markdown="{{ $msg->contenido }}"></div>
                </div>
            </div>
            @endif
            @endforeach

            {{-- Typing indicator --}}
            <div id="typing-indicator" class="hidden flex gap-3 mensaje-ia pb-2">
                <div class="w-9 h-9 rounded-xl bg-[#1737c8] flex items-center justify-center shrink-0 shadow-md">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 ml-1">Quivex CFO</p>
                    <div class="bg-[#f3f3f4] px-5 py-4 rounded-2xl rounded-tl-[4px] shadow-sm">
                        <div class="typing-indicator flex gap-1.5 items-center justify-center h-4">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUGERENCIAS RÁPIDAS --}}
        <div class="px-5 py-3 border-t border-[#c4c5da]/10 flex gap-2 overflow-x-auto custom-scrollbar">
            @foreach(['¿Cuánto gané este mes?', '¿Qué producto es más rentable?', '¿Cuánto me cuestan los gastos?'] as $sugerencia)
            <button onclick="usarSugerencia('{{ $sugerencia }}')"
                    class="sugerencia-btn shrink-0 px-4 py-2 bg-[#f3f3f4] rounded-xl text-[10px] font-bold text-[#747688] hover:bg-[#1737c8] hover:text-white transition-colors duration-200 whitespace-nowrap">
                {{ $sugerencia }}
            </button>
            @endforeach
        </div>

        {{-- INPUT --}}
        <div class="px-5 py-4 border-t border-[#c4c5da]/10 bg-white dark:bg-[#1e2022]">
            <div class="flex gap-3">
                <input type="text" id="chat-input" placeholder="Pregúntale a tu negocio..." maxlength="500"
                       onkeydown="if(event.key==='Enter') enviarMensaje()"
                       class="flex-1 border border-[#c4c5da]/40 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-[#1737c8] focus:ring-4 focus:ring-[#1737c8]/10 bg-[#f9f9f9] transition-all"/>
                <button onclick="enviarMensaje()" id="btn-enviar"
                        class="bg-[#1737c8] text-white rounded-2xl px-5 py-3.5 hover:opacity-90 transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </div>
        </div>
    </div>

</main>

{{-- MODAL LÍMITE --}}
<div id="modal-limite" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" style="animation: fadeInBg 0.3s ease;">
    <div class="bg-white rounded-3xl w-full max-w-sm p-8 text-center shadow-2xl transform transition-all" style="animation: slideUpFade 0.4s ease;">
        <span class="material-symbols-outlined text-4xl text-amber-500 block mb-3">stars</span>
        <h2 class="text-xl font-black mb-2">Límite mensual alcanzado</h2>
        <p class="text-sm text-[#747688] mb-8" id="limite-mensaje"></p>
        <button onclick="document.getElementById('modal-limite').classList.add('hidden')"
                class="w-full bg-[#1737c8] text-white rounded-2xl py-3.5 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-md">
            Entendido
        </button>
    </div>
</div>

@include('partials._sidebar')

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

// Renderizar markdown en mensajes existentes
document.querySelectorAll('[data-markdown]').forEach(el => {
    el.innerHTML = marked.parse(el.dataset.markdown);
});

// Scroll suave inicial
const chatContainer = document.getElementById('chat-container');
chatContainer.scrollTop = chatContainer.scrollHeight;

function hacerScrollSuave() {
    setTimeout(() => {
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: 'smooth'
        });
    }, 50);
}

function usarSugerencia(texto) {
    document.getElementById('chat-input').value = texto;
    enviarMensaje();
}

async function enviarMensaje() {
    const input   = document.getElementById('chat-input');
    const mensaje = input.value.trim();
    if (!mensaje) return;

    input.value = '';
    document.getElementById('btn-enviar').disabled = true;

    // Agregar mensaje del usuario
    agregarMensaje('user', mensaje);

    // Mostrar typing con animacion fluida
    const typing = document.getElementById('typing-indicator');
    typing.classList.remove('hidden');
    hacerScrollSuave();

    try {
        const resp = await fetch('{{ route("chat.ia.mensaje") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ mensaje }),
        });

        const data = await resp.json();
        typing.classList.add('hidden');

        if (data.error) {
            document.getElementById('limite-mensaje').textContent = data.mensaje;
            document.getElementById('modal-limite').classList.remove('hidden');
        } else {
            agregarMensaje('assistant', data.respuesta);
            document.getElementById('consultas-restantes').textContent = data.consultas_restantes + ' consultas';
        }

    } catch (err) {
        typing.classList.add('hidden');
        agregarMensaje('assistant', 'Error al conectar. Intenta de nuevo.');
    }

    document.getElementById('btn-enviar').disabled = false;
    // Retornamos el enfoque al input para seguir chateando
    input.focus(); 
}

function agregarMensaje(role, contenido) {
    const div = document.createElement('div');

    if (role === 'user') {
        div.className = 'flex gap-3 justify-end mensaje-ia';
        div.innerHTML = `
            <div class="max-w-[85%] sm:max-w-[75%]">
                <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 text-right mr-1">Tú</p>
                <div class="bg-[#1737c8] text-white px-5 py-3.5 text-sm rounded-2xl rounded-tr-[4px] shadow-md">${contenido}</div>
            </div>`;
    } else {
        div.className = 'flex gap-3 mensaje-ia';
        div.innerHTML = `
            <div class="w-9 h-9 rounded-xl bg-[#1737c8] flex items-center justify-center shrink-0 shadow-md">
                <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
            </div>
            <div class="flex-1 max-w-[90%] sm:max-w-[85%]">
                <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1.5 ml-1">Quivex CFO</p>
                <div class="bg-[#f3f3f4] px-5 py-4 text-sm prose max-w-none rounded-2xl rounded-tl-[4px] shadow-sm">${marked.parse(contenido)}</div>
            </div>`;
    }

    // Insertar justo antes del typing indicator para no romper el orden
    const typing = document.getElementById('typing-indicator');
    chatContainer.insertBefore(div, typing);
    hacerScrollSuave();
}
</script>
</body>
</html>