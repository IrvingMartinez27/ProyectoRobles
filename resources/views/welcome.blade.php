<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quivex — El sistema más inteligente para tu tienda o showroom de moda</title>
    <meta name="description" content="Organiza tus tallas y modelos, registra ventas por voz y conoce tu ganancia neta real. Para marcas locales, importaciones y streetwear en México.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;vertical-align:middle;}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg:#f3f3f4; --bg2:#ffffff; --bg3:#f3f3f4; --text:#1a1c1c; --text2:#747688;
            --border:rgba(196,197,218,0.3); --card:#ffffff; --nav-bg:rgba(243,243,244,0.92);
            --dark-section:#1a1c1c;
        }
        [data-theme="dark"] {
            --bg:#0d0f10; --bg2:#1a1c1c; --bg3:#141618; --text:#f3f3f4; --text2:#9496a8;
            --border:rgba(255,255,255,0.08); --card:#1e2022; --nav-bg:rgba(13,15,16,0.92);
            --dark-section:#141618;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        html{scroll-behavior:smooth;}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;transition:background 0.3s,color 0.3s;}

        /* NAV */
        nav{position:sticky;top:0;z-index:100;background:var(--nav-bg);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);}
        .nav-inner{max-width:1140px;margin:0 auto;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;}
        .nav-logo{font-size:20px;font-weight:900;letter-spacing:-1px;color:var(--text);text-decoration:none;display:flex;align-items:center;gap:8px;}
        .nav-logo em{font-style:normal;color:#1737c8;}
        .nav-links{display:flex;align-items:center;gap:6px;}
        .nav-link{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.13em;color:var(--text2);text-decoration:none;padding:8px 10px;transition:color 0.15s;}
        .nav-link:hover{color:var(--text);}
        .btn-theme{background:none;border:1.5px solid var(--border);color:var(--text2);width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;transition:all 0.2s;}
        .btn-theme:hover{border-color:#1737c8;color:#1737c8;}
        .btn-login{border:1.5px solid var(--border);border-radius:8px;color:var(--text);padding:9px 16px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.13em;text-decoration:none;transition:all 0.15s;}
        .btn-login:hover{border-color:var(--text);}
        .btn-nav{background:#1737c8;color:#fff;border-radius:8px;padding:9px 16px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.13em;text-decoration:none;transition:opacity 0.15s;}
        .btn-nav:hover{opacity:0.88;}

        /* HERO */
        .hero-section{background:#0d0f10;position:relative;overflow:hidden;}
        .hero-bg-lines{position:absolute;top:0;left:0;right:0;bottom:0;pointer-events:none;}
        .hero-inner{max-width:1140px;margin:0 auto;padding:60px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;}
        .hero-left{padding-bottom:60px;}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(23,55,200,0.15);border:1px solid rgba(23,55,200,0.3);color:#6b8ef5;font-size:10px;font-weight:800;letter-spacing:0.18em;text-transform:uppercase;padding:6px 12px;margin-bottom:24px;border-radius:4px;}
        .hero-badge-dot{width:6px;height:6px;background:#6b8ef5;border-radius:50%;animation:blink 2s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:0.3}}
        .hero-h1{font-size:clamp(28px,4vw,52px);font-weight:900;letter-spacing:-2px;line-height:1.0;color:#fff;margin-bottom:16px;}
        .hero-h1 em{font-style:normal;color:#4d7fff;}
        .hero-sub{font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;margin-bottom:28px;max-width:380px;}
        .hero-btns{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;}
        .btn-primary-hero{background:#1737c8;color:#fff;padding:14px 24px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;border-radius:8px;transition:opacity 0.15s;}
        .btn-primary-hero:hover{opacity:0.88;}
        .btn-secondary-hero{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.7);padding:14px 24px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;border-radius:8px;transition:all 0.15s;}
        .btn-secondary-hero:hover{background:rgba(255,255,255,0.1);}
        .trust-row{display:flex;gap:16px;flex-wrap:wrap;}
        .trust-item{display:flex;align-items:center;gap:6px;font-size:11px;color:rgba(255,255,255,0.35);font-weight:600;}
        .trust-check{width:14px;height:14px;background:rgba(34,197,94,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:8px;color:#22c55e;}

        /* DEVICE */
        .device-wrap{position:relative;padding-bottom:40px;}
        .device{background:#1a1c1e;border:1px solid rgba(255,255,255,0.08);border-radius:12px;overflow:hidden;transform:perspective(800px) rotateY(-8deg) rotateX(3deg);transform-origin:left center;}
        .device-bar{background:#141618;border-bottom:1px solid rgba(255,255,255,0.06);padding:10px 14px;display:flex;align-items:center;gap:10px;}
        .device-dots{display:flex;gap:5px;}
        .device-dot{width:8px;height:8px;border-radius:50%;}
        .device-url{flex:1;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.06);border-radius:4px;padding:4px 10px;font-size:10px;color:rgba(255,255,255,0.3);font-family:monospace;}
        .device-body{padding:14px;}
        .kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px;}
        .kpi{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);border-radius:6px;padding:10px 12px;}
        .kpi.blue{background:#1737c8;border-color:#1737c8;}
        .kpi-lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);margin-bottom:4px;}
        .kpi.blue .kpi-lbl{color:rgba(255,255,255,0.6);}
        .kpi-val{font-size:18px;font-weight:900;color:#fff;letter-spacing:-0.5px;}
        .chart-area{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:6px;padding:12px;margin-bottom:10px;height:80px;display:flex;align-items:flex-end;gap:4px;}
        .bar{flex:1;border-radius:2px 2px 0 0;background:rgba(23,55,200,0.25);}
        .bar.hi{background:#1737c8;}
        .ai-card{background:rgba(23,55,200,0.1);border:1px solid rgba(23,55,200,0.2);border-radius:6px;padding:10px 12px;display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;}
        .ai-icon{width:22px;height:22px;background:#1737c8;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
        .ai-text{font-size:10px;color:rgba(255,255,255,0.6);line-height:1.5;}
        .ai-text strong{color:#6b8ef5;}
        .products-row{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;}
        .product-card{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:6px;padding:8px 10px;}
        .product-name{font-size:9px;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;margin-bottom:2px;}
        .product-price{font-size:12px;font-weight:900;color:#fff;}
        .product-stock{font-size:8px;color:rgba(255,255,255,0.3);margin-top:2px;}
        .stock-bar{height:2px;background:rgba(255,255,255,0.08);border-radius:1px;margin-top:6px;}
        .stock-fill{height:100%;border-radius:1px;}
        .floating-badge{position:absolute;background:#0d0f10;border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:8px;font-size:11px;color:rgba(255,255,255,0.8);font-weight:600;white-space:nowrap;}
        .floating-badge.b1{top:20px;right:-10px;animation:float 3s ease-in-out infinite;}
        .floating-badge.b2{bottom:80px;left:-16px;animation:float 3s ease-in-out infinite 1.5s;}
        @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-6px);}}
        .fb-icon{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:14px;}

        /* CONTAINER */
        .container{max-width:1140px;margin:0 auto;padding:0 20px;}

        /* SECTIONS */
        .section-eyebrow{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.2em;color:#1737c8;margin-bottom:12px;}
        .section-title{font-size:clamp(24px,4vw,44px);font-weight:900;letter-spacing:-1.5px;color:var(--text);line-height:1.05;margin-bottom:12px;}
        .section-sub{font-size:15px;color:var(--text2);line-height:1.6;margin-bottom:40px;}

        /* STATS */
        .stats-bar{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);margin:60px 0;}
        .stat{background:var(--bg2);padding:32px 24px;text-align:center;}
        .stat-num{font-size:44px;font-weight:900;letter-spacing:-2px;color:var(--text);line-height:1;}
        .stat-num em{font-style:normal;color:#1737c8;}
        .stat-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:var(--text2);margin-top:8px;}

        /* FEATURES */
        .features{padding:60px 0;}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);}
        .feature-card{background:var(--card);padding:32px 28px;transition:background 0.2s;}
        .feature-card:hover{background:var(--bg3);}
        .feature-card.highlight{background:#1737c8;}
        .feature-card.highlight:hover{background:#1535b5;}
        .feature-num{font-size:11px;font-weight:900;color:var(--text2);letter-spacing:0.15em;margin-bottom:16px;font-family:monospace;}
        .feature-card.highlight .feature-num{color:rgba(255,255,255,0.4);}
        .feature-icon{font-size:28px;margin-bottom:12px;}
        .feature-card h3{font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;color:var(--text);}
        .feature-card p{font-size:13px;color:var(--text2);line-height:1.65;}
        .feature-card.highlight h3{color:#fff;}
        .feature-card.highlight p{color:rgba(255,255,255,0.7);}

        /* IA SECTION */
        .ai-section{padding:60px 0;}
        .ai-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;}
        .ai-list{display:flex;flex-direction:column;gap:12px;}
        .ai-item{display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--card);border:1px solid var(--border);border-radius:8px;transition:border-color 0.2s;}
        .ai-item:hover{border-color:#1737c8;}
        .ai-item-num{font-size:11px;font-weight:900;color:#1737c8;font-family:monospace;margin-top:2px;flex-shrink:0;}
        .ai-item h4{font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;color:var(--text);}
        .ai-item p{font-size:12px;color:var(--text2);line-height:1.6;}
        .ai-visual{background:#0d0f10;border:1px solid rgba(255,255,255,0.06);border-radius:8px;padding:24px;}
        .terminal-bar{display:flex;align-items:center;gap:8px;margin-bottom:20px;}
        .terminal-dot{width:10px;height:10px;border-radius:50%;}
        .terminal-title{font-size:11px;color:rgba(255,255,255,0.3);font-family:monospace;margin-left:8px;}
        .chat-msg{margin-bottom:14px;}
        .chat-label{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;color:rgba(255,255,255,0.3);margin-bottom:6px;}
        .chat-bubble{padding:12px 16px;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;border-radius:8px;}
        .chat-bubble.bot{background:rgba(255,255,255,0.05);border-left:2px solid #1737c8;}
        .chat-bubble.user{background:#1737c8;margin-left:24px;}
        .chat-bubble strong{color:#fff;}
        .typing-dots{display:flex;gap:4px;padding:12px 0;}
        .typing-dot{width:6px;height:6px;background:rgba(255,255,255,0.3);border-radius:50%;animation:tdot 1.4s infinite;}
        .typing-dot:nth-child(2){animation-delay:0.2s;}
        .typing-dot:nth-child(3){animation-delay:0.4s;}
        @keyframes tdot{0%,80%,100%{opacity:0.3;transform:scale(0.8);}40%{opacity:1;transform:scale(1);}}

        /* PRICING */
        .pricing{padding:60px 0;}
        .pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);border-radius:12px;overflow:hidden;}
        .plan{background:var(--card);padding:36px 28px;display:flex;flex-direction:column;}
        .plan.featured{background:#1737c8;}
        .plan-badge{font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.2em;color:var(--text2);margin-bottom:16px;}
        .plan.featured .plan-badge{color:rgba(255,255,255,0.6);}
        .plan-name{font-size:26px;font-weight:900;letter-spacing:-1px;color:var(--text);margin-bottom:8px;}
        .plan.featured .plan-name{color:#fff;}
        .plan-price{display:flex;align-items:baseline;gap:4px;margin-bottom:8px;}
        .plan-price-sym{font-size:18px;font-weight:700;color:var(--text2);}
        .plan-price-num{font-size:48px;font-weight:900;letter-spacing:-2px;color:var(--text);line-height:1;}
        .plan.featured .plan-price-sym,.plan.featured .plan-price-num{color:#fff;}
        .plan-price-per{font-size:13px;color:var(--text2);font-weight:500;}
        .plan.featured .plan-price-per{color:rgba(255,255,255,0.6);}
        .plan-desc{font-size:13px;color:var(--text2);margin-bottom:24px;line-height:1.6;}
        .plan.featured .plan-desc{color:rgba(255,255,255,0.7);}
        .plan-divider{height:1px;background:var(--border);margin-bottom:20px;}
        .plan.featured .plan-divider{background:rgba(255,255,255,0.15);}
        .plan-features{flex:1;display:flex;flex-direction:column;gap:10px;margin-bottom:28px;}
        .plan-feature{display:flex;gap:10px;align-items:flex-start;font-size:13px;color:var(--text);}
        .plan.featured .plan-feature{color:rgba(255,255,255,0.9);}
        .plan-feature.off{opacity:0.3;}
        .plan-cta{background:var(--text);color:var(--bg);padding:14px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;text-align:center;text-decoration:none;display:block;border-radius:8px;transition:opacity 0.15s;border:none;cursor:pointer;}
        .plan.featured .plan-cta{background:#fff;color:#1737c8;}
        .plan-cta:hover{opacity:0.85;}

        /* TRUST */
        .trust-section{background:var(--bg2);padding:60px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);}
        .trust-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);margin-top:40px;border-radius:12px;overflow:hidden;}
        .trust-item-card{background:var(--bg2);padding:28px 20px;text-align:center;}
        .trust-icon{font-size:32px;margin-bottom:14px;color:#1737c8;}
        .trust-item-card h4{font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;color:var(--text);margin-bottom:8px;}
        .trust-item-card p{font-size:12px;color:var(--text2);line-height:1.6;}

        /* MP */
        .mp-section{background:var(--dark-section);padding:60px 0;}
        .mp-inner{max-width:1140px;margin:0 auto;padding:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}
        .mp-section .section-eyebrow{color:rgba(255,255,255,0.4);}
        .mp-section .section-title{color:#fff;}
        .mp-section .section-sub{color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .mp-methods{display:flex;flex-wrap:wrap;gap:8px;}
        .mp-method{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:10px 14px;font-size:12px;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:6px;}
        .mp-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:28px;}
        .mp-card-header{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.2em;color:rgba(255,255,255,0.3);margin-bottom:20px;}
        .mp-row{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:14px;}
        .mp-key{color:rgba(255,255,255,0.5);}
        .mp-val{color:#fff;font-weight:700;}
        .mp-btn{margin-top:20px;background:#1737c8;color:#fff;padding:14px;text-align:center;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;border:none;width:100%;cursor:pointer;border-radius:8px;transition:opacity 0.15s;}
        .mp-btn:hover{opacity:0.9;}

        /* CTA FINAL */
        .cta-section{padding:80px 0;text-align:center;}
        .cta-section h2{font-size:clamp(28px,5vw,56px);font-weight:900;letter-spacing:-2px;color:var(--text);margin-bottom:16px;line-height:1.05;}
        .cta-section h2 em{font-style:normal;color:#1737c8;}
        .cta-section p{font-size:15px;color:var(--text2);margin-bottom:36px;}
        .btn-primary{background:#1737c8;color:#fff;padding:14px 28px;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;border-radius:8px;transition:opacity 0.2s;}
        .btn-primary:hover{opacity:0.9;}
        .btn-secondary{background:transparent;color:var(--text);padding:14px 28px;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;border:2px solid var(--border);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;border-radius:8px;transition:all 0.2s;}
        .btn-secondary:hover{border-color:var(--text);}
        .hero-cta{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
        .cta-trust{display:flex;gap:20px;justify-content:center;flex-wrap:wrap;margin-top:28px;}
        .cta-trust-item{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text2);font-weight:600;}
        .cta-trust-dot{width:6px;height:6px;background:#22c55e;border-radius:50%;}

        /* FOOTER */
        footer{border-top:1px solid var(--border);padding:28px 0;}
        .footer-inner{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
        .footer-logo{font-size:16px;font-weight:900;color:var(--text);letter-spacing:-0.5px;display:flex;align-items:center;gap:8px;}
        .footer-logo em{font-style:normal;color:#1737c8;}
        footer p{font-size:11px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:0.1em;}

        /* FADE */
        .fade-up{opacity:0;transform:translateY(24px);transition:opacity 0.6s ease,transform 0.6s ease;}
        .fade-up.visible{opacity:1;transform:translateY(0);}

        /* QUIV CHAT */
        #quiv-container{position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:12px;}
        #quiv-chat{width:300px;background:#fff;border:1px solid rgba(0,0,0,0.1);border-radius:16px;overflow:hidden;display:none;flex-direction:column;box-shadow:0 8px 40px rgba(0,0,0,0.15);}
        [data-theme="dark"] #quiv-chat{background:#1a1c1e;border-color:rgba(255,255,255,0.08);}
        #quiv-chat.open{display:flex;}
        .qchat-header{background:#1737c8;padding:14px 16px;display:flex;align-items:center;gap:10px;}
        .qavatar{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
        .qname{font-size:13px;font-weight:700;color:#fff;}
        .qstatus{font-size:11px;color:rgba(255,255,255,0.6);display:flex;align-items:center;gap:4px;}
        .qstatus-dot{width:6px;height:6px;background:#22c55e;border-radius:50%;}
        .qclose{background:none;border:none;color:rgba(255,255,255,0.7);cursor:pointer;font-size:20px;line-height:1;padding:0;margin-left:auto;}
        .qmessages{padding:16px;display:flex;flex-direction:column;gap:10px;min-height:160px;max-height:240px;overflow-y:auto;}
        .qmsg{display:flex;gap:8px;align-items:flex-start;animation:qfade 0.3s ease;}
        @keyframes qfade{from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);}}
        .qmsg-avatar{width:28px;height:28px;border-radius:50%;background:#1737c8;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;}
        .qbubble{background:#f3f3f4;border-radius:0 12px 12px 12px;padding:10px 12px;font-size:13px;color:#1a1c1c;line-height:1.5;max-width:220px;}
        [data-theme="dark"] .qbubble{background:#2a2c2e;color:#f3f3f4;}
        .qbubble strong{color:#1737c8;}
        .qtyping{background:#f3f3f4;border-radius:0 12px 12px 12px;padding:12px 16px;display:flex;gap:4px;align-items:center;}
        [data-theme="dark"] .qtyping{background:#2a2c2e;}
        .qtdot{width:6px;height:6px;background:#747688;border-radius:50%;animation:qtd 1.2s infinite;}
        .qtdot:nth-child(2){animation-delay:0.2s;}
        .qtdot:nth-child(3){animation-delay:0.4s;}
        @keyframes qtd{0%,80%,100%{opacity:0.3;transform:scale(0.8);}40%{opacity:1;transform:scale(1);}}
        .qoptions{padding:0 16px 16px;display:flex;flex-direction:column;gap:8px;}
        .qopt{background:#fff;border:1px solid rgba(0,0,0,0.1);border-radius:10px;padding:10px 14px;font-size:13px;color:#1a1c1c;cursor:pointer;text-align:left;transition:all 0.15s;display:flex;align-items:center;gap:8px;}
        [data-theme="dark"] .qopt{background:#2a2c2e;border-color:rgba(255,255,255,0.08);color:#f3f3f4;}
        .qopt:hover{border-color:#1737c8;color:#1737c8;}
        .qrestart{background:none;border:none;color:#747688;font-size:11px;cursor:pointer;padding:4px 0;text-align:center;width:100%;margin-top:4px;}
        .qdemo-note{font-size:11px;color:#747688;text-align:center;padding:8px 16px;border-top:1px solid rgba(0,0,0,0.06);}
        [data-theme="dark"] .qdemo-note{border-color:rgba(255,255,255,0.06);}
        #quiv-trigger{width:52px;height:52px;background:#1737c8;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 4px 20px rgba(23,55,200,0.4);transition:transform 0.2s,box-shadow 0.2s;position:relative;}
        #quiv-trigger:hover{transform:scale(1.08);box-shadow:0 6px 28px rgba(23,55,200,0.5);}
        .trigger-badge{position:absolute;top:-4px;right:-4px;width:16px;height:16px;background:#22c55e;border-radius:50%;border:2px solid var(--bg);animation:pbadge 2s infinite;}
        @keyframes pbadge{0%,100%{transform:scale(1);}50%{transform:scale(1.2);}}


        /* HAMBURGER */
        .btn-hamburger{display:none;flex-direction:column;justify-content:center;gap:5px;background:none;border:1.5px solid var(--border);border-radius:8px;width:36px;height:36px;padding:6px;cursor:pointer;}
        .btn-hamburger span{display:block;height:1.5px;background:var(--text);border-radius:1px;transition:all 0.25s ease;}
        .btn-hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg);}
        .btn-hamburger.open span:nth-child(2){opacity:0;}
        .btn-hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg);}
        #mobile-menu{display:none;flex-direction:column;background:var(--nav-bg);backdrop-filter:blur(16px);border-top:1px solid var(--border);padding:16px 20px 20px;}
        #mobile-menu.open{display:flex;}
        #mobile-menu a{font-size:15px;font-weight:700;color:var(--text);text-decoration:none;padding:12px 0;border-bottom:1px solid var(--border);}
        #mobile-menu a:last-of-type{border-bottom:none;}
        .mobile-menu-btns{display:flex;gap:10px;margin-top:16px;}
        .mobile-btn-login{flex:1;text-align:center;border:1.5px solid var(--border);border-radius:8px;color:var(--text);padding:11px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;transition:all 0.15s;}
        .mobile-btn-register{flex:1;text-align:center;background:#1737c8;color:#fff;border-radius:8px;padding:11px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;transition:opacity 0.15s;}
        .mobile-btn-register:hover{opacity:0.88;}

        /* ── RESPONSIVE ─────────────────────────────────────── */
        @media(max-width:900px){
            .hero-inner{grid-template-columns:1fr;padding:48px 20px 0;}
            .device-wrap{display:none;}
            .hero-left{padding-bottom:48px;}
            .ai-grid{grid-template-columns:1fr;}
            .pricing-grid{grid-template-columns:1fr;gap:0;}
            .stats-bar{grid-template-columns:1fr;gap:0;}
            .features-grid{grid-template-columns:1fr 1fr;}
            .trust-grid{grid-template-columns:1fr 1fr;}
            .mp-inner{grid-template-columns:1fr;gap:32px;}
            .nav-link{display:none;}
            .btn-login{display:none;}
            .btn-nav{display:none;}
            .btn-hamburger{display:flex;}
        }
        @media(max-width:600px){
            .hero-h1{font-size:32px;letter-spacing:-1px;}
            .hero-sub{font-size:13px;}
            .hero-btns{flex-direction:column;gap:8px;}
            .btn-primary-hero,.btn-secondary-hero{justify-content:center;width:100%;}
            .trust-row{gap:12px;}
            .features-grid{grid-template-columns:1fr;}
            .trust-grid{grid-template-columns:1fr;}
            .section-title{font-size:26px;}
            .nav-inner{padding:12px 16px;}
            .btn-login{display:none;}
            .stats-bar{margin:40px 0;}
            .stat{padding:24px 20px;}
            .stat-num{font-size:36px;}
            .features{padding:40px 0;}
            .ai-section{padding:40px 0;}
            .pricing{padding:40px 0;}
            .cta-section{padding:56px 0;}
            .plan{padding:28px 20px;}
            .mp-card{padding:20px;}
            .ai-visual{display:none;}
            .hero-cta{flex-direction:column;align-items:center;}
            .btn-primary,.btn-secondary{width:100%;max-width:320px;justify-content:center;}
            footer p:last-child{display:none;}
            #quiv-container{bottom:16px;right:16px;}
            #quiv-chat{width:calc(100vw - 32px);}
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img src="/images/quivex-logo.png" alt="Quivex" id="nav-logo-img" style="width:32px;height:32px;object-fit:contain;">
            Qui<em>vex</em>
        </a>
        <div class="nav-links">
            <a href="#funciones" class="nav-link">Funciones</a>
            <a href="#ia" class="nav-link">IA</a>
            <a href="#precios" class="nav-link">Precios</a>
            <button class="btn-theme" id="theme-toggle" onclick="toggleTheme()">
                <span id="theme-icon">☀️</span>
            </button>
            <a href="/login" class="btn-login">Entrar</a>
            <a href="/register" class="btn-nav">Registrarse →</a>
            {{-- HAMBURGER MÓVIL --}}
            <button class="btn-hamburger" id="btn-hamburger" onclick="toggleMobileMenu()" aria-label="Menú">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
    {{-- MENU MÓVIL --}}
    <div id="mobile-menu">
        <a href="#funciones" onclick="closeMobileMenu()">Funciones</a>
        <a href="#ia" onclick="closeMobileMenu()">IA</a>
        <a href="#precios" onclick="closeMobileMenu()">Precios</a>
        <div class="mobile-menu-btns">
            <a href="/login" class="mobile-btn-login">Entrar</a>
            <a href="/register" class="mobile-btn-register">Registrarse →</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section">
    <svg class="hero-bg-lines" viewBox="0 0 1140 600" preserveAspectRatio="xMidYMid slice" style="position:absolute;top:0;left:0;width:100%;height:100%;">
        <line x1="0" y1="150" x2="1140" y2="150" stroke="rgba(255,255,255,0.02)" stroke-width="1"/>
        <line x1="0" y1="300" x2="1140" y2="300" stroke="rgba(255,255,255,0.02)" stroke-width="1"/>
        <line x1="0" y1="450" x2="1140" y2="450" stroke="rgba(255,255,255,0.02)" stroke-width="1"/>
        <circle cx="800" cy="300" r="350" fill="none" stroke="rgba(23,55,200,0.06)" stroke-width="1"/>
        <circle cx="800" cy="300" r="200" fill="none" stroke="rgba(23,55,200,0.04)" stroke-width="1"/>
    </svg>
    <div class="hero-inner">
        <div class="hero-left">
            <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                <span class="material-symbols-outlined" style="font-size:12px;">bolt</span> El primer sistema con inteligencia artificial
            </div>
            <h1 class="hero-h1">
                El sistema más<br>inteligente para tu<br><em>tienda o showroom</em><br>de moda
            </h1>
            <p class="hero-sub">Organiza tus tallas y modelos, registra ventas por voz y conoce tu ganancia neta real en segundos. Diseñado para marcas locales, importaciones y streetwear en México.</p>
            <div class="hero-btns">
                <a href="/register" class="btn-primary-hero">Crear cuenta gratis →</a>
                <a href="#precios" class="btn-secondary-hero">Ver planes</a>
            </div>
            <div class="trust-row">
                <div class="trust-item"><div class="trust-check"><span class="material-symbols-outlined" style="font-size:8px;">check</span></div> Sin tarjeta de crédito</div>
                <div class="trust-item"><div class="trust-check"><span class="material-symbols-outlined" style="font-size:8px;">check</span></div> Cancela cuando quieras</div>
                <div class="trust-item"><div class="trust-check"><span class="material-symbols-outlined" style="font-size:8px;">check</span></div> En español</div>
            </div>
        </div>
        <div class="device-wrap">
            <div class="floating-badge b1">
                <div class="fb-icon" style="background:rgba(34,197,94,0.15);"><span class="material-symbols-outlined" style="font-size:14px;color:#22c55e;">trending_up</span></div>
                +18% vs ayer
            </div>
            <div class="floating-badge b2">
                <div class="fb-icon" style="background:rgba(23,55,200,0.2);"><span class="material-symbols-outlined" style="font-size:14px;color:#6b8ef5;">smart_toy</span></div>
                IA activa ahora
            </div>
            <div class="device">
                <div class="device-bar">
                    <div class="device-dots">
                        <div class="device-dot" style="background:#ff5f57;"></div>
                        <div class="device-dot" style="background:#febc2e;"></div>
                        <div class="device-dot" style="background:#28c840;"></div>
                    </div>
                    <div class="device-url">quivex.app/dashboard</div>
                </div>
                <div class="device-body">
                    <div class="kpi-row">
                        <div class="kpi blue"><div class="kpi-lbl">Ventas hoy</div><div class="kpi-val">$12,400</div></div>
                        <div class="kpi"><div class="kpi-lbl">Transacciones</div><div class="kpi-val">38</div></div>
                        <div class="kpi"><div class="kpi-lbl">Utilidad real</div><div class="kpi-val" style="color:#22c55e;">$6,800</div></div>
                    </div>
                    <div class="chart-area">
                        <div class="bar" style="height:35%;"></div><div class="bar" style="height:55%;"></div>
                        <div class="bar" style="height:40%;"></div><div class="bar" style="height:70%;"></div>
                        <div class="bar hi" style="height:90%;"></div><div class="bar" style="height:65%;"></div>
                        <div class="bar" style="height:50%;"></div><div class="bar" style="height:75%;"></div>
                        <div class="bar" style="height:45%;"></div><div class="bar" style="height:60%;"></div>
                    </div>
                    <div class="ai-card">
                        <div class="ai-icon"><span class="material-symbols-outlined" style="font-size:12px;color:#fff;">auto_awesome</span></div>
                        <div class="ai-text">Hoy vas <strong>+18%</strong> vs el lunes pasado. Tus Nike Air talla 27 se agotan en <strong>2 días</strong>.</div>
                    </div>
                    <div class="products-row">
                        <div class="product-card"><div class="product-name">Air Force One</div><div class="product-price">$2,500</div><div class="product-stock">Tallas: 25-29 MX</div><div class="stock-bar"><div class="stock-fill" style="width:75%;background:#1737c8;"></div></div></div>
                        <div class="product-card"><div class="product-name">Jordan 1 Retro</div><div class="product-price">$3,200</div><div class="product-stock">Tallas: 26-28 MX</div><div class="stock-bar"><div class="stock-fill" style="width:30%;background:#f59e0b;"></div></div></div>
                        <div class="product-card"><div class="product-name">All Saints Tee</div><div class="product-price">$550</div><div class="product-stock">Tallas: S M L XL</div><div class="stock-bar"><div class="stock-fill" style="width:90%;background:#22c55e;"></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- STATS -->
    <div class="stats-bar fade-up">
        <div class="stat"><div class="stat-num"><em>20</em></div><div class="stat-label">Productos en plan gratis</div></div>
        <div class="stat"><div class="stat-num">3<em>x</em></div><div class="stat-label">Más barato que Shopify</div></div>
        <div class="stat"><div class="stat-num">24<em>/7</em></div><div class="stat-label">IA siempre disponible</div></div>
    </div>

    <!-- FEATURES -->
    <section class="features fade-up" id="funciones">
        <div class="section-eyebrow">Por qué Quivex</div>
        <div class="section-title">Todo lo que tu tienda necesita</div>
        <p class="section-sub">Un sistema completo, sin complicaciones, hecho para México.</p>
        <div class="features-grid">
            <div class="feature-card highlight">
                <div class="feature-num">01</div>
                <div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#fff;">smart_toy</span></div>
                <h3>IA que aprende tu negocio</h3>
                <p>Entre más lo usas, más inteligente se vuelve. Analiza ventas, clientes y temporadas en tiempo real.</p>
            </div>
            <div class="feature-card"><div class="feature-num">02</div><div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#1737c8;">point_of_sale</span></div><h3>Registro rápido de ventas</h3><p>Registra ventas en segundos. Por teclado o por voz — ideal para el mostrador con clientes esperando.</p></div>
            <div class="feature-card"><div class="feature-num">03</div><div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#1737c8;">inventory_2</span></div><h3>Inventario por tallas</h3><p>Controla cada talla y modelo. Alertas de stock bajo y predicción de agotamiento automática.</p></div>
            <div class="feature-card"><div class="feature-num">04</div><div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#1737c8;">payments</span></div><h3>Ganancia neta real</h3><p>No solo ventas brutas — descuenta costo de proveedor y gastos para ver tu dinero real en la bolsa.</p></div>
            <div class="feature-card"><div class="feature-num">05</div><div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#1737c8;">group</span></div><h3>Usuarios y almacenes</h3><p>Roles personalizados para tu equipo. Hasta 3 almacenes — local, cajuela, bodega, lo que necesites.</p></div>
            <div class="feature-card"><div class="feature-num">06</div><div class="feature-icon"><span class="material-symbols-outlined" style="font-size:28px;color:#1737c8;">devices</span></div><h3>Desde cualquier dispositivo</h3><p>Funciona en computadora, tablet y celular. Sin instalaciones, solo abre el navegador.</p></div>
        </div>
    </section>

    <!-- IA -->
    <section class="ai-section fade-up" id="ia">
        <div class="section-eyebrow">Inteligencia Artificial</div>
        <div class="section-title">Tu asistente de negocio,<br>siempre disponible</div>
        <p class="section-sub">Pregúntale lo que quieras sobre tus ventas, clientes e inventario.</p>
        <div class="ai-grid">
            <div class="ai-list">
                <div class="ai-item"><div class="ai-item-num">01</div><div><h4>Lenguaje natural</h4><p>Pregunta en español y recibe análisis claros de tus ventas y tendencias del negocio.</p></div></div>
                <div class="ai-item"><div class="ai-item-num">02</div><div><h4>Predicción de stock</h4><p>La IA predice cuándo se agotará cada producto antes de que te quedes sin existencias.</p></div></div>
                <div class="ai-item"><div class="ai-item-num">03</div><div><h4>Registro por voz</h4><p>Dicta ventas sin tocar el teclado. Ideal para momentos de alta afluencia en mostrador.</p></div></div>
                <div class="ai-item"><div class="ai-item-num">04</div><div><h4>CFO Virtual (Business)</h4><p>Pregúntale cuánto ganaste libre este mes, qué producto te deja más margen y más.</p></div></div>
            </div>
            <div class="ai-visual">
                <div class="terminal-bar">
                    <div class="terminal-dot" style="background:#ff5f57;"></div>
                    <div class="terminal-dot" style="background:#febc2e;"></div>
                    <div class="terminal-dot" style="background:#28c840;"></div>
                    <span class="terminal-title">Quivex CFO — chat financiero</span>
                </div>
                <div class="chat-msg"><div class="chat-label">Asistente Quivex</div><div class="chat-bubble bot">Buenos días. Ayer tuviste ventas por <strong>$12,400 MXN</strong>, un 18% más que el lunes anterior.</div></div>
                <div class="chat-msg"><div class="chat-label" style="text-align:right;">Tú</div><div class="chat-bubble user">¿Cuánto gané libre este mes?</div></div>
                <div class="chat-msg"><div class="chat-label">Asistente Quivex</div><div class="chat-bubble bot">Tu utilidad neta es <strong>$65,000 MXN</strong>. Margen real: <strong>36%</strong>.</div></div>
                <div class="typing-dots"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section class="pricing fade-up" id="precios">
        <div class="section-eyebrow">Planes</div>
        <div class="section-title">Empieza gratis, crece sin límites</div>
        <p class="section-sub">Sin sorpresas. Cancela cuando quieras.</p>
        <div class="pricing-grid">
            <div class="plan">
                <div class="plan-badge">Para empezar</div>
                <div class="plan-name">Gratis</div>
                <div class="plan-price"><span class="plan-price-sym">$</span><span class="plan-price-num">0</span><span class="plan-price-per">/mes</span></div>
                <div class="plan-desc">Perfecto para tiendas que están arrancando.</div>
                <div class="plan-divider"></div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Hasta 20 productos</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> 1 usuario (admin)</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Inventario básico</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Registro de ventas</div>
                    <div class="plan-feature off"><span class="material-symbols-outlined" style="font-size:14px;">close</span> Asistente IA</div>
                    <div class="plan-feature off"><span class="material-symbols-outlined" style="font-size:14px;">close</span> Reportes avanzados</div>
                </div>
                <a href="/register" class="plan-cta">Empezar gratis</a>
            </div>
            <div class="plan featured">
                <div class="plan-badge"><span class="material-symbols-outlined" style="font-size:11px;">workspace_premium</span> Más popular</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price"><span class="plan-price-sym">$</span><span class="plan-price-num">299</span><span class="plan-price-per">/mes</span></div>
                <div class="plan-desc">Para tiendas que quieren crecer con inteligencia.</div>
                <div class="plan-divider"></div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Productos ilimitados</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Usuarios ilimitados</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Asistente IA</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Reportes avanzados</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Registro por voz</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Programa de lealtad</div>
                </div>
                <a href="/register?plan=pro" class="plan-cta">Elegir Pro</a>
            </div>
            <div class="plan">
                <div class="plan-badge"><span class="material-symbols-outlined" style="font-size:11px;">bolt</span> Para cadenas</div>
                <div class="plan-name">Business</div>
                <div class="plan-price"><span class="plan-price-sym">$</span><span class="plan-price-num">549</span><span class="plan-price-per">/mes</span></div>
                <div class="plan-desc">Control total con rentabilidad real y chat IA financiero.</div>
                <div class="plan-divider"></div>
                <div class="plan-features">
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Todo lo de Pro</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Hasta 3 almacenes</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Rentabilidad real</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> CFO virtual con IA</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Módulo de gastos</div>
                    <div class="plan-feature"><span class="material-symbols-outlined" style="font-size:14px;">check</span> Exportar PDF</div>
                </div>
                <a href="/register?plan=business" class="plan-cta">Elegir Business</a>
            </div>
        </div>
    </section>
</div>

<!-- TRUST -->
<section class="trust-section fade-up">
    <div class="container">
        <div style="text-align:center;">
            <div class="section-eyebrow">Seguridad y confianza</div>
            <div class="section-title">Tu negocio, protegido</div>
        </div>
        <div class="trust-grid">
            <div class="trust-item-card"><div class="trust-icon"><span class="material-symbols-outlined" style="font-size:32px;color:#1737c8;">database</span></div><h4>Datos por tenant</h4><p>Cada tienda tiene su propia base de datos. Tus datos nunca se mezclan.</p></div>
            <div class="trust-item-card"><div class="trust-icon"><span class="material-symbols-outlined" style="font-size:32px;color:#1737c8;">lock</span></div><h4>Pagos seguros</h4><p>Suscripciones vía MercadoPago. Nunca guardamos datos de tarjetas.</p></div>
            <div class="trust-item-card"><div class="trust-icon"><span class="material-symbols-outlined" style="font-size:32px;color:#1737c8;">cloud_done</span></div><h4>Siempre disponible</h4><p>Infraestructura en la nube. Sin descargas ni instalaciones.</p></div>
            <div class="trust-item-card"><div class="trust-icon"><span class="material-symbols-outlined" style="font-size:32px;color:#1737c8;">location_on</span></div><h4>Hecho en México</h4><p>Precios en MXN, soporte en español, pensado para el mercado local.</p></div>
        </div>
    </div>
</section>

<!-- MP -->
<section class="mp-section fade-up">
    <div class="mp-inner">
        <div>
            <div class="section-eyebrow">Pagos</div>
            <div class="section-title">Cobra como quieras.<br>MercadoPago lo hace fácil.</div>
            <p class="section-sub">Tarjetas mexicanas, OXXO y SPEI. Cobro mensual automático sin sorpresas.</p>
            <div class="mp-methods">
                <div class="mp-method"><span class="material-symbols-outlined" style="font-size:16px;">credit_card</span> Tarjeta</div>
                <div class="mp-method"><span class="material-symbols-outlined" style="font-size:16px;">store</span> OXXO</div>
                <div class="mp-method"><span class="material-symbols-outlined" style="font-size:16px;">account_balance</span> SPEI</div>
                <div class="mp-method"><span class="material-symbols-outlined" style="font-size:16px;">autorenew</span> Recurrente</div>
            </div>
        </div>
        <div class="mp-card">
            <div class="mp-card-header">Resumen de suscripción</div>
            <div class="mp-row"><span class="mp-key">Plan</span><span class="mp-val">Pro — $499/mes</span></div>
            <div class="mp-row"><span class="mp-key">Próximo cobro</span><span class="mp-val">Automático</span></div>
            <div class="mp-row"><span class="mp-key">Método</span><span class="mp-val">MercadoPago</span></div>
            <div class="mp-row"><span class="mp-key">Estado</span><span class="mp-val" style="color:#22c55e;"><span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;">check_circle</span> Activo</span></div>
            <button class="mp-btn">Gestionar suscripción</button>
        </div>
    </div>
</section>

<div class="container">
    <section class="cta-section fade-up">
        <h2>¿Listo para vender<br>más con <em>Quivex</em>?</h2>
        <p>Únete gratis hoy. Sin tarjeta de crédito, sin contratos.</p>
        <div class="hero-cta">
            <a href="/register" class="btn-primary">Crear cuenta gratis →</a>
            <a href="/login" class="btn-secondary">Iniciar sesión</a>
        </div>
        <div class="cta-trust">
            <div class="cta-trust-item"><div class="cta-trust-dot"></div> Sin tarjeta de crédito</div>
            <div class="cta-trust-item"><div class="cta-trust-dot"></div> Cancela cuando quieras</div>
            <div class="cta-trust-item"><div class="cta-trust-dot"></div> Soporte en español</div>
        </div>
    </section>
    <footer>
        <div class="footer-inner">
            <div class="footer-logo">
                <img src="/images/quivex-logo.png" id="footer-logo-img" alt="Quivex" style="width:26px;height:26px;object-fit:contain;opacity:0.7;">
                Qui<em>vex</em>
            </div>
            <p>© {{ date('Y') }} Quivex — Sistema inteligente para tiendas y showrooms de moda en México</p>
            <p>MercadoPago · Laravel · Claude AI</p>
        </div>
    </footer>
</div>

<!-- QUIV ASISTENTE -->
<div id="quiv-container">
    <div id="quiv-chat">
        <div class="qchat-header">
            <div class="qavatar"><span class="material-symbols-outlined" style="font-size:18px;color:#fff;">smart_toy</span></div>
            <div><div class="qname">Quiv — Asistente Quivex</div><div class="qstatus"><div class="qstatus-dot"></div> En línea ahora</div></div>
            <button class="qclose" onclick="toggleQuiv()">×</button>
        </div>
        <div class="qmessages" id="qmsgs"></div>
        <div class="qoptions" id="qopts"></div>
        <div class="qdemo-note">Quiv te ayuda a encontrar el plan ideal</div>
    </div>
    <button id="quiv-trigger" onclick="toggleQuiv()">
        <span class="material-symbols-outlined" style="font-size:22px;color:#fff;">smart_toy</span>
        <div class="trigger-badge"></div>
    </button>
</div>

<script>
const savedTheme = localStorage.getItem('qvx-theme') || 'dark';
document.documentElement.setAttribute('data-theme', savedTheme);
document.getElementById('theme-icon').textContent = savedTheme === 'dark' ? '☀️' : '🌙';

function applyLogoFilter(theme) {
    const f = theme === 'dark' ? 'invert(1) brightness(1.2)' : 'none';
    ['nav-logo-img','footer-logo-img'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.filter = f;
    });
}
document.addEventListener('DOMContentLoaded', () => applyLogoFilter(savedTheme));

function toggleTheme() {
    const curr = document.documentElement.getAttribute('data-theme');
    const next = curr === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('qvx-theme', next);
    document.getElementById('theme-icon').textContent = next === 'dark' ? '☀️' : '🌙';
    applyLogoFilter(next);
}

const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, {threshold:0.1});
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

const synth = window.speechSynthesis;
let quivOpen = false, quivStarted = false;

const flows = {
    start:{msg:'¡Hola! 👋 Soy Quiv. ¿Cómo manejas tu negocio actualmente?',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">storefront</span>',text:'Tengo una tienda física',next:'fisica'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">inventory_2</span>',text:'Vendo por Instagram/WhatsApp',next:'online'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">sync</span>',text:'Tengo las dos',next:'ambas'}]},
    fisica:{msg:'¡Perfecto! Con Quivex registras ventas por voz en el mostrador y ves tu ganancia neta real. ¿Qué te interesa más?',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">mic</span>',text:'Registrar ventas por voz',next:'voz'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">payments</span>',text:'Ver mi ganancia real',next:'ganancia'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Quiero registrarme gratis',next:'registro'}]},
    online:{msg:'¡Para revendedores Quivex es clave! Controla qué tienes en la cajuela, en casa o con tu primo. 🔥',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">inventory_2</span>',text:'Controlar varios almacenes',next:'almacenes'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">payments</span>',text:'Calcular mi ganancia real',next:'ganancia'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Quiero empezar gratis',next:'registro'}]},
    ambas:{msg:'¡Exactamente para lo que fue diseñado Quivex Business! Unifica tu tienda física y ventas online con hasta 3 almacenes.',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">bolt</span>',text:'Ver plan Business',next:'business'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Empezar gratis primero',next:'registro'}]},
    voz:{msg:'Di "Air Force One talla 27" y Quivex lo agrega al ticket automáticamente. Sin tocar el teclado.',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Quiero probarlo gratis',next:'registro'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">arrow_back</span>',text:'Ver más funciones',next:'fisica'}]},
    ganancia:{msg:'Quivex te dice tu ganancia neta real: ventas menos costo de proveedor menos gastos. ¡Por fin sabes cuánto te quedas!',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Quiero ver mis números reales',next:'registro'}]},
    almacenes:{msg:'Con Business tienes hasta 3 almacenes virtuales — Cajuela, Casa, Bodega, lo que necesites.',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Empezar gratis',next:'registro'},{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">bolt</span>',text:'Ver Business',next:'business'}]},
    business:{msg:'Business $999/mes: 3 almacenes, rentabilidad real, chat financiero IA, módulo de gastos y exportar PDF.',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">rocket_launch</span>',text:'Registrarme ahora',next:'registro'}]},
    registro:{msg:'¡Excelente decisión! 🎉 Sin tarjeta de crédito, sin contratos. En 2 minutos ya estás vendiendo.',opts:[{icon:'<span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">auto_awesome</span>',text:'Crear cuenta gratis →',next:'go',url:'/register'}]}
};

function speak(text) {
    if (!synth) return;
    synth.cancel();
    const u = new SpeechSynthesisUtterance(text.replace(/<[^>]+>/g,'').replace(/[🎤💰🚀📦🔄🏪←⚡✨👋🔥🎉]/g,''));
    u.lang='es-MX'; u.rate=1.1;
    const v = synth.getVoices().find(x=>x.lang.startsWith('es'));
    if(v) u.voice=v;
    synth.speak(u);
}

function toggleQuiv() {
    quivOpen = !quivOpen;
    const chat = document.getElementById('quiv-chat');
    if(quivOpen){ chat.classList.add('open'); if(!quivStarted){quivStarted=true;setTimeout(()=>showStep('start'),400);} }
    else{ chat.classList.remove('open'); synth&&synth.cancel(); }
}

function addMsg(text) {
    return new Promise(resolve => {
        const msgs = document.getElementById('qmsgs');
        const typing = document.createElement('div');
        typing.className='qmsg';
        typing.innerHTML=`<div class="qmsg-avatar"><span class="material-symbols-outlined" style="font-size:14px;color:#fff;">smart_toy</span></div><div class="qtyping"><div class="qtdot"></div><div class="qtdot"></div><div class="qtdot"></div></div>`;
        msgs.appendChild(typing); msgs.scrollTop=99999;
        speak(text);
        setTimeout(()=>{
            typing.remove();
            const m=document.createElement('div'); m.className='qmsg';
            m.innerHTML=`<div class="qmsg-avatar"><span class="material-symbols-outlined" style="font-size:14px;color:#fff;">smart_toy</span></div><div class="qbubble">${text}</div>`;
            msgs.appendChild(m); msgs.scrollTop=99999; resolve();
        },1200);
    });
}

function showOpts(opts) {
    const c=document.getElementById('qopts'); c.innerHTML='';
    opts.forEach(opt=>{
        const btn=document.createElement('button'); btn.className='qopt';
        btn.innerHTML=`${opt.icon} ${opt.text}`;
        btn.onclick=()=>{
            if(opt.url){window.location.href=opt.url;return;}
            c.innerHTML='';
            const u=document.createElement('div');
            u.style.cssText='display:flex;justify-content:flex-end;animation:qfade 0.3s ease;';
            u.innerHTML=`<div style="background:#1737c8;color:#fff;border-radius:12px 0 12px 12px;padding:10px 12px;font-size:13px;max-width:200px;">${opt.text}</div>`;
            document.getElementById('qmsgs').appendChild(u); document.getElementById('qmsgs').scrollTop=99999;
            setTimeout(()=>showStep(opt.next),400);
        };
        c.appendChild(btn);
    });
    const r=document.createElement('button'); r.className='qrestart'; r.textContent='↩ Empezar de nuevo';
    r.onclick=()=>{ document.getElementById('qmsgs').innerHTML=''; document.getElementById('qopts').innerHTML=''; showStep('start'); };
    c.appendChild(r);
}

async function showStep(key) {
    const f=flows[key]; if(!f)return;
    await addMsg(f.msg); showOpts(f.opts);
}

function animarDashboard() {
    const bars=document.querySelectorAll('.bar');
    const kpis=document.querySelectorAll('.kpi-val');
    let ventas=12400, txs=38;
    setInterval(()=>{
        bars.forEach(bar=>{const h=Math.floor(Math.random()*65+25);bar.style.height=h+'%';bar.style.transition='height 0.8s ease';if(h>75)bar.classList.add('hi');else bar.classList.remove('hi');});
        const nueva=Math.floor(Math.random()*800+200); ventas+=nueva; txs+=1;
        if(kpis[0]){kpis[0].style.transition='all 0.4s ease';kpis[0].style.transform='scale(1.1)';kpis[0].textContent='$'+ventas.toLocaleString('es-MX');setTimeout(()=>{kpis[0].style.transform='scale(1)';},300);}
        if(kpis[1])kpis[1].textContent=txs;
        if(kpis[2])kpis[2].textContent='$'+Math.floor(ventas*0.55).toLocaleString('es-MX');
    },2000);
    const aiMsgs=['<span class="material-symbols-outlined" style="font-size:10px;color:#6b8ef5;">trending_up</span> Hoy vas <strong>+18%</strong> vs el lunes. Tus Nike Air talla 27 se agotan en <strong>2 días</strong>.','La talla <strong>28 MX</strong> es la más vendida hoy. Solo <strong>2 pares</strong> disponibles.','Tu ticket promedio subió a <strong>$326 MXN</strong>. Los Jordan 1 están jalando bien.','Stock crítico: <strong>All Saints Tee talla L</strong> — 1 pieza.'];
    let msgIdx=0;
    const aiText=document.querySelector('.ai-text');
    if(aiText){setInterval(()=>{msgIdx=(msgIdx+1)%aiMsgs.length;aiText.style.opacity='0';aiText.style.transition='opacity 0.4s';setTimeout(()=>{aiText.innerHTML=aiMsgs[msgIdx];aiText.style.opacity='1';},400);},3500);}
    const stockFills=document.querySelectorAll('.stock-fill');
    setInterval(()=>{stockFills.forEach(fill=>{const curr=parseInt(fill.style.width)||50;const newW=Math.max(5,Math.min(100,curr+Math.floor(Math.random()*10-3)));fill.style.transition='width 1s ease';fill.style.width=newW+'%';fill.style.background=newW<25?'#ef4444':newW<50?'#f59e0b':'#22c55e';});},2500);
}

setTimeout(()=>{ const d=document.querySelector('.device'); if(d)animarDashboard(); },800);

function addDeviceTilt() {
    const wrap=document.querySelector('.device-wrap'); if(!wrap)return;
    wrap.addEventListener('mousemove',e=>{
        const r=wrap.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-0.5,y=(e.clientY-r.top)/r.height-0.5;
        const device=wrap.querySelector('.device');
        if(device){device.style.transition='transform 0.1s ease';device.style.transform=`perspective(800px) rotateY(${-8+x*6}deg) rotateX(${3+y*-4}deg)`;}
        const b1=wrap.querySelector('.b1'),b2=wrap.querySelector('.b2');
        if(b1)b1.style.transform=`translate(${x*12}px,${y*8}px)`;
        if(b2)b2.style.transform=`translate(${x*-10}px,${y*6}px)`;
    });
    wrap.addEventListener('mouseleave',()=>{
        const device=wrap.querySelector('.device');
        if(device)device.style.transform='perspective(800px) rotateY(-8deg) rotateX(3deg)';
        const b1=wrap.querySelector('.b1'),b2=wrap.querySelector('.b2');
        if(b1)b1.style.transform=''; if(b2)b2.style.transform='';
    });
}

function addMagnetic(selector) {
    document.querySelectorAll(selector).forEach(btn=>{
        btn.addEventListener('mousemove',e=>{const r=btn.getBoundingClientRect(),x=(e.clientX-r.left-r.width/2)*0.25,y=(e.clientY-r.top-r.height/2)*0.25;btn.style.transform=`translate(${x}px,${y}px) scale(1.03)`;btn.style.transition='transform 0.15s ease';});
        btn.addEventListener('mouseleave',()=>{btn.style.transform='translate(0,0) scale(1)';btn.style.transition='transform 0.4s ease';});
    });
}

function addStaggerReveal() {
    document.querySelectorAll('.features-grid,.pricing-grid,.trust-grid,.ai-list').forEach(grid=>{
        Array.from(grid.children).forEach((child,i)=>{
            child.style.opacity='0'; child.style.transform='translateY(20px)';
            child.style.transition=`opacity 0.5s ease ${i*0.08}s,transform 0.5s ease ${i*0.08}s`;
            const obs2=new IntersectionObserver(entries=>{if(entries[0].isIntersecting){child.style.opacity='1';child.style.transform='translateY(0)';obs2.disconnect();}},{threshold:0.1});
            obs2.observe(child);
        });
    });
}

function animarContadores() {
    document.querySelectorAll('.stat-num').forEach(el=>{
        const obs=new IntersectionObserver(entries=>{
            if(!entries[0].isIntersecting)return; obs.disconnect();
            const emEl=el.querySelector('em'); if(!emEl)return;
            const target=parseFloat(emEl.textContent.replace(/[^0-9.]/g,'')); if(isNaN(target))return;
            let current=0; const dur=1500,start=performance.now();
            function update(now){const p=Math.min((now-start)/dur,1),ease=1-Math.pow(1-p,3);current=target*ease;emEl.textContent=Number.isInteger(target)?Math.round(current):current.toFixed(1);if(p<1)requestAnimationFrame(update);}
            requestAnimationFrame(update);
        },{threshold:0.5});
        obs.observe(el);
    });
}

addDeviceTilt();
addMagnetic('.btn-primary-hero,.btn-primary,.btn-nav,.plan-cta');
addStaggerReveal();
animarContadores();
window.addEventListener('load',()=>{ if(synth)synth.getVoices(); });

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('btn-hamburger');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
}
function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('open');
    document.getElementById('btn-hamburger').classList.remove('open');
    document.body.style.overflow = '';
}
</script>
</body>
</html>