<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AssetsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithColumnWidths
{
    public function __construct(
        protected array $filters = [],
        protected ?string $sort = null,
        protected ?string $direction = null,
        protected ?array $assetIds = null,
    ) {
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Asset::with(['category', 'store'])
            ->applyFilters($this->filters);

        if ($this->assetIds !== null) {
            $query->whereIn('assets.id', $this->assetIds);
        }

        return $query->applySorting($this->sort, $this->direction);
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

    public function columnWidths(): array
    {
        return [
            'A' => 16, 'B' => 28, 'C' => 20, 'D' => 14, 'E' => 22,
            'F' => 14, 'G' => 16, 'H' => 18, 'I' => 20, 'J' => 28,
            'K' => 14, 'L' => 14, 'M' => 16, 'N' => 16, 'O' => 16,
            'P' => 22, 'Q' => 18, 'R' => 28, 'S' => 20,
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
