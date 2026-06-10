<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Crear cuenta — Quivex</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
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
        
        /* Bloqueo estricto de scroll para TODO el documento */
        html, body { height: 100dvh; margin: 0; padding: 0; font-family: 'Inter', sans-serif; overflow: hidden; }
        @supports not (height: 100dvh) { html, body { height: 100%; } }

        .plan-card { transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1); cursor: pointer; }
        .plan-card:hover { transform: translateY(-2px) scale(1.02); }
        .plan-card.selected { border-color: #1737c8; box-shadow: 0 0 0 2px #1737c8, 0 8px 24px rgba(23,55,200,0.15); }
        .plan-card.selected .plan-check { opacity: 1; transform: scale(1); }
        .plan-check { opacity: 0; transform: scale(0); transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1); }

        /* PARTÍCULAS */
        #particles { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
        .particle {
            position: absolute; width: 2px; height: 2px; background: #1737c8; border-radius: 50%;
            animation: float-particle linear infinite; opacity: 0;
        }
        @keyframes float-particle {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100px) translateX(var(--dx)); opacity: 0; }
        }

        /* GRID ANIMADO */
        .hero-grid {
            position: absolute; inset: 0;
            background-image: linear-gradient(rgba(23,55,200,0.06) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(23,55,200,0.06) 1px, transparent 1px);
            background-size: 40px 40px; animation: grid-move 20s linear infinite;
        }
        @keyframes grid-move { 0% { background-position: 0 0; } 100% { background-position: 40px 40px; } }

        /* GLOW ORBS */
        .orb { position: absolute; border-radius: 50%; filter: blur(60px); animation: orb-float ease-in-out infinite; }
        @keyframes orb-float {
            0%,100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* MOCKUP */
        .mockup-wrap { transform: perspective(800px) rotateY(8deg) rotateX(-3deg); transform-origin: right center; transition: transform 0.15s ease; }

        /* TILT */
        .tilt-card { transition: transform 0.15s ease, box-shadow 0.15s ease; will-change: transform; }

        /* INPUT FOCUS */
        input:focus { transform: scale(1.01); }
        input { transition: all 0.2s ease; }

        /* BOTÓN */
        #btn-registro { transition: all 0.2s cubic-bezier(0.34,1.56,0.64,1); }
        #btn-registro:hover { transform: translateY(-1px) scale(1.02); box-shadow: 0 8px 24px rgba(23,55,200,0.3); }
        #btn-registro:active { transform: scale(0.97); }

        /* FADE IN */
        .fade-in { opacity: 0; transform: translateY(16px); animation: fadeUp 0.5s ease forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        /* CURSOR */
        #reg-cursor { position:fixed;pointer-events:none;z-index:99999;width:10px;height:10px;background:#1737c8;border-radius:50%;transform:translate(-50%,-50%);mix-blend-mode:difference;transition:width 0.2s,height 0.2s; }
        #reg-cursor-outer { position:fixed;pointer-events:none;z-index:99998;width:32px;height:32px;border:1.5px solid rgba(23,55,200,0.4);border-radius:50%;transform:translate(-50%,-50%);transition:all 0.12s ease; }

        /* STAT COUNTER */
        .stat-item { animation: stat-pop 0.6s cubic-bezier(0.34,1.56,0.64,1) forwards; opacity: 0; }
        @keyframes stat-pop { to { opacity:1; transform:scale(1); } from { transform:scale(0.8); } }
    </style>
</head>

<body class="bg-white text-on-surface antialiased selection:bg-primary selection:text-white">

<div id="reg-cursor"></div>
<div id="reg-cursor-outer"></div>

<div class="flex h-screen overflow-hidden">

    <!-- IZQUIERDA (Fija) -->
    <section class="hidden md:flex md:w-5/12 lg:w-1/2 items-center justify-center p-8 overflow-hidden relative shrink-0" style="background:#0d0f10;">

        <!-- Grid animado -->
        <div class="hero-grid"></div>

        <!-- Orbs -->
        <div class="orb" style="width:300px;height:300px;background:rgba(23,55,200,0.12);top:-80px;left:-80px;animation-duration:8s;"></div>
        <div class="orb" style="width:200px;height:200px;background:rgba(23,55,200,0.08);bottom:100px;right:-60px;animation-duration:6s;animation-delay:2s;"></div>

        <!-- Partículas -->
        <div id="particles"></div>

        <!-- Mockup flotante -->
        <div class="mockup-wrap absolute right-8 top-1/2 -translate-y-1/2 w-56 hidden lg:block" id="reg-mockup">
            <div style="background:#1a1c1e;border:1px solid rgba(255,255,255,0.08);border-radius:10px;overflow:hidden;box-shadow:0 24px 48px rgba(0,0,0,0.4);">
                <div style="background:#141618;padding:8px 12px;display:flex;gap:5px;align-items:center;">
                    <div style="width:7px;height:7px;border-radius:50%;background:#ff5f57;"></div>
                    <div style="width:7px;height:7px;border-radius:50%;background:#febc2e;"></div>
                    <div style="width:7px;height:7px;border-radius:50%;background:#28c840;"></div>
                    <span style="font-size:9px;color:rgba(255,255,255,0.3);margin-left:6px;font-family:monospace;">dashboard</span>
                </div>
                <div style="padding:12px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">
                        <div style="background:#1737c8;border-radius:4px;padding:8px;">
                            <div style="font-size:8px;color:rgba(255,255,255,0.6);margin-bottom:3px;text-transform:uppercase;font-weight:700;">Ventas</div>
                            <div id="mock-ventas" style="font-size:14px;font-weight:900;color:#fff;">$12,400</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);border-radius:4px;padding:8px;">
                            <div style="font-size:8px;color:rgba(255,255,255,0.4);margin-bottom:3px;text-transform:uppercase;font-weight:700;">Utilidad</div>
                            <div id="mock-util" style="font-size:14px;font-weight:900;color:#22c55e;">$6,800</div>
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:4px;padding:8px;display:flex;align-items:flex-end;gap:3px;height:48px;" id="mock-chart">
                        <div class="mbar" style="flex:1;background:rgba(23,55,200,0.3);border-radius:1px;height:40%;"></div>
                        <div class="mbar" style="flex:1;background:rgba(23,55,200,0.3);border-radius:1px;height:60%;"></div>
                        <div class="mbar" style="flex:1;background:rgba(23,55,200,0.3);border-radius:1px;height:35%;"></div>
                        <div class="mbar" style="flex:1;background:#1737c8;border-radius:1px;height:85%;"></div>
                        <div class="mbar" style="flex:1;background:rgba(23,55,200,0.3);border-radius:1px;height:55%;"></div>
                        <div class="mbar" style="flex:1;background:rgba(23,55,200,0.3);border-radius:1px;height:70%;"></div>
                    </div>
                    <div style="background:rgba(23,55,200,0.1);border:1px solid rgba(23,55,200,0.2);border-radius:4px;padding:7px;margin-top:6px;display:flex;gap:6px;align-items:flex-start;">
                        <div style="width:16px;height:16px;background:#1737c8;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:9px;flex-shrink:0;">✨</div>
                        <div id="mock-ai" style="font-size:9px;color:rgba(255,255,255,0.6);line-height:1.4;">Nike Air talla 27 se agota en <strong style="color:#6b8ef5;">2 días</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido izquierdo -->
        <div class="relative z-10 text-white flex flex-col gap-5 max-w-xs">
            <a href="/" class="flex items-center gap-2 mb-2">
                <img src="/images/quivex-logo.png" alt="Quivex" style="width:40px;height:40px;object-fit:contain;filter:invert(1) brightness(1.2);">
                <span style="font-size:20px;font-weight:900;letter-spacing:-0.5px;">Qui<span style="color:#4d7fff;">vex</span></span>
            </a>
            <div class="fade-in text-3xl font-black tracking-tight leading-tight">
                El sistema más inteligente para tu <span style="color:#4d7fff;">tienda o showroom</span> de moda
            </div>
            <div class="flex flex-col gap-2.5 fade-in delay-1">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                    <span class="text-sm" style="color:rgba(255,255,255,0.7);">Registra ventas por voz en el mostrador</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                    <span class="text-sm" style="color:rgba(255,255,255,0.7);">Controla tallas y stock en tiempo real</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                    <span class="text-sm" style="color:rgba(255,255,255,0.7);">Conoce tu ganancia neta real al instante</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#1737c8] text-base">check_circle</span>
                    <span class="text-sm" style="color:rgba(255,255,255,0.7);">Sin tarjeta de crédito para empezar</span>
                </div>
            </div>

            <!-- Stats animados -->
            <div class="grid grid-cols-3 gap-3 fade-in delay-2" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:16px;margin-top:4px;">
                <div class="stat-item" style="animation-delay:0.6s;">
                    <div style="font-size:20px;font-weight:900;color:#fff;letter-spacing:-1px;">20</div>
                    <div style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;letter-spacing:0.08em;">Gratis</div>
                </div>
                <div class="stat-item" style="animation-delay:0.75s;">
                    <div style="font-size:20px;font-weight:900;color:#4d7fff;letter-spacing:-1px;">IA</div>
                    <div style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;letter-spacing:0.08em;">Integrada</div>
                </div>
                <div class="stat-item" style="animation-delay:0.9s;">
                    <div style="font-size:20px;font-weight:900;color:#22c55e;letter-spacing:-1px;">3x</div>
                    <div style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;letter-spacing:0.08em;">+ barato</div>
                </div>
            </div>
        </div>
    </section>

    <!-- DERECHA (Completamente Fija y compactada en PC) -->
    <!-- Reemplazamos overflow-y-auto por overflow-hidden en todo lado para garantizar fijación -->
    <section class="flex-1 flex flex-col h-full overflow-hidden bg-white relative">

        <!-- HEADER -->
        <div class="flex justify-between items-center px-4 md:px-6 py-2 md:py-3 border-b border-gray-100 shrink-0">
            <a href="/" class="font-black tracking-tighter text-[#1a1c1c] text-lg md:text-xl hover:text-primary transition-colors">
                Qui<span class="text-[#1737c8]">vex</span>
            </a>
            <a href="/login" class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors">
                ¿Ya tienes cuenta? Inicia sesión →
            </a>
        </div>

        <!-- CONTENIDO CENTRAL -->
        <div class="flex-1 flex flex-col items-center justify-center px-4 md:px-6 py-1 md:py-2">
            <!-- Hacemos el max-w más grande en PC (lg:max-w-lg) para que los inputs quepan en 2 columnas -->
            <div class="w-full max-w-sm md:max-w-md lg:max-w-lg flex flex-col gap-1.5 md:gap-2 my-auto">

                <!-- TÍTULO -->
                <div class="fade-in shrink-0">
                    <h2 class="text-lg md:text-2xl font-black tracking-tight text-on-surface uppercase leading-none">Crear cuenta</h2>
                    <p class="text-secondary text-[9px] md:text-xs mt-0.5 md:mt-1">Elige tu plan y empieza hoy.</p>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg px-3 py-1.5 md:py-2 fade-in shrink-0">
                    @foreach ($errors->all() as $error)
                    <p class="text-[10px] md:text-xs text-red-600 font-medium">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form action="/register" method="POST" class="flex flex-col gap-1 md:gap-2 w-full shrink-0">
                    @csrf

                    <!-- SECCIÓN PLANES -->
                    <div class="flex flex-col gap-1 fade-in delay-1">
                        <label class="text-[9px] font-bold tracking-widest text-secondary uppercase leading-none mt-1">Elige tu plan</label>
                        <input type="hidden" name="plan" id="plan-seleccionado" value="gratis"/>
                        
                        <div class="grid grid-cols-2 gap-1.5 md:gap-2">
                            <!-- PLAN GRATIS -->
                            <div class="plan-card tilt-card selected border-2 border-[#1737c8] rounded-xl p-2 md:p-3 relative" onclick="seleccionarPlan('gratis', this)">
                                <div class="plan-check absolute top-1.5 right-1.5 md:top-2 md:right-2 w-3.5 md:w-4 h-3.5 md:h-4 bg-[#1737c8] rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>
                                </div>
                                <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-[#747688] mb-0 md:mb-0.5">Gratis</p>
                                <p class="text-base md:text-lg font-black text-[#1a1c1c] leading-none mt-0.5">$0 <span class="text-[8px] md:text-xs font-normal text-[#747688]">/mes</span></p>
                                <ul class="mt-1 space-y-0 text-[8px] md:text-[9px] text-[#5e5e5e]">
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[8px] md:text-[10px]">check</span>20 productos</li>
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[8px] md:text-[10px]">check</span>1 usuario</li>
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-[#1737c8] text-[8px] md:text-[10px]">check</span>Ventas básicas</li>
                                </ul>
                            </div>
                            
                            <!-- PLAN PRO -->
                            <div class="plan-card tilt-card border-2 border-gray-200 rounded-xl p-2 md:p-3 relative bg-[#1737c8]" onclick="seleccionarPlan('pro', this)">
                                <div class="absolute -top-1.5 md:-top-2 left-1/2 -translate-x-1/2 bg-yellow-400 text-[#1a1c1c] text-[7px] md:text-[8px] font-black uppercase tracking-widest px-1.5 md:px-2 py-0 md:py-0.5 rounded-full whitespace-nowrap shadow-sm">
                                    Más popular
                                </div>
                                <div class="plan-check absolute top-1.5 right-1.5 md:top-2 md:right-2 w-3.5 md:w-4 h-3.5 md:h-4 bg-white rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[#1737c8] text-[8px] md:text-[10px]">check</span>
                                </div>
                                <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-white/70 mb-0 md:mb-0.5">Pro</p>
                                <p class="text-base md:text-lg font-black text-white leading-none mt-0.5">$499 <span class="text-[8px] md:text-xs font-normal text-white/60">/mes</span></p>
                                <ul class="mt-1 space-y-0 text-[8px] md:text-[9px] text-white/80">
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Ilimitado</li>
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Usuarios sin límite</li>
                                    <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>IA + Reportes</li>
                                </ul>
                            </div>

                            <!-- PLAN BUSINESS -->
                            <div class="plan-card tilt-card col-span-2 border-2 border-gray-200 rounded-xl p-2 md:p-3 relative bg-[#1a1c1c]" onclick="seleccionarPlan('business', this)">
                                <div class="plan-check absolute top-1.5 right-1.5 md:top-2 md:right-2 w-3.5 md:w-4 h-3.5 md:h-4 bg-white rounded-full flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined text-[#1a1c1c] text-[8px] md:text-[10px]">check</span>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-1 md:gap-3">
                                    <div class="flex-1">
                                        <p class="text-[7px] md:text-[8px] font-black uppercase tracking-widest text-white/50 mb-0 flex items-center gap-0.5"><span class="material-symbols-outlined text-[9px] md:text-[10px]">bolt</span> Para cadenas</p>
                                        <p class="text-xs md:text-sm font-black text-white leading-none mt-0.5 mb-0.5">Business</p>
                                        <p class="text-base md:text-xl font-black text-white tracking-tight leading-none mt-0.5">$999 <span class="text-[8px] md:text-xs font-normal text-white/60">/mes</span></p>
                                        <p class="text-[8px] md:text-[9px] text-white/60 leading-tight mt-0.5 hidden sm:block">Control total con rentabilidad real y chat IA financiero.</p>
                                    </div>
                                    <ul class="flex-1 grid grid-cols-2 sm:flex sm:flex-col gap-x-1 gap-y-0 border-t border-white/10 sm:border-t-0 sm:border-l pt-1 sm:pt-0 sm:pl-3 mt-0.5 sm:mt-0 text-[8px] md:text-[9px] text-white/80">
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Todo lo de Pro</li>
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>3 almacenes</li>
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Rentabilidad</li>
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>CFO con IA</li>
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Módulo gastos</li>
                                        <li class="flex items-center gap-0.5 md:gap-1"><span class="material-symbols-outlined text-white text-[8px] md:text-[10px]">check</span>Exportar PDF</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INPUTS FORMULARIO COMPACTADOS EN 2 COLUMNAS EN PC -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5 md:gap-3 mt-0.5 md:mt-1">
                        <div class="fade-in delay-2">
                            <label class="text-[9px] font-bold tracking-widest text-secondary uppercase mb-0.5 block leading-none">Nombre completo</label>
                            <input name="name" value="{{ old('name') }}"
                                   class="w-full bg-transparent border border-gray-300 rounded-lg px-3 py-1.5 md:py-2 text-[11px] md:text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none"
                                   type="text" placeholder="Tu nombre completo" required>
                        </div>
                        <div class="fade-in delay-3">
                            <label class="text-[9px] font-bold tracking-widest text-secondary uppercase mb-0.5 block leading-none">Correo electrónico</label>
                            <input name="email" value="{{ old('email') }}"
                                   class="w-full bg-transparent border border-gray-300 rounded-lg px-3 py-1.5 md:py-2 text-[11px] md:text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none"
                                   type="email" placeholder="tu@correo.com" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5 md:gap-3 mt-0 md:mt-1">
                        <div class="fade-in delay-4">
                            <label class="text-[9px] font-bold tracking-widest text-secondary uppercase mb-0.5 block leading-none">Contraseña</label>
                            <input name="password"
                                   class="w-full bg-transparent border border-gray-300 rounded-lg px-3 py-1.5 md:py-2 text-[11px] md:text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none"
                                   type="password" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div class="fade-in delay-5">
                            <label class="text-[9px] font-bold tracking-widest text-secondary uppercase mb-0.5 block leading-none">Confirmar contraseña</label>
                            <input name="password_confirmation"
                                   class="w-full bg-transparent border border-gray-300 rounded-lg px-3 py-1.5 md:py-2 text-[11px] md:text-sm focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none"
                                   type="password" placeholder="Repite tu contraseña" required>
                        </div>
                    </div>

                    <div id="aviso-pro" class="hidden bg-blue-50 border border-blue-200 rounded-lg px-2 py-1.5 text-[9px] md:text-[10px] text-blue-700 leading-tight mt-0.5">
                        <!-- Contenido dinámico -->
                    </div>

                    <button type="submit" id="btn-registro"
                            class="w-full bg-primary text-white font-bold py-2 md:py-2.5 rounded-lg uppercase tracking-widest text-[9px] md:text-[10px] fade-in delay-5 mt-1">
                        Crear cuenta gratis
                    </button>
                </form>

                <!-- BOTONES REDES SOCIALES -->
                <div class="flex items-center gap-2 md:gap-3 fade-in delay-5 my-0.5 shrink-0">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-gray-400">o continúa con</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="flex gap-2 fade-in delay-5 shrink-0">
                    <a href="{{ route('google.redirect') }}"
                       class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 md:py-2 border border-gray-200 rounded-lg font-semibold text-[10px] md:text-xs text-[#1a1c1c] bg-white hover:bg-gray-50 hover:border-gray-300 transition-all">
                        <svg width="14" height="14" viewBox="0 0 48 48" fill="none">
                            <path d="M43.611 20.083H42V20H24v8h11.303C33.654 32.657 29.332 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                            <path d="M6.306 14.691l6.571 4.819C14.655 15.108 19.001 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                            <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.314 0-9.822-3.422-11.408-8.167l-6.52 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                            <path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                        </svg>
                        Google
                    </a>
                    <button disabled class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 md:py-2 border border-gray-200 rounded-lg font-semibold text-[10px] md:text-xs text-gray-300 bg-white cursor-not-allowed">
                        <svg width="14" height="14" viewBox="0 0 814 1000" fill="none">
                            <path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-42.8-154.9-112.3C142.1 683.3 100.1 588.4 100.1 498c0-154.3 100.7-235.6 199.8-235.6 51.8 0 95.1 34.4 128.2 34.4 31.6 0 81.1-36.5 140.9-36.5 22.6 0 108.1 2 166.1 81.4zm-56.9-194.5c27.4-32.9 47.3-78.7 47.3-124.6 0-6.4-.6-12.8-1.9-18.9-44.9 1.9-98.4 30.2-130.3 67.7-24.8 27.4-48.5 73.3-48.5 119.8 0 7 1.3 13.9 1.9 16.2 2.6.6 6.4 1.3 10.2 1.3 40.3 0 90.4-26.8 121.3-61.5z" fill="#d1d5db"/>
                        </svg>
                        Apple
                    </button>
                </div>

                <!-- FOOTER -->
                <p class="flex items-center justify-center gap-1 text-[8px] text-gray-400 mt-1 pb-1 md:pb-0 shrink-0 fade-in delay-5">
                    <span class="material-symbols-outlined text-[10px]">lock</span>
                    Tus datos están protegidos con HTTPS.
                </p>

            </div>
        </div>
    </section>
</div>

<script>
// PLAN SELECTOR
function seleccionarPlan(plan, card) {
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('plan-seleccionado').value = plan;
    const btn = document.getElementById('btn-registro');
    const aviso = document.getElementById('aviso-pro');
    
    if (plan === 'pro') {
        btn.textContent = 'Crear cuenta y pagar $499';
        btn.style.background = '#1737c8';
        aviso.innerHTML = '<p class="font-bold">🔒 Plan Pro — $499/mes</p><p class="mt-0.5">Serás redirigido a Mercado Pago para completar el pago.</p>';
        aviso.className = 'bg-blue-50 border border-blue-200 rounded-lg px-2 py-1.5 text-[9px] md:text-[10px] text-blue-700 leading-tight fade-in mt-0.5';
        aviso.classList.remove('hidden');
    } else if (plan === 'business') {
        btn.textContent = 'Crear cuenta y pagar $999';
        btn.style.background = '#1a1c1c';
        aviso.innerHTML = '<p class="font-bold">🔒 Plan Business — $999/mes</p><p class="mt-0.5">Serás redirigido a Mercado Pago para completar el pago.</p>';
        aviso.className = 'bg-gray-100 border border-gray-300 rounded-lg px-2 py-1.5 text-[9px] md:text-[10px] text-gray-800 leading-tight fade-in mt-0.5';
        aviso.classList.remove('hidden');
    } else {
        btn.textContent = 'Crear cuenta gratis';
        btn.style.background = '#1737c8';
        aviso.classList.add('hidden');
    }
    
    // Efecto bounce en botón
    btn.style.transform = 'scale(0.95)';
    setTimeout(() => btn.style.transform = '', 200);
}

// PARTÍCULAS
function crearParticulas() {
    const container = document.getElementById('particles');
    if (!container) return;
    for (let i = 0; i < 20; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.cssText = `
            left: ${Math.random() * 100}%;
            width: ${Math.random() * 3 + 1}px;
            height: ${Math.random() * 3 + 1}px;
            animation-duration: ${Math.random() * 8 + 6}s;
            animation-delay: ${Math.random() * 5}s;
            --dx: ${(Math.random() - 0.5) * 60}px;
            opacity: 0;
        `;
        container.appendChild(p);
    }
}

// TILT en plan cards
function addTilt() {
    document.querySelectorAll('.tilt-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top) / r.height - 0.5;
            card.style.transform = `perspective(400px) rotateX(${-y * 10}deg) rotateY(${x * 10}deg) scale(1.04) translateY(-2px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}

// MOCKUP animado
function animarMockup() {
    const ventas = document.getElementById('mock-ventas');
    const util = document.getElementById('mock-util');
    const bars = document.querySelectorAll('.mbar');
    const aiEl = document.getElementById('mock-ai');
    let v = 12400;

    const aiMsgs = [
        'Nike Air talla 27 se agota en <strong style="color:#6b8ef5;">2 días</strong>',
        'Ventas <strong style="color:#6b8ef5;">+18%</strong> vs ayer',
        'Ticket promedio <strong style="color:#6b8ef5;">$326</strong> hoy',
        'Stock crítico: <strong style="color:#ef4444;">Jordan L</strong>',
    ];
    let aiIdx = 0;

    setInterval(() => {
        v += Math.floor(Math.random() * 600 + 100);
        if (ventas) {
            ventas.style.transform = 'scale(1.1)';
            ventas.textContent = '$' + v.toLocaleString('es-MX');
            setTimeout(() => ventas.style.transform = '', 200);
        }
        if (util) util.textContent = '$' + Math.floor(v * 0.55).toLocaleString('es-MX');
        bars.forEach(b => {
            const h = Math.floor(Math.random() * 70 + 20);
            b.style.height = h + '%';
            b.style.transition = 'height 0.7s ease';
            b.style.background = h > 70 ? '#1737c8' : 'rgba(23,55,200,0.3)';
        });
    }, 2000);

    setInterval(() => {
        aiIdx = (aiIdx + 1) % aiMsgs.length;
        if (aiEl) {
            aiEl.style.opacity = '0';
            setTimeout(() => { aiEl.innerHTML = aiMsgs[aiIdx]; aiEl.style.opacity = '1'; }, 300);
            aiEl.style.transition = 'opacity 0.3s';
        }
    }, 3500);
}

// 3D TILT en mockup al mover cursor
function mockupParallax() {
    const left = document.querySelector('section.hidden.md\\:flex');
    const mockup = document.getElementById('reg-mockup');
    if (!left || !mockup) return;
    left.addEventListener('mousemove', e => {
        const r = left.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        mockup.style.transform = `perspective(800px) rotateY(${8 + x * 8}deg) rotateX(${-3 + y * -5}deg)`;
        mockup.style.transition = 'transform 0.1s ease';
    });
    left.addEventListener('mouseleave', () => {
        mockup.style.transform = 'perspective(800px) rotateY(8deg) rotateX(-3deg)';
        mockup.style.transition = 'transform 0.5s ease';
    });
}

// CURSOR
function initCursor() {
    const c = document.getElementById('reg-cursor');
    const co = document.getElementById('reg-cursor-outer');
    let mx=0,my=0,ox=0,oy=0;
    document.addEventListener('mousemove', e => {
        mx=e.clientX; my=e.clientY;
        c.style.left=mx+'px'; c.style.top=my+'px';
    });
    function animC() {
        ox+=(mx-ox)*0.15; oy+=(my-oy)*0.15;
        co.style.left=ox+'px'; co.style.top=oy+'px';
        requestAnimationFrame(animC);
    }
    animC();
    document.querySelectorAll('a,button,.plan-card,input').forEach(el => {
        el.addEventListener('mouseenter', () => {
            c.style.width='18px'; c.style.height='18px';
            co.style.width='48px'; co.style.height='48px';
        });
        el.addEventListener('mouseleave', () => {
            c.style.width='10px'; c.style.height='10px';
            co.style.width='32px'; co.style.height='32px';
        });
    });
}

// INICIAR
crearParticulas();
addTilt();
animarMockup();
mockupParallax();
initCursor();
</script>
</body>
</html>