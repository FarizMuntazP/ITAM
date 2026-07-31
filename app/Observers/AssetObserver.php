<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetActivity;
use App\Models\Category;
use App\Models\Store;
use App\Models\Employee;
use Carbon\Carbon;

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
        $descriptions = [];
        $oldValues = [];
        $newValues = [];

        foreach ($asset->getDirty() as $field => $newValue) {
            $oldValue = $asset->getOriginal($field);

            if ($this->isIgnoredField($field)) {
                continue;
            }

            $oldValues[$field] = $oldValue;
            $newValues[$field] = $newValue;
            $descriptions[] = $this->describeChange($field, $oldValue, $newValue);
        }

        if ($descriptions === []) {
            return;
        }

        AssetActivity::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => implode(', ', $descriptions),
            'properties' => ['old' => $oldValues, 'new' => $newValues],
        ]);
    }

    private function isIgnoredField(string $field): bool
    {
        return in_array($field, ['updated_at', 'qr_code_path', 'photo', 'photo_thumbnail'], true);
    }

    private function describeChange(string $field, mixed $oldValue, mixed $newValue): string
    {
        return match ($field) {
            'condition' => $this->describeSimpleChange('Kondisi', $oldValue, $newValue, true),
            'status' => $this->describeSimpleChange('Status', $oldValue, $newValue, true),
            'current_employee_id' => $this->describeEmployeeChange($oldValue, $newValue),
            'store_id' => $this->describeRelationChange('Store', $oldValue, $newValue, Store::class, 'store_name'),
            'category_id' => $this->describeRelationChange('Kategori', $oldValue, $newValue, Category::class, 'category_name'),
            'asset_name' => "Nama aset diubah menjadi '{$newValue}'",
            'brand' => $this->describeSimpleChange('Merek', $oldValue, $newValue),
            'model' => $this->describeSimpleChange('Model', $oldValue, $newValue),
            'serial_number' => $this->describeSimpleChange('Serial Number', $oldValue, $newValue),
            'notes' => "Catatan diubah menjadi '{$this->displayValue($newValue)}'",
            'location_detail' => $this->describeSimpleChange('Detail lokasi', $oldValue, $newValue),
            'purchase_date' => $this->describeDateChange('Tanggal pembelian', $oldValue, $newValue),
            'warranty_until' => $this->describeDateChange('Garansi', $oldValue, $newValue),
            'purchase_price' => $this->describePriceChange($oldValue, $newValue),
            'specs' => "Spesifikasi diubah menjadi '{$this->displayValue($newValue)}'",
            default => "Kolom '{$field}' diperbarui",
        };
    }

    private function describeSimpleChange(string $label, mixed $oldValue, mixed $newValue, bool $capitalize = false): string
    {
        $old = $this->displayValue($oldValue);
        $new = $this->displayValue($newValue);
        if ($capitalize) {
            $old = ucfirst($old);
            $new = ucfirst($new);
        }
        return "{$label} diubah dari '{$old}' ke '{$new}'";
    }

    private function describeRelationChange(string $label, mixed $oldValue, mixed $newValue, string $model, string $nameField): string
    {
        $old = $model::find($oldValue);
        $new = $model::find($newValue);
        $oldName = $old ? $old->{$nameField} : "ID {$oldValue}";
        $newName = $new ? $new->{$nameField} : "ID {$newValue}";
        return "{$label} diubah dari '{$oldName}' ke '{$newName}'";
    }

    private function describeEmployeeChange(mixed $oldValue, mixed $newValue): string
    {
        $oldEmployee = $oldValue ? Employee::find($oldValue) : null;
        $newEmployee = $newValue ? Employee::find($newValue) : null;
        $oldName = $oldEmployee ? $oldEmployee->name : "Karyawan ID {$oldValue}";
        $newName = $newEmployee ? $newEmployee->name : "Karyawan ID {$newValue}";

        if (empty($oldValue) && !empty($newValue)) {
            return "Aset ditugaskan (check-out) kepada: {$newName}";
        }
        if (!empty($oldValue) && empty($newValue)) {
            return "Aset dikembalikan (check-in) oleh: {$oldName}";
        }
        return "Tugas dialihkan dari {$oldName} ke {$newName}";
    }

    private function describeDateChange(string $label, mixed $oldValue, mixed $newValue): string
    {
        return $this->describeSimpleChange($label, $oldValue ? Carbon::parse($oldValue)->format('d M Y') : null, $newValue ? Carbon::parse($newValue)->format('d M Y') : null);
    }

    private function describePriceChange(mixed $oldValue, mixed $newValue): string
    {
        $old = $oldValue ? 'Rp ' . number_format($oldValue, 0, ',', '.') : 'kosong';
        $new = $newValue ? 'Rp ' . number_format($newValue, 0, ',', '.') : 'kosong';
        return "Harga pembelian diubah dari '{$old}' ke '{$new}'";
    }

    private function displayValue(mixed $value): string
    {
        return $value ?: 'kosong';
    }
}
