<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials._favicon')
<title>Pago pendiente — Quivex</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center px-6">
    <div class="max-w-md text-center">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-yellow-500 text-4xl">hourglass_top</span>
        </div>
        <h1 class="text-3xl font-black tracking-tight text-[#1a1c1c] mb-3">Pago en proceso</h1>
        <p class="text-[#5e5e5e] mb-6">Tu pago está siendo verificado por Mercado Pago. Esto puede tomar unos minutos. Tu cuenta se activará automáticamente cuando el pago sea confirmado.</p>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-8 text-sm text-yellow-700">
            Recibirás una notificación en tu correo cuando el pago sea aprobado.
        </div>
        <a href="/dashboard" class="inline-block bg-[#1737c8] text-white font-bold px-8 py-3 rounded-xl hover:opacity-90 transition-all uppercase tracking-widest text-sm">
            Ir al dashboard
        </a>
    </div>
</body>
</html>