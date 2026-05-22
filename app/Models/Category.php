<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'category_code',
        'category_name',
        'description',
    ];

    /**
     * Get all assets in this category.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the count of assets in this category.
     */
    public function getAssetCountAttribute(): int
    {
        return $this->assets()->count();
    }
}
