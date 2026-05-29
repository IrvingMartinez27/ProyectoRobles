<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;

class PagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    // ── CREAR PREFERENCIA DE PAGO ─────────────────────────────
    public function crearPreferencia(Request $request)
    {
        $user = Auth::user();

        $client = new PreferenceClient();

        $preference = $client->create([
            'items' => [
                [
                    'id'          => 'plan_pro',
                    'title'       => 'Quivex Plan Pro — Mensual',
                    'description' => 'Productos ilimitados, múltiples usuarios, reportes avanzados y más.',
                    'quantity'    => 1,
                    'unit_price'  => 1.00,
                    'currency_id' => 'MXN',
                ],
            ],
            'payer' => [
                'email' => 'test_user_3786738964434967203@testuser.com',
            ],
            'back_urls' => [
                'success' => env('NGROK_URL') . '/pago/exito',
                'failure' => env('NGROK_URL') . '/pago/fallo',
                'pending' => env('NGROK_URL') . '/pago/pendiente',
            ],
            'auto_return'          => 'approved',
            'notification_url'     => env('NGROK_URL') . '/pago/webhook',
            'external_reference'   => (string) $user->id,
            'statement_descriptor' => 'QUIVEX',
        ]);

        return redirect($preference->sandbox_init_point);
    }

    // ── PAGO EXITOSO ──────────────────────────────────────────
    public function exito(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->plan !== 'pro') {
            $this->activarPlanPro($user);
        }

        return redirect('/dashboard')->with('success', '¡Bienvenido a Quivex Pro! Tu cuenta ha sido activada.');
    }

    // ── PAGO FALLIDO ──────────────────────────────────────────
    public function fallo(Request $request)
    {
        return redirect('/pago/planes')->with('error', 'El pago no pudo procesarse. Intenta de nuevo.');
    }

    // ── PAGO PENDIENTE ────────────────────────────────────────
    public function pendiente(Request $request)
    {
        return view('pago-pendiente');
    }

    // ── WEBHOOK DE MERCADO PAGO ───────────────────────────────
    public function webhook(Request $request)
    {
        Log::info('MP Webhook recibido', $request->all());

        $tipo = $request->input('type') ?? $request->input('topic');

        if ($tipo === 'payment') {
            $paymentId = $request->input('data.id') ?? $request->input('id');

            try {
                MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

                $paymentClient = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $paymentClient->get($paymentId);

                if ($payment->status === 'approved') {
                    $userId = $payment->external_reference;
                    $user   = User::find($userId);

                    if ($user && $user->plan !== 'pro') {
                        $this->activarPlanPro($user);
                        Log::info("Plan Pro activado para usuario {$userId}");
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error procesando webhook MP: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ── PÁGINA DE PLANES ──────────────────────────────────────
    public function planes()
    {
        $plan = Auth::check() ? (Auth::user()->plan ?? 'gratis') : 'gratis';
        return view('planes', compact('plan'));
    }

    // ── ACTIVAR PLAN PRO ──────────────────────────────────────
    private function activarPlanPro(User $user): void
    {
        $user->update(['plan' => 'pro']);

        // Actualizar también el tenant
        if ($user->tenant_id) {
            \App\Models\Tenant::where('id', $user->tenant_id)->update(['plan' => 'pro']);
        }
    }
}