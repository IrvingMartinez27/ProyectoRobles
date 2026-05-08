<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = ['sale_id', 'folio'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            $ticket->folio = 'TKT-' . strtoupper(Str::random(8));
        });
    }

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }
}