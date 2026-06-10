<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteExport implements WithMultipleSheets
{
    public function __construct(
        public array  $metrics,
        public string $periodo,
        public        $user,
        public string $plan
    ) {}

    public function sheets(): array
    {
        return [
            new Sheets\DashboardSheet($this->metrics, $this->periodo, $this->user),
            new Sheets\TransaccionesSheet($this->metrics['ventasAll']),
            new Sheets\ProductosSheet($this->metrics['topProductos']),
        ];
    }
}