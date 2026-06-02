<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoOperativo extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'almacen_id',
        'concepto',
        'categoria',
        'monto',
        'fecha',
    ];
    protected $table = 'gastos_operativos';

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}