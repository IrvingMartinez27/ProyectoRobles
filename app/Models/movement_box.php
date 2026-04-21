<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class movement_box extends Model
{
    protected $fillable = [
        'box_id',
        'tipo',
        'concepto',
        'monto'
        ];
    
    public function box(){
        return $this->hasMany(box::class);
    }
}