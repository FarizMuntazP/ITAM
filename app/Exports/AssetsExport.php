<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssetsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Asset ID',
            'Nama Aset',
            'Kategori',
            'Kode Kategori',
            'Store',
            'Kode Store',
            'Merek',
            'Model',
            'Serial Number',
            'Spesifikasi',
            'Kondisi',
            'Status',
            'Tanggal Pembelian',
            'Garansi Hingga',
            'Harga Beli',
            'Lokasi Detail',
            'Umur Aset',
            'Catatan',
            'Tanggal Ditambahkan'
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->asset_id,
            $asset->asset_name,
            $asset->category ? $asset->category->category_name : '-',
            $asset->category ? $asset->category->category_code : '-',
            $asset->store ? $asset->store->store_name : '-',
            $asset->store ? $asset->store->store_code : '-',
            $asset->brand ?? '-',
            $asset->model ?? '-',
            $asset->serial_number ?? '-',
            $asset->specs ?? '-',
            ucfirst($asset->condition),
            ucfirst($asset->status),
            $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '-',
            $asset->warranty_until ? $asset->warranty_until->format('Y-m-d') : '-',
            $asset->purchase_price,
            $asset->location_detail ?? '-',
            $asset->age,
            $asset->notes ?? '-',
            $asset->added_at ? $asset->added_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    public function title(): string
    {
        return 'Data Aset IT';
    }
}
