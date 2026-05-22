<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'store_code',
        'store_name',
        'location',
        'region',
    ];

    /**
     * Get all assets belonging to this store.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the count of assets in this store.
     */
    public function getAssetCountAttribute(): int
    {
        return $this->assets()->count();
    }
}
