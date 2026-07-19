<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class AssetsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $successCount = 0;
    protected $failedCount = 0;
    protected $previewRows = [];
    protected ?Collection $storesCache = null;
    protected ?Collection $categoriesCache = null;

    public function __construct(private readonly bool $dryRun = false)
    {
    }

    public function collection(Collection $rows)
    {
        $processedSerials = [];
        $previewCategoryCounts = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Header is row 1

            // Clean up keys and extract values
            $data = [
                'asset_name' => $this->cleanCell($row['asset_name'] ?? ''),
                'qty' => $this->cleanCell($row['qty'] ?? '1'),
                'category_code' => $this->cleanCell($row['category_code'] ?? ''),
                'store_code' => $this->cleanCell($row['store_code'] ?? ''),
                'brand' => $this->nullableCell($row['brand'] ?? null),
                'model' => $this->nullableCell($row['model'] ?? null),
                'serial_number' => $this->nullableCell($row['serial_number'] ?? null),
                'specs' => $this->nullableCell($row['specs'] ?? null),
                'condition' => strtolower($this->cleanCell($row['condition'] ?? 'good')),
                'status' => strtolower($this->cleanCell($row['status'] ?? 'active')),
                'purchase_date' => $this->nullableCell($row['purchase_date'] ?? null),
                'warranty_until' => $this->nullableCell($row['warranty_until'] ?? null),
                'purchase_price' => $this->nullableCell($row['purchase_price'] ?? null),
                'location_detail' => $this->nullableCell($row['location_detail'] ?? null),
                'notes' => $this->nullableCell($row['notes'] ?? null),
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
                $category = $this->resolveCategory($data['category_code']);
                if (!$category) {
                    $rowErrors[] = "Kategori dengan kode '{$data['category_code']}' tidak ditemukan.";
                }
            }

            $store = null;
            if (!empty($data['store_code'])) {
                $store = $this->resolveStore($data['store_code']);
                if (!$store) {
                    $rowErrors[] = "Store '{$data['store_code']}' tidak ditemukan. Isi kode store seperti '04' atau nama store seperti 'EXPRESS CARUBAN'.";
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

            if ($data['serial_number'] !== null) {
                if (in_array($data['serial_number'], $processedSerials)) {
                    $rowErrors[] = "Nomor Seri '{$data['serial_number']}' duplikat di dalam file Excel.";
                } else {
                    $processedSerials[] = $data['serial_number'];
                    if (Asset::where('serial_number', $data['serial_number'])->exists()) {
                        $rowErrors[] = "Nomor Seri '{$data['serial_number']}' sudah terdaftar di sistem.";
                    }
                }
            }

            $qty = (int) $data['qty'];
            if ($qty < 1) {
                $rowErrors[] = "Qty minimal 1.";
            } elseif ($qty > 50) {
                $rowErrors[] = "Qty maksimal 50 per baris import.";
            }

            if (!empty($data['serial_number']) && $qty > 1) {
                $rowErrors[] = "Jika Serial Number (SN) diisi, Qty hanya bisa 1.";
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
                if ($this->dryRun) {
                    if (!isset($previewCategoryCounts[$category->id])) {
                        $previewCategoryCounts[$category->id] = Asset::where('category_id', $category->id)->count() + 1;
                    }

                    $assetId = 'ITAM-' . $category->category_code . '-' . str_pad($previewCategoryCounts[$category->id], 4, '0', STR_PAD_LEFT);
                    $previewCategoryCounts[$category->id]++;

                    $this->previewRows[] = [
                        'row' => $rowNumber,
                        'asset_id' => $assetId,
                        'asset_name' => $data['asset_name'],
                        'qty' => $qty,
                        'category_code' => $data['category_code'],
                        'store_code' => $store->store_code,
                        'brand' => $data['brand'],
                        'model' => $data['model'],
                        'serial_number' => $data['serial_number'],
                        'condition' => $data['condition'],
                        'status' => $data['status'],
                    ];
                    $this->successCount++;
                    continue;
                }

                $assetId = Asset::generateAssetId($category->id);
                $assetType = (empty($data['serial_number']) && $qty > 1) ? 'bulk' : 'unit';

                $asset = Asset::create([
                    'asset_id' => $assetId,
                    'asset_type' => $assetType,
                    'quantity' => $qty,
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

    public function getPreviewRows(): array
    {
        return $this->previewRows;
    }

    private function cleanCell(mixed $value): string
    {
        $value = (string) ($value ?? '');
        $value = str_replace("\u{00A0}", ' ', $value);

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private function nullableCell(mixed $value): ?string
    {
        $value = $this->cleanCell($value);

        return $value === '' ? null : $value;
    }

    private function resolveCategory(string $categoryCode): ?Category
    {
        $needle = $this->normalizeLookup($categoryCode);
        $this->categoriesCache ??= Category::all();

        return $this->categoriesCache->first(function (Category $category) use ($needle) {
            return $this->normalizeLookup($category->category_code) === $needle;
        });
    }

    private function resolveStore(string $storeInput): ?Store
    {
        $needle = $this->normalizeLookup($storeInput);
        $needleNumeric = ctype_digit($needle) ? ltrim($needle, '0') : null;
        $this->storesCache ??= Store::all();

        return $this->storesCache->first(function (Store $store) use ($needle, $needleNumeric) {
            $code = $this->normalizeLookup($store->store_code);
            $name = $this->normalizeLookup($store->store_name);

            if ($code === $needle || $name === $needle) {
                return true;
            }

            return $needleNumeric !== null
                && ctype_digit($code)
                && ltrim($code, '0') === $needleNumeric;
        });
    }

    private function normalizeLookup(string $value): string
    {
        return strtolower($this->cleanCell($value));
    }
}
