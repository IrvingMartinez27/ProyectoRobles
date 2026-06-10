<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Collection;

class ProductosSheet implements
    FromCollection, WithTitle, WithHeadings, WithStyles,
    WithColumnWidths, WithMapping, WithColumnFormatting
{
    public function __construct(private $productos) {}

    public function title(): string
    {
        return 'Análisis de Productos';
    }

    public function collection(): Collection
    {
        return collect($this->productos)->sortByDesc('monto')->values();
    }

    public function headings(): array
    {
        return ['#', 'Producto', 'Unidades Vendidas', 'Ingreso Total'];
    }

    public function map($producto): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $producto['nombre'],
            $producto['unidades'],
            $producto['monto'],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 6, 'B' => 38, 'C' => 20, 'D' => 18];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"$"#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');
        $sheet->getRowDimension(1)->setRowHeight(28);

        $lastRow = $sheet->getHighestRow();

        if ($lastRow > 1) {
            // Bordes
            $sheet->getStyle('A2:D' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'EFEFEF'],
                    ],
                ],
            ]);

            // Zebra
            for ($i = 2; $i <= $lastRow; $i++) {
                if ($i % 2 === 0) {
                    $sheet->getStyle('A' . $i . ':D' . $i)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F9F9FB');
                }
            }

            // Top 3 destacados
            $highlights = ['1737C8', 'C4C5DA', 'F59E0B'];
            for ($i = 2; $i <= min(4, $lastRow); $i++) {
                $color = $highlights[$i - 2] ?? null;
                if ($color) {
                    $sheet->getStyle('A' . $i)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => $color]],
                    ]);
                }
            }

            // Alinear
            $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Fila TOTAL al final
            $totalRow = $lastRow + 1;
            $sheet->setCellValue('B' . $totalRow, 'TOTAL');
            $sheet->setCellValue('C' . $totalRow, '=SUM(C2:C' . $lastRow . ')');
            $sheet->setCellValue('D' . $totalRow, '=SUM(D2:D' . $lastRow . ')');
            $sheet->getStyle('B' . $totalRow . ':D' . $totalRow)->applyFromArray([
                'font'    => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']],
                'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1535B5']]],
            ]);
            $sheet->getStyle('C' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('D' . $totalRow)->getNumberFormat()->setFormatCode('"$"#,##0.00');
        }

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1737C8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}