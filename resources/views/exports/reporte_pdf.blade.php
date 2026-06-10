<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    /* ── BASE ─────────────────────────────────────────────── */
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
        font-size: 11px;
        color: #1a1c1c;
        background: #ffffff;
    }
    a { text-decoration:none; color:inherit; }

    /* ── LAYOUT HELPERS ──────────────────────────────────── */
    .w100 { width:100%; }
    .page { padding: 0 0 20px 0; }

    /* ── HEADER ──────────────────────────────────────────── */
    .header-wrap {
        background: #1737c8;
        padding: 0;
        margin-bottom: 0;
    }
    .header-inner {
        padding: 28px 32px;
    }
    .brand {
        font-size: 26px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.5px;
    }
    .brand-accent { color: #a0b4ff; }
    .store-name {
        font-size: 13px;
        font-weight: 700;
        color: rgba(255,255,255,0.8);
        margin-top: 4px;
    }
    .header-desc {
        font-size: 9px;
        color: rgba(255,255,255,0.45);
        margin-top: 3px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .header-right-label {
        font-size: 9px;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 4px;
    }
    .header-periodo {
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
    }
    .header-fecha {
        font-size: 10px;
        color: rgba(255,255,255,0.6);
        margin-top: 4px;
    }
    .plan-badge {
        display: inline-block;
        background: rgba(255,255,255,0.15);
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 3px 10px;
        border-radius: 20px;
        margin-top: 8px;
    }

    /* ── DIVIDER GRADIENT ────────────────────────────────── */
    .gradient-bar {
        height: 3px;
        background: #1535b5;
        margin-bottom: 24px;
    }

    /* ── SECTION TITLE ───────────────────────────────────── */
    .section-title {
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #1737c8;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e8e8f0;
    }

    /* ── KPI CARDS ───────────────────────────────────────── */
    .kpi-card {
        background: #f3f3f4;
        border-radius: 10px;
        padding: 18px 16px;
        text-align: center;
    }
    .kpi-card-blue {
        background: #1737c8;
        border-radius: 10px;
        padding: 18px 16px;
        text-align: center;
    }
    .kpi-label {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #9496a8;
        margin-bottom: 8px;
    }
    .kpi-label-white {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: rgba(255,255,255,0.6);
        margin-bottom: 8px;
    }
    .kpi-value {
        font-size: 26px;
        font-weight: 900;
        color: #1737c8;
        letter-spacing: -0.5px;
        line-height: 1;
    }
    .kpi-value-white {
        font-size: 26px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.5px;
        line-height: 1;
    }
    .kpi-value-dark {
        font-size: 26px;
        font-weight: 900;
        color: #1a1c1c;
        letter-spacing: -0.5px;
        line-height: 1;
    }
    .kpi-sub {
        font-size: 9px;
        color: #9496a8;
        margin-top: 4px;
    }
    .kpi-sub-white {
        font-size: 9px;
        color: rgba(255,255,255,0.55);
        margin-top: 4px;
    }

    /* ── DESGLOSE CARDS ──────────────────────────────────── */
    .desglose-card {
        background: #f9f9fb;
        border-radius: 10px;
        border: 1px solid #e8e8f0;
        padding: 16px;
    }
    .desglose-title {
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #747688;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e8e8f0;
    }
    .desglose-row-label {
        font-size: 10px;
        font-weight: 700;
        color: #1a1c1c;
        text-transform: uppercase;
    }
    .desglose-row-count {
        font-size: 9px;
        color: #9496a8;
        margin-top: 2px;
    }
    .desglose-row-amount {
        font-size: 12px;
        font-weight: 900;
        color: #1737c8;
        text-align: right;
    }
    .desglose-row-sep {
        border-bottom: 1px solid #efefef;
    }
    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* ── BAR PROGRESS ────────────────────────────────────── */
    .bar-bg {
        background: #e8e8f0;
        border-radius: 4px;
        height: 5px;
        margin-top: 6px;
    }
    .bar-fill {
        background: #1737c8;
        border-radius: 4px;
        height: 5px;
    }
    .bar-fill-green  { background: #22c55e; }
    .bar-fill-amber  { background: #f59e0b; }
    .bar-fill-purple { background: #8b5cf6; }

    /* ── MAIN TABLE ──────────────────────────────────────── */
    .main-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }
    .main-table thead tr {
        background: #1a1c1c;
    }
    .main-table thead th {
        padding: 10px 12px;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #ffffff;
        text-align: left;
    }
    .main-table thead th.right { text-align: right; }
    .main-table tbody tr:nth-child(even) { background: #f9f9fb; }
    .main-table tbody tr:nth-child(odd)  { background: #ffffff; }
    .main-table tbody td {
        padding: 9px 12px;
        font-size: 10px;
        color: #1a1c1c;
        border-bottom: 1px solid #efefef;
        vertical-align: middle;
    }
    .main-table tbody td.muted { color: #9496a8; font-family: monospace; }
    .main-table tbody td.right { text-align: right; }
    .main-table tbody td.amount {
        text-align: right;
        font-weight: 900;
        color: #1737c8;
    }
    .main-table tbody td.bold { font-weight: 700; }

    /* BADGES */
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .badge-efectivo      { background: #dcfce7; color: #16a34a; }
    .badge-tarjeta       { background: #dbeafe; color: #1d4ed8; }
    .badge-transferencia { background: #fef3c7; color: #d97706; }
    .badge-mayoreo       { background: #fef3c7; color: #d97706; }
    .badge-menudeo       { background: #f3f4f6; color: #6b7280; }

    /* ── FOOTER ──────────────────────────────────────────── */
    .footer-wrap {
        background: #f3f3f4;
        border-top: 2px solid #1737c8;
        padding: 12px 32px;
        margin-top: 24px;
    }
    .footer-brand  { font-weight: 900; color: #1737c8; }
    .footer-right  { text-align: right; color: #9496a8; font-size: 9px; }
    .footer-left   { color: #9496a8; font-size: 9px; }

    /* ── SPACING ─────────────────────────────────────────── */
    .mb8  { margin-bottom: 8px; }
    .mb16 { margin-bottom: 16px; }
    .mb24 { margin-bottom: 24px; }
    .px32 { padding-left: 32px; padding-right: 32px; }
</style>
</head>
<body>

{{-- ── HEADER ──────────────────────────────────────────────── --}}
<div class="header-wrap">
    <div class="header-inner">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="60%" valign="middle">
                    <div class="brand">Qui<span class="brand-accent">vex</span></div>
                    <div class="store-name">{{ $user->store_name ?? 'Mi tienda' }}</div>
                    <div class="header-desc">Sistema inteligente para tiendas de moda · México</div>
                </td>
                <td width="40%" valign="middle" align="right">
                    <div class="header-right-label">Reporte de ventas</div>
                    <div class="header-periodo">{{ $periodo }}</div>
                    <div class="header-fecha">Generado el {{ $generadoEn }}</div>
                    <div class="plan-badge">Plan {{ ucfirst($plan) }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="gradient-bar"></div>

<div class="px32">

{{-- ── KPI CARDS ────────────────────────────────────────────── --}}
<div class="section-title">Resumen del período</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mb24">
    <tr>
        <td width="34%" valign="top" style="padding-right:8px;">
            <div class="kpi-card-blue">
                <div class="kpi-label-white">Total ingresado</div>
                <div class="kpi-value-white">${{ number_format($metrics['totalPeriodo'], 2) }}</div>
                <div class="kpi-sub-white">En el período seleccionado</div>
            </div>
        </td>
        <td width="33%" valign="top" style="padding:0 4px;">
            <div class="kpi-card">
                <div class="kpi-label">Transacciones</div>
                <div class="kpi-value-dark">{{ $metrics['totalReg'] }}</div>
                <div class="kpi-sub">Ventas registradas</div>
            </div>
        </td>
        <td width="33%" valign="top" style="padding-left:8px;">
            <div class="kpi-card">
                <div class="kpi-label">Ticket promedio</div>
                <div class="kpi-value">${{ number_format($metrics['ticketProm'], 2) }}</div>
                <div class="kpi-sub">Por transacción</div>
            </div>
        </td>
    </tr>
</table>

{{-- ── DESGLOSE ─────────────────────────────────────────────── --}}
@if(!empty($metrics['porMetodo']))
@php
    $totalMet = collect($metrics['porMetodo'])->sum(fn($d) => $d['monto']);
@endphp
<div class="section-title">Desglose por método y tipo de venta</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mb24">
    <tr>
        {{-- MÉTODO DE PAGO --}}
        <td width="49%" valign="top" style="padding-right:8px;">
            <div class="desglose-card">
                <div class="desglose-title">Por método de pago</div>
                @php
                    $metColors = ['efectivo'=>'#22c55e','tarjeta'=>'#1737c8','transferencia'=>'#f59e0b'];
                @endphp
                @foreach($metrics['porMetodo'] as $metodo => $data)
                @php
                    $pct = $totalMet > 0 ? round(($data['monto'] / $totalMet) * 100) : 0;
                    $col = $metColors[$metodo] ?? '#9496a8';
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:10px;">
                    <tr>
                        <td valign="middle" width="60%">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="10" valign="middle">
                                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $col }};"></div>
                                    </td>
                                    <td valign="middle" style="padding-left:6px;">
                                        <div class="desglose-row-label">{{ ucfirst($metodo) }}</div>
                                        <div class="desglose-row-count">{{ $data['cantidad'] }} ventas</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="middle" align="right">
                            <div class="desglose-row-amount">${{ number_format($data['monto'], 2) }}</div>
                            <div style="font-size:9px;color:#9496a8;text-align:right;">{{ $pct }}%</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="bar-bg">
                                <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $col }};border-radius:4px;height:5px;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
                @endforeach
            </div>
        </td>

        {{-- TIPO DE VENTA --}}
        <td width="49%" valign="top" style="padding-left:8px;">
            <div class="desglose-card">
                <div class="desglose-title">Por tipo de venta</div>
                @php
                    $totalTipo = collect($metrics['porTipo'] ?? [])->sum(fn($d) => $d['monto'] ?? 0);
                @endphp
                @if(!empty($metrics['porTipo']))
                @foreach($metrics['porTipo'] as $tipo => $data)
                @php
                    $pctT = $totalTipo > 0 ? round(($data['monto'] / $totalTipo) * 100) : 0;
                    $colT = $tipo === 'mayoreo' ? '#f59e0b' : '#1737c8';
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:10px;">
                    <tr>
                        <td valign="middle" width="60%">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="10" valign="middle">
                                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $colT }};"></div>
                                    </td>
                                    <td valign="middle" style="padding-left:6px;">
                                        <div class="desglose-row-label">{{ ucfirst($tipo) }}</div>
                                        <div class="desglose-row-count">{{ $data['cantidad'] }} ventas</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="middle" align="right">
                            <div class="desglose-row-amount" style="color:{{ $colT }}">${{ number_format($data['monto'], 2) }}</div>
                            <div style="font-size:9px;color:#9496a8;text-align:right;">{{ $pctT }}%</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="bar-bg">
                                <div class="bar-fill" style="width:{{ $pctT }}%;background:{{ $colT }};border-radius:4px;height:5px;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
                @endforeach
                @else
                <p style="font-size:10px;color:#9496a8;">Sin datos de tipo de venta</p>
                @endif
            </div>
        </td>
    </tr>
</table>
@endif

{{-- ── TOP PRODUCTOS ────────────────────────────────────────── --}}
@if($metrics['topProductos']->count() > 0)
<div class="section-title">Top productos del período</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mb24">
    <tr>
        @php $maxM = $metrics['topProductos']->max('monto'); @endphp
        @foreach($metrics['topProductos']->take(3) as $i => $prod)
        @php
            $pctP = $maxM > 0 ? round(($prod['monto'] / $maxM) * 100) : 0;
            $medals = ['🥇','🥈','🥉'];
            $borderColors = ['#1737c8','#9496a8','#f59e0b'];
        @endphp
        <td width="33%" valign="top" style="padding:{{ $i === 1 ? '0 6px' : '0' }};">
            <div style="background:#f9f9fb;border-radius:10px;border:1px solid #e8e8f0;border-top:3px solid {{ $borderColors[$i] }};padding:14px;">
                <div style="font-size:18px;margin-bottom:6px;">{{ $medals[$i] }}</div>
                <div style="font-size:10px;font-weight:900;color:#1a1c1c;text-transform:uppercase;margin-bottom:2px;">{{ Str::limit($prod['nombre'], 22) }}</div>
                <div style="font-size:9px;color:#9496a8;margin-bottom:8px;">{{ $prod['unidades'] }} unidades</div>
                <div style="font-size:15px;font-weight:900;color:#1737c8;">${{ number_format($prod['monto'], 2) }}</div>
                <div class="bar-bg" style="margin-top:8px;">
                    <div style="width:{{ $pctP }}%;background:#1737c8;border-radius:4px;height:4px;"></div>
                </div>
            </div>
        </td>
        @endforeach
    </tr>
</table>
@endif

{{-- ── DETALLE DE VENTAS ────────────────────────────────────── --}}
<div class="section-title">Detalle de ventas</div>
<table class="main-table mb24">
    <thead>
        <tr>
            <th width="6%"># Venta</th>
            <th width="22%">Cliente</th>
            <th width="8%">Piezas</th>
            <th width="12%">Método</th>
            <th width="10%">Tipo</th>
            <th width="18%">Fecha</th>
            <th width="14%" class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($metrics['ventasAll'] as $venta)
        <tr>
            <td class="muted">#{{ $venta->id }}</td>
            <td class="bold">{{ $venta->client->name ?? 'Sin cliente' }}</td>
            <td style="text-align:center;color:#747688;">{{ $venta->details->sum('cantidad') }}</td>
            <td>
                <span class="badge badge-{{ $venta->metodo_pago ?? 'efectivo' }}">
                    {{ ucfirst($venta->metodo_pago ?? 'efectivo') }}
                </span>
            </td>
            <td>
                <span class="badge badge-{{ $venta->tipo_venta ?? 'menudeo' }}">
                    {{ ucfirst($venta->tipo_venta ?? 'menudeo') }}
                </span>
            </td>
            <td style="color:#747688;">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
            <td class="amount">${{ number_format($venta->total, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:20px;color:#9496a8;">Sin ventas registradas en este período</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>{{-- /px32 --}}

{{-- ── FOOTER ───────────────────────────────────────────────── --}}
<div class="footer-wrap">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="footer-left" valign="middle">
                <span class="footer-brand">Quivex</span> · Sistema inteligente para tiendas y showrooms de moda en México
            </td>
            <td class="footer-right" valign="middle" align="right">
                Confidencial · Solo para uso interno · {{ $generadoEn }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>