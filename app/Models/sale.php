<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sale extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'sales';

    protected $fillable = [
        'tenant_id',
        'client_id',
        'user_id',
        'box_id',
        'total',
        'metodo_pago',
        'tipo_venta',
        'puntos_ganados',
        'puntos_canjeados',
        'descuento_puntos',
        'almacen_id',
    ];
}