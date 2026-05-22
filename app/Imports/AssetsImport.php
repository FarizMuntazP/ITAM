<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class AssetsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $successCount = 0;
    protected $failedCount = 0;

    public function collection(Collection $rows)
    {
        $processedSerials = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Header is row 1

            // Clean up keys and extract values
            $data = [
                'asset_name' => isset($row['asset_name']) ? trim($row['asset_name']) : '',
                'category_code' => isset($row['category_code']) ? trim($row['category_code']) : '',
                'store_code' => isset($row['store_code']) ? trim($row['store_code']) : '',
                'brand' => (isset($row['brand']) && trim($row['brand']) !== '') ? trim($row['brand']) : null,
                'model' => (isset($row['model']) && trim($row['model']) !== '') ? trim($row['model']) : null,
                'serial_number' => (isset($row['serial_number']) && trim($row['serial_number']) !== '') ? trim($row['serial_number']) : null,
                'specs' => isset($row['specs']) ? trim($row['specs']) : null,
                'condition' => isset($row['condition']) ? strtolower(trim($row['condition'])) : 'good',
                'status' => isset($row['status']) ? strtolower(trim($row['status'])) : 'active',
                'purchase_date' => (isset($row['purchase_date']) && trim($row['purchase_date']) !== '') ? trim($row['purchase_date']) : null,
                'warranty_until' => (isset($row['warranty_until']) && trim($row['warranty_until']) !== '') ? trim($row['warranty_until']) : null,
                'purchase_price' => (isset($row['purchase_price']) && trim($row['purchase_price']) !== '') ? trim($row['purchase_price']) : null,
                'location_detail' => (isset($row['location_detail']) && trim($row['location_detail']) !== '') ? trim($row['location_detail']) : null,
                'notes' => isset($row['notes']) ? trim($row['notes']) : null,
            ];

            // If empty row, skip
            if (empty($data['asset_name']) && empty($data['category_code']) && empty($data['store_code'])) {
                continue;
            }

            $rowErrors = [];

            // Validation
            if (empty($data['asset_name'])) {
                $rowErrors[] = 'Nama aset wajib diisi.';
            }
            if (empty($data['category_code'])) {
                $rowErrors[] = 'Kode kategori wajib diisi.';
            }
            if (empty($data['store_code'])) {
                $rowErrors[] = 'Kode store wajib diisi.';
            }

            $category = null;
            if (!empty($data['category_code'])) {
                $category = Category::where('category_code', $data['category_code'])->first();
                if (!$category) {
                    $rowErrors[] = "Kategori dengan kode '{$data['category_code']}' tidak ditemukan.";
                }
            }

            $store = null;
            if (!empty($data['store_code'])) {
                $store = Store::where('store_code', $data['store_code'])->first();
                if (!$store) {
                    $rowErrors[] = "Store dengan kode '{$data['store_code']}' tidak ditemukan.";
                }
            }

            $allowedConditions = ['good', 'fair', 'poor', 'damaged'];
            if (!in_array($data['condition'], $allowedConditions)) {
                $rowErrors[] = "Kondisi harus berupa salah satu dari: " . implode(', ', $allowedConditions);
            }

            $allowedStatuses = ['active', 'inactive', 'maintenance', 'disposed'];
            if (!in_array($data['status'], $allowedStatuses)) {
                $rowErrors[] = "Status harus berupa salah satu dari: " . implode(', ', $allowedStatuses);
            }

            if ($data['serial_number']) {
                if (in_array($data['serial_number'], $processedSerials)) {
                    $rowErrors[] = "Nomor Seri '{$data['serial_number']}' duplikat di dalam file Excel.";
                } else {
                    $processedSerials[] = $data['serial_number'];
                    if (Asset::where('serial_number', $data['serial_number'])->exists()) {
                        $rowErrors[] = "Nomor Seri '{$data['serial_number']}' sudah terdaftar di sistem.";
                    }
                }
            }

            if ($data['purchase_price'] !== null && !is_numeric($data['purchase_price'])) {
                $rowErrors[] = "Harga beli harus berupa angka.";
            }

            if ($data['purchase_date']) {
                $d = \DateTime::createFromFormat('Y-m-d', $data['purchase_date']);
                // Handle float Excel date if necessary, but assume string format first
                if (!$d || $d->format('Y-m-d') !== $data['purchase_date']) {
                    // Try to convert Excel timestamp or float to date if it's numeric
                    if (is_numeric($data['purchase_date'])) {
                        try {
                            $data['purchase_date'] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['purchase_date']))->format('Y-m-d');
                        } catch (\Exception $ex) {
                            $rowErrors[] = "Format tanggal pembelian salah (harus YYYY-MM-DD).";
                        }
                    } else {
                        $rowErrors[] = "Format tanggal pembelian salah (harus YYYY-MM-DD).";
                    }
                }
            }

            if ($data['warranty_until']) {
                $d = \DateTime::createFromFormat('Y-m-d', $data['warranty_until']);
                if (!$d || $d->format('Y-m-d') !== $data['warranty_until']) {
                    if (is_numeric($data['warranty_until'])) {
                        try {
                            $data['warranty_until'] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['warranty_until']))->format('Y-m-d');
                        } catch (\Exception $ex) {
                            $rowErrors[] = "Format tanggal garansi salah (harus YYYY-MM-DD).";
                        }
                    } else {
                        $rowErrors[] = "Format tanggal garansi salah (harus YYYY-MM-DD).";
                    }
                }
            }

            if (!empty($rowErrors)) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'asset_name' => $data['asset_name'] ?: 'N/A',
                    'errors' => $rowErrors
                ];
                $this->failedCount++;
                continue;
            }

            try {
                $assetId = Asset::generateAssetId($category->id);

                $asset = Asset::create([
                    'asset_id' => $assetId,
                    'asset_name' => $data['asset_name'],
                    'category_id' => $category->id,
                    'store_id' => $store->id,
                    'brand' => $data['brand'],
                    'model' => $data['model'],
                    'serial_number' => $data['serial_number'],
                    'specs' => $data['specs'],
                    'condition' => $data['condition'],
                    'status' => $data['status'],
                    'purchase_date' => $data['purchase_date'],
                    'warranty_until' => $data['warranty_until'],
                    'purchase_price' => $data['purchase_price'],
                    'location_detail' => $data['location_detail'],
                    'notes' => $data['notes'],
                    'added_at' => now(),
                ]);

                $this->generateQrCode($asset);
                $this->successCount++;
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'asset_name' => $data['asset_name'],
                    'errors' => ['Database error: ' . $e->getMessage()]
                ];
                $this->failedCount++;
            }
        }
    }

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

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = $directory . '/' . $filename;

        $qrCodeContent = QrCode::format('svg')
            ->size(250)
            ->margin(1)
            ->generate($jsonStr);

        Storage::disk('public')->put($path, $qrCodeContent);

        $asset->update(['qr_code_path' => $path]);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }
}
