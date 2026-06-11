<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class box extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'boxes';

    protected $fillable = [
        'tenant_id','user_id','fecha_apertura',
        'fecha_cierre','monto_apertura','monto_final','estado',
    ];

    public function sales()
    {
        return $this->hasMany(sale::class);
    }

    public function movements()
    {
        return $this->hasMany(movement_box::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}