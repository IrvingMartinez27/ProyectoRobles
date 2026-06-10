<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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

    // ── CREAR SUSCRIPCIÓN MENSUAL RECURRENTE ──────────────────
    public function crearPreferencia(Request $request)
    {
        $user = Auth::user();
        $plan = $request->input('plan', 'pro');

        if (!isset($this->planes[$plan])) {
            return redirect()->route('planes')->with('error', 'Plan no válido.');
        }

        $info = $this->planes[$plan];

        $response = \Illuminate\Support\Facades\Http::withToken(env('MP_ACCESS_TOKEN'))
            ->post('https://api.mercadopago.com/preapproval', [
                'reason'         => $info['nombre'] . ' — Mensual',
                'auto_recurring' => [
                    'frequency'          => 1,
                    'frequency_type'     => 'months',
                    'transaction_amount' => $info['precio'],
                    'currency_id'        => 'MXN',
                ],
                'back_url'           => env('APP_URL') . '/pago/exito-suscripcion',
                'payer_email'        => $user->email,
                'external_reference' => $user->id . '|' . $plan,
                'notification_url'   => env('APP_URL') . '/pago/webhook',
            ]);

        if ($response->failed()) {
            Log::error('Error creando suscripción MP', $response->json());
            return redirect()->route('planes')->with('error', 'No se pudo iniciar la suscripción. Intenta de nuevo.');
        }

        $data    = $response->json();
        $initUrl = $data['init_point'] ?? null;

        if (!$initUrl) {
            return redirect()->route('planes')->with('error', 'Error al obtener el link de pago.');
        }

        return redirect($initUrl);
    }

    // ── ÉXITO SUSCRIPCIÓN ─────────────────────────────────────
    public function exitoSuscripcion(Request $request)
    {
        $user          = Auth::user();
        $preapprovalId = $request->input('preapproval_id');

        if ($user && $preapprovalId) {
            try {
                $response = \Illuminate\Support\Facades\Http::withToken(env('MP_ACCESS_TOKEN'))
                    ->get("https://api.mercadopago.com/preapproval/{$preapprovalId}");

                $data  = $response->json();
                $ref   = $data['external_reference'] ?? '';
                $parts = explode('|', $ref);
                $plan  = $parts[1] ?? 'pro';

                DB::connection('mysql')->table('users')->where('id', $user->id)->update([
                    'plan'              => $plan,
                    'tipo_suscripcion'  => 'mensual',
                    'mp_preapproval_id' => $preapprovalId,
                    'mp_payer_email'    => $data['payer_email'] ?? $user->email,
                    'plan_vence_en'     => now()->addMonth(),
                ]);

                if ($user->tenant_id) {
                    DB::connection('mysql')->table('tenants')
                        ->where('id', $user->tenant_id)
                        ->update(['plan' => $plan]);
                }
            } catch (\Exception $e) {
                Log::error('Error activando suscripción: ' . $e->getMessage());
            }
        }

        return redirect('/dashboard')->with('success', '¡Suscripción activada! Se renovará automáticamente cada mes.');
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

        if ($tipo === 'subscription_preapproval') {
            $preapprovalId = $request->input('data.id') ?? $request->input('id');

            try {
                $response = \Illuminate\Support\Facades\Http::withToken(env('MP_ACCESS_TOKEN'))
                    ->get("https://api.mercadopago.com/preapproval/{$preapprovalId}");

                $data   = $response->json();
                $status = $data['status'] ?? '';
                $ref    = $data['external_reference'] ?? '';
                $parts  = explode('|', $ref);
                $userId = $parts[0];
                $plan   = $parts[1] ?? 'pro';
                $user   = User::find($userId);

                if ($user) {
                    if ($status === 'authorized') {
                        // Renovación exitosa
                        DB::connection('mysql')->table('users')->where('id', $userId)->update([
                            'plan'            => $plan,
                            'tipo_suscripcion'=> 'mensual',
                            'plan_vence_en'   => now()->addMonth(),
                        ]);
                        if ($user->tenant_id) {
                            DB::connection('mysql')->table('tenants')
                                ->where('id', $user->tenant_id)
                                ->update(['plan' => $plan]);
                        }
                        Log::info("Suscripción renovada: usuario {$userId}, plan {$plan}");

                    } elseif (in_array($status, ['cancelled', 'paused'])) {
                        DB::connection('mysql')->table('users')->where('id', $userId)->update([
                            'mp_preapproval_id' => null,
                            'tipo_suscripcion'  => 'ninguna',
                        ]);
                        Log::info("Suscripción cancelada: usuario {$userId}");
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error webhook preapproval: ' . $e->getMessage());
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