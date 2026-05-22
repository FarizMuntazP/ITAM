<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
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
            'topStores'
        ));
    }
}
