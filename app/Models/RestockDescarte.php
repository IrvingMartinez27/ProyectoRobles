<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockDescarte extends Model
{
    protected $table = 'restock_descartes';

    protected $fillable = [
        'user_id',
        'product_id',
        'talla',
        'descartado_hasta',
    ];

    protected $casts = [
        'descartado_hasta' => 'datetime',
    ];
}