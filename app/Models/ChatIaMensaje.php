<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatIaMensaje extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'user_id',
        'role',
        'contenido',
    ];
}