<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::withCount('assets')->with('store');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('store_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($storeId = $request->input('store_id')) {
            $query->where('employees.store_id', $storeId);
        }

        // Sorting
        $sortField = $request->input('sort', 'name');
        $sortDir = $request->input('direction', 'asc');
        $allowedSorts = ['employee_code', 'name', 'store', 'assets_count'];

        if ($sortField === 'store') {
            $query->leftJoin('stores', 'employees.store_id', '=', 'stores.id')
                  ->select('employees.*')
                  ->orderBy('stores.store_name', $sortDir === 'desc' ? 'desc' : 'asc');
        } elseif (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $perPage = $request->input('per_page', 50);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 50;
        }

        $employees = $query->paginate($perPage)->withQueryString();
        $stores = \App\Models\Store::orderBy('store_name')->get();

        return view('employees.index', compact('employees', 'stores'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $stores = \App\Models\Store::orderBy('store_name')->get();
        return view('employees.create', compact('stores'));
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:employees,email',
            'store_id' => 'nullable|exists:stores,id',
            'phone' => 'nullable|string|max:30',
        ]);

        $employee = Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', "Karyawan {$employee->name} ({$employee->employee_code}) berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load([
            'assets.category',
            'loans' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'loans.asset.category',
            'loans.loanedBy',
            'loans.returnedBy'
        ]);
        $stores = \App\Models\Store::orderBy('store_name')->get();
        return view('employees.edit', compact('employee', 'stores'));
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:employees,email,' . $employee->id,
            'store_id' => 'nullable|exists:stores,id',
            'phone' => 'nullable|string|max:30',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', "Data karyawan {$employee->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->assets()->exists()) {
            return redirect()->route('employees.index')
                ->with('error', "Gagal menghapus! Karyawan {$employee->name} saat ini sedang memegang aset IT.");
        }

        $name = $employee->name;
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', "Karyawan {$name} berhasil dihapus.");
    }
}
