<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'slug',
        'descripcion',
        'precio',
        'category_id',
        'estado',
        'imagen',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function stockTotal()
    {
        return $this->inventories()->sum('stock');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name ?? 'producto') . '-' . uniqid();
        });
    }
}