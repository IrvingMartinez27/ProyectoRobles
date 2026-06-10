<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Iniciar sesión — Quivex</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        html,body{height:100%;font-family:'Inter',sans-serif;overflow:hidden;}
        @media(max-width:767px){html,body{overflow:auto;}}

        /* CURSOR */
        #cur{position:fixed;pointer-events:none;z-index:99999;width:8px;height:8px;background:#fff;border-radius:50%;transform:translate(-50%,-50%);transition:width .2s,height .2s;}
        #cur-ring{position:fixed;pointer-events:none;z-index:99998;width:28px;height:28px;border:1.5px solid rgba(255,255,255,0.25);border-radius:50%;transform:translate(-50%,-50%);transition:all .12s ease;}

        /* PANEL IZQUIERDO */
        #left{background:linear-gradient(135deg,#0a0e2e 0%,#1737c8 50%,#0f1980 100%);position:relative;overflow:hidden;}

        .lgrid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px);background-size:48px 48px;animation:grid-drift 30s linear infinite;}
        @keyframes grid-drift{0%{background-position:0 0;}100%{background-position:48px 48px;}}

        .orb{position:absolute;border-radius:50%;filter:blur(70px);animation:ob ease-in-out infinite;}
        @keyframes ob{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-24px) scale(1.06);}}

        .geo{position:absolute;border:1px solid rgba(255,255,255,0.1);animation:geo-spin linear infinite;}
        @keyframes geo-spin{from{transform:rotateX(0deg) rotateY(0deg) rotateZ(0deg);}to{transform:rotateX(360deg) rotateY(360deg) rotateZ(360deg);}}

        .scanline{position:absolute;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(100,140,255,0.5),transparent);animation:scan 5s linear infinite;}
        @keyframes scan{0%{top:0;opacity:0;}10%{opacity:1;}90%{opacity:1;}100%{top:100%;opacity:0;}}

        @keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(.8);}}

        .flip-board{font-size:clamp(44px,6vw,82px);font-weight:900;letter-spacing:-3px;color:#fff;font-variant-numeric:tabular-nums;text-shadow:0 0 40px rgba(100,140,255,0.4),0 2px 0 rgba(0,0,0,0.3);transition:all 0.15s ease;}
        .flip-board.flash{transform:scale(1.04);text-shadow:0 0 60px rgba(100,180,255,0.7),0 2px 0 rgba(0,0,0,0.3);}

        #particles{position:absolute;inset:0;pointer-events:none;overflow:hidden;}
        .pt{position:absolute;border-radius:50%;animation:ptf linear infinite;opacity:0;}
        @keyframes ptf{0%{transform:translateY(100%) translateX(0);opacity:0;}10%{opacity:1;}90%{opacity:.4;}100%{transform:translateY(-10%) translateX(var(--dx));opacity:0;}}

        /* PANEL DERECHO */
        #right{background:#080a0d;}

        /* INPUTS — fondo oscuro forzado incluso con autofill */
        .dark-input{
            width:100%;
            padding:13px 16px;
            font-size:14px;
            font-family:'Inter',sans-serif;
            font-weight:500;
            background:rgba(255,255,255,0.06) !important;
            border:1.5px solid rgba(255,255,255,0.1);
            border-radius:14px !important;
            color:#fff !important;
            outline:none;
            transition:border-color .25s ease, box-shadow .25s ease, background .25s ease;
            -webkit-text-fill-color: #fff !important;
        }
        .dark-input::placeholder{color:rgba(255,255,255,0.25);-webkit-text-fill-color:rgba(255,255,255,0.25) !important;}
        .dark-input:focus{
            border-color:#4d7fff;
            background:rgba(77,127,255,0.08) !important;
            box-shadow:0 0 0 3px rgba(77,127,255,0.15), 0 0 20px rgba(77,127,255,0.08);
        }
        /* KILL autofill white bg */
        .dark-input:-webkit-autofill,
        .dark-input:-webkit-autofill:hover,
        .dark-input:-webkit-autofill:focus,
        .dark-input:-webkit-autofill:active{
            -webkit-box-shadow:0 0 0 1000px #111520 inset !important;
            -webkit-text-fill-color:#fff !important;
            caret-color:#fff;
            border-color:rgba(255,255,255,0.1) !important;
            transition:background-color 9999s ease-in-out 0s;
        }

        /* BOTÓN */
        #btn-login{
            position:relative;overflow:hidden;
            background:linear-gradient(135deg,#1737c8,#2d50f0);
            border:none;cursor:pointer;width:100%;
            padding:14px;border-radius:14px;
            color:#fff;font-size:13px;font-weight:800;
            text-transform:uppercase;letter-spacing:.15em;
            font-family:'Inter',sans-serif;
            transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        #btn-login::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.12),transparent);transition:left .5s ease;}
        #btn-login:hover{transform:translateY(-2px) scale(1.02);box-shadow:0 12px 32px rgba(23,55,200,0.45);}
        #btn-login:hover::before{left:100%;}
        #btn-login:active{transform:scale(0.97);}

        /* SOCIAL */
        .social-btn{background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.1);color:#fff;transition:all .2s ease;border-radius:14px;cursor:pointer;}
        .social-btn:hover{background:rgba(255,255,255,0.09);border-color:rgba(255,255,255,0.2);transform:translateY(-1px);}

        /* FADE IN */
        .fi{opacity:0;transform:translateY(14px);animation:fiu .5s ease forwards;}
        @keyframes fiu{to{opacity:1;transform:translateY(0);}}
        .d1{animation-delay:.08s;}.d2{animation-delay:.16s;}.d3{animation-delay:.24s;}.d4{animation-delay:.32s;}.d5{animation-delay:.4s;}

        /* input wrapper con ícono */
        .input-wrap{position:relative;}
        .input-icon{position:absolute;right:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.3);cursor:pointer;transition:color .2s;background:none;border:none;padding:0;}
        .input-icon:hover{color:rgba(255,255,255,.8);}
        .input-icon .material-symbols-outlined{font-size:18px;}

        /* Eye button no focus outline */
        .input-icon:focus{outline:none;}
    </style>
</head>
<body>

<div id="cur"></div>
<div id="cur-ring"></div>

<div style="display:flex;height:100vh;">

    <!-- ══ IZQUIERDA ══ -->
    <section id="left" class="hidden md:flex md:w-5/12 lg:w-1/2 flex-col items-center justify-center p-10" style="flex-shrink:0;">
        <div class="lgrid"></div>
        <div class="scanline"></div>
        <div id="particles"></div>
        <div class="orb" style="width:320px;height:320px;background:rgba(100,140,255,0.15);top:-100px;left:-100px;animation-duration:9s;"></div>
        <div class="orb" style="width:240px;height:240px;background:rgba(23,55,200,0.2);bottom:-80px;right:-60px;animation-duration:7s;animation-delay:3s;"></div>
        <div class="geo" style="width:110px;height:110px;top:9%;left:7%;animation-duration:18s;border-radius:20px;"></div>
        <div class="geo" style="width:75px;height:75px;bottom:13%;right:9%;animation-duration:22s;border-radius:50%;animation-direction:reverse;"></div>
        <div class="geo" style="width:55px;height:55px;top:48%;left:6%;animation-duration:14s;"></div>

        <div style="position:relative;z-index:10;width:100%;max-width:340px;display:flex;flex-direction:column;gap:28px;">
            <!-- Logo -->
            <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <img src="/images/quivex-logo.png" alt="Quivex" style="width:34px;height:34px;object-fit:contain;filter:brightness(0) invert(1);" onerror="this.style.display='none'">
                <span style="font-size:21px;font-weight:900;letter-spacing:-.5px;color:#fff;">Qui<span style="color:#6b8ef5;">vex</span></span>
            </a>

            <!-- Contador -->
            <div>
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.2em;color:rgba(255,255,255,.35);margin-bottom:8px;">Ventas registradas hoy</p>
                <div class="flip-board" id="flip-ventas">$24,830</div>
                <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                    <div style="width:6px;height:6px;border-radius:50%;background:#22c55e;animation:pulse-dot 2s infinite;flex-shrink:0;"></div>
                    <span style="font-size:11px;color:rgba(255,255,255,.4);font-weight:600;">En tiempo real</span>
                    <span id="txn-count" style="font-size:11px;color:rgba(255,255,255,.25);font-weight:600;margin-left:auto;">48 transacciones</span>
                </div>
            </div>

            <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent);"></div>

            <!-- Stats -->
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <span style="font-size:22px;font-weight:900;color:#fff;letter-spacing:-1px;display:block;" id="stat-ticket">$486</span>
                    <span style="font-size:9px;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:rgba(255,255,255,.3);">Ticket prom.</span>
                </div>
                <div>
                    <span style="font-size:22px;font-weight:900;color:#22c55e;letter-spacing:-1px;display:block;">+12%</span>
                    <span style="font-size:9px;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:rgba(255,255,255,.3);">vs ayer</span>
                </div>
                <div>
                    <span style="font-size:22px;font-weight:900;color:#6b8ef5;letter-spacing:-1px;display:block;">IA</span>
                    <span style="font-size:9px;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:rgba(255,255,255,.3);">Activa</span>
                </div>
            </div>

            <!-- Mini chart -->
            <div>
                <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.25);margin-bottom:10px;">Últimas 7 horas</p>
                <div style="display:flex;align-items:flex-end;gap:5px;height:52px;" id="mini-chart">
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:30%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:55%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:40%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:75%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:60%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:rgba(107,142,245,0.25);border-radius:3px 3px 0 0;height:88%;transition:height .7s ease;"></div>
                    <div class="mbar" style="flex:1;background:#6b8ef5;border-radius:3px 3px 0 0;height:100%;box-shadow:0 0 14px rgba(107,142,245,0.55);transition:height .7s ease;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ DERECHA ══ -->
    <section id="right" class="flex-1 flex flex-col" style="overflow-y:auto;">

        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 28px;border-bottom:1px solid rgba(255,255,255,0.06);flex-shrink:0;">
            <a href="/" style="font-size:20px;font-weight:900;letter-spacing:-.5px;color:#fff;text-decoration:none;">
                Qui<span style="color:#6b8ef5;">vex</span>
            </a>
            <a href="/register" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.3);text-decoration:none;transition:color .2s;"
               onmouseover="this.style.color='#6b8ef5'" onmouseout="this.style.color='rgba(255,255,255,.3)'">
                ¿Sin cuenta? Regístrate →
            </a>
        </div>

        <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:28px;">
            <div style="width:100%;max-width:380px;display:flex;flex-direction:column;gap:22px;">

                <div class="fi">
                    <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.2em;color:rgba(255,255,255,.3);margin-bottom:8px;">Bienvenido de vuelta</p>
                    <h1 style="font-size:30px;font-weight:900;letter-spacing:-.5px;color:#fff;line-height:1.1;">Iniciar sesión</h1>
                </div>

                @if($errors->any())
                <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:14px;padding:12px 16px;" class="fi">
                    @foreach($errors->all() as $error)
                    <p style="font-size:13px;color:#f87171;font-weight:500;">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form action="/login" method="POST" style="display:flex;flex-direction:column;gap:16px;">
                    @csrf

                    <div class="fi d1">
                        <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.35);margin-bottom:7px;">Correo electrónico</label>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="tu@correo.com" required
                               class="dark-input" autocomplete="email"/>
                    </div>

                    <div class="fi d2">
                        <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.35);margin-bottom:7px;">Contraseña</label>
                        <div class="input-wrap">
                            <input name="password" id="pwd" type="password" placeholder="••••••••" required
                                   class="dark-input" style="padding-right:44px;" autocomplete="current-password"/>
                            <button type="button" class="input-icon" onclick="togglePwd()">
                                <span class="material-symbols-outlined" id="eye-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="fi d3">
                        Acceder al panel
                    </button>
                </form>

                <div class="fi d3" style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;height:1px;background:rgba(255,255,255,.07);"></div>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.2);">o continúa con</span>
                    <div style="flex:1;height:1px;background:rgba(255,255,255,.07);"></div>
                </div>

                <div class="fi d4" style="display:flex;gap:10px;">
                    <a href="{{ route('google.redirect') }}"
                       class="social-btn" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:13px;font-size:13px;font-weight:600;text-decoration:none;color:#fff;border-radius:14px;">
                        <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                            <path d="M43.611 20.083H42V20H24v8h11.303C33.654 32.657 29.332 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                            <path d="M6.306 14.691l6.571 4.819C14.655 15.108 19.001 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                            <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.314 0-9.822-3.422-11.408-8.167l-6.52 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                            <path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                        </svg>
                        Google
                    </a>
                    <button disabled class="social-btn" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:13px;font-size:13px;font-weight:600;color:rgba(255,255,255,.2);cursor:not-allowed;border-radius:14px;">
                        <svg width="16" height="16" viewBox="0 0 814 1000" fill="none">
                            <path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-42.8-154.9-112.3C142.1 683.3 100.1 588.4 100.1 498c0-154.3 100.7-235.6 199.8-235.6 51.8 0 95.1 34.4 128.2 34.4 31.6 0 81.1-36.5 140.9-36.5 22.6 0 108.1 2 166.1 81.4zm-56.9-194.5c27.4-32.9 47.3-78.7 47.3-124.6 0-6.4-.6-12.8-1.9-18.9-44.9 1.9-98.4 30.2-130.3 67.7-24.8 27.4-48.5 73.3-48.5 119.8 0 7 1.3 13.9 1.9 16.2 2.6.6 6.4 1.3 10.2 1.3 40.3 0 90.4-26.8 121.3-61.5z" fill="currentColor"/>
                        </svg>
                        Apple
                    </button>
                </div>

                <p class="fi d5" style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:10px;color:rgba(255,255,255,.18);padding-bottom:4px;">
                    <span class="material-symbols-outlined" style="font-size:13px;color:rgba(255,255,255,.2);">lock</span>
                    Uso exclusivo para usuarios registrados de Quivex.
                </p>
            </div>
        </div>
    </section>
</div>

<script>
function togglePwd() {
    const i = document.getElementById('pwd');
    const ic = document.getElementById('eye-icon');
    i.type = i.type === 'password' ? 'text' : 'password';
    ic.textContent = i.type === 'password' ? 'visibility' : 'visibility_off';
}

// PARTÍCULAS
(function(){
    const c = document.getElementById('particles');
    if (!c) return;
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'pt';
        p.style.cssText = `left:${Math.random()*100}%;width:${Math.random()*3+1}px;height:${Math.random()*3+1}px;background:rgba(107,142,245,${Math.random()*.5+.2});animation-duration:${Math.random()*10+8}s;animation-delay:${Math.random()*6}s;--dx:${(Math.random()-.5)*80}px;`;
        c.appendChild(p);
    }
})();

// CONTADOR FLIP
(function(){
    const el = document.getElementById('flip-ventas');
    const txn = document.getElementById('txn-count');
    const bars = document.querySelectorAll('.mbar');
    const ticket = document.getElementById('stat-ticket');
    let v = 24830, t = 48;
    setInterval(() => {
        v += Math.floor(Math.random() * 800 + 200);
        t += Math.floor(Math.random() * 2 + 1);
        if (el) {
            el.classList.add('flash');
            el.textContent = '$' + v.toLocaleString('es-MX');
            setTimeout(() => el.classList.remove('flash'), 200);
        }
        if (txn) txn.textContent = t + ' transacciones';
        if (ticket) ticket.textContent = '$' + Math.floor(v / t);
        bars.forEach((b, idx) => {
            if (idx === bars.length - 1) {
                const h = Math.floor(Math.random() * 25 + 75);
                b.style.height = h + '%';
                b.style.boxShadow = `0 0 ${h/7}px rgba(107,142,245,0.55)`;
            } else {
                const h = Math.floor(Math.random() * 65 + 20);
                b.style.height = h + '%';
                b.style.background = 'rgba(107,142,245,0.25)';
            }
        });
    }, 2200);
})();

// 3D PARALLAX
(function(){
    const left = document.getElementById('left');
    const geos = document.querySelectorAll('.geo');
    if (!left) return;
    left.addEventListener('mousemove', e => {
        const r = left.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        geos.forEach((g, i) => {
            const d = (i + 1) * 14;
            g.style.transform = `translateX(${x*d}px) translateY(${y*d}px) rotateX(${y*20}deg) rotateY(${x*20}deg)`;
            g.style.transition = 'transform .1s ease';
        });
    });
    left.addEventListener('mouseleave', () => {
        geos.forEach(g => { g.style.transform = ''; g.style.transition = 'transform .5s ease'; });
    });
})();

// CURSOR
(function(){
    const c = document.getElementById('cur');
    const co = document.getElementById('cur-ring');
    let mx=0,my=0,ox=0,oy=0;
    document.addEventListener('mousemove', e => { mx=e.clientX; my=e.clientY; c.style.left=mx+'px'; c.style.top=my+'px'; });
    (function anim(){ ox+=(mx-ox)*.15; oy+=(my-oy)*.15; co.style.left=ox+'px'; co.style.top=oy+'px'; requestAnimationFrame(anim); })();
    document.querySelectorAll('a,button,input').forEach(el => {
        el.addEventListener('mouseenter', () => { c.style.width='16px'; c.style.height='16px'; co.style.width='44px'; co.style.height='44px'; });
        el.addEventListener('mouseleave', () => { c.style.width='8px'; c.style.height='8px'; co.style.width='28px'; co.style.height='28px'; });
    });
})();
</script>
</body>
</html>