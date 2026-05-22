<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    /**
     * Store a newly created maintenance log.
     */
    public function store(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'issue' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'solution' => 'nullable|string',
        ]);

        $maintenance = $asset->maintenances()->create($validated);

        if ($request->has('change_asset_status') && filter_var($request->change_asset_status, FILTER_VALIDATE_BOOLEAN)) {
            $asset->update(['status' => 'maintenance']);
        }

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Log maintenance berhasil ditambahkan.');
    }

    /**
     * Update the specified maintenance log.
     */
    public function update(Request $request, AssetMaintenance $maintenance)
    {
        $validated = $request->validate([
            'issue' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'solution' => 'nullable|string',
        ]);

        $maintenance->update($validated);

        if ($request->has('restore_asset_status') && filter_var($request->restore_asset_status, FILTER_VALIDATE_BOOLEAN)) {
            if ($validated['status'] === 'completed') {
                $maintenance->asset->update(['status' => 'active']);
            }
        }

        return redirect()->route('assets.show', $maintenance->asset_id)
            ->with('success', 'Log maintenance berhasil diperbarui.');
    }

    /**
     * Remove the specified maintenance log.
     */
    public function destroy(AssetMaintenance $maintenance)
    {
        $assetId = $maintenance->asset_id;
        $maintenance->delete();

        return redirect()->route('assets.show', $assetId)
            ->with('success', 'Log maintenance berhasil dihapus.');
    }
}
