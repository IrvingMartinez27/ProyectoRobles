<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quivex - Resumen diario</title>
@vite(['resources/css/app.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; min-height: 100dvh; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
</head>
<body class="bg-[#f9f9f9]">

@include('partials._nav')

<div class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#747688] py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#1737c8] transition-colors">
            <span class="material-symbols-outlined text-[14px]">grid_view</span>Inicio
        </a>
        <span class="material-symbols-outlined text-[14px] text-[#c4c5da]">chevron_right</span>
        <span class="text-[#1a1c1c]">Resumen diario</span>
    </div>
</div>

<main class="pb-20 md:pb-12 px-6 max-w-7xl mx-auto">
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[0.75rem] uppercase tracking-[0.2em] font-semibold text-[#1737c8] mb-2 block">Resumen</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1a1c1c]">Resumen diario</h1>
        </div>
        <a href="{{ route('dashboard') }}">
            <button class="bg-[#1a1c1c] text-white px-6 py-3 text-sm font-semibold tracking-wide hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>VOLVER AL INICIO
            </button>
        </a>
    </header>

    <section class="bg-white p-6 border border-[#c4c5da]/20 mb-8">
        <form method="GET" action="{{ route('resumen') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black tracking-widest uppercase text-[#747688]">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha', now()->toDateString()) }}" class="border border-[#c4c5da]/40 px-4 py-2 text-sm font-medium bg-[#f9f9f9] focus:outline-none focus:border-[#1737c8] transition-colors">
            </div>
            <button type="submit" class="bg-[#1737c8] text-white px-6 py-2 text-xs font-black tracking-widest uppercase hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">filter_list</span>FILTRAR
            </button>
            <a href="{{ route('resumen') }}">
                <button type="button" class="border border-[#c4c5da]/40 px-6 py-2 text-xs font-black tracking-widest uppercase hover:bg-[#f3f3f4] transition-all">HOY</button>
            </a>
        </form>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-[#1737c8] text-white p-8">
            <p class="text-[10px] font-black tracking-widest uppercase opacity-70 mb-2">Total del día</p>
            <h2 class="text-4xl font-black tracking-tight">${{ $totalDia ?? '0.00' }}</h2>
            <p class="text-[10px] tracking-widest uppercase opacity-60 mt-3">{{ request('fecha', now()->toDateString()) }}</p>
        </div>
        <div class="bg-[#f3f3f4] p-8 border border-[#c4c5da]/20">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#747688] mb-2">Número de ventas</p>
            <h2 class="text-4xl font-black tracking-tight text-[#1a1c1c]">{{ $numVentas ?? '0' }}</h2>
            <p class="text-[10px] tracking-widest uppercase text-[#747688] mt-3">Transacciones</p>
        </div>
        <div class="bg-[#f3f3f4] p-8 border border-[#c4c5da]/20">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#747688] mb-2">Ticket promedio</p>
            <h2 class="text-4xl font-black tracking-tight text-[#1a1c1c]">${{ $ticketPromedio ?? '0.00' }}</h2>
            <p class="text-[10px] tracking-widest uppercase text-[#747688] mt-3">Por transacción</p>
        </div>
    </div>

    <section class="bg-white p-8 border border-[#c4c5da]/20">
        <h2 class="text-xl font-bold tracking-tight mb-8">Productos vendidos hoy</h2>
        <div class="space-y-6">
            @forelse($productosDelDia ?? [] as $producto)
            <div class="flex items-center justify-between border-b border-[#c4c5da]/20 pb-4">
                <div>
                    <h3 class="text-sm font-bold uppercase">{{ $producto['nombre'] }}</h3>
                    <span class="text-[10px] text-[#747688] font-semibold tracking-widest uppercase">{{ $producto['cantidad'] }} unidades</span>
                </div>
                <span class="text-sm font-black text-[#1737c8]">${{ $producto['subtotal'] }}</span>
            </div>
            @empty
            <p class="text-[#747688] text-sm">Sin ventas para esta fecha</p>
            @endforelse
        </div>
    </section>
</main>

@include('partials._sidebar')

</body>
</html>