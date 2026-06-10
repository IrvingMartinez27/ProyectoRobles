<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class detail_sale extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'detail_sales';

    protected $fillable = [
        'tenant_id',
        'sale_id',
        'product_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'talla',
    ];
}