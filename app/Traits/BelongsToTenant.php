<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function booted(): void
    {
        // Filtro automático por tenant en todas las queries
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (tenant()) {
                $builder->where((new static)->getTable() . '.tenant_id', tenant('id'));
            }
        });

        // Asigna tenant_id automáticamente al crear
        static::creating(function ($model) {
            if (tenant() && empty($model->tenant_id)) {
                $model->tenant_id = tenant('id');
            }
        });
    }
}