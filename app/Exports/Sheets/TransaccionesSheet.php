<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransaccionesSheet implements
    FromCollection, WithTitle, WithHeadings, WithStyles,
    WithColumnWidths, WithMapping, WithColumnFormatting
{
    public function __construct(private $ventas) {}

    public function title(): string
    {
        return 'Transacciones';
    }

    public function collection()
    {
        return $this->ventas;
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'Fecha',
            'Cliente',
            'Vendedor',
            'Método de Pago',
            'Tipo de Venta',
            'Cant. Artículos',
            'Total',
        ];
    }

    public function map($venta): array
    {
        return [
            $venta->id,
            $venta->created_at->format('d/m/Y H:i'),
            $venta->client->name ?? 'Sin cliente',
            $venta->user->name ?? 'Sistema',
            ucfirst($venta->metodo_pago ?? 'efectivo'),
            ucfirst($venta->tipo_venta ?? 'menudeo'),
            $venta->details->sum('cantidad'),
            $venta->total,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 18,
            'C' => 26,
            'D' => 22,
            'E' => 16,
            'F' => 14,
            'G' => 16,
            'H' => 16,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'dd/mm/yyyy hh:mm',
            'H' => '"$"#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Freeze header row
        $sheet->freezePane('A2');

        // Alto de encabezado
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Bordes en toda la data
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            $sheet->getStyle('A2:H' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'EFEFEF'],
                    ],
                ],
            ]);

            // Zebra rows
            for ($i = 2; $i <= $lastRow; $i++) {
                if ($i % 2 === 0) {
                    $sheet->getStyle('A' . $i . ':H' . $i)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F9F9FB');
                }
            }

            // Alinear total a la derecha
            $sheet->getStyle('H2:H' . $lastRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Alinear cantidad al centro
            $sheet->getStyle('G2:G' . $lastRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
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