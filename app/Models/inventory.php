<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'talla',
        'stock',
        'precio_decimal',
    ];

    // Relación principal — usada en el dashboard y controllers
    public function product()
    {
        return $this->belongsTo(product::class);
    }

    // Alias para compatibilidad con código existente
    public function products()
    {
        return $this->belongsTo(product::class);
    }

    public function details()
    {
        return $this->hasMany(detail_sale::class);
    }
}