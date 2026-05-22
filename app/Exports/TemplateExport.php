<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'asset_name',
            'category_code',
            'store_code',
            'brand',
            'model',
            'serial_number',
            'specs',
            'condition',
            'status',
            'purchase_date',
            'warranty_until',
            'purchase_price',
            'location_detail',
            'notes'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Laptop Dell Latitude 5420',
                'NTB',
                'STR-001',
                'Dell',
                'Latitude 5420',
                'SN12345678',
                'RAM 16GB, SSD 512GB',
                'good',
                'active',
                '2024-01-15',
                '2026-01-15',
                '15000000',
                'Ruang IT Lantai 2',
                'Aset baru untuk tim developer'
            ]
        ];
    }

    public function title(): string
    {
        return 'Template Import Aset';
    }
}
