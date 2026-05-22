<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ─────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Protected Routes ────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Assets — custom routes BEFORE resource (order matters)
    Route::get('assets/generate-id', [AssetController::class, 'generateId'])->name('assets.generate-id');
    Route::get('assets/export', [ExcelController::class, 'export'])->name('assets.export');
    Route::get('assets/template', [ExcelController::class, 'downloadTemplate'])->name('assets.template');
    Route::get('assets/import', [ExcelController::class, 'showImport'])->name('assets.import.form');
    Route::post('assets/import', [ExcelController::class, 'import'])->name('assets.import');
    Route::get('assets/{asset}/qr/download', [AssetController::class, 'downloadQr'])->name('assets.qr.download');
    Route::get('assets/{asset}/qr/print', [AssetController::class, 'printQr'])->name('assets.qr.print');
    Route::get('assets/lookup/{assetId}', [AssetController::class, 'lookup'])->name('assets.lookup');

    // Assets — Resource
    Route::resource('assets', AssetController::class);

    // Asset Loans / Assignment
    Route::post('assets/{asset}/checkout', [\App\Http\Controllers\AssetLoanController::class, 'checkout'])->name('assets.checkout');
    Route::post('assets/{asset}/checkin', [\App\Http\Controllers\AssetLoanController::class, 'checkin'])->name('assets.checkin');

    // Asset Maintenance
    Route::post('assets/{asset}/maintenances', [\App\Http\Controllers\AssetMaintenanceController::class, 'store'])->name('assets.maintenances.store');
    Route::put('assets/maintenances/{maintenance}', [\App\Http\Controllers\AssetMaintenanceController::class, 'update'])->name('assets.maintenances.update');
    Route::delete('assets/maintenances/{maintenance}', [\App\Http\Controllers\AssetMaintenanceController::class, 'destroy'])->name('assets.maintenances.destroy');

    // Stores
    Route::get('stores/{store}/print', [StoreController::class, 'print'])->name('stores.print');
    Route::resource('stores', StoreController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Employees
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    // Global Activity Logs
    Route::get('logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('logs/export', [\App\Http\Controllers\ActivityLogController::class, 'export'])->name('logs.export');
});
