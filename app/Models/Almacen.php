<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Almacen extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'almacenes';

    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion',
        'tipo',
        'user_id',
    ];
}