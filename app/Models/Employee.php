<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'store_id',
        'phone',
    ];

    /**
     * Get the store that this employee belongs to.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected static function booted()
    {
        static::creating(function ($employee) {
            if (!$employee->employee_code) {
                $employee->employee_code = self::generateEmployeeCode();
            }
        });
    }

    /**
     * Get all assets currently assigned to this employee.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'current_employee_id');
    }

    /**
     * Get all loans history for this employee.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(AssetLoan::class);
    }

    /**
     * Generate unique employee code.
     * Format: EMP-XXXX
     */
    public static function generateEmployeeCode(): string
    {
        $lastEmployee = self::orderBy('id', 'desc')->first();
        $nextNum = $lastEmployee ? ((int) str_replace('EMP-', '', $lastEmployee->employee_code)) + 1 : 1;
        return 'EMP-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
