<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class client extends Model
{
    use HasFactory;

    protected $fillable = ['name','telefono','direccion'];
    
    public function sales(){
        return $this->hasMany(sale::class);
    }

     public function totalGastado()
    {
        return $this->sales()->sum('total');
    }
}