<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Exports\AssetsExport;
use App\Exports\TemplateExport;
use App\Imports\AssetsImport;
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
        $query = Asset::with(['category', 'store']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_id', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('added_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('added_at', '<=', $request->date_to);
        }

        // Sort
        $sortField = $request->input('sort', 'added_at');
        $sortDir = $request->input('direction', 'desc');
        $allowedSorts = ['asset_id', 'asset_name', 'condition', 'status', 'added_at', 'purchase_price'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('added_at');
        }

        $filename = 'Asset_Inventory_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AssetsExport($query), $filename);
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

        $query = Asset::with(['category', 'store'])
            ->whereIn('id', $request->asset_ids)
            ->latest('added_at');

        $filename = 'Asset_Selected_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AssetsExport($query), $filename);
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
