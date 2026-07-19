<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'category_code');
        $sortDir = $request->input('direction', 'asc');
        $allowedSorts = ['category_code', 'category_name', 'assets_count'];
        
        $query = Category::withCount('assets');
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('category_code', 'asc');
        }
        
        $categories = $query->paginate(25)->withQueryString();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_code' => 'required|string|max:10|unique:categories,category_code|alpha_num',
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Force uppercase for code
        $validated['category_code'] = strtoupper($validated['category_code']);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category_code' => 'required|string|max:10|unique:categories,category_code,' . $category->id . '|alpha_num',
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['category_code'] = strtoupper($validated['category_code']);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $previousUrl = url()->previous();
        
        if ($category->assets()->count() > 0) {
            return redirect()->to($previousUrl)
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->assets()->count() . ' aset terhubung.');
        }

        $category->delete();

        return redirect()->to($previousUrl)
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
