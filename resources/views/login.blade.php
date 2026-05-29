<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Iniciar sesión — Quivex</title>
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
        body { font-family: 'Inter', sans-serif; }
        html, body { height: 100%; overflow: hidden; }
        @media (max-width: 767px) {
            html, body { overflow: auto; }
        }
    </style>
</head>

<body class="bg-white text-on-surface antialiased selection:bg-primary selection:text-white h-full">

    <div class="flex flex-col md:flex-row h-full">

        <!-- IZQUIERDA — solo desktop/tablet landscape -->
        <section class="hidden md:flex md:w-1/2 bg-[#1a1c1c] items-center justify-center p-10 overflow-hidden relative shrink-0">
            <div class="absolute inset-0 z-0">
                <img alt="Fondo deportivo"
                     class="w-full h-full object-cover opacity-20"
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDg9QP40wMNox6ElGYVjkOfTX-5Topv2RBvTEgzWD-zPtsWSHu9g0wFjgWxITBuifpqrtE9qSoabW0qgbZTIcwiAlV4Alw18MS7ORfzb2IBsq6_LQdDdsc0SAIK2K1xLl1BbZnrMQDZVosJbTD-9cjxrqeg6t5xe2jtxHNy5FZnzY6EDXAx-wCxs2kor95F86WgFilXDGnue2aBB0F1sxi1_AeBym8oB4rfEo0V05VoEeOrPTsIHVskWdoyoj7nrk1-t94jQnoNViI"/>
            </div>
            <div class="relative z-10 text-white flex flex-col gap-6 max-w-sm w-full">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/30 mb-2">Bienvenido de vuelta a</p>
                    <div class="text-4xl font-black tracking-tight">Qui<span class="text-[#1737c8]">vex</span></div>
                    <p class="text-white/40 text-sm mt-2 leading-relaxed">Tu sistema de punto de venta para tiendas deportivas mexicanas.</p>
                </div>
                <div class="flex flex-col gap-0">
                    <div class="flex items-center gap-4 py-3 border-b border-white/8">
                        <span class="material-symbols-outlined text-[#1737c8] text-xl shrink-0">point_of_sale</span>
                        <div>
                            <p class="text-sm font-bold text-white">Ventas rápidas y sin complicaciones</p>
                            <p class="text-xs text-white/40 mt-0.5">Registra cada venta en segundos desde cualquier dispositivo.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-3 border-b border-white/8">
                        <span class="material-symbols-outlined text-[#1737c8] text-xl shrink-0">inventory_2</span>
                        <div>
                            <p class="text-sm font-bold text-white">Control total de tu inventario</p>
                            <p class="text-xs text-white/40 mt-0.5">Productos, stock y alertas en un solo panel.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-3 border-b border-white/8">
                        <span class="material-symbols-outlined text-[#1737c8] text-xl shrink-0">group</span>
                        <div>
                            <p class="text-sm font-bold text-white">Gestión de clientes incluida</p>
                            <p class="text-xs text-white/40 mt-0.5">Historial y seguimiento de tus mejores compradores.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-3">
                        <span class="material-symbols-outlined text-[#1737c8] text-xl shrink-0">lock</span>
                        <div>
                            <p class="text-sm font-bold text-white">Seguro y siempre disponible</p>
                            <p class="text-xs text-white/40 mt-0.5">Backups diarios, HTTPS y acceso desde cualquier lugar.</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/10 pt-4">
                    <p class="text-[11px] text-white/25 uppercase tracking-[0.2em] font-bold">Hecho para el mercado mexicano · En español</p>
                </div>
            </div>
        </section>

        <!-- DERECHA — formulario -->
        <section class="flex-1 flex flex-col h-full md:overflow-y-auto">

            <!-- Header móvil -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-white sticky top-0 z-10 md:relative">
                <a href="/" class="font-black tracking-tighter text-[#1a1c1c] text-2xl hover:text-primary transition-colors">
                    Qui<span class="text-[#1737c8]">vex</span>
                </a>
                <a href="/register" class="text-[11px] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors">
                    Regístrate →
                </a>
            </div>

            <!-- Contenido del formulario -->
            <div class="flex-1 flex items-center justify-center px-6 py-8">
                <div class="w-full max-w-md flex flex-col gap-6">

                    <div class="flex flex-col gap-1">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-on-surface uppercase">Iniciar sesión</h2>
                        <p class="text-secondary text-sm">Ingresa tus credenciales para acceder a tu panel.</p>
                    </div>

                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600 font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <form action="/login" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-2 block">Correo electrónico</label>
                            <input name="email" value="{{ old('email') }}"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="email" placeholder="tu@correo.com" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-2 block">Contraseña</label>
                            <input name="password"
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                   type="password" placeholder="••••••••" required>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary text-white font-bold py-3.5 rounded-xl hover:brightness-110 hover:shadow-lg active:scale-[0.97] transition-all uppercase tracking-widest text-sm">
                            Acceder al panel
                        </button>
                    </form>

                    <div class="flex items-center gap-4">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">o continúa con</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('google.redirect') }}"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl font-semibold text-sm text-[#1a1c1c] bg-white hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm transition-all">
                            <svg width="18" height="18" viewBox="0 0 48 48" fill="none">
                                <path d="M43.611 20.083H42V20H24v8h11.303C33.654 32.657 29.332 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                                <path d="M6.306 14.691l6.571 4.819C14.655 15.108 19.001 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                                <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.314 0-9.822-3.422-11.408-8.167l-6.52 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                                <path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                            </svg>
                            Google
                        </a>
                        <button disabled
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl font-semibold text-sm text-gray-300 bg-white cursor-not-allowed">
                            <svg width="18" height="18" viewBox="0 0 814 1000" fill="none">
                                <path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-42.8-154.9-112.3C142.1 683.3 100.1 588.4 100.1 498c0-154.3 100.7-235.6 199.8-235.6 51.8 0 95.1 34.4 128.2 34.4 31.6 0 81.1-36.5 140.9-36.5 22.6 0 108.1 2 166.1 81.4zm-56.9-194.5c27.4-32.9 47.3-78.7 47.3-124.6 0-6.4-.6-12.8-1.9-18.9-44.9 1.9-98.4 30.2-130.3 67.7-24.8 27.4-48.5 73.3-48.5 119.8 0 7 1.3 13.9 1.9 16.2 2.6.6 6.4 1.3 10.2 1.3 40.3 0 90.4-26.8 121.3-61.5z" fill="#d1d5db"/>
                            </svg>
                            Apple
                        </button>
                    </div>

                    <p class="flex items-center justify-center gap-2 text-[11px] text-gray-400 tracking-wide">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        Uso exclusivo para usuarios registrados de Quivex.
                    </p>

                </div>
            </div>
        </section>
    </div>

</body>
</html>