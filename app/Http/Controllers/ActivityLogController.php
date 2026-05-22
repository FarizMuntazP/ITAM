<?php

namespace App\Http\Controllers;

use App\Models\AssetActivity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of all global asset activities with search and filters.
     */
    public function index(Request $request)
    {
        $query = AssetActivity::with(['asset', 'user']);

        // Search by Asset ID, Asset Name, User Name, or Description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('asset', function ($assetQ) use ($search) {
                      $assetQ->where('asset_id', 'like', "%{$search}%")
                            ->orWhere('asset_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Default sorting: latest first
        $perPage = $request->input('per_page', 50);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 50;
        }

        $activities = $query->latest('id')->paginate($perPage)->withQueryString();

        return view('logs.index', compact('activities'));
    }

    /**
     * Export activity logs to Excel based on search and filters.
     */
    public function export(Request $request)
    {
        $query = AssetActivity::with(['asset', 'user']);

        // Search by Asset ID, Asset Name, User Name, or Description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('asset', function ($assetQ) use ($search) {
                      $assetQ->where('asset_id', 'like', "%{$search}%")
                            ->orWhere('asset_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query->latest('id');

        $filename = 'Activity_Logs_' . now()->format('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ActivityLogsExport($query), $filename);
    }
}
