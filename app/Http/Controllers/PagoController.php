<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class PagoController extends Controller
{
    private array $planes = [
        'pro' => [
            'nombre'      => 'Quivex Plan Pro',
            'descripcion' => 'Productos ilimitados, voz IA, reportes avanzados y más.',
            'precio'      => 299.00,
        ],
        'business' => [
            'nombre'      => 'Quivex Plan Business',
            'descripcion' => 'Todo Pro + Ticket Studio, PDF, WhatsApp y multi-almacén.',
            'precio'      => 549.00,
        ],
    ];

    // ── PÁGINA DE PLANES ──────────────────────────────────────
    public function planes()
    {
        $plan            = Auth::check() ? (Auth::user()->plan ?? 'gratis') : 'gratis';
        $tipoSuscripcion = Auth::check() ? (Auth::user()->tipo_suscripcion ?? 'ninguna') : 'ninguna';
        $vence           = Auth::check() ? Auth::user()->plan_vence_en : null;
        return view('planes', compact('plan', 'tipoSuscripcion', 'vence'));
    }

    // ── CREAR PREFERENCIA DE PAGO (Checkout Pro) ─────────────
    public function crearPreferencia(Request $request)
    {
        $user = Auth::user();
        $plan = $request->input('plan', 'pro');

        if (!isset($this->planes[$plan])) {
            return redirect()->route('planes')->with('error', 'Plan no válido.');
        }

        $info = $this->planes[$plan];

        try {
            MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

            $client     = new PreferenceClient();
            $preference = $client->create([
                'items' => [[
                    'id'          => "plan_{$plan}",
                    'title'       => $info['nombre'] . ' — Mensual',
                    'description' => $info['descripcion'],
                    'quantity'    => 1,
                    'unit_price'  => $info['precio'],
                    'currency_id' => 'MXN',
                ]],
                'payer' => ['email' => $user->email],
                'back_urls' => [
                    'success' => env('APP_URL') . '/pago/exito-suscripcion',
                    'failure' => env('APP_URL') . '/pago/fallo',
                    'pending' => env('APP_URL') . '/pago/pendiente',
                ],
                'auto_return'          => 'approved',
                'notification_url'     => env('APP_URL') . '/pago/webhook',
                'external_reference'   => $user->id . '|' . $plan,
                'statement_descriptor' => 'QUIVEX',
            ]);

            return redirect($preference->init_point);

        } catch (\Exception $e) {
            Log::error('Error creando preferencia MP', [
                'error'   => $e->getMessage(),
                'plan'    => $plan,
                'user_id' => $user->id,
            ]);
            return redirect()->route('planes')->with('error', 'No se pudo iniciar el pago. Intenta de nuevo.');
        }
    }

    // ── ÉXITO PAGO ────────────────────────────────────────────
    public function exitoSuscripcion(Request $request)
    {
        $user      = Auth::user();
        $paymentId = $request->input('payment_id');
        $plan      = 'pro';

        if ($user && $paymentId) {
            try {
                MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
                $paymentClient = new PaymentClient();
                $payment       = $paymentClient->get($paymentId);

                if ($payment && $payment->external_reference) {
                    $parts = explode('|', $payment->external_reference);
                    $plan  = $parts[1] ?? 'pro';
                }

                DB::connection('mysql')->table('users')->where('id', $user->id)->update([
                    'plan'             => $plan,
                    'tipo_suscripcion' => 'mensual',
                    'plan_vence_en'    => now()->addMonth(),
                ]);

                if ($user->tenant_id) {
                    DB::connection('mysql')->table('tenants')
                        ->where('id', $user->tenant_id)
                        ->update(['plan' => $plan]);
                }

                Log::info("Plan {$plan} activado para usuario {$user->id}");

            } catch (\Exception $e) {
                Log::error('Error activando plan: ' . $e->getMessage());
            }
        }

        return redirect('/dashboard')->with('success', '¡Plan ' . ucfirst($plan) . ' activado! Bienvenido.');
    }

    // ── CANCELAR SUSCRIPCIÓN ──────────────────────────────────
    public function cancelarSuscripcion(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->mp_preapproval_id) {
            return back()->with('error', 'No tienes una suscripción activa.');
        }

        try {
            \Illuminate\Support\Facades\Http::withToken(env('MP_ACCESS_TOKEN'))
                ->put("https://api.mercadopago.com/preapproval/{$user->mp_preapproval_id}", [
                    'status' => 'cancelled',
                ]);

            DB::connection('mysql')->table('users')->where('id', $user->id)->update([
                'mp_preapproval_id' => null,
                'tipo_suscripcion'  => 'ninguna',
            ]);

            $fecha = \Carbon\Carbon::parse($user->plan_vence_en)->format('d/m/Y');
            return back()->with('success', "Suscripción cancelada. Tu plan sigue activo hasta el {$fecha}.");

        } catch (\Exception $e) {
            Log::error('Error cancelando suscripción: ' . $e->getMessage());
            return back()->with('error', 'No se pudo cancelar. Contacta a soporte.');
        }
    }

    // ── WEBHOOK ───────────────────────────────────────────────
    public function webhook(Request $request)
    {
        Log::info('MP Webhook', $request->all());

        $tipo = $request->input('type') ?? $request->input('topic');

        if ($tipo === 'payment') {
            $paymentId = $request->input('data.id') ?? $request->input('id');
            try {
                MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
                $paymentClient = new PaymentClient();
                $payment       = $paymentClient->get($paymentId);

                if ($payment->status === 'approved') {
                    $parts  = explode('|', $payment->external_reference);
                    $userId = $parts[0];
                    $plan   = $parts[1] ?? 'pro';
                    $user   = User::find($userId);

                    if ($user) {
                        DB::connection('mysql')->table('users')->where('id', $userId)->update([
                            'plan'             => $plan,
                            'tipo_suscripcion' => 'mensual',
                            'plan_vence_en'    => now()->addMonth(),
                        ]);
                        if ($user->tenant_id) {
                            DB::connection('mysql')->table('tenants')
                                ->where('id', $user->tenant_id)
                                ->update(['plan' => $plan]);
                        }
                        Log::info("Plan {$plan} activado via webhook para usuario {$userId}");
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error webhook payment: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ── FALLO / PENDIENTE ─────────────────────────────────────
    public function fallo(Request $request)
    {
        return redirect()->route('planes')->with('error', 'El pago no pudo procesarse. Intenta de nuevo.');
    }

    public function pendiente(Request $request)
    {
        return view('pago-pendiente');
    }
}