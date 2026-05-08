<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'descripcion']; // ← agregar descripcion

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name ?? 'categoria') . '-' . uniqid();
            if (empty($category->descripcion)) {
                $category->descripcion = 'Categoría de ' . $category->name;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}