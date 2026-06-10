<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
        font-size: 11px;
        color: #1a1c1c;
        background: #ffffff;
        width: 226px;
    }

    /* ── TEMPLATES ───────────────────────────────────────── */

    /* MINIMALISTA */
    .t-minimalista .header    { padding: 16px 14px 12px; border-bottom: 2px solid {{ $color }}; }
    .t-minimalista .store     { font-size: 15px; font-weight: 900; color: {{ $color }}; letter-spacing: -0.3px; }
    .t-minimalista .folio     { font-size: 9px; color: #9496a8; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.1em; }
    .t-minimalista .section   { padding: 10px 14px; border-bottom: 1px dashed #e5e5f0; }
    .t-minimalista .sec-title { font-size: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #9496a8; margin-bottom: 6px; }
    .t-minimalista .total-row { padding: 12px 14px; background: {{ $color }}; }
    .t-minimalista .total-lbl { font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; }
    .t-minimalista .total-val { font-size: 20px; font-weight: 900; color: #ffffff; }
    .t-minimalista .footer    { padding: 10px 14px; text-align: center; }

    /* CLÁSICO */
    .t-clasico .header        { padding: 14px; background: {{ $color }}; text-align: center; }
    .t-clasico .store         { font-size: 14px; font-weight: 900; color: #ffffff; letter-spacing: -0.2px; }
    .t-clasico .folio         { font-size: 9px; color: rgba(255,255,255,0.7); margin-top: 2px; }
    .t-clasico .section       { padding: 10px 14px; border-bottom: 1px solid #efefef; }
    .t-clasico .sec-title     { font-size: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.12em; color: {{ $color }}; margin-bottom: 6px; }
    .t-clasico .total-row     { padding: 10px 14px; border-top: 2px solid {{ $color }}; }
    .t-clasico .total-lbl     { font-size: 9px; font-weight: 700; color: #747688; text-transform: uppercase; }
    .t-clasico .total-val     { font-size: 18px; font-weight: 900; color: {{ $color }}; }
    .t-clasico .footer        { padding: 10px 14px; text-align: center; background: #f9f9fb; }

    /* CENTRADO */
    .t-centrado .header       { padding: 16px 14px; text-align: center; border-bottom: 1px solid #efefef; }
    .t-centrado .store        { font-size: 15px; font-weight: 900; color: #1a1c1c; }
    .t-centrado .folio        { font-size: 9px; color: #9496a8; margin-top: 3px; }
    .t-centrado .accent-bar   { height: 3px; background: {{ $color }}; margin: 0; }
    .t-centrado .section      { padding: 10px 14px; border-bottom: 1px dashed #e5e5f0; text-align: center; }
    .t-centrado .sec-title    { font-size: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: {{ $color }}; margin-bottom: 6px; text-align: center; }
    .t-centrado .total-row    { padding: 12px 14px; text-align: center; }
    .t-centrado .total-lbl    { font-size: 9px; font-weight: 700; color: #747688; text-transform: uppercase; }
    .t-centrado .total-val    { font-size: 20px; font-weight: 900; color: {{ $color }}; }
    .t-centrado .footer       { padding: 10px 14px; text-align: center; }

    /* ── COMUNES ─────────────────────────────────────────── */
    .row-prod      { margin-bottom: 6px; }
    .prod-name     { font-size: 10px; font-weight: 700; color: #1a1c1c; }
    .prod-detail   { font-size: 9px; color: #9496a8; margin-top: 1px; }
    .prod-precio   { font-size: 10px; font-weight: 900; color: #1a1c1c; text-align: right; }
    .cliente-name  { font-size: 11px; font-weight: 700; color: #1a1c1c; }
    .cliente-det   { font-size: 9px; color: #9496a8; margin-top: 2px; }
    .metrica-lbl   { font-size: 9px; color: #9496a8; }
    .metrica-val   { font-size: 10px; font-weight: 700; color: #1a1c1c; text-align: right; }
    .puntos-badge  { display: inline-block; background: #f3f3f4; border-radius: 6px; padding: 4px 8px; font-size: 9px; font-weight: 700; color: {{ $color }}; margin-top: 4px; }
    .footer-msg    { font-size: 10px; color: #1a1c1c; font-weight: 600; margin-bottom: 6px; }
    .footer-sub    { font-size: 8px; color: #9496a8; }
    .sep-dots      { border: none; border-top: 1px dashed #d4d5e8; margin: 0; }
    .qr-wrap       { text-align: center; padding: 10px 0 6px; }
    .qr-label      { font-size: 8px; color: #9496a8; margin-top: 4px; text-align: center; }
    .descuento-row { font-size: 9px; color: #22c55e; font-weight: 700; }
    .powered       { font-size: 7px; color: #c4c5da; text-align: center; margin-top: 6px; letter-spacing: 0.1em; }
</style>
</head>
<body class="t-{{ $template }}">

@php
    // Datos reales o de ejemplo para preview
    if ($venta) {
        $folio       = $venta->ticket->folio ?? ('QVX-' . str_pad($venta->id, 4, '0', STR_PAD_LEFT));
        $clienteNom  = $venta->client->name ?? 'Sin cliente';
        $clienteTel  = $venta->client->telefono ?? '';
        $fecha       = $venta->created_at->format('d/m/Y H:i');
        $metodo      = ucfirst($venta->metodo_pago ?? 'efectivo');
        $productos   = $venta->details->map(fn($d) => [
            'nombre'   => $d->product->name ?? '—',
            'talla'    => $d->talla ?? '',
            'cantidad' => $d->cantidad,
            'precio'   => $d->subtotal,
        ])->toArray();
        $subtotal    = $venta->details->sum('subtotal');
        $descuento   = $venta->descuento_puntos ?? 0;
        $total       = $venta->total;
        $puntos      = $venta->puntos_ganados ?? 0;
        $vendedor    = $venta->user->name ?? '';
    } else {
        $ej          = $ejemplo ?? [];
        $folio       = $ej['folio']    ?? 'QVX-0001';
        $clienteNom  = $ej['cliente']  ?? 'Cliente Ejemplo';
        $clienteTel  = $ej['telefono'] ?? '';
        $fecha       = $ej['fecha']    ?? now()->format('d/m/Y H:i');
        $metodo      = $ej['metodo']   ?? 'Efectivo';
        $productos   = $ej['productos'] ?? [];
        $subtotal    = $ej['subtotal'] ?? 0;
        $descuento   = $ej['descuento'] ?? 0;
        $total       = $ej['total']    ?? 0;
        $puntos      = $ej['puntos']   ?? 0;
        $vendedor    = '';
    }
@endphp

{{-- ── HEADER ──────────────────────────────────────────────── --}}
<div class="header">
    @if($logoBase64)
    <div style="text-align:{{ $template === 'centrado' || $template === 'clasico' ? 'center' : 'left' }};margin-bottom:6px;">
        <img src="{{ $logoBase64 }}" style="max-height:36px;max-width:100px;object-fit:contain;"/>
    </div>
    @endif
    <div class="store">{{ $storeName }}</div>
    @if($whatsapp)
    <div class="folio" style="margin-top:1px;font-size:8px;color:{{ $template === 'clasico' ? 'rgba(255,255,255,0.6)' : '#9496a8' }};">
        {{ $whatsapp }}
    </div>
    @endif
    <div class="folio">{{ $folio }} · {{ $fecha }}</div>
</div>
@if($template === 'centrado')
<div class="accent-bar"></div>
@endif

{{-- ── CLIENTE ──────────────────────────────────────────────── --}}
<div class="section">
    <div class="sec-title">Cliente</div>
    <div class="cliente-name">{{ $clienteNom }}</div>
    @if($clienteTel)
    <div class="cliente-det">{{ $clienteTel }}</div>
    @endif
    <div class="cliente-det">{{ $metodo }}@if($vendedor) · {{ $vendedor }}@endif</div>
</div>

{{-- ── PRODUCTOS ────────────────────────────────────────────── --}}
<div class="section">
    <div class="sec-title">Productos</div>
    @foreach($productos as $prod)
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:6px;">
        <tr>
            <td valign="top" width="72%">
                <div class="prod-name">{{ $prod['nombre'] }}</div>
                <div class="prod-detail">
                    x{{ $prod['cantidad'] }}@if($prod['talla'] ?? '') · Talla {{ $prod['talla'] }}@endif
                </div>
            </td>
            <td valign="top" align="right" width="28%">
                <div class="prod-precio">${{ number_format($prod['precio'], 2) }}</div>
            </td>
        </tr>
    </table>
    @endforeach
</div>

{{-- ── TOTALES ──────────────────────────────────────────────── --}}
<div class="section">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="metrica-lbl">Subtotal</td>
            <td class="metrica-val">${{ number_format($subtotal, 2) }}</td>
        </tr>
        @if($descuento > 0)
        <tr>
            <td class="descuento-row">Descuento puntos</td>
            <td class="descuento-row" style="text-align:right;">-${{ number_format($descuento, 2) }}</td>
        </tr>
        @endif
    </table>
</div>

{{-- ── TOTAL GRANDE ─────────────────────────────────────────── --}}
<div class="total-row">
    <div class="total-lbl">Total</div>
    <div class="total-val">${{ number_format($total, 2) }}</div>
    @if($puntos > 0)
    <div class="puntos-badge" style="background:rgba(255,255,255,0.15);color:#fff;margin-top:6px;">
        +{{ $puntos }} puntos ganados
    </div>
    @endif
</div>

{{-- ── QR ─────────────────────────────────────────────────── --}}
@if($qrUrl)
<div class="qr-wrap">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode($qrUrl) }}&bgcolor=ffffff&color=1a1c1c&margin=2"
         width="70" height="70" style="border-radius:4px;"/>
    <div class="qr-label">
        @if($whatsapp) Escríbenos por WhatsApp @else Ver catálogo digital @endif
    </div>
</div>
@endif

{{-- ── FOOTER ───────────────────────────────────────────────── --}}
<div class="footer">
    @if($mensaje)
    <div class="footer-msg">{{ $mensaje }}</div>
    @endif
    <div class="footer-sub">{{ now()->format('d/m/Y H:i') }}</div>
    <div class="powered">GENERADO POR QUIVEX</div>
</div>

</body>
</html>