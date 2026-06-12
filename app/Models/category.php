<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class category extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'categories';

    protected $fillable = ['tenant_id', 'name', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $baseSlug = Str::slug($category->name);
                $slug = $baseSlug;
                $counter = 1;

                while (
                    static::where('tenant_id', $category->tenant_id)
                        ->where('slug', $slug)
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(product::class);
    }
}