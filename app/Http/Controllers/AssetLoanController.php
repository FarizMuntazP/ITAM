<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\Employee;
use Illuminate\Http\Request;

class AssetLoanController extends Controller
{
    /**
     * Assign (Checkout) asset to an employee.
     */
    public function checkout(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        if ($asset->status !== 'active') {
            return back()->with('error', 'Gagal! Hanya aset aktif yang dapat ditugaskan.');
        }

        if ($asset->current_employee_id) {
            return back()->with('error', 'Gagal! Aset ini sedang dipinjam/ditugaskan kepada staff lain.');
        }

        // Update asset current employee
        $asset->update([
            'current_employee_id' => $validated['employee_id']
        ]);

        // Create loan record
        AssetLoan::create([
            'asset_id' => $asset->id,
            'employee_id' => $validated['employee_id'],
            'loaned_by' => auth()->id(),
            'loan_date' => now(),
            'status' => 'active',
            'notes' => $validated['notes'],
        ]);

        $employee = Employee::find($validated['employee_id']);

        return back()->with('success', "Aset berhasil ditugaskan kepada {$employee->name}.");
    }

    /**
     * Return (Checkin) asset back.
     */
    public function checkin(Request $request, Asset $asset)
    {
        if (!$asset->current_employee_id) {
            return back()->with('error', 'Gagal! Aset ini tidak sedang dipinjam.');
        }

        // Find active loan
        $activeLoan = AssetLoan::where('asset_id', $asset->id)
            ->where('status', 'active')
            ->first();

        if ($activeLoan) {
            $activeLoan->update([
                'return_date' => now(),
                'returned_by' => auth()->id(),
                'status' => 'returned',
                'notes' => $request->input('notes') ? ($activeLoan->notes . "\n[Check-in Notes]: " . $request->input('notes')) : $activeLoan->notes,
            ]);
        }

        $employeeName = $asset->currentEmployee ? $asset->currentEmployee->name : 'karyawan';

        // Clear current employee
        $asset->update([
            'current_employee_id' => null
        ]);

        return back()->with('success', "Aset berhasil dikembalikan oleh {$employeeName}.");
    }
}
