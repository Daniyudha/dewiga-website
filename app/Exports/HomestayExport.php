<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class HomestayExport implements FromCollection, WithHeadings, WithEvents
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Name (ID)',
            'Name (EN)',
            'Address (ID)',
            'Address (EN)',
            'Latitude',
            'Longitude',
            'Capacity',
            'Is Active',
            'Order',
        ];
    }

    /**
     * Satu baris sampel data agar pengguna memahami format isian.
     */
    public function collection(): Collection
    {
        return collect([
            [
                'Rumah Keluarga Sutarjo',
                "Sutarjo's Family House",
                'Dusun Gabugan, Tanjungsari',
                'Gabugan Hamlet, Tanjungsari',
                -7.7523,
                110.2312,
                6,
                1,
                1,
            ],
        ]);
    }

    /**
     * Beri warna pada baris header dan rapikan lebar kolom.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header (baris 1, kolom A s/d I) dengan background hijau & teks putih
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF00A877'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(22);

                // Lebar kolom otomatis
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}