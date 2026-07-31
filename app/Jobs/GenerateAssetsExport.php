<?php

namespace App\Jobs;

use App\Exports\AssetsExport;
use App\Models\AssetExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class GenerateAssetsExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600;

    public function __construct(
        public array $filters,
        public ?string $sort,
        public ?string $direction,
        public ?array $assetIds,
        public string $filename,
        public int $exportId,
    ) {
        $this->onQueue('exports');
    }

    public function handle(): void
    {
        $export = AssetExport::findOrFail($this->exportId);
        $export->update(['status' => 'processing', 'started_at' => now()]);

        try {
            Excel::store(
                new AssetsExport($this->filters, $this->sort, $this->direction, $this->assetIds),
                'exports/' . $this->filename,
                'public',
            );

            $export->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $export->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    public function failed(\Throwable $exception): void
    {
        AssetExport::whereKey($this->exportId)->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
