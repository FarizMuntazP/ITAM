<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetActivity;
use App\Models\AssetLoan;
use App\Models\AssetMaintenance;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ItamBackupService
{
    public function create(): string
    {
        $filename = 'itam_backup_' . now()->format('Ymd_His') . '.zip';
        $relativePath = 'backups/' . $filename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        File::ensureDirectoryExists(dirname($absolutePath));

        $zip = new ZipArchive();
        $zip->open($absolutePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFromString('database/assets.json', Asset::with(['category', 'store', 'currentEmployee'])->get()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/categories.json', Category::all()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/stores.json', Store::all()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/employees.json', Employee::with('store')->get()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/asset_loans.json', AssetLoan::all()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/asset_maintenances.json', AssetMaintenance::all()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/asset_activities.json', AssetActivity::all()->toJson(JSON_PRETTY_PRINT));
        $zip->addFromString('database/users.json', User::select('id', 'name', 'username', 'role', 'created_at', 'updated_at')->get()->toJson(JSON_PRETTY_PRINT));

        foreach (Storage::disk('public')->allFiles() as $file) {
            $zip->addFile(Storage::disk('public')->path($file), 'storage/public/' . $file);
        }

        $zip->close();

        return $relativePath;
    }
}
