<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ticket extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'tickets';

    protected $fillable = [
        'tenant_id','sale_id','folio',
    ];

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }
}