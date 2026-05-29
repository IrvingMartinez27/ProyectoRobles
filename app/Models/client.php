<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class client extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = ['name', 'telefono', 'direccion', 'puntos'];

    public function sales()
    {
        return $this->hasMany(sale::class);
    }

    public function totalGastado()
    {
        return $this->sales()->sum('total');
    }
}