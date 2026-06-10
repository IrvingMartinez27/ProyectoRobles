<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inventory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'inventories';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'talla',
        'stock',
        'precio_decimal',
        'almacen_id',
        'en_transito',
        'apartado',
    ];
}