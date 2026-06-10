<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title as ChartTitle;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class DashboardSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithCharts
{
    public function __construct(
        private array  $metrics,
        private string $periodo,
        private        $user
    ) {}

    public function title(): string
    {
        return 'Dashboard Ejecutivo';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22, 'B' => 18, 'C' => 18, 'D' => 18,
            'E' => 18, 'F' => 18, 'G' => 18,
        ];
    }

    public function array(): array
    {
        $m          = $this->metrics;
        $totalVentas = $m['totalPeriodo'];
        $totalReg    = $m['totalReg'];
        $ticketProm  = $totalReg > 0 ? round($m['totalPeriodo'] / $totalReg, 2) : 0;

        $rows   = [];
        $rows[] = ['QUIVEX — DASHBOARD EJECUTIVO', '', '', '', '', '', ''];
        $rows[] = [$this->user->store_name ?? 'Mi tienda', '', '', '', '', '', ''];
        $rows[] = ['Período: ' . $this->periodo, '', '', '', '', '', ''];
        $rows[] = ['', '', '', '', '', '', ''];
        $rows[] = ['TOTAL INGRESADO', 'TRANSACCIONES', 'TICKET PROMEDIO', '', '', '', ''];
        $rows[] = ['$' . number_format($totalVentas, 2), $totalReg, '$' . number_format($ticketProm, 2), '', '', '', ''];
        $rows[] = ['', '', '', '', '', '', ''];
        $rows[] = ['', '', '', '', '', '', ''];

        // Datos para pie chart — método de pago
        $rows[] = ['MÉTODO DE PAGO', 'MONTO', '', '', '', '', ''];
        $metodos = ['efectivo' => 0, 'tarjeta' => 0, 'transferencia' => 0];
        foreach ($m['porMetodo'] as $met => $data) {
            $metodos[$met] = is_array($data) ? floatval($data['monto']) : 0;
        }
        $rows[] = ['Efectivo',      $metodos['efectivo'],      '', '', '', '', ''];
        $rows[] = ['Tarjeta',       $metodos['tarjeta'],       '', '', '', '', ''];
        $rows[] = ['Transferencia', $metodos['transferencia'], '', '', '', '', ''];
        $rows[] = ['', '', '', '', '', '', ''];

        // Datos para bar chart — tipo de venta
        $rows[] = ['TIPO DE VENTA', 'MONTO', '', '', '', '', ''];
        $tipos = ['menudeo' => 0, 'mayoreo' => 0];
        foreach (($m['porTipo'] ?? []) as $tipo => $data) {
            $tipos[$tipo] = is_array($data) ? floatval($data['monto'] ?? 0) : 0;
        }
        $rows[] = ['Menudeo', $tipos['menudeo'], '', '', '', '', ''];
        $rows[] = ['Mayoreo', $tipos['mayoreo'], '', '', '', '', ''];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->setShowGridlines(false);

        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

        $sheet->getRowDimension(1)->setRowHeight(42);
        $sheet->getRowDimension(5)->setRowHeight(28);
        $sheet->getRowDimension(6)->setRowHeight(48);

        return [
            'A1' => [
                'font'      => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            'A2' => [
                'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1535B5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            'A3' => [
                'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '747688']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
            ],
            'A5' => [
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '9496A8']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            'B5' => [
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '9496A8']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            'C5' => [
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '9496A8']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            'A6' => [
                'font'      => ['bold' => true, 'size' => 22, 'color' => ['rgb' => '1737C8']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E5F0']]],
            ],
            'B6' => [
                'font'      => ['bold' => true, 'size' => 22, 'color' => ['rgb' => '1A1C1C']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E5F0']]],
            ],
            'C6' => [
                'font'      => ['bold' => true, 'size' => 22, 'color' => ['rgb' => '1737C8']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F3F4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E5F0']]],
            ],
            'A9'  => ['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']]],
            'B9'  => ['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']]],
            'A13' => ['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']]],
            'B13' => ['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']]],
        ];
    }

    public function charts(): array
    {
        // ── PIE CHART — Método de pago (filas 10-12) ─────────
        $pieLabels = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_STRING,
            'Dashboard Ejecutivo!$A$10:$A$12',
            null, 3
        );
        $pieValues = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_NUMBER,
            'Dashboard Ejecutivo!$B$10:$B$12',
            null, 3
        );
        $pieSeries   = new DataSeries(
            DataSeries::TYPE_PIECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, 0),
            [],
            [$pieLabels],
            [$pieValues]
        );
        $piePlotArea  = new PlotArea(null, [$pieSeries]);
        $pieLegend    = new Legend(Legend::POSITION_RIGHT, null, false);
        $pieChart      = new Chart('pieMetodo', new ChartTitle('Ventas por Método de Pago'), $pieLegend, $piePlotArea);
        $pieChart->setTopLeftPosition('D5');
        $pieChart->setBottomRightPosition('G14');

        // ── BAR CHART — Tipo de venta (filas 15-16) ──────────
        $barLabels = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_STRING,
            'Dashboard Ejecutivo!$A$15:$A$16',
            null, 2
        );
        $barValues = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_NUMBER,
            'Dashboard Ejecutivo!$B$15:$B$16',
            null, 2
        );
        $barSeries   = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, 0),
            [],
            [$barLabels],
            [$barValues]
        );
        $barPlotArea  = new PlotArea(null, [$barSeries]);
        $barLegend    = new Legend(Legend::POSITION_BOTTOM, null, false);
        $barChart      = new Chart('barTipo', new ChartTitle('Ingresos por Tipo de Venta'), $barLegend, $barPlotArea);
        $barChart->setTopLeftPosition('D15');
        $barChart->setBottomRightPosition('G26');

        return [$pieChart, $barChart];
    }
}