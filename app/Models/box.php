<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class box extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'boxes';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_apertura',
        'monto_final',
        'estado',
    ];
}