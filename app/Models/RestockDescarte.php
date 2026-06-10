<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestockDescarte extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'restock_descartes';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'inventory_id',
        'tipo',
        'cantidad',
        'motivo',
        'user_id',
    ];
}