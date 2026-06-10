<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'store_name',
            'plan',
            'timezone',
            'lealtad_activo',
            'ticket_color',
            'ticket_logo',
            'ticket_mensaje',
            'ticket_template',
            'ticket_whatsapp',
            'ticket_font',
        ];
    }
}