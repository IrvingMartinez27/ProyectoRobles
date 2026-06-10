<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VerificarSuscripciones extends Command
{
    protected $signature   = 'suscripciones:verificar';
    protected $description = 'Baja el plan de usuarios cuya suscripción venció';

    public function handle(): void
    {
        $vencidos = DB::connection('mysql')
            ->table('users')
            ->where('plan', '!=', 'gratis')
            ->where('tipo_suscripcion', '!=', 'ninguna')
            ->whereNotNull('plan_vence_en')
            ->where('plan_vence_en', '<', now())
            ->get();

        foreach ($vencidos as $user) {
            DB::connection('mysql')->table('users')->where('id', $user->id)->update([
                'plan'             => 'gratis',
                'tipo_suscripcion' => 'ninguna',
                'mp_preapproval_id'=> null,
                'plan_vence_en'    => null,
            ]);
            if ($user->tenant_id) {
                DB::connection('mysql')->table('tenants')->where('id', $user->tenant_id)->update(['plan' => 'gratis']);
            }
            $this->info("Usuario {$user->id} ({$user->email}) bajado a gratis por vencimiento.");
        }

        $this->info("Verificación completada. {$vencidos->count()} usuario(s) procesado(s).");
    }
}