<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'nombre',
        'tipo',
        'activo',
    ];
    protected $table = 'almacenes';

    public function gastos()
    {
        return $this->hasMany(GastoOperativo::class);
    }

    public function ventas()
    {
        return $this->hasMany(sale::class);
    }
}