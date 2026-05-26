<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Configura tu tienda — Quivex</title>
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
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="bg-white text-on-surface antialiased selection:bg-primary selection:text-white min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-md flex flex-col gap-8">

        {{-- Logo --}}
        <div class="text-center">
            <span class="font-black tracking-tighter text-3xl text-[#1a1c1c]">
                Qui<span class="text-[#1737c8]">vex</span>
            </span>
        </div>

        {{-- Icono y título --}}
        <div class="flex flex-col items-center gap-4 text-center">
            <div class="w-16 h-16 bg-[#1737c8]/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#1737c8] text-3xl">storefront</span>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight uppercase">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                <p class="text-secondary text-sm mt-1">Una última cosa — ¿cómo se llama tu tienda?</p>
            </div>
        </div>

        {{-- Errores --}}
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            @foreach ($errors->all() as $error)
            <p class="text-sm text-red-600 font-medium">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- Formulario --}}
        <form action="/setup" method="POST" class="flex flex-col gap-5">
            @csrf
            <div>
                <label class="text-[10px] font-bold tracking-widest text-secondary uppercase mb-2 block">Nombre de tu tienda</label>
                <input name="store_name" value="{{ old('store_name') }}" autofocus
                       class="w-full bg-transparent border border-gray-300 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all duration-300 hover:border-primary text-lg font-semibold"
                       type="text" placeholder="Ej. Robles Sport, IrvingSport..." required>
                <p class="text-[11px] text-gray-400 mt-2">Este nombre aparecerá en tu panel y en tu catálogo público.</p>
            </div>

            <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:brightness-110 hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 uppercase tracking-widest text-sm">
                Ir a mi panel
            </button>
        </form>

        {{-- Pie --}}
        <p class="text-center text-[11px] text-gray-400">
            Puedes cambiar el nombre de tu tienda después desde configuración.
        </p>

    </div>

</body>
</html>