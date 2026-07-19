<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetActivity;
use App\Models\Category;
use App\Models\Store;
use App\Models\Employee;

class AssetObserver
{
    /**
     * Handle the Asset "created" event.
     */
    public function created(Asset $asset): void
    {
        AssetActivity::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => "Aset pertama kali didaftarkan dengan status: " . ucfirst($asset->status) . " dan kondisi: " . ucfirst($asset->condition),
            'properties' => [
                'new' => $asset->only([
                    'asset_id', 'asset_name', 'category_id', 'store_id', 'brand', 'model', 'serial_number', 'condition', 'status'
                ])
            ]
        ]);
    }

    /**
     * Handle the Asset "updating" event.
     */
    public function updating(Asset $asset): void
    {
        $dirty = $asset->getDirty();
        if (empty($dirty)) {
            return;
        }

        $descriptions = [];
        $oldValues = [];
        $newValues = [];

        foreach ($dirty as $field => $newValue) {
            $oldValue = $asset->getOriginal($field);

            // Skip timestamps or fields we don't want to log individually
            if (in_array($field, ['updated_at', 'qr_code_path', 'photo', 'photo_thumbnail'])) {
                continue;
            }

            $oldValues[$field] = $oldValue;
            $newValues[$field] = $newValue;

            switch ($field) {
                case 'condition':
                    $descriptions[] = "Kondisi diubah dari '" . ucfirst($oldValue ?? 'none') . "' ke '" . ucfirst($newValue) . "'";
                    break;
                case 'status':
                    $descriptions[] = "Status diubah dari '" . ucfirst($oldValue ?? 'none') . "' ke '" . ucfirst($newValue) . "'";
                    break;
                case 'current_employee_id':
                    if (empty($oldValue) && !empty($newValue)) {
                        $emp = Employee::find($newValue);
                        $descriptions[] = "Aset ditugaskan (check-out) kepada: " . ($emp ? $emp->name : "Karyawan ID {$newValue}");
                    } elseif (!empty($oldValue) && empty($newValue)) {
                        $emp = Employee::find($oldValue);
                        $descriptions[] = "Aset dikembalikan (check-in) oleh: " . ($emp ? $emp->name : "Karyawan ID {$oldValue}");
                    } else {
                        $empOld = Employee::find($oldValue);
                        $empNew = Employee::find($newValue);
                        $descriptions[] = "Tugas dialihkan dari " . ($empOld ? $empOld->name : "Karyawan ID {$oldValue}") . " ke " . ($empNew ? $empNew->name : "Karyawan ID {$newValue}");
                    }
                    break;
                case 'store_id':
                    $storeOld = Store::find($oldValue);
                    $storeNew = Store::find($newValue);
                    $descriptions[] = "Store dipindahkan dari '" . ($storeOld ? $storeOld->store_name : "ID {$oldValue}") . "' ke '" . ($storeNew ? $storeNew->store_name : "ID {$newValue}") . "'";
                    break;
                case 'category_id':
                    $catOld = Category::find($oldValue);
                    $catNew = Category::find($newValue);
                    $descriptions[] = "Kategori diubah dari '" . ($catOld ? $catOld->category_name : "ID {$oldValue}") . "' ke '" . ($catNew ? $catNew->category_name : "ID {$newValue}") . "'";
                    break;
                case 'asset_name':
                    $descriptions[] = "Nama aset diubah menjadi '" . $newValue . "'";
                    break;
                case 'brand':
                    $descriptions[] = "Merek diubah dari '" . ($oldValue ?: 'kosong') . "' ke '" . ($newValue ?: 'kosong') . "'";
                    break;
                case 'model':
                    $descriptions[] = "Model diubah dari '" . ($oldValue ?: 'kosong') . "' ke '" . ($newValue ?: 'kosong') . "'";
                    break;
                case 'serial_number':
                    $descriptions[] = "Serial Number diubah dari '" . ($oldValue ?: 'kosong') . "' ke '" . ($newValue ?: 'kosong') . "'";
                    break;
                case 'notes':
                    $descriptions[] = "Catatan diubah menjadi '" . ($newValue ?: 'kosong') . "'";
                    break;
                case 'location_detail':
                    $descriptions[] = "Detail lokasi diubah dari '" . ($oldValue ?: 'kosong') . "' ke '" . ($newValue ?: 'kosong') . "'";
                    break;
                case 'purchase_date':
                    $oldDate = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d M Y') : 'kosong';
                    $newDate = $newValue ? \Carbon\Carbon::parse($newValue)->format('d M Y') : 'kosong';
                    $descriptions[] = "Tanggal pembelian diubah dari '" . $oldDate . "' ke '" . $newDate . "'";
                    break;
                case 'warranty_until':
                    $oldDate = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d M Y') : 'kosong';
                    $newDate = $newValue ? \Carbon\Carbon::parse($newValue)->format('d M Y') : 'kosong';
                    $descriptions[] = "Garansi diubah dari '" . $oldDate . "' ke '" . $newDate . "'";
                    break;
                case 'purchase_price':
                    $oldPrice = $oldValue ? 'Rp ' . number_format($oldValue, 0, ',', '.') : 'kosong';
                    $newPrice = $newValue ? 'Rp ' . number_format($newValue, 0, ',', '.') : 'kosong';
                    $descriptions[] = "Harga pembelian diubah dari '" . $oldPrice . "' ke '" . $newPrice . "'";
                    break;
                case 'specs':
                    $descriptions[] = "Spesifikasi diubah menjadi '" . ($newValue ?: 'kosong') . "'";
                    break;
                default:
                    $descriptions[] = "Kolom '" . $field . "' diperbarui";
                    break;
            }
        }

        if (count($descriptions) > 0) {
            AssetActivity::create([
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'description' => implode(', ', $descriptions),
                'properties' => [
                    'old' => $oldValues,
                    'new' => $newValues
                ]
            ]);
        }
    }
}
