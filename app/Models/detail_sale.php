<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_sale extends Model
{
    public $timestamps = false;

    protected $connection = 'tenant';

    protected $fillable = [
        'sale_id',
        'product_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'talla',
    ];

    public function product()
    {
        return $this->belongsTo(product::class);
    }

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }
}