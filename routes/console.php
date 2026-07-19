<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\ItamBackupService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('itam:backup', function (ItamBackupService $backupService) {
    $path = $backupService->create();

    $this->info("Backup ITAM berhasil dibuat: {$path}");
})->purpose('Create an ITAM JSON and public storage ZIP backup');
