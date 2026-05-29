<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Crear cuenta — Quivex</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1737c8",
                        surface: "#f3f3f4",
                        "on-surface": "#1a1c1c",
                        secondary: "#5e5e5e"
                    }
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', sans-serif; overflow: hidden; }
        @media (max-width: 767px) { html, body { overflow: auto; } }
        .plan-card { transition: all 0.2s; cursor: pointer; }
        .plan-card.selected { border-color: #1737c8; box-shadow: 0 0 0 2px #1737c8; }
        .plan-card.selected .plan-check { opacity: 1; }
        .plan-check { opacity: 0; transition: opacity 0.2s; }
    </style>
</head>

<body class="bg-white text-on-surface antialiased selection:bg-primary selection:text-white">

    <div class="flex h-screen">

        <!-- IZQUIERDA — solo desktop/tablet -->
        <section class="hidden md:flex md:w-5/12 lg:w-1/2 bg-[#1a1c1c] items-center justify-center p-8 overflow-hidden relative shrink-0">
            <div class="absolute inset-0 z-0">
                <img alt="Fondo deportivo"
                     class="w-full h-full object-cover opacity-20"
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDg9QP40wMNox6ElGYVjkOfTX-5Topv2RBvTEgzWD-zPtsWSHu9g0wFjgWxITBuifpqrtE9qSoabW0qgbZTIcwiAlV4Alw18MS7ORfzb2IBsq6_LQdDdsc0SAIK2K1xLl1BbZnrMQDZVosJbTD-9cjxrqeg6t5xe2jtxHNy5FZnzY6EDXAx-wCxs2kor95F86WgFilXDGnue2aBB0F1sxi1_AeBym8oB4rfEo0V05VoEeOrPTsIHVskWdoyoj7nrk1-t94jQnoNViI"/>
            </div>
            <div class="relative z-10 text-white flex flex-col gap-5 max-w-sm">
                <div class="text-3xl font-black tracking-tight leading-tight">
                    El POS más inteligente para tu <span class="text-[#1737c8]">tienda deportiva</span>
                </div>
                <div class="flex flex-col gap-2.5">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                        <span class="text-sm text-white/70">Inventario y ventas en un solo lugar</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                        <span class="text-sm text-white/70">Plan gratis disponible</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                        <span class="text-sm text-white/70">Sin tarjeta de crédito para empezar</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                        <span class="text-sm text-white/70">Cancela cuando quieras</span>
                    </div>
                </div>
                <div class="border-t border-white/10 pt-3">
                    <p class="text-[10px] text-white/30 uppercase tracking-widest font-bold">Plan gratuito disponible · Sin compromisos</p>
                </div>
            </div>
        </section>

        <!-- DERECHA — scrollable internamente -->
        <section class="flex-1 flex flex-col h-full overflow-y-auto">

            <!-- Header compacto -->
            <div class="flex justify-between items-center px-6 py-3 border-b border-gray-100 shrink-0">
                <a href="/" class="font-black tracking-tighter text-[#1a1c1c] text-xl hover:text-primary transition-colors">
                    Qui<span class="text-[#1737c8]">vex</span>
                </a>
                <a href="/login" class="text-[11px] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors">
                    ¿Ya tienes cuenta? Inicia sesión →
                </a>
            </div>

            <!-- Formulario centrado -->
            <div class="flex-1 flex items-center justify-center px-6 py-4">
                <div class="w-full max-w-sm flex flex-col gap-4">

                    <div>
                        <h2 class="text-2xl font-black tracking-tight text-on-surface uppercase">Crear cuenta</h2>
                        <p class="text-secondary text-xs mt-0.5">Elige tu plan y empieza hoy.</p>
                    </div>

                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600 font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <form action="/register" method="POST" class="flex flex-col gap-3">
                        @csrf

                        {{-- SELECTOR DE PLAN --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase">Elige tu plan</label>
                            <input type="hidden" name="plan" id="plan-seleccionado" value="gratis"/>
                            <div class="grid grid-cols-2 gap-2">
                                {{-- PLAN GRATIS --}}
                                <div class="plan-card selected border-2 border-[#1737c8] rounded-xl p-3 relative"
                                     onclick="seleccionarPlan('gratis', this)">
                                    <div class="plan-check absolute top-2 right-2 w-4 h-4 bg-[#1737c8] rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-[10px]">check</span>
                                    </div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-[#747688] mb-0.5">Gratis</p>
                                    <p class="text-lg font-black text-[#1a1c1c]">$0 <span class="text-xs font-normal text-[#747688]">/mes</span></p>
                                    <ul class="mt-1.5 space-y-0.5">
                                        <li class="text-[9px] text-[#5e5e5e] flex items-center gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[10px]">check</span>100 productos</li>
                                        <li class="text-[9px] text-[#5e5e5e] flex items-center gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[10px]">check</span>1 usuario</li>
                                        <li class="text-[9px] text-[#5e5e5e] flex items-center gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[10px]">check</span>Ventas básicas</li>
                                    </ul>
                                </div>

                                {{-- PLAN PRO --}}
                                <div class="plan-card border-2 border-gray-200 rounded-xl p-3 relative bg-[#1737c8]"
                                     onclick="seleccionarPlan('pro', this)">
                                    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-yellow-400 text-[#1a1c1c] text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full whitespace-nowrap">
                                        Más popular
                                    </div>
                                    <div class="plan-check absolute top-2 right-2 w-4 h-4 bg-white rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[#1737c8] text-[10px]">check</span>
                                    </div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-white/70 mb-0.5">Pro</p>
                                    <p class="text-lg font-black text-white">$499 <span class="text-xs font-normal text-white/60">/mes</span></p>
                                    <ul class="mt-1.5 space-y-0.5">
                                        <li class="text-[9px] text-white/80 flex items-center gap-1"><span class="material-symbols-outlined text-white text-[10px]">check</span>Ilimitado</li>
                                        <li class="text-[9px] text-white/80 flex items-center gap-1"><span class="material-symbols-outlined text-white text-[10px]">check</span>Usuarios ilimitados</li>
                                        <li class="text-[9px] text-white/80 flex items-center gap-1"><span class="material-symbols-outlined text-white text-[10px]">check</span>IA + Reportes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- CAMPOS --}}
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-1 block">Nombre completo</label>
                            <input name="name" value="{{ old('name') }}"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="text" placeholder="Tu nombre completo" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-1 block">Correo electrónico</label>
                            <input name="email" value="{{ old('email') }}"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="email" placeholder="tu@correo.com" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-1 block">Contraseña</label>
                            <input name="password"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="password" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-1 block">Confirmar contraseña</label>
                            <input name="password_confirmation"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="password" placeholder="Repite tu contraseña" required>
                        </div>

                        {{-- AVISO PRO --}}
                        <div id="aviso-pro" class="hidden bg-blue-50 border border-blue-200 rounded-xl px-3 py-2 text-xs text-blue-700">
                            <p class="font-bold">🔒 Plan Pro — $499/mes</p>
                            <p class="mt-0.5">Serás redirigido a Mercado Pago para completar el pago.</p>
                        </div>

                        <button type="submit" id="btn-registro"
                                class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:brightness-110 active:scale-[0.97] transition-all uppercase tracking-widest text-xs">
                            Crear cuenta gratis
                        </button>
                    </form>

                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">o continúa con</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('google.redirect') }}"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl font-semibold text-sm text-[#1a1c1c] bg-white hover:bg-gray-50 hover:border-gray-300 transition-all">
                            <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                                <path d="M43.611 20.083H42V20H24v8h11.303C33.654 32.657 29.332 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                                <path d="M6.306 14.691l6.571 4.819C14.655 15.108 19.001 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                                <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.314 0-9.822-3.422-11.408-8.167l-6.52 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                                <path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                            </svg>
                            Google
                        </a>
                        <button disabled class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl font-semibold text-sm text-gray-300 bg-white cursor-not-allowed">
                            <svg width="16" height="16" viewBox="0 0 814 1000" fill="none">
                                <path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-42.8-154.9-112.3C142.1 683.3 100.1 588.4 100.1 498c0-154.3 100.7-235.6 199.8-235.6 51.8 0 95.1 34.4 128.2 34.4 31.6 0 81.1-36.5 140.9-36.5 22.6 0 108.1 2 166.1 81.4zm-56.9-194.5c27.4-32.9 47.3-78.7 47.3-124.6 0-6.4-.6-12.8-1.9-18.9-44.9 1.9-98.4 30.2-130.3 67.7-24.8 27.4-48.5 73.3-48.5 119.8 0 7 1.3 13.9 1.9 16.2 2.6.6 6.4 1.3 10.2 1.3 40.3 0 90.4-26.8 121.3-61.5z" fill="#d1d5db"/>
                            </svg>
                            Apple
                        </button>
                    </div>

                    <p class="flex items-center justify-center gap-1.5 text-[10px] text-gray-400 pb-2">
                        <span class="material-symbols-outlined text-xs">lock</span>
                        Tus datos están protegidos con HTTPS.
                    </p>

                </div>
            </div>
        </section>
    </div>

<script>
function seleccionarPlan(plan, card) {
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('plan-seleccionado').value = plan;
    const btn   = document.getElementById('btn-registro');
    const aviso = document.getElementById('aviso-pro');
    if (plan === 'pro') {
        btn.textContent = 'Crear cuenta y pagar $499';
        btn.classList.remove('bg-primary');
        btn.classList.add('bg-[#1a1c1c]');
        aviso.classList.remove('hidden');
    } else {
        btn.textContent = 'Crear cuenta gratis';
        btn.classList.add('bg-primary');
        btn.classList.remove('bg-[#1a1c1c]');
        aviso.classList.add('hidden');
    }
}
</script>

</body>
</html>