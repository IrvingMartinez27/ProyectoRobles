<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Planes — Quivex</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white min-h-screen">

@include('partials._nav')

<main class="max-w-5xl mx-auto px-6 pt-28 pb-20">

    <div class="text-center mb-14">
        <p class="text-[11px] font-bold uppercase tracking-widest text-[#1737c8] mb-3">Planes y precios</p>
        <h1 class="text-5xl font-black tracking-tight text-[#1a1c1c] mb-4">Elige tu plan</h1>
        <p class="text-[#5e5e5e] text-lg">Sin contratos. Cancela cuando quieras.</p>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-green-100 text-green-700 px-6 py-3 text-sm font-bold flex items-center gap-2 rounded-xl">
        <span class="material-symbols-outlined text-sm">check_circle</span>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-8 bg-red-100 text-red-700 px-6 py-3 text-sm font-bold flex items-center gap-2 rounded-xl">
        <span class="material-symbols-outlined text-sm">error</span>{{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- PLAN GRATIS --}}
        <div class="border border-[#c4c5da]/40 rounded-2xl p-8 flex flex-col">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-2">Para empezar</p>
            <h2 class="text-3xl font-black text-[#1a1c1c] mb-1">Gratis</h2>
            <div class="flex items-end gap-1 mb-4">
                <span class="text-4xl font-black text-[#1a1c1c]">$0</span>
                <span class="text-[#747688] mb-1">/mes</span>
            </div>
            <p class="text-sm text-[#5e5e5e] mb-6">Perfecto para tiendas que están arrancando.</p>
            <ul class="space-y-3 mb-8 flex-1">
                @foreach(['Hasta 100 productos', '1 usuario', 'Inventario básico', 'Registro de ventas'] as $feat)
                <li class="flex items-center gap-2 text-sm text-[#1a1c1c]">
                    <span class="material-symbols-outlined text-[#1737c8] text-sm">check</span>{{ $feat }}
                </li>
                @endforeach
                @foreach(['Asistente IA', 'Reportes avanzados'] as $feat)
                <li class="flex items-center gap-2 text-sm text-[#c4c5da]">
                    <span class="material-symbols-outlined text-[#c4c5da] text-sm">close</span>{{ $feat }}
                </li>
                @endforeach
            </ul>
            @if($plan === 'gratis')
            <div class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-[#f3f3f4] text-[#747688] rounded-xl">
                Plan actual
            </div>
            @else
            <div class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-[#f3f3f4] text-[#747688] rounded-xl">
                Plan incluido
            </div>
            @endif
        </div>

        {{-- PLAN PRO --}}
        <div class="bg-[#1737c8] rounded-2xl p-8 flex flex-col relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-yellow-400 text-[#1a1c1c] text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-full flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">star</span>Más popular
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Para crecer</p>
            <h2 class="text-3xl font-black text-white mb-1">Pro</h2>
            <div class="flex items-end gap-1 mb-4">
                <span class="text-4xl font-black text-white">$499</span>
                <span class="text-white/60 mb-1">/mes</span>
            </div>
            <p class="text-sm text-white/70 mb-6">Para tiendas que quieren crecer con inteligencia.</p>
            <ul class="space-y-3 mb-8 flex-1">
                @foreach(['Productos ilimitados', 'Múltiples usuarios', 'Asistente IA completo', 'Predicción de stock', 'Reportes avanzados', 'Registro por voz'] as $feat)
                <li class="flex items-center gap-2 text-sm text-white">
                    <span class="material-symbols-outlined text-white text-sm">check</span>{{ $feat }}
                </li>
                @endforeach
            </ul>
            @if($plan === 'pro')
            <div class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-white/20 text-white rounded-xl">
                ✓ Plan activo
            </div>
            @else
            <a href="/pago/crear-preferencia"
               class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-white text-[#1737c8] rounded-xl hover:bg-white/90 transition-all block">
                Elegir Pro →
            </a>
            @endif
        </div>

        {{-- PLAN BUSINESS --}}
        <div class="border border-[#c4c5da]/40 rounded-2xl p-8 flex flex-col">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#747688] mb-2">Para cadenas</p>
            <h2 class="text-3xl font-black text-[#1a1c1c] mb-1">Business</h2>
            <div class="flex items-end gap-1 mb-4">
                <span class="text-4xl font-black text-[#1a1c1c]">$999</span>
                <span class="text-[#747688] mb-1">/mes</span>
            </div>
            <p class="text-sm text-[#5e5e5e] mb-6">Multi-sucursal con IA entrenada en tus datos.</p>
            <ul class="space-y-3 mb-8 flex-1">
                @foreach(['Todo lo de Pro', 'Múltiples sucursales', 'IA personalizada', 'Exportar PDF', 'Soporte prioritario', 'Detector de anomalías'] as $feat)
                <li class="flex items-center gap-2 text-sm text-[#1a1c1c]">
                    <span class="material-symbols-outlined text-[#1737c8] text-sm">check</span>{{ $feat }}
                </li>
                @endforeach
            </ul>
            @if($plan === 'business')
            <div class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-[#f3f3f4] text-[#747688] rounded-xl">
                Plan actual
            </div>
            @else
            <a href="mailto:ventas@quivex.com"
               class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest bg-[#1a1c1c] text-white rounded-xl hover:opacity-90 transition-all block">
                Contactar ventas
            </a>
            @endif
        </div>

    </div>
</main>

</body>
</html>