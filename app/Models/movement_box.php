<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class movement_box extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'movement_boxes';

    protected $fillable = [
        'tenant_id',
        'box_id',
        'tipo',
        'monto',
        'descripcion',
    ];
}