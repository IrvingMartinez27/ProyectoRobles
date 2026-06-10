<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\sale;
use App\Models\ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    // ── TICKET STUDIO — Vista de configuración ────────────────
    public function studio()
    {
        $plan = Auth::user()->plan ?? 'gratis';
        if ($plan !== 'business') abort(403);

        $tenant = Tenant::find(Auth::user()->tenant_id);

        return view('ticket_studio', compact('tenant', 'plan'));
    }

    // ── GUARDAR CONFIGURACIÓN ─────────────────────────────────
    public function guardarConfig(Request $request)
    {
        $plan = Auth::user()->plan ?? 'gratis';
        if ($plan !== 'business') abort(403);

        $request->validate([
            'ticket_color'    => 'nullable|string|max:7',
            'ticket_mensaje'  => 'nullable|string|max:200',
            'ticket_template' => 'nullable|in:minimalista,destacado,clasico',
            'ticket_font'     => 'nullable|string|max:50',
            'ticket_whatsapp' => 'nullable|string|max:20',
            'ticket_logo'     => 'nullable|image|mimes:jpeg,png,webp|max:1024',
        ]);

        $tenantId = Auth::user()->tenant_id;

        // Subir logo si viene
        $logoPath = null;
        if ($request->hasFile('ticket_logo') && $request->file('ticket_logo')->isValid()) {
            $tenant = Tenant::find($tenantId);
            if ($tenant && $tenant->ticket_logo) {
                Storage::disk('public')->delete($tenant->ticket_logo);
            }
            $logoPath = $request->file('ticket_logo')->store('tickets/logos', 'public');
        }

        // Actualizar directo en BD central
        $data = [
            'ticket_color'    => $request->ticket_color    ?? '#1737c8',
            'ticket_mensaje'  => $request->ticket_mensaje  ?? '',
            'ticket_template' => $request->ticket_template ?? 'minimalista',
            'ticket_font'     => $request->ticket_font     ?? 'Inter',
            'ticket_whatsapp' => $request->ticket_whatsapp ?? '',
        ];
        if ($logoPath) {
            $data['ticket_logo'] = $logoPath;
        }

        \DB::connection('mysql')->table('tenants')
            ->where('id', $tenantId)
            ->update($data);

        return back()->with('success', 'Configuración del ticket guardada correctamente.');
    }

    // ── GENERAR PDF DE UN TICKET ──────────────────────────────
    public function pdf($ventaId)
    {
        $plan = Auth::user()->plan ?? 'gratis';
        if ($plan !== 'business') abort(403);

        $venta = sale::with(['client', 'details.product', 'ticket', 'user'])
            ->findOrFail($ventaId);

        $tenant = Tenant::find(Auth::user()->tenant_id);

        // Logo en base64 para dompdf
        $logoBase64 = null;
        if ($tenant->ticket_logo && Storage::disk('public')->exists($tenant->ticket_logo)) {
            $logoContent = Storage::disk('public')->get($tenant->ticket_logo);
            $logoMime    = Storage::disk('public')->mimeType($tenant->ticket_logo);
            $logoBase64  = 'data:' . $logoMime . ';base64,' . base64_encode($logoContent);
        }

        // QR — URL del catálogo o WhatsApp
        $qrUrl = null;
        if ($tenant->ticket_whatsapp) {
            $whatsapp = preg_replace('/[^0-9]/', '', $tenant->ticket_whatsapp);
            $qrUrl    = 'https://wa.me/' . $whatsapp;
        } else {
            $qrUrl = url('/catalogo/' . $tenant->id);
        }

        $pdf = Pdf::loadView('exports.ticket_pdf', [
            'venta'       => $venta,
            'tenant'      => $tenant,
            'logoBase64'  => $logoBase64,
            'qrUrl'       => $qrUrl,
            'color'       => $tenant->ticket_color    ?? '#1737c8',
            'mensaje'     => $tenant->ticket_mensaje  ?? '',
            'template'    => $tenant->ticket_template ?? 'minimalista',
            'storeName'   => $tenant->store_name      ?? 'Mi tienda',
            'whatsapp'    => $tenant->ticket_whatsapp ?? '',
        ])->setPaper([0, 0, 226.77, 600], 'portrait'); // 80mm de ancho

        return $pdf->download('ticket-' . ($venta->ticket->folio ?? $venta->id) . '.pdf');
    }

    // ── PREVIEW AJAX ──────────────────────────────────────────
    public function preview(Request $request)
    {
        $plan = Auth::user()->plan ?? 'gratis';
        if ($plan !== 'business') abort(403);

        $tenant = Tenant::find(Auth::user()->tenant_id);

        // Venta de ejemplo para el preview
        $ventaEjemplo = [
            'folio'      => 'QVX-0001',
            'cliente'    => 'Cliente Ejemplo',
            'telefono'   => $request->whatsapp ?? '',
            'fecha'      => now()->format('d/m/Y H:i'),
            'metodo'     => 'Efectivo',
            'productos'  => [
                ['nombre' => 'Jordan 1 Retro', 'talla' => '28 MX', 'cantidad' => 1, 'precio' => 3200.00],
                ['nombre' => 'Air Force One',  'talla' => '27 MX', 'cantidad' => 2, 'precio' => 2500.00],
            ],
            'subtotal'   => 8200.00,
            'descuento'  => 0,
            'total'      => 8200.00,
            'puntos'     => 164,
        ];

        return view('exports.ticket_pdf', [
            'venta'      => null,
            'ejemplo'    => $ventaEjemplo,
            'tenant'     => $tenant,
            'logoBase64' => null,
            'qrUrl'      => 'https://wa.me/5212345678901',
            'color'      => $request->color    ?? $tenant->ticket_color    ?? '#1737c8',
            'mensaje'    => $request->mensaje  ?? $tenant->ticket_mensaje  ?? '',
            'template'   => $request->template ?? $tenant->ticket_template ?? 'minimalista',
            'storeName'  => $tenant->store_name ?? 'Mi tienda',
            'whatsapp'   => $request->whatsapp  ?? $tenant->ticket_whatsapp ?? '',
            'isPreview'  => true,
        ]);
    }
}