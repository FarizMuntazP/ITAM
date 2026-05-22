<?php

namespace App\Exports;

use App\Models\AssetActivity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ActivityLogsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
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
            'Waktu',
            'Asset ID',
            'Nama Aset',
            'Aksi',
            'Deskripsi Perubahan',
            'Aktor (Admin)'
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s') : '-',
            $activity->asset ? $activity->asset->asset_id : 'Aset Terhapus',
            $activity->asset ? $activity->asset->asset_name : '-',
            ucfirst($activity->action),
            $activity->description,
            $activity->user ? $activity->user->name : 'System / Seeder',
        ];
    }

    public function title(): string
    {
        return 'Log Aktivitas Aset';
    }
}
