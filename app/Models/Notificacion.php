<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'color',
        'data',
        'leida_at',
    ];

    protected $casts = [
        'data'     => 'array',
        'leida_at' => 'datetime',
    ];

    // Solo las no leídas
    public function scopeNoLeidas($query)
    {
        return $query->whereNull('leida_at');
    }

    // Solo del usuario actual
    public function scopeDelUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}