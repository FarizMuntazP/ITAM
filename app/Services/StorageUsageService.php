<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class StorageUsageService
{
    public function publicDiskStats(): array
    {
        $groups = [
            'photos' => 'assets/photos',
            'thumbnails' => 'assets/photos/thumbnails',
            'qrcodes' => 'qrcodes',
        ];

        $stats = [];
        $totalBytes = 0;
        $totalFiles = 0;

        foreach ($groups as $key => $directory) {
            $files = $key === 'photos'
                ? Storage::disk('public')->files($directory)
                : Storage::disk('public')->allFiles($directory);
            $bytes = array_sum(array_map(
                fn (string $file): int => Storage::disk('public')->size($file),
                $files
            ));

            $stats[$key] = [
                'directory' => $directory,
                'files' => count($files),
                'bytes' => $bytes,
                'human_size' => $this->formatBytes($bytes),
            ];

            $totalBytes += $bytes;
            $totalFiles += count($files);
        }

        return [
            'total_bytes' => $totalBytes,
            'total_files' => $totalFiles,
            'human_total' => $this->formatBytes($totalBytes),
            'groups' => $stats,
        ];
    }

    public function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $value = $bytes / 1024;

        foreach ($units as $index => $unit) {
            if ($value < 1024 || $index === count($units) - 1) {
                return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.') . ' ' . $unit;
            }

            $value /= 1024;
        }

        return $bytes . ' B';
    }
}
