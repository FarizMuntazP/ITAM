<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'asset_id',
        'asset_type',
        'quantity',
        'asset_name',
        'category_id',
        'store_id',
        'brand',
        'model',
        'serial_number',
        'specs',
        'condition',
        'status',
        'purchase_date',
        'warranty_until',
        'purchase_price',
        'location_detail',
        'notes',
        'photo',
        'photo_thumbnail',
        'qr_code_path',
        'added_at',
        'current_employee_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'warranty_until' => 'date',
            'purchase_price' => 'decimal:2',
            'added_at' => 'datetime',
            'quantity' => 'integer',
        ];
    }

    /**
     * Determine if this asset is a bulk (non-SN) type.
     */
    public function isBulk(): bool
    {
        return $this->asset_type === 'bulk';
    }

    /**
     * Determine if this asset is a unit (SN) type.
     */
    public function isUnit(): bool
    {
        return $this->asset_type === 'unit';
    }

    // ─── Relationships ───────────────────────────────────────

    /**
     * Get the category of this asset.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the store where this asset is located.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the employee currently holding this asset.
     */
    public function currentEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'current_employee_id');
    }

    /**
     * Get all loan assignments for this asset.
     */
    public function loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssetLoan::class)->latest('id');
    }

    /**
     * Get all activity log entries for this asset.
     */
    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssetActivity::class)->latest('id');
    }

    /**
     * Get all maintenance history entries for this asset.
     */
    public function maintenances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssetMaintenance::class)->orderByDesc('start_date')->orderByDesc('id');
    }

    // ─── Accessors ───────────────────────────────────────────

    /**
     * Calculate asset age from added_at to now.
     * Returns human-readable format: "X Tahun Y Bulan" or "X Bulan Y Hari" or "X Hari"
     */
    public function getAgeAttribute(): string
    {
        $start = Carbon::parse($this->added_at);
        $now = Carbon::now();
        $diff = $start->diff($now);

        if ($diff->y > 0) {
            return "{$diff->y} Tahun {$diff->m} Bulan";
        } elseif ($diff->m > 0) {
            return "{$diff->m} Bulan {$diff->d} Hari";
        } else {
            return "{$diff->d} Hari";
        }
    }

    /**
     * Get age category for color coding.
     * green: < 2 years (new)
     * yellow: 2-4 years (consider replacement)
     * red: > 4 years (old)
     */
    public function getAgeColorAttribute(): string
    {
        $start = Carbon::parse($this->added_at);
        $years = $start->diffInYears(Carbon::now());

        if ($years < 2) {
            return 'green';
        } elseif ($years < 4) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    /**
     * Get the condition badge color.
     */
    public function getConditionColorAttribute(): string
    {
        return match ($this->condition) {
            'good' => 'green',
            'fair' => 'yellow',
            'poor' => 'orange',
            'damaged' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'maintenance' => 'yellow',
            'disposed' => 'red',
            default => 'gray',
        };
    }

    // ─── Static Helpers ──────────────────────────────────────

    /**
     * Generate a unique asset ID based on category code.
     * Format: ITAM-{CATEGORY_CODE}-{SEQUENTIAL_NUMBER_4_DIGITS}
     */
    public static function generateAssetId(int $categoryId): string
    {
        $category = Category::findOrFail($categoryId);
        $count = self::where('category_id', $categoryId)->count() + 1;

        return 'ITAM-' . $category->category_code . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
