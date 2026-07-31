<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Models\AssetExport;
use App\Services\ItamBackupService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('itam:backup', function (ItamBackupService $backupService) {
    $path = $backupService->create();

    $this->info("Backup ITAM berhasil dibuat: {$path}");
})->purpose('Create an ITAM JSON and public storage ZIP backup');

Artisan::command('itam:cleanup-exports', function () {
    $cutoff = now()->subHours(config('itam_exports.retention_hours', 48));
    $exports = AssetExport::whereIn('status', ['completed', 'failed'])
        ->where('created_at', '<', $cutoff)
        ->get();

    foreach ($exports as $export) {
        Storage::disk('public')->delete('exports/' . $export->filename);
        $export->delete();
    }

    $this->info("Export lama dibersihkan: {$exports->count()} record.");
})->purpose('Remove completed and failed asset exports older than the retention period');

Schedule::command('itam:cleanup-exports')->dailyAt('03:00');
