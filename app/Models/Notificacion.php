<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'notificaciones';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
        'data',
    ];
}