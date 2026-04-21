<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'box_id',
        'total',
        'metodo_pago',];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function client(){
        return $this->belongsTo(client::class);
    }

    public function details(){
        return $this->hasMany(detail_sale::class);
    }

    public function tickets(){
        return $this->hasOne(ticket::class);
    }

    public function box(){
        return $this->belongsTo(box::class);
    }

    public function caja(){
        return $this->belongsTo(sale::class);
    }

}