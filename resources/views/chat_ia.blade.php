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
    #chat-container { height: calc(100dvh - 220px); overflow-y: auto; }
    .mensaje-ia { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .prose strong { font-weight: 900; }
    .prose ul { list-style: disc; padding-left: 1.2rem; }
    .prose p { margin-bottom: 0.5rem; }
    .typing-indicator span { display: inline-block; width: 6px; height: 6px; background: #1737c8; border-radius: 50%; animation: typing 1s infinite; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 0%, 100% { transform: translateY(0); opacity: 0.4; } 50% { transform: translateY(-4px); opacity: 1; } }
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
            <div class="flex items-center gap-1.5 bg-white border border-[#c4c5da]/20 px-3 py-2">
                <span class="material-symbols-outlined text-sm text-amber-500">stars</span>
                <span class="text-[10px] font-black text-[#747688]" id="consultas-restantes">{{ $consultas_restantes }} consultas</span>
            </div>
        </div>
    </div>

    {{-- CHAT --}}
    <div class="bg-white border border-[#c4c5da]/20 flex flex-col" style="height: calc(100dvh - 240px); min-height: 400px;">

        {{-- MENSAJES --}}
        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4">

            {{-- Mensaje bienvenida --}}
            @if(count($mensajes) === 0)
            <div class="flex gap-3 mensaje-ia">
                <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div class="flex-1">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Quivex CFO</p>
                    <div class="bg-[#f3f3f4] px-4 py-3 text-sm prose max-w-none">
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
            <div class="flex gap-3 justify-end">
                <div class="max-w-[80%]">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1 text-right">Tú</p>
                    <div class="bg-[#1737c8] text-white px-4 py-3 text-sm">
                        {{ $msg->contenido }}
                    </div>
                </div>
            </div>
            @else
            <div class="flex gap-3 mensaje-ia">
                <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div class="flex-1">
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Quivex CFO</p>
                    <div class="bg-[#f3f3f4] px-4 py-3 text-sm prose max-w-none" data-markdown="{{ $msg->contenido }}"></div>
                </div>
            </div>
            @endif
            @endforeach

            {{-- Typing indicator --}}
            <div id="typing-indicator" class="hidden flex gap-3">
                <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
                </div>
                <div class="bg-[#f3f3f4] px-4 py-3">
                    <div class="typing-indicator flex gap-1 items-center">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUGERENCIAS RÁPIDAS --}}
        <div class="px-4 py-2 border-t border-[#c4c5da]/10 flex gap-2 overflow-x-auto">
            @foreach(['¿Cuánto gané este mes?', '¿Qué producto es más rentable?', '¿Cuánto me cuestan los gastos?', '¿Cómo están mis ventas?'] as $sugerencia)
            <button onclick="usarSugerencia('{{ $sugerencia }}')"
                    class="shrink-0 px-3 py-1.5 bg-[#f3f3f4] text-[10px] font-bold text-[#747688] hover:bg-[#1737c8] hover:text-white transition-all whitespace-nowrap">
                {{ $sugerencia }}
            </button>
            @endforeach
        </div>

        {{-- INPUT --}}
        <div class="px-4 py-3 border-t border-[#c4c5da]/10 flex gap-2">
            <input type="text" id="chat-input" placeholder="Pregúntale a tu negocio..." maxlength="500"
                   onkeydown="if(event.key==='Enter') enviarMensaje()"
                   class="flex-1 border border-[#c4c5da]/40 px-4 py-2.5 text-sm focus:outline-none focus:border-[#1737c8] bg-[#f9f9f9]"/>
            <button onclick="enviarMensaje()" id="btn-enviar"
                    class="bg-[#1737c8] text-white px-4 py-2.5 hover:opacity-90 transition-all flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">send</span>
            </button>
        </div>
    </div>

</main>

{{-- MODAL LÍMITE --}}
<div id="modal-limite" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm p-8 text-center">
        <span class="material-symbols-outlined text-4xl text-amber-500 block mb-3">stars</span>
        <h2 class="text-xl font-black mb-2">Límite mensual alcanzado</h2>
        <p class="text-sm text-[#747688] mb-6" id="limite-mensaje"></p>
        <button onclick="document.getElementById('modal-limite').classList.add('hidden')"
                class="bg-[#1737c8] text-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:opacity-90">
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

// Scroll al fondo
const chatContainer = document.getElementById('chat-container');
chatContainer.scrollTop = chatContainer.scrollHeight;

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

    // Mostrar typing
    const typing = document.getElementById('typing-indicator');
    typing.classList.remove('hidden');
    chatContainer.scrollTop = chatContainer.scrollHeight;

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
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function agregarMensaje(role, contenido) {
    const div = document.createElement('div');

    if (role === 'user') {
        div.className = 'flex gap-3 justify-end mensaje-ia';
        div.innerHTML = `
            <div class="max-w-[80%]">
                <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1 text-right">Tú</p>
                <div class="bg-[#1737c8] text-white px-4 py-3 text-sm">${contenido}</div>
            </div>`;
    } else {
        div.className = 'flex gap-3 mensaje-ia';
        div.innerHTML = `
            <div class="w-8 h-8 bg-[#1737c8] flex items-center justify-center shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-white text-sm">auto_awesome</span>
            </div>
            <div class="flex-1">
                <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-1">Quivex CFO</p>
                <div class="bg-[#f3f3f4] px-4 py-3 text-sm prose max-w-none">${marked.parse(contenido)}</div>
            </div>`;
    }

    chatContainer.appendChild(div);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}
</script>
</body>
</html>