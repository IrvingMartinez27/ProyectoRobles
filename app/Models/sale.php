<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sale extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'sales';

    protected $fillable = [
        'tenant_id','client_id','user_id','box_id','total',
        'metodo_pago','tipo_venta','puntos_ganados',
        'puntos_canjeados','descuento_puntos','almacen_id',
    ];

    public function client()
    {
        return $this->belongsTo(client::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function box()
    {
        return $this->belongsTo(box::class);
    }

    public function details()
    {
        return $this->hasMany(detail_sale::class);
    }

    public function ticket()
    {
        return $this->hasOne(ticket::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}