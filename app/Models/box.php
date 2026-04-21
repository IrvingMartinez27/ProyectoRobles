<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class box extends Model
{
    protected $fillable = [
        'user_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_apertura',
        'monto_final',
        'estado'];
    
    public function user(){
            return $this->belongsTo(User::class);
    }

    public function sale(){
        return $this->hasMany(sale::class);
    }

    public function venta(){
        return $this->hasMany(sale::class);
    }

    public function movenment_box(){
        return $this->hasMany(movement_box::class);
    }
}