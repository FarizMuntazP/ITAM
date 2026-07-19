<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Store;
use App\Models\Category;
use App\Services\StorageUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(StorageUsageService $storageUsageService)
    {
        $totalAssets = Asset::count();
        $totalStores = Store::count();
        $totalCategories = Category::count();
        $damagedAssets = Asset::where('condition', 'damaged')->count();

        // 10 latest assets
        $latestAssets = Asset::with(['category', 'store'])
            ->latest('added_at')
            ->take(10)
            ->get();

        // 5 worst condition assets (poor/damaged)
        $worstAssets = Asset::with(['category', 'store'])
            ->whereIn('condition', ['poor', 'damaged'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Aggregate condition stats
        $conditionCounts = Asset::select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->pluck('total', 'condition')
            ->toArray();

        $conditionsData = [
            'good' => $conditionCounts['good'] ?? 0,
            'fair' => $conditionCounts['fair'] ?? 0,
            'poor' => $conditionCounts['poor'] ?? 0,
            'damaged' => $conditionCounts['damaged'] ?? 0,
        ];

        // Aggregate status stats
        $statusCounts = Asset::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusesData = [
            'active' => $statusCounts['active'] ?? 0,
            'inactive' => $statusCounts['inactive'] ?? 0,
            'maintenance' => $statusCounts['maintenance'] ?? 0,
            'disposed' => $statusCounts['disposed'] ?? 0,
        ];

        // Top 5 categories by asset count
        $topCategories = Category::withCount('assets')
            ->orderBy('assets_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->category_name,
                    'count' => $category->assets_count,
                ];
            })
            ->toArray();

        // Top 5 stores by asset count
        $topStores = Store::withCount('assets')
            ->orderBy('assets_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($store) {
                return [
                    'name' => $store->store_name,
                    'count' => $store->assets_count,
                ];
            })
            ->toArray();

        // Warranty expiring within 90 days
        $warrantyExpiringAssets = Asset::with(['category'])
            ->whereNotNull('warranty_until')
            ->whereBetween('warranty_until', [now(), now()->addDays(90)])
            ->orderBy('warranty_until', 'asc')
            ->take(10)
            ->get();

        // Assets older than 4 years (by purchase_date or added_at)
        $oldAssets = Asset::with(['category'])
            ->where(function($q) {
                $q->where('purchase_date', '<=', now()->subYears(4))
                  ->orWhere(function($q2) {
                      $q2->whereNull('purchase_date')
                         ->where('added_at', '<=', now()->subYears(4));
                  });
            })
            ->whereNotIn('status', ['disposed']) // don't count disposed
            ->orderBy('purchase_date', 'asc')
            ->take(10)
            ->get();

        // Assets in maintenance for more than 30 days
        $longMaintenanceAssets = Asset::with(['category', 'store'])
            ->where('status', 'maintenance')
            ->where('updated_at', '<=', now()->subDays(30))
            ->orderBy('updated_at', 'asc')
            ->take(10)
            ->get();

        $storageStats = $storageUsageService->publicDiskStats();

        return view('dashboard.index', compact(
            'totalAssets',
            'totalStores',
            'totalCategories',
            'damagedAssets',
            'latestAssets',
            'worstAssets',
            'conditionsData',
            'statusesData',
            'topCategories',
            'topStores',
            'storageStats',
            'warrantyExpiringAssets',
            'oldAssets',
            'longMaintenanceAssets'
        ));
    }
}
