<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'descripcion',
        'precio',
        'category_id',
        'estado'];
    
    public function inventory(){
        return $this->hasMany(inventory::class);
    }   
}