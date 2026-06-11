<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class category extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'categories';

    protected $fillable = ['tenant_id','name'];

    public function products()
    {
        return $this->hasMany(product::class);
    }
}