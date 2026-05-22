<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::withCount('assets');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('store_code', 'like', "%{$search}%")
                  ->orWhere('store_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        // Filter by assets status
        if ($filter = $request->input('filter')) {
            if ($filter === 'active') {
                $query->has('assets');
            } elseif ($filter === 'empty') {
                $query->doesntHave('assets');
            }
        }

        // Sort
        $sort = $request->input('sort', 'store_code');
        $direction = $request->input('direction', 'asc');
        $allowedSorts = ['store_code', 'store_name', 'assets_count'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('store_code', 'asc');
        }

        $stores = $query->paginate($request->input('per_page', 25))->withQueryString();

        // Stats
        $totalStores = Store::count();
        $totalAssets = \App\Models\Asset::count();
        $storesWithAssets = Store::whereHas('assets')->count();
        $storesEmpty = $totalStores - $storesWithAssets;

        return view('stores.index', compact(
            'stores', 'totalStores', 'totalAssets', 'storesWithAssets', 'storesEmpty'
        ));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function show(Store $store)
    {
        return redirect()->route('stores.edit', $store);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_code' => 'required|string|max:20|unique:stores,store_code',
            'store_name' => 'required|string|max:100',
            'location' => 'required|string|max:200',
            'region' => 'nullable|string|max:100',
        ]);

        Store::create($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store berhasil ditambahkan.');
    }

    public function edit(Request $request, Store $store)
    {
        $query = $store->assets()->with('category');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_id', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter by condition
        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $assets = $query->latest('added_at')->paginate(25)->withQueryString();

        // Stats for this store
        $totalAssets = $store->assets()->count();
        $conditionStats = [
            'good' => $store->assets()->where('condition', 'good')->count(),
            'fair' => $store->assets()->where('condition', 'fair')->count(),
            'poor' => $store->assets()->where('condition', 'poor')->count(),
            'damaged' => $store->assets()->where('condition', 'damaged')->count(),
        ];
        $statusStats = [
            'active' => $store->assets()->where('status', 'active')->count(),
            'inactive' => $store->assets()->where('status', 'inactive')->count(),
            'maintenance' => $store->assets()->where('status', 'maintenance')->count(),
            'disposed' => $store->assets()->where('status', 'disposed')->count(),
        ];

        return view('stores.edit', compact(
            'store', 'assets', 'totalAssets', 'conditionStats', 'statusStats'
        ));
    }

    public function print(Request $request, Store $store)
    {
        $query = $store->assets()->with('category');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_id', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter by condition
        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Fetch all matching assets without pagination for print layout
        $assets = $query->latest('added_at')->get();

        return view('stores.print', compact('store', 'assets'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'store_code' => 'required|string|max:20|unique:stores,store_code,' . $store->id,
            'store_name' => 'required|string|max:100',
            'location' => 'required|string|max:200',
            'region' => 'nullable|string|max:100',
        ]);

        $store->update($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        if ($store->assets()->count() > 0) {
            return redirect()->route('stores.index')
                ->with('error', 'Store tidak dapat dihapus karena masih memiliki ' . $store->assets()->count() . ' aset terhubung.');
        }

        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store berhasil dihapus.');
    }
}
