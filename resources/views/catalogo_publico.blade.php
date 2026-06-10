<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $store_name }} — Catálogo</title>
<meta name="description" content="Catálogo de productos de {{ $store_name }}. Encuentra tus tallas favoritas y pregunta por WhatsApp.">
<meta property="og:title" content="{{ $store_name }} — Catálogo"/>
<meta property="og:description" content="Encuentra tus productos favoritos y pregunta por WhatsApp."/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Inter',system-ui,sans-serif;background:#f9f9f9;color:#1a1c1c;min-height:100dvh;-webkit-font-smoothing:antialiased;}

    /* NAV */
    nav{position:sticky;top:0;z-index:50;background:rgba(255,255,255,0.92);backdrop-filter:blur(12px);border-bottom:1px solid rgba(196,197,218,0.2);padding:14px 24px;display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .nav-logo{font-size:18px;font-weight:900;letter-spacing:-0.5px;color:#1a1c1c;}
    .nav-logo em{font-style:normal;color:#1737c8;}
    .nav-store{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#747688;}
    .btn-wa-nav{display:flex;align-items:center;gap:8px;background:#22c55e;color:#fff;padding:10px 18px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;border:none;cursor:pointer;transition:opacity 0.15s;text-decoration:none;white-space:nowrap;}
    .btn-wa-nav:hover{opacity:0.88;}

    /* HERO */
    .hero{background:#1a1c1c;padding:48px 24px;text-align:center;}
    .hero h1{font-size:clamp(28px,5vw,52px);font-weight:900;letter-spacing:-1.5px;color:#fff;margin-bottom:12px;line-height:1.05;}
    .hero h1 em{font-style:normal;color:#4d7fff;}
    .hero p{font-size:15px;color:rgba(255,255,255,0.5);margin-bottom:28px;}
    .hero-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(23,55,200,0.2);border:1px solid rgba(23,55,200,0.3);color:#6b8ef5;font-size:10px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;padding:5px 12px;margin-bottom:20px;}

    /* FILTROS */
    .filtros{max-width:1200px;margin:32px auto 0;padding:0 24px;}
    .filtros-inner{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;}
    .filtros-inner::-webkit-scrollbar{display:none;}
    .filtro-btn{padding:8px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;border:1.5px solid rgba(196,197,218,0.4);background:#fff;color:#747688;cursor:pointer;white-space:nowrap;transition:all 0.15s;}
    .filtro-btn.activo{background:#1a1c1c;color:#fff;border-color:#1a1c1c;}
    .filtro-btn:hover:not(.activo){border-color:#1a1c1c;color:#1a1c1c;}

    /* GRID */
    .grid-section{max-width:1200px;margin:0 auto;padding:32px 24px 100px;}
    .productos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:32px;}

    /* CARD */
    .producto-card{background:#fff;border:1px solid rgba(196,197,218,0.15);overflow:hidden;transition:transform 0.2s ease,box-shadow 0.2s ease;}
    .producto-card:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(0,0,0,0.1);}
    .card-img{aspect-ratio:4/5;background:#f3f3f4;overflow:hidden;position:relative;}
    .card-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease;}
    .producto-card:hover .card-img img{transform:scale(1.04);}
    .card-img-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#c4c5da;}
    .card-img-placeholder svg{width:48px;height:48px;}
    .stock-badge{position:absolute;top:12px;left:12px;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;padding:4px 10px;}
    .stock-badge.ok{background:#1737c8;color:#fff;}
    .stock-badge.poco{background:#1a1c1c;color:#fff;}
    .card-body{padding:16px;}
    .card-nombre{font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.02em;color:#1a1c1c;margin-bottom:4px;}
    .card-precio{font-size:18px;font-weight:900;color:#1737c8;margin-bottom:12px;}
    .card-tallas-label{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;color:#747688;margin-bottom:6px;}
    .card-tallas{display:flex;flex-wrap:wrap;gap:4px;margin-bottom:14px;}
    .talla-badge{background:#f3f3f4;padding:4px 8px;font-size:10px;font-weight:700;color:#1a1c1c;}
    .talla-badge.bajo{color:#ef4444;}
    .btn-preguntar{width:100%;padding:11px;background:#22c55e;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:opacity 0.15s;}
    .btn-preguntar:hover{opacity:0.88;}

    /* WA FLOTANTE */
    .wa-float{position:fixed;bottom:24px;right:24px;z-index:100;display:flex;align-items:center;gap:10px;background:#22c55e;color:#fff;padding:14px 20px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;box-shadow:0 8px 24px rgba(34,197,94,0.35);cursor:pointer;border:none;transition:all 0.2s;text-decoration:none;}
    .wa-float:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(34,197,94,0.45);}
    .wa-pulse{width:8px;height:8px;background:#fff;border-radius:50%;animation:wapulse 2s infinite;}
    @keyframes wapulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.5;transform:scale(0.8);}}

    /* EMPTY */
    .empty{text-align:center;padding:80px 24px;color:#747688;}
    .empty svg{width:56px;height:56px;margin:0 auto 16px;color:#c4c5da;}

    /* FOOTER */
    footer{background:#1a1c1c;padding:24px;text-align:center;}
    footer p{font-size:11px;color:rgba(255,255,255,0.3);font-weight:600;text-transform:uppercase;letter-spacing:0.1em;}
    footer a{color:#4d7fff;text-decoration:none;}

    /* ANIMACIONES */
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
    .producto-card{animation:fadeUp 0.4s ease both;}
    .producto-card:nth-child(1){animation-delay:0.03s;}
    .producto-card:nth-child(2){animation-delay:0.06s;}
    .producto-card:nth-child(3){animation-delay:0.09s;}
    .producto-card:nth-child(4){animation-delay:0.12s;}
    .producto-card:nth-child(5){animation-delay:0.15s;}
    .producto-card:nth-child(6){animation-delay:0.18s;}
    .producto-card:nth-child(n+7){animation-delay:0.21s;}

    @media(max-width:600px){
        nav{padding:12px 16px;}
        .hero{padding:36px 16px;}
        .filtros,.grid-section{padding-left:16px;padding-right:16px;}
        .productos-grid{grid-template-columns:repeat(2,1fr);gap:16px;}
        .card-body{padding:12px;}
        .card-nombre{font-size:12px;}
        .wa-float{padding:12px 16px;font-size:11px;bottom:16px;right:16px;}
    }
</style>
</head>
<body>

<!-- NAV -->
<nav>
    <div>
        <div class="nav-store">Catálogo</div>
        <div class="nav-logo">{{ $store_name }}</div>
    </div>
    @if($whatsapp)
    <a href="https://wa.me/52{{ preg_replace('/\D/', '', $whatsapp) }}?text={{ urlencode('Hola, vi tu catálogo y me interesa preguntar por un producto 👋') }}"
       target="_blank" class="btn-wa-nav">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
        Preguntar por WhatsApp
    </a>
    @endif
</nav>

<!-- HERO -->
<div class="hero">
    <div class="hero-badge">
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="5" fill="#22c55e"/></svg>
        Catálogo en línea
    </div>
    <h1>Bienvenido a<br><em>{{ $store_name }}</em></h1>
    <p>Explora nuestro catálogo y pregunta por tu talla favorita</p>
</div>

<!-- FILTROS -->
<div class="filtros">
    <div class="filtros-inner">
        <button class="filtro-btn activo" onclick="filtrar('todos', this)">Todos ({{ count($productos) }})</button>
        @php
            $cats = $productos->pluck('categoria')->unique()->sort()->values();
        @endphp
        @foreach($cats as $cat)
        <button class="filtro-btn" onclick="filtrar('{{ $cat }}', this)">{{ ucfirst($cat) }}</button>
        @endforeach
    </div>
</div>

<!-- PRODUCTOS -->
<div class="grid-section">
    @if($productos->isEmpty())
    <div class="empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
        <p style="font-size:16px;font-weight:700;color:#1a1c1c;margin-bottom:6px;">Sin productos disponibles</p>
        <p style="font-size:13px;">Vuelve pronto para ver el catálogo actualizado.</p>
    </div>
    @else
    <div class="productos-grid" id="grid-productos">
        @foreach($productos as $producto)
        <div class="producto-card" data-categoria="{{ $producto['categoria'] }}">
            <div class="card-img">
                @if($producto['imagen'])
                <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" loading="lazy"/>
                @else
                <div class="card-img-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                @endif
                @if($producto['stock_total'] < 10 && $producto['stock_total'] > 0)
                <div class="stock-badge poco">Pocas piezas</div>
                @elseif($producto['stock_total'] >= 10)
                <div class="stock-badge ok">Disponible</div>
                @endif
            </div>
            <div class="card-body">
                <div class="card-nombre">{{ $producto['nombre'] }}</div>
                <div class="card-precio">${{ number_format($producto['precio'], 2) }}</div>
                @if(count($producto['tallas']) > 0)
                <div class="card-tallas-label">Tallas disponibles</div>
                <div class="card-tallas">
                    @foreach($producto['tallas'] as $talla => $stock)
                    <span class="talla-badge {{ $stock <= 2 ? 'bajo' : '' }}">{{ $talla }}</span>
                    @endforeach
                </div>
                @endif
                @if($whatsapp)
                <button class="btn-preguntar"
                        onclick="preguntarProducto('{{ $producto['nombre'] }}', '{{ number_format($producto['precio'], 2) }}')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                    Preguntar por WhatsApp
                </button>
                @else
                <div style="width:100%;padding:11px;background:#f3f3f4;color:#747688;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;text-align:center;">
                    Contactar a la tienda
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- WA FLOTANTE -->
@if($whatsapp)
<a href="https://wa.me/52{{ preg_replace('/\D/', '', $whatsapp) }}?text={{ urlencode('Hola! Vi tu catálogo y quiero hacer una pregunta 👋') }}"
   target="_blank" class="wa-float">
    <div class="wa-pulse"></div>
    <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.849L0 24l6.341-1.501A11.933 11.933 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.369l-.359-.214-3.722.881.916-3.618-.235-.372A9.808 9.808 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
    Preguntar por WhatsApp
</a>
@endif

<!-- FOOTER -->
<footer>
    <p>{{ $store_name }} · Catálogo digital · Powered by <a href="/">Quivex</a></p>
</footer>

<script>
const WA = '{{ $whatsapp ? preg_replace("/\D/", "", $whatsapp) : "" }}';
const STORE = '{{ $store_name }}';

function preguntarProducto(nombre, precio) {
    if (!WA) return;
    const msg = `Hola ${STORE}! 👋 Vi tu catálogo y me interesa el producto:\n\n*${nombre}*\nPrecio: $${precio}\n\n¿Puedes darme más información sobre disponibilidad y tallas?`;
    window.open(`https://wa.me/52${WA}?text=${encodeURIComponent(msg)}`, '_blank');
}

function filtrar(cat, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');
    document.querySelectorAll('.producto-card').forEach(c => {
        c.style.display = (cat === 'todos' || c.dataset.categoria === cat) ? '' : 'none';
    });
}
</script>
</body>
</html>