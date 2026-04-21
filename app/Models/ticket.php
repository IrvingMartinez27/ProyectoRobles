<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
    protected $fillable = [
        'sale_id',
        'folio',
        ];

    public function sale(){
        return $this->belongsTo(sale::class);
    }
}
