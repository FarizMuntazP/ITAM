<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Store;
use App\Models\Category;
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:stores,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:assets,serial_number',
            'specs' => 'nullable|string',
            'condition' => 'required|in:good,fair,poor,damaged',
            'status' => 'required|in:active,inactive,maintenance,disposed',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'location_detail' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Generate Asset ID
        $validated['asset_id'] = Asset::generateAssetId($validated['category_id']);
        $validated['added_at'] = now();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $validated['asset_id'] . '_' . time() . '_' . uniqid() . '.jpg';
            $path = 'assets/photos/' . $filename;
            $path = $this->compressAndResizeImage($file, $path);
            $validated['photo'] = $path;
        }

        $asset = Asset::create($validated);

        // Generate QR Code
        $this->generateQrCode($asset);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil ditambahkan dengan ID: ' . $asset->asset_id);
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'store', 'currentEmployee', 'loans.employee', 'loans.loanedBy', 'loans.returnedBy', 'activities.user', 'maintenances']);
        $employees = \App\Models\Employee::orderBy('name')->get();
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
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:stores,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:assets,serial_number,' . $asset->id,
            'specs' => 'nullable|string',
            'condition' => 'required|in:good,fair,poor,damaged',
            'status' => 'required|in:active,inactive,maintenance,disposed',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'location_detail' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($asset->photo) {
                Storage::disk('public')->delete($asset->photo);
            }
            $file = $request->file('photo');
            $filename = $asset->asset_id . '_' . time() . '_' . uniqid() . '.jpg';
            $path = 'assets/photos/' . $filename;
            $path = $this->compressAndResizeImage($file, $path);
            $validated['photo'] = $path;
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
    public function destroy(Asset $asset)
    {
        // Delete photo file
        if ($asset->photo) {
            Storage::disk('public')->delete($asset->photo);
        }
        // Delete QR code file
        if ($asset->qr_code_path) {
            Storage::disk('public')->delete($asset->qr_code_path);
        }

        $assetId = $asset->asset_id;
        $asset->delete();

        return redirect()->route('assets.index')
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
     * Download QR Code.
     */
    public function downloadQr(Asset $asset)
    {
        if ($asset->qr_code_path && Storage::disk('public')->exists($asset->qr_code_path)) {
            $ext = pathinfo($asset->qr_code_path, PATHINFO_EXTENSION) ?: 'svg';
            return Storage::disk('public')->download($asset->qr_code_path, $asset->asset_id . '_QR.' . $ext);
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
     * Compress and resize uploaded image using PHP GD.
     * Fallback to normal upload if GD is not available.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $targetPath
     * @param  int  $maxWidth
     * @param  int  $quality
     * @return string
     */
    private function compressAndResizeImage($file, $targetPath, $maxWidth = 1200, $quality = 80)
    {
        if (!extension_loaded('gd')) {
            // Fallback: store as-is
            return $file->storeAs('assets/photos', basename($targetPath), 'public');
        }

        $tempPath = $file->getRealPath();
        list($width, $height, $type) = getimagesize($tempPath);

        // Load image resource based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = @imagecreatefromjpeg($tempPath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = @imagecreatefrompng($tempPath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = @imagecreatefromwebp($tempPath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = @imagecreatefromgif($tempPath);
                break;
            default:
                $sourceImage = false;
        }

        if (!$sourceImage) {
            // Fallback if loading failed
            return $file->storeAs('assets/photos', basename($targetPath), 'public');
        }

        // Calculate new dimensions
        $newWidth = $width;
        $newHeight = $height;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)($height * ($maxWidth / $width));
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Fill background with white (since JPEG has no transparency)
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);

        // Resize
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Capture compressed JPEG output using output buffering
        ob_start();
        imagejpeg($newImage, null, $quality);
        $compressedData = ob_get_clean();

        // Save using Storage facade
        Storage::disk('public')->put($targetPath, $compressedData);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $targetPath;
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
