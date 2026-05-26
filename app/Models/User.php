<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Siempre usa la BD central — users nunca va en la BD del tenant
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'store_name',
        'email',
        'password',
        'estado',
        'role',
        'plan',
        'tenant_id',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }

    // Relación con el tenant de esta cuenta
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendedor(): bool
    {
        return $this->role === 'vendedor';
    }

    public function esPlanGratis(): bool
    {
        return ($this->plan ?? 'gratis') === 'gratis';
    }

    public function esPlanPro(): bool
    {
        return $this->plan === 'pro';
    }

    public function esPlanBusiness(): bool
    {
        return $this->plan === 'business';
    }
}