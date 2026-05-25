<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quivex — POS con IA para tiendas deportivas</title>
    <meta name="description" content="Sistema de punto de venta con inteligencia artificial para tiendas deportivas mexicanas.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f3f3f4; color: #1a1c1c; -webkit-font-smoothing: antialiased; }

        /* NAV */
        nav { position: sticky; top: 0; z-index: 50; background: rgba(243,243,244,0.92); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(196,197,218,0.25); }
        .nav-inner { max-width: 1100px; margin: 0 auto; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { font-size: 22px; font-weight: 900; letter-spacing: -1px; color: #1a1c1c; text-decoration: none; }
        .nav-logo span { color: #1737c8; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.13em; color: #747688; text-decoration: none; transition: color 0.15s; }
        .nav-links a:hover { color: #1a1c1c; }
        .btn-login { border: 1.5px solid #1a1c1c; color: #1a1c1c !important; padding: 9px 20px; font-size: 11px !important; font-weight: 800; text-transform: uppercase; letter-spacing: 0.13em; text-decoration: none; transition: background 0.15s, color 0.15s !important; }
        .btn-login:hover { background: #1a1c1c; color: #fff !important; }
        .btn-nav { background: #1737c8; color: #fff !important; padding: 9px 20px; font-size: 11px !important; font-weight: 800; text-transform: uppercase; letter-spacing: 0.13em; text-decoration: none; transition: opacity 0.15s; }
        .btn-nav:hover { opacity: 0.88; }

        /* CONTAINER */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }

        /* HERO */
        .hero { padding: 80px 0 60px; text-align: center; }
        .hero-badge { display: inline-block; background: rgba(23,55,200,0.08); color: #1737c8; font-size: 11px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; padding: 6px 16px; margin-bottom: 24px; }
        .hero h1 { font-size: clamp(42px,6vw,72px); font-weight: 900; letter-spacing: -2px; line-height: 1.05; color: #1a1c1c; margin-bottom: 24px; }
        .hero h1 em { font-style: normal; color: #1737c8; }
        .hero p { font-size: 18px; color: #747688; max-width: 520px; margin: 0 auto 40px; line-height: 1.6; }
        .hero-cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

        /* BUTTONS */
        .btn-primary { background: #1737c8; color: #fff; padding: 16px 32px; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; border: none; cursor: pointer; text-decoration: none; display: inline-block; transition: opacity 0.15s; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-secondary { background: transparent; color: #1a1c1c; padding: 16px 32px; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; border: 2px solid #1a1c1c; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.15s, color 0.15s; }
        .btn-secondary:hover { background: #1a1c1c; color: #fff; }

        /* STATS */
        .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: rgba(196,197,218,0.3); margin: 60px 0; }
        .stat { background: #f3f3f4; padding: 32px; text-align: center; }
        .stat-num { font-size: 40px; font-weight: 900; letter-spacing: -2px; color: #1a1c1c; }
        .stat-num span { color: #1737c8; }
        .stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #747688; margin-top: 4px; }

        /* SECTION */
        .section-label { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: #1737c8; margin-bottom: 12px; }
        .section-title { font-size: clamp(28px,4vw,40px); font-weight: 900; letter-spacing: -1px; margin-bottom: 8px; line-height: 1.1; }
        .section-sub { font-size: 16px; color: #747688; margin-bottom: 48px; }

        /* FEATURES */
        .features { padding: 60px 0; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap: 1px; background: rgba(196,197,218,0.3); }
        .feature-card { background: #fff; padding: 32px; }
        .feature-icon { width: 40px; height: 40px; background: rgba(23,55,200,0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 20px; color: #1737c8; }
        .feature-card h3 { font-size: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
        .feature-card p { font-size: 13px; color: #747688; line-height: 1.6; }
        .feature-card.highlight { background: #1737c8; }
        .feature-card.highlight h3, .feature-card.highlight p { color: #fff; }
        .feature-card.highlight .feature-icon { background: rgba(255,255,255,0.15); color: #fff; }

        /* IA */
        .ai-section { padding: 60px 0; }
        .ai-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: start; }
        .ai-list { display: flex; flex-direction: column; gap: 16px; }
        .ai-item { display: flex; gap: 16px; align-items: flex-start; padding: 20px; background: #fff; }
        .ai-item-icon { width: 36px; height: 36px; background: #1737c8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .ai-item h4 { font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .ai-item p { font-size: 12px; color: #747688; line-height: 1.5; }
        .ai-visual { background: #1a1c1c; padding: 32px; }
        .terminal-bar { display: flex; gap: 6px; margin-bottom: 20px; }
        .terminal-dot { width: 10px; height: 10px; border-radius: 50%; }
        .chat-bubble { background: rgba(255,255,255,0.07); border-radius: 4px; padding: 12px 16px; margin-bottom: 12px; }
        .chat-bubble.user { background: #1737c8; margin-left: 32px; }
        .chat-bubble .bubble-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); margin-bottom: 6px; }
        .chat-bubble.user .bubble-label { color: rgba(255,255,255,0.6); }
        .chat-bubble p { font-size: 13px; color: rgba(255,255,255,0.85); line-height: 1.5; }
        .chat-bubble p strong { color: #fff; }
        .typing { display: flex; gap: 4px; align-items: center; padding: 8px 0; }
        .dot { width: 6px; height: 6px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: qvx-pulse 1.4s infinite; }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes qvx-pulse { 0%,80%,100% { opacity: 0.3; } 40% { opacity: 1; } }

        /* PRICING */
        .pricing { padding: 60px 0; }
        .pricing-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: rgba(196,197,218,0.3); }
        .plan { background: #fff; padding: 40px 32px; display: flex; flex-direction: column; }
        .plan.featured { background: #1737c8; }
        .plan-badge { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #747688; margin-bottom: 16px; }
        .plan.featured .plan-badge { color: rgba(255,255,255,0.6); }
        .plan-name { font-size: 24px; font-weight: 900; letter-spacing: -0.5px; margin-bottom: 8px; }
        .plan.featured .plan-name, .plan.featured .plan-price, .plan.featured .plan-desc { color: #fff; }
        .plan-price { font-size: 44px; font-weight: 900; letter-spacing: -2px; line-height: 1; margin-bottom: 4px; }
        .plan-price sup { font-size: 20px; font-weight: 700; vertical-align: super; }
        .plan-price sub { font-size: 14px; font-weight: 500; letter-spacing: 0; vertical-align: bottom; }
        .plan-desc { font-size: 13px; color: #747688; margin-bottom: 32px; line-height: 1.5; }
        .plan-features { flex: 1; display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
        .plan-feature { display: flex; gap: 10px; align-items: flex-start; font-size: 13px; }
        .plan-feature .check { color: #1737c8; font-size: 16px; flex-shrink: 0; font-weight: 900; line-height: 1.4; }
        .plan.featured .plan-feature { color: rgba(255,255,255,0.9); }
        .plan.featured .plan-feature .check { color: rgba(255,255,255,0.8); }
        .plan-feature.disabled { opacity: 0.3; }
        .plan-cta { background: #1a1c1c; color: #fff; padding: 14px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; text-align: center; cursor: pointer; border: none; width: 100%; transition: opacity 0.15s; text-decoration: none; display: block; }
        .plan.featured .plan-cta { background: #fff; color: #1737c8; }
        .plan-cta:hover { opacity: 0.85; }

        /* TRUST */
        .trust-section { background: #fff; padding: 60px 0; }
        .trust-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1px; background: rgba(196,197,218,0.2); margin-top: 48px; }
        .trust-item { padding: 32px 24px; background: #fff; text-align: center; }
        .trust-icon { font-size: 28px; margin-bottom: 12px; color: #1737c8; }
        .trust-item h4 { font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .trust-item p { font-size: 12px; color: #747688; line-height: 1.5; }

        /* PAYMENTS */
        .mp-section { background: #1a1c1c; padding: 60px 0; }
        .mp-inner { max-width: 1100px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
        .mp-section .section-label { color: rgba(255,255,255,0.4); }
        .mp-section .section-title { color: #fff; }
        .mp-section .section-sub { color: rgba(255,255,255,0.5); margin-bottom: 32px; }
        .mp-methods { display: flex; flex-wrap: wrap; gap: 8px; }
        .mp-method { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); padding: 10px 16px; font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.1em; display: flex; align-items: center; gap: 6px; }
        .mp-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); padding: 32px; }
        .mp-card-label { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(255,255,255,0.4); margin-bottom: 24px; }
        .mp-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 14px; color: rgba(255,255,255,0.7); }
        .mp-row span:last-child { color: #fff; font-weight: 700; }
        .mp-btn { margin-top: 24px; background: #1737c8; color: #fff; padding: 14px; text-align: center; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; cursor: pointer; border: none; width: 100%; transition: opacity 0.15s; }
        .mp-btn:hover { opacity: 0.9; }

        /* CTA FINAL */
        .cta-section { padding: 80px 0; text-align: center; }
        .cta-section h2 { font-size: clamp(36px,5vw,56px); font-weight: 900; letter-spacing: -2px; margin-bottom: 16px; line-height: 1.05; }
        .cta-section h2 em { font-style: normal; color: #1737c8; }
        .cta-section p { font-size: 16px; color: #747688; margin-bottom: 40px; }

        /* FOOTER */
        footer { border-top: 1px solid rgba(196,197,218,0.3); padding: 32px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        footer p { font-size: 11px; color: #747688; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .ai-grid, .pricing-grid, .trust-grid, .mp-inner { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr; }
            .nav-links .nav-hide { display: none; }
        }
    </style>
</head>
<body>

<!-- ═══ NAV ═══ -->
<nav>
    <div class="nav-inner">
        <a href="/" class="nav-logo">Qui<span>vex</span></a>
        <div class="nav-links">
            <a href="#funciones" class="nav-hide">Funciones</a>
            <a href="#ia" class="nav-hide">IA</a>
            <a href="#precios" class="nav-hide">Precios</a>
            <a href="/login" class="btn-login">Iniciar sesión</a>
            <a href="/register" class="btn-nav">Registrarse</a>
        </div>
    </div>
</nav>

<!-- ═══ HERO ═══ -->
<div class="container">
    <section class="hero">
        <div class="hero-badge">Sistema POS con Inteligencia Artificial</div>
        <h1>El POS más inteligente<br>para tu <em>tienda deportiva</em></h1>
        <p>Vende más, controla tu inventario y deja que la IA trabaje por ti. Hecho para el mercado mexicano.</p>
        <div class="hero-cta">
            <a href="/register" class="btn-primary">Crear cuenta gratis →</a>
            <a href="/login" class="btn-secondary">Iniciar sesión</a>
        </div>
    </section>

    <!-- STATS -->
    <div class="stats">
        <div class="stat">
            <div class="stat-num">100<span>%</span></div>
            <div class="stat-label">En español</div>
        </div>
        <div class="stat">
            <div class="stat-num"><span>3</span>x</div>
            <div class="stat-label">Más barato que Shopify</div>
        </div>
        <div class="stat">
            <div class="stat-num">24<span>/7</span></div>
            <div class="stat-label">IA siempre activa</div>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="features" id="funciones">
        <div class="section-label">Por qué Quivex</div>
        <div class="section-title">Todo lo que tu tienda necesita</div>
        <p class="section-sub">Un sistema completo, sin complicaciones.</p>
        <div class="features-grid">
            <div class="feature-card highlight">
                <div class="feature-icon"><i class="ti ti-robot" aria-hidden="true"></i></div>
                <h3>IA que aprende tu negocio</h3>
                <p>Entre más lo usas, más inteligente se vuelve. Aprende tus productos, clientes y temporadas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ti ti-receipt" aria-hidden="true"></i></div>
                <h3>POS completo</h3>
                <p>Registra ventas en segundos. Interfaz rápida pensada para el mostrador, con soporte de voz.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ti ti-box" aria-hidden="true"></i></div>
                <h3>Inventario inteligente</h3>
                <p>Alertas de stock bajo, predicción de agotamiento y restock automático con un clic.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ti ti-chart-bar" aria-hidden="true"></i></div>
                <h3>Reportes en tiempo real</h3>
                <p>Analítica de ventas por día, semana o mes. Exporta PDF con un solo botón.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ti ti-users" aria-hidden="true"></i></div>
                <h3>Multiusuario y sucursales</h3>
                <p>Roles personalizados: el dueño ve todo, el vendedor solo lo que necesita.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ti ti-device-mobile" aria-hidden="true"></i></div>
                <h3>Desde cualquier dispositivo</h3>
                <p>Funciona en computadora, tablet y celular. Sin instalaciones, solo abre el navegador.</p>
            </div>
        </div>
    </section>

    <!-- IA -->
    <section class="ai-section" id="ia">
        <div class="section-label">Inteligencia Artificial</div>
        <div class="section-title">Tu asistente de negocio,<br>siempre disponible</div>
        <p class="section-sub">Pregúntale lo que quieras sobre tus ventas, clientes e inventario.</p>
        <div class="ai-grid">
            <div class="ai-list">
                <div class="ai-item">
                    <div class="ai-item-icon"><i class="ti ti-messages" aria-hidden="true"></i></div>
                    <div>
                        <h4>Lenguaje natural</h4>
                        <p>Pregunta en español y recibe análisis claros de tus ventas y tendencias del negocio.</p>
                    </div>
                </div>
                <div class="ai-item">
                    <div class="ai-item-icon"><i class="ti ti-trending-up" aria-hidden="true"></i></div>
                    <div>
                        <h4>Predicción de stock</h4>
                        <p>La IA predice cuándo se agotará cada producto antes de que te quedes sin existencias.</p>
                    </div>
                </div>
                <div class="ai-item">
                    <div class="ai-item-icon"><i class="ti ti-microphone" aria-hidden="true"></i></div>
                    <div>
                        <h4>Registro por voz</h4>
                        <p>Dicta ventas sin tocar el teclado. Ideal para momentos de alta afluencia en mostrador.</p>
                    </div>
                </div>
                <div class="ai-item">
                    <div class="ai-item-icon"><i class="ti ti-alarm" aria-hidden="true"></i></div>
                    <div>
                        <h4>Briefing diario</h4>
                        <p>Cada mañana, un resumen automático con lo más importante de tu negocio del día anterior.</p>
                    </div>
                </div>
            </div>
            <div class="ai-visual">
                <div class="terminal-bar">
                    <div class="terminal-dot" style="background:#ff5f57;"></div>
                    <div class="terminal-dot" style="background:#febc2e;"></div>
                    <div class="terminal-dot" style="background:#28c840;"></div>
                </div>
                <div class="chat-bubble">
                    <div class="bubble-label">Asistente Quivex</div>
                    <p>Buenos días. Ayer tuviste ventas por <strong>$12,400 MXN</strong>, un 18% más que el lunes anterior. Tus tenis Nike Air se están agotando — quedan 3 pares del talla 28.</p>
                </div>
                <div class="chat-bubble user">
                    <div class="bubble-label">Tú</div>
                    <p>¿Cuáles productos debo reponer esta semana?</p>
                </div>
                <div class="chat-bubble">
                    <div class="bubble-label">Asistente Quivex</div>
                    <p>Basado en tu ritmo de ventas, te recomiendo reponer: Nike Air Max (tallas 27–29), calcetas Adidas y shorts Under Armour. Históricamente tienes un pico los jueves.</p>
                </div>
                <div class="typing">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section class="pricing" id="precios">
        <div class="section-label">Planes</div>
        <div class="section-title">Empieza gratis, crece sin límites</div>
        <p class="section-sub">Sin sorpresas. Cancela cuando quieras.</p>
        <div class="pricing-grid">
            <div class="plan">
                <div class="plan-badge">Para empezar</div>
                <div class="plan-name">Gratis</div>
                <div class="plan-price"><sup>$</sup>0<sub>/mes</sub></div>
                <div class="plan-desc">Perfecto para tiendas que están arrancando.</div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="check">✓</span> Hasta 100 productos</div>
                    <div class="plan-feature"><span class="check">✓</span> 1 usuario</div>
                    <div class="plan-feature"><span class="check">✓</span> Inventario básico</div>
                    <div class="plan-feature"><span class="check">✓</span> Registro de ventas</div>
                    <div class="plan-feature disabled"><span class="check">✗</span> Asistente IA</div>
                    <div class="plan-feature disabled"><span class="check">✗</span> Reportes avanzados</div>
                </div>
                <a href="/register" class="plan-cta">Empezar gratis</a>
            </div>
            <div class="plan featured">
                <div class="plan-badge">⭐ Más popular</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price"><sup>$</sup>499<sub>/mes</sub></div>
                <div class="plan-desc">Para tiendas que quieren crecer con inteligencia.</div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="check">✓</span> Productos ilimitados</div>
                    <div class="plan-feature"><span class="check">✓</span> Múltiples usuarios</div>
                    <div class="plan-feature"><span class="check">✓</span> Asistente IA completo</div>
                    <div class="plan-feature"><span class="check">✓</span> Predicción de stock</div>
                    <div class="plan-feature"><span class="check">✓</span> Reportes avanzados</div>
                    <div class="plan-feature"><span class="check">✓</span> Registro por voz</div>
                </div>
                <a href="/register" class="plan-cta">Elegir Pro</a>
            </div>
            <div class="plan">
                <div class="plan-badge">Para cadenas</div>
                <div class="plan-name">Business</div>
                <div class="plan-price"><sup>$</sup>999<sub>/mes</sub></div>
                <div class="plan-desc">Multi-sucursal con IA entrenada en tus datos.</div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="check">✓</span> Todo lo de Pro</div>
                    <div class="plan-feature"><span class="check">✓</span> Múltiples sucursales</div>
                    <div class="plan-feature"><span class="check">✓</span> IA personalizada</div>
                    <div class="plan-feature"><span class="check">✓</span> Exportar PDF</div>
                    <div class="plan-feature"><span class="check">✓</span> Soporte prioritario</div>
                    <div class="plan-feature"><span class="check">✓</span> Detector de anomalías</div>
                </div>
                <a href="mailto:hola@quivex.mx" class="plan-cta">Contactar ventas</a>
            </div>
        </div>
    </section>
</div>

<!-- TRUST -->
<section class="trust-section">
    <div class="container">
        <div style="text-align:center;">
            <div class="section-label" style="text-align:center;">Seguridad y confianza</div>
            <div class="section-title">Tu negocio, protegido</div>
        </div>
        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-icon"><i class="ti ti-shield-check" aria-hidden="true"></i></div>
                <h4>Certificación PCI DSS</h4>
                <p>Pagos 100% seguros vía MercadoPago. Nunca guardamos datos de tarjetas.</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="ti ti-lock" aria-hidden="true"></i></div>
                <h4>2FA para el dueño</h4>
                <p>Doble verificación de identidad para el acceso de administrador.</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="ti ti-database" aria-hidden="true"></i></div>
                <h4>Backups diarios</h4>
                <p>Respaldo automático de todos tus datos cada 24 horas.</p>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><i class="ti ti-lock-access" aria-hidden="true"></i></div>
                <h4>HTTPS obligatorio</h4>
                <p>Toda la comunicación va encriptada de extremo a extremo.</p>
            </div>
        </div>
    </div>
</section>

<!-- MERCADOPAGO -->
<section class="mp-section">
    <div class="mp-inner">
        <div>
            <div class="section-label">Pagos</div>
            <div class="section-title">Cobra como quieras.<br>MercadoPago lo hace fácil.</div>
            <p class="section-sub">Tarjetas mexicanas, OXXO y SPEI. Cobro mensual automático.</p>
            <div class="mp-methods">
                <div class="mp-method"><i class="ti ti-credit-card" aria-hidden="true"></i> Tarjeta</div>
                <div class="mp-method"><i class="ti ti-building-store" aria-hidden="true"></i> OXXO</div>
                <div class="mp-method"><i class="ti ti-transfer" aria-hidden="true"></i> SPEI</div>
                <div class="mp-method"><i class="ti ti-refresh" aria-hidden="true"></i> Recurrente</div>
            </div>
        </div>
        <div class="mp-card">
            <div class="mp-card-label">Resumen de suscripción</div>
            <div class="mp-row"><span>Plan Pro</span><span>$499/mes</span></div>
            <div class="mp-row"><span>Próximo cobro</span><span>Automático</span></div>
            <div class="mp-row"><span>Método</span><span>MercadoPago</span></div>
            <button class="mp-btn">Gestionar suscripción</button>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<div class="container">
    <section class="cta-section">
        <h2>¿Listo para vender<br>más con <em>Quivex</em>?</h2>
        <p>Únete gratis hoy. Sin tarjeta de crédito, sin contratos.</p>
        <div class="hero-cta">
            <a href="/register" class="btn-primary">Crear cuenta gratis →</a>
            <a href="/login" class="btn-secondary">Iniciar sesión</a>
        </div>
    </section>

    <footer>
        <p>© {{ date('Y') }} Quivex — POS con IA para tiendas deportivas mexicanas</p>
        <p>MercadoPago · Laravel · Hecho en México 🇲🇽</p>
    </footer>
</div>

</body>
</html>