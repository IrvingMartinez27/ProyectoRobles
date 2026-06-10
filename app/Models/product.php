<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'products';

    protected $fillable = [
        'tenant_id',
        'name',
        'precio',
        'costo',
        'estado',
        'category_id',
    ];
}