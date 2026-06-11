<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'products';

    protected $fillable = [
        'tenant_id','name','precio','costo','estado','category_id',
    ];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function inventories()
    {
        return $this->hasMany(inventory::class);
    }

    public function details()
    {
        return $this->hasMany(detail_sale::class);
    }
}