<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class client extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'clients';

    protected $fillable = [
        'tenant_id','name','telefono','direccion','puntos',
    ];

    public function sales()
    {
        return $this->hasMany(sale::class);
    }
}