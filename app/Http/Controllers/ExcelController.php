<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetExport;
use App\Exports\AssetsExport;
use App\Exports\TemplateExport;
use App\Imports\AssetsImport;
use App\Jobs\GenerateAssetsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    /**
     * Show import form.
     */
    public function showImport()
    {
        return view('assets.import');
    }

    /**
     * Export assets to Excel.
     */
    public function export(Request $request)
    {
        return $this->downloadOrQueueExport(
            $request->only(['search', 'store_id', 'category_id', 'status', 'condition', 'date_from', 'date_to']),
            $request->input('sort'),
            $request->input('direction'),
        );
    }

    /**
     * Export selected assets to Excel.
     */
    public function bulkExport(Request $request)
    {
        $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
        ]);

        return $this->downloadOrQueueExport(
            [],
            'added_at',
            'desc',
            array_map('intval', $request->asset_ids),
            'Asset_Selected_',
        );
    }

    private function downloadOrQueueExport(
        array $filters,
        ?string $sort,
        ?string $direction,
        ?array $assetIds = null,
        string $filenamePrefix = 'Asset_Inventory_',
    ) {
        $query = Asset::query()->applyFilters($filters);
        if ($assetIds !== null) {
            $query->whereIn('assets.id', $assetIds);
        }

        $filename = $filenamePrefix . now()->format('Ymd_His') . '_' . uniqid() . '.xlsx';
        $export = new AssetsExport($filters, $sort, $direction, $assetIds);

        if ($query->count() <= (int) config('itam_exports.queue_threshold', 10000)) {
            return Excel::download($export, $filename);
        }

        $exportRecord = AssetExport::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'status' => 'pending',
            'asset_count' => $query->count(),
        ]);

        GenerateAssetsExport::dispatch($filters, $sort, $direction, $assetIds, $filename, $exportRecord->id);

        return redirect()->route('assets.index')
            ->with('success', 'Export besar sedang diproses oleh queue worker.')
            ->with('export_pending', [
                'id' => $exportRecord->id,
                'filename' => $filename,
                'url' => route('assets.export.download', ['filename' => $filename]),
                'status_url' => route('assets.export.status', $exportRecord),
            ]);
    }

    public function download(string $filename)
    {
        abort_unless(preg_match('/^Asset_(Inventory|Selected)_\d{8}_\d{6}_[a-f0-9]+\.xlsx$/', $filename), 404);

        $export = AssetExport::where('filename', $filename)->firstOrFail();
        abort_unless($export->user_id === auth()->id(), 403);
        abort_unless($export->status === 'completed', 404, 'Export belum selesai diproses.');

        $path = 'exports/' . $filename;
        abort_unless(Storage::disk('public')->exists($path), 404, 'Export belum selesai diproses.');

        return Storage::disk('public')->download($path, $filename);
    }

    public function status(AssetExport $export)
    {
        abort_unless($export->user_id === auth()->id(), 403);

        return response()->json([
            'status' => $export->status,
            'download_url' => $export->status === 'completed'
                ? route('assets.export.download', ['filename' => $export->filename])
                : null,
            'error' => $export->status === 'failed' ? $export->error_message : null,
        ]);
    }

    /**
     * Import assets from Excel.
     */
    public function import(Request $request)
    {
        if ($request->input('import_action') === 'confirm') {
            return $this->confirmImport($request);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $previewPath = $file->store('imports/previews', 'local');
        $import = new AssetsImport(dryRun: true);

        try {
            Excel::import($import, Storage::disk('local')->path($previewPath));

            $successCount = $import->getSuccessCount();
            $failedCount = $import->getFailedCount();
            $errors = $import->getErrors();

            return redirect()->route('assets.import.form')
                ->with('warning', "Preview selesai: {$successCount} aset siap diimport, {$failedCount} baris perlu diperbaiki.")
                ->with('import_preview', [
                    'path' => $previewPath,
                    'ready_count' => $successCount,
                    'failed_count' => $failedCount,
                    'rows' => $import->getPreviewRows(),
                    'errors' => $errors,
                ]);
        } catch (\Exception $e) {
            Storage::disk('local')->delete($previewPath);
            return back()->with('error', 'Terjadi kesalahan saat mengimport file: ' . $e->getMessage());
        }
    }

    private function confirmImport(Request $request)
    {
        $validated = $request->validate([
            'preview_path' => 'required|string',
        ]);

        $previewPath = $validated['preview_path'];

        if (!str_starts_with($previewPath, 'imports/previews/') || !Storage::disk('local')->exists($previewPath)) {
            return redirect()->route('assets.import.form')
                ->with('error', 'File preview import tidak ditemukan. Silakan upload ulang file Excel.');
        }

        $import = new AssetsImport();

        try {
            Excel::import($import, Storage::disk('local')->path($previewPath));
            Storage::disk('local')->delete($previewPath);

            $successCount = $import->getSuccessCount();
            $failedCount = $import->getFailedCount();
            $errors = $import->getErrors();

            if ($failedCount > 0) {
                return redirect()->route('assets.import.form')
                    ->with('warning', "Import selesai dengan beberapa error: {$successCount} aset berhasil diimport, {$failedCount} gagal.")
                    ->with('import_errors', $errors);
            }

            return redirect()->route('assets.index')
                ->with('success', "Seluruh aset ({$successCount} item) berhasil diimport.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimport file: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateExport(), 'Template_Import_Asset_ITAM.xlsx');
    }
}
