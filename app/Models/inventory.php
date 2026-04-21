<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    protected $fillable = [
        'product_id',
        'talla',
        'stock',
        'precio_decimal',
        'update_at'];

    public function products(){
        return $this->belongsTo(product::class);
    }
    public function details(){
        return $this->hasMany(detail_sale::class);
    }
}