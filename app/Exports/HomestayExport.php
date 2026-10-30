<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class HomestayExport implements WithHeadings
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
}