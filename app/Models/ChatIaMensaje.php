<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatIaMensaje extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'chat_ia_mensajes';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'rol',
        'contenido',
        'tokens_usados',
    ];
}