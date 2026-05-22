<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('assets')->orderBy('category_code')->paginate(25);
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
        if ($category->assets()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->assets()->count() . ' aset terhubung.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
