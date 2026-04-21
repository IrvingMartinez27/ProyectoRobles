<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    protected $fillable = ['name','telefono','direccion'];
    
    public function sales(){
        return $this->hasMany(sale::class);
    }
}