<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_sale extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'precio_unitario',
        'subtotal'];
    
    public function inventory(){
        return $this->belongsTo(inventory::class);
    }

    public function sale(){
        return $this->belongsTo(sale::class);
    }
}