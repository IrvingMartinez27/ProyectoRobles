<!DOCTYPE html>
<!-- Documento HTML5 -->

<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <!-- Codificación UTF-8 -->

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <!-- Responsive -->

    <title>Panel de Administración - Roblesport</title>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>

    <!-- Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Configuración de colores -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0035c5",
                        surface: "#f9f9f9",
                        "on-surface": "#1a1c1c",
                        secondary: "#5e5e5e"
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; min-height: 100vh; }
    </style>
</head>

<body class="bg-white text-on-surface antialiased selection:bg-primary selection:text-white">

    <!-- HEADER -->
    <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl flex justify-center items-center px-6 h-16">
        
        <!-- LOGO con animación al pasar el mouse -->
        <div class="font-black tracking-tighter text-[#1a1c1c] text-2xl uppercase 
                    transition-all duration-300 hover:scale-110 hover:text-primary cursor-pointer">
            Roblesport
        </div>
    </header>

    <main class="flex flex-col md:flex-row min-h-screen">
        
        <!-- SECCIÓN IZQUIERDA -->
        <section class="hidden md:flex md:w-1/2 bg-slate-200 items-center justify-center p-12 overflow-hidden relative">
            
            <!-- IMAGEN DE FONDO con animación suave -->
            <div class="absolute inset-0 z-0">
                <img alt="Fondo decorativo" 
                     class="w-full h-full object-cover grayscale brightness-75 transition-all duration-500 hover:scale-105" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDg9QP40wMNox6ElGYVjkOfTX-5Topv2RBvTEgzWD-zPtsWSHu9g0wFjgWxITBuifpqrtE9qSoabW0qgbZTIcwiAlV4Alw18MS7ORfzb2IBsq6_LQdDdsc0SAIK2K1xLl1BbZnrMQDZVosJbTD-9cjxrqeg6t5xe2jtxHNy5FZnzY6EDXAx-wCxs2kor95F86WgFilXDGnue2aBB0F1sxi1_AeBym8oB4rfEo0V05VoEeOrPTsIHVskWdoyoj7nrk1-t94jQnoNViI"/> 
            </div>

            <!-- LOGO -->
            <div class="relative z-10 text-white flex flex-col gap-4 max-w-sm">
                <img src="/images/logo-white.svg" alt="Logo Blanco" class="w-64 h-auto object-contain"/>
            </div>
        </section>

        <!-- SECCIÓN DERECHA -->
        <section class="flex-1 flex flex-col justify-center items-center px-6 pt-24 pb-12 bg-white">
            
            <div class="w-full max-w-md flex flex-col gap-12">
                
                <!-- TÍTULO -->
                <div class="flex flex-col gap-2">
                    <h2 class="text-3xl font-black tracking-tight text-on-surface uppercase">
                        PORTAL DEL ADMINISTRADOR
                    </h2>

                    <p class="text-secondary text-sm">
                        Ingresa tus credenciales del administrador para acceder.
                    </p>
                </div>

                <!-- FORMULARIO -->
                <form action="/dashboard" method="GET" class="flex flex-col gap-8">
                    
                    <div class="flex flex-col gap-6">

                        <!-- EMAIL -->
                        <!-- group permite animar el contenedor -->
                        <div class="group transition-all duration-300 hover:scale-[1.02]">
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-2 block">
                                Correo Electronico:
                            </label>

                            <!-- INPUT con bordes redondeados + animaciones -->
                            <input name="email" 
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-4 py-3 
                                   focus:border-primary focus:ring-2 focus:ring-primary/30 
                                   outline-none transition-all duration-300 ease-in-out
                                   hover:border-primary hover:shadow-md"
                                   type="email" placeholder="admin@ejemplo.com" required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="group transition-all duration-300 hover:scale-[1.02]">
                            <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-2 block">
                                Contraseña:
                            </label>

                            <input name="password" 
                                   class="w-full bg-transparent border border-gray-300 rounded-xl px-4 py-3 
                                   focus:border-primary focus:ring-2 focus:ring-primary/30 
                                   outline-none transition-all duration-300 ease-in-out
                                   hover:border-primary hover:shadow-md"
                                   type="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- BOTÓN con animaciones -->
                    <button type="submit" 
                            class="w-full bg-primary text-white font-bold py-5 rounded-xl 
                            hover:brightness-110 hover:shadow-lg hover:scale-[1.03] 
                            active:scale-[0.97] transition-all duration-300 ease-in-out">
                        ACCEDER
                    </button>
                </form>

                <!-- TEXTO -->
                <div class="flex justify-center mt-4">

            <!-- Contenedor flex para alinear icono + texto -->
            <p class="flex items-center gap-2 text-[11px] text-gray-400 tracking-wide
              transition-all duration-300 hover:text-primary">

            <!-- ICONO DE CANDADO -->
            <span class="material-symbols-outlined text-sm transition-all duration-300 group-hover:scale-110">
            lock
            </span>

            <!-- TEXTO -->
            Uso exclusivo para personal autorizado de Roblesport.

            </p>

            </div>

            </div>
        </section>
    </main>

</body>
</html>