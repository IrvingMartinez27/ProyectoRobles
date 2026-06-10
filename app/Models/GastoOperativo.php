<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GastoOperativo extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'gastos_operativos';

    protected $fillable = [
        'tenant_id',
        'concepto',
        'monto',
        'fecha',
        'categoria',
        'user_id',
    ];
}