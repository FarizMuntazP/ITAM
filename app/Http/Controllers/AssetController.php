<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Store;
use App\Models\Category;
use App\Services\AssetImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * Display a listing of assets with filters, search, sort, and pagination.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'store']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('assets.asset_id', 'like', "%{$search}%")
                  ->orWhere('assets.asset_name', 'like', "%{$search}%")
                  ->orWhere('assets.brand', 'like', "%{$search}%")
                  ->orWhere('assets.model', 'like', "%{$search}%")
                  ->orWhere('assets.serial_number', 'like', "%{$search}%");
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
        
        if ($sortField === 'category') {
            $query->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
                  ->select('assets.*')
                  ->orderBy('categories.category_name', $sortDir === 'asc' ? 'asc' : 'desc');
        } elseif ($sortField === 'store') {
            $query->leftJoin('stores', 'assets.store_id', '=', 'stores.id')
                  ->select('assets.*')
                  ->orderBy('stores.store_name', $sortDir === 'asc' ? 'asc' : 'desc');
        } elseif (in_array($sortField, $allowedSorts)) {
            $query->orderBy('assets.' . $sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('assets.added_at');
        }

        // Pagination
        $perPage = in_array($request->input('per_page'), ['10', '25', '50']) ? (int)$request->per_page : 25;
        $assets = $query->paginate($perPage)->withQueryString();

        // For filter dropdowns
        $stores = Store::orderBy('store_name')->get();
        $categories = Category::orderBy('category_name')->get();

        return view('assets.index', compact('assets', 'stores', 'categories'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $stores = Store::orderBy('store_name')->get();
        $categories = Category::orderBy('category_name')->get();
        return view('assets.create', compact('stores', 'categories'));
    }

    /**
     * Store a newly created asset.
     */
    public function store(Request $request, AssetImageService $assetImageService)
    {
        $assetType = $request->input('asset_type', 'unit');

        $validated = $request->validate([
            'asset_type'     => 'required|in:unit,bulk',
            'quantity'       => 'required|integer|min:1|max:50',
            'asset_name'     => 'required|string|max:150',
            'category_id'    => 'required|exists:categories,id',
            'store_id'       => 'required|exists:stores,id',
            'brand'          => 'nullable|string|max:100',
            'model'          => 'nullable|string|max:100',
            // Serial number only required-unique for unit type with qty=1
            'serial_number'  => $assetType === 'unit' && (int)$request->quantity === 1
                                    ? 'nullable|string|max:100|unique:assets,serial_number'
                                    : 'nullable|string|max:100',
            'specs'          => 'nullable|string',
            'condition'      => 'required|in:good,fair,poor,damaged',
            'status'         => 'required|in:active,inactive,maintenance,disposed',
            'purchase_date'  => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'location_detail'=> 'nullable|string|max:200',
            'notes'          => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo upload once (shared for all generated assets)
        $photoData = [];
        if ($request->hasFile('photo')) {
            $tempAssetId = 'TEMP-' . time();
            $storedPhoto = $assetImageService->storeAssetPhoto($request->file('photo'), $tempAssetId);
            $photoData['photo'] = $storedPhoto['photo'];
            $photoData['photo_thumbnail'] = $storedPhoto['photo_thumbnail'];
        }

        $qty = (int) $validated['quantity'];

        // ── BULK ASSET: single row, qty > 1, no SN ────────────────────────
        if ($assetType === 'bulk') {
            $assetData = array_merge($validated, $photoData, [
                'asset_id'   => Asset::generateAssetId($validated['category_id']),
                'asset_type' => 'bulk',
                'quantity'   => $qty,
                'serial_number' => null,
                'added_at'   => now(),
            ]);
            $asset = Asset::create($assetData);
            $this->generateQrCode($asset);

            return redirect()->route('assets.show', $asset)
                ->with('success', "Aset massal berhasil ditambahkan (Qty: {$qty}) dengan ID: {$asset->asset_id}");
        }

        // ── UNIT ASSET: generate N separate rows, each with unique ID & QR ──
        $createdAssets = [];
        for ($i = 0; $i < $qty; $i++) {
            $assetData = array_merge($validated, $photoData, [
                'asset_id'      => Asset::generateAssetId($validated['category_id']),
                'asset_type'    => 'unit',
                'quantity'      => 1,
                // SN only on single-unit input; multi-unit SN to be filled per asset later
                'serial_number' => $qty === 1 ? ($validated['serial_number'] ?? null) : null,
                'added_at'      => now(),
            ]);
            $asset = Asset::create($assetData);
            $this->generateQrCode($asset);
            $createdAssets[] = $asset;
        }

        if ($qty === 1) {
            return redirect()->route('assets.show', $createdAssets[0])
                ->with('success', 'Aset berhasil ditambahkan dengan ID: ' . $createdAssets[0]->asset_id);
        }

        return redirect()->route('assets.index')
            ->with('success', "{$qty} aset berhasil di-generate! ID: {$createdAssets[0]->asset_id} s/d {$createdAssets[$qty-1]->asset_id}. Harap isi Serial Number masing-masing melalui tombol Edit.");
    }


    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'store', 'currentEmployee.store', 'loans.employee.store', 'loans.loanedBy', 'loans.returnedBy', 'activities.user', 'maintenances']);
        $employees = \App\Models\Employee::with('store')->orderBy('name')->get();
        return view('assets.show', compact('asset', 'employees'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        $stores = Store::orderBy('store_name')->get();
        $categories = Category::orderBy('category_name')->get();
        return view('assets.edit', compact('asset', 'stores', 'categories'));
    }

    /**
     * Update the specified asset.
     */
    public function update(Request $request, Asset $asset, AssetImageService $assetImageService)
    {
        $validated = $request->validate([
            'asset_name'     => 'required|string|max:150',
            'category_id'    => 'required|exists:categories,id',
            'store_id'       => 'required|exists:stores,id',
            'brand'          => 'nullable|string|max:100',
            'model'          => 'nullable|string|max:100',
            // SN unique only for unit assets
            'serial_number'  => $asset->isUnit()
                                    ? 'nullable|string|max:100|unique:assets,serial_number,' . $asset->id
                                    : 'nullable|string|max:100',
            'quantity'       => $asset->isBulk() ? 'required|integer|min:1|max:50' : 'nullable|integer',
            'specs'          => 'nullable|string',
            'condition'      => 'required|in:good,fair,poor,damaged',
            'status'         => 'required|in:active,inactive,maintenance,disposed',
            'purchase_date'  => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'location_detail'=> 'nullable|string|max:200',
            'notes'          => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // For bulk assets, clear SN and set qty; for unit, qty is always 1
        if ($asset->isBulk()) {
            $validated['serial_number'] = null;
        } else {
            $validated['quantity'] = 1;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            $assetImageService->deleteAssetPhoto($asset->photo, $asset->photo_thumbnail);

            $storedPhoto = $assetImageService->storeAssetPhoto(
                $request->file('photo'),
                $asset->asset_id
            );

            $validated['photo'] = $storedPhoto['photo'];
            $validated['photo_thumbnail'] = $storedPhoto['photo_thumbnail'];
        }

        $asset->update($validated);

        // Regenerate QR Code
        $this->generateQrCode($asset);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified asset.
     */
    public function destroy(Asset $asset, AssetImageService $assetImageService)
    {
        // Delete photo file
        $assetImageService->deleteAssetPhoto($asset->photo, $asset->photo_thumbnail);
        // Delete QR code file
        if ($asset->qr_code_path) {
            Storage::disk('public')->delete($asset->qr_code_path);
        }

        $assetId = $asset->asset_id;
        $assetShowUrl = route('assets.show', $asset->id);
        $asset->delete();

        $previousUrl = url()->previous();
        if (str_contains($previousUrl, $assetShowUrl)) {
            return redirect()->route('assets.index')
                ->with('success', "Aset {$assetId} berhasil dihapus.");
        }

        return redirect()->to($previousUrl)
            ->with('success', "Aset {$assetId} berhasil dihapus.");
    }

    /**
     * Generate Asset ID preview via AJAX.
     */
    public function generateId(Request $request)
    {
        $categoryId = $request->input('category_id');
        if (!$categoryId) {
            return response()->json(['asset_id' => '']);
        }

        try {
            $assetId = Asset::generateAssetId($categoryId);
            return response()->json(['asset_id' => $assetId]);
        } catch (\Exception $e) {
            return response()->json(['asset_id' => ''], 422);
        }
    }

    /**
     * Download QR Code as PNG.
     */
    public function downloadQr(Asset $asset)
    {
        if ($asset->qr_code_path && Storage::disk('public')->exists($asset->qr_code_path)) {
            $svgContent = Storage::disk('public')->get($asset->qr_code_path);
            $filename = $asset->asset_id . '_QR.png';

            // Parse SVG dimensions
            $pngSize = 500;

            // Create a blank white image
            $image = imagecreatetruecolor($pngSize, $pngSize);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            imagefill($image, 0, 0, $white);

            // Extract rect elements from SVG to reconstruct QR code
            preg_match_all('/\<rect[^>]*\/>/', $svgContent, $matches);

            // Get viewBox to determine SVG coordinate space
            preg_match('/viewBox=["\']([^"\']+)["\']/', $svgContent, $viewBoxMatch);
            $svgWidth = 250; // default
            if (!empty($viewBoxMatch[1])) {
                $parts = preg_split('/[\s,]+/', trim($viewBoxMatch[1]));
                if (count($parts) >= 4) {
                    $svgWidth = (float)$parts[2];
                }
            }

            $scale = $pngSize / $svgWidth;

            foreach ($matches[0] as $rect) {
                // Check if it's a dark/black module (not white background)
                $hasFill = preg_match('/fill=["\']([^"\']+)["\']/', $rect, $fillMatch);
                if ($hasFill && (strtolower($fillMatch[1]) === '#ffffff' || strtolower($fillMatch[1]) === 'white')) {
                    continue;
                }

                preg_match('/x=["\']([^"\']+)["\']/', $rect, $xMatch);
                preg_match('/y=["\']([^"\']+)["\']/', $rect, $yMatch);
                preg_match('/width=["\']([^"\']+)["\']/', $rect, $wMatch);
                preg_match('/height=["\']([^"\']+)["\']/', $rect, $hMatch);

                if (!empty($xMatch) && !empty($yMatch) && !empty($wMatch) && !empty($hMatch)) {
                    $x = (int)round((float)$xMatch[1] * $scale);
                    $y = (int)round((float)$yMatch[1] * $scale);
                    $w = (int)round((float)$wMatch[1] * $scale);
                    $h = (int)round((float)$hMatch[1] * $scale);

                    imagefilledrectangle($image, $x, $y, $x + $w - 1, $y + $h - 1, $black);
                }
            }

            // Output to buffer
            ob_start();
            imagepng($image);
            $pngContent = ob_get_clean();
            imagedestroy($image);

            return response($pngContent)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        return back()->with('error', 'QR Code belum tersedia untuk aset ini.');
    }

    /**
     * Print QR Code page.
     */
    public function printQr(Asset $asset)
    {
        $asset->load(['category', 'store']);
        return view('assets.print-qr', compact('asset'));
    }

    /**
     * Generate QR Code for the asset and save it.
     */
    private function generateQrCode(Asset $asset)
    {
        $asset->load(['category', 'store']);

        $data = [
            'asset_id' => $asset->asset_id,
            'asset_name' => $asset->asset_name,
            'category' => $asset->category ? $asset->category->category_name : '',
            'brand' => $asset->brand ?? '',
            'model' => $asset->model ?? '',
            'serial_number' => $asset->serial_number ?? '',
            'store' => $asset->store ? $asset->store->store_name : '',
            'condition' => ucfirst($asset->condition),
            'status' => ucfirst($asset->status),
            'added_at' => $asset->added_at ? $asset->added_at->format('Y-m-d') : '',
        ];

        $jsonStr = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $filename = 'qr_' . $asset->asset_id . '_' . time() . '.svg';
        $directory = 'qrcodes';

        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = $directory . '/' . $filename;

        // Generate QR code content as SVG
        $qrCodeContent = QrCode::format('svg')
            ->size(250)
            ->margin(1)
            ->generate($jsonStr);

        Storage::disk('public')->put($path, $qrCodeContent);

        // Delete old QR code if exists
        if ($asset->qr_code_path) {
            Storage::disk('public')->delete($asset->qr_code_path);
        }

        $asset->update(['qr_code_path' => $path]);
    }

    /**
     * Bulk update store for selected assets.
     */
    public function bulkUpdateStore(Request $request)
    {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
            'store_id' => 'required|exists:stores,id',
        ]);

        Asset::whereIn('id', $validated['asset_ids'])->update(['store_id' => $validated['store_id']]);

        // Regenerate QR codes to reflect new store
        $assets = Asset::whereIn('id', $validated['asset_ids'])->get();
        foreach ($assets as $asset) {
            $this->generateQrCode($asset);
        }

        return back()->with('success', count($validated['asset_ids']) . ' aset berhasil dipindahkan ke store baru.');
    }

    /**
     * Bulk update status for selected assets.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
            'status' => 'required|in:active,inactive,maintenance,disposed',
        ]);

        Asset::whereIn('id', $validated['asset_ids'])->update(['status' => $validated['status']]);

        // Regenerate QR codes to reflect new status
        $assets = Asset::whereIn('id', $validated['asset_ids'])->get();
        foreach ($assets as $asset) {
            $this->generateQrCode($asset);
        }

        return back()->with('success', 'Status ' . count($validated['asset_ids']) . ' aset berhasil diperbarui.');
    }

    /**
     * Print QR Code for multiple selected assets.
     */
    public function bulkPrintQr(Request $request)
    {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
        ]);

        $assets = Asset::with(['category', 'store'])
            ->whereIn('id', $validated['asset_ids'])
            ->get();

        if ($assets->isEmpty()) {
            return back()->with('error', 'Tidak ada aset yang dipilih.');
        }

        return view('assets.bulk-print-qr', compact('assets'));
    }

    /**
     * Lookup asset by asset_id and redirect to show page.
     */
    public function lookup($assetId)
    {
        $asset = Asset::where('asset_id', $assetId)->first();
        if (!$asset) {
            return redirect()->route('assets.index')->with('error', "Aset dengan ID {$assetId} tidak ditemukan.");
        }
        return redirect()->route('assets.show', $asset);
    }
}
