<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::ordered()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key'        => 'required|string|max:100|unique:categories,key',
            'name'       => 'required|string|max:100',
            'color'      => 'required|string|max:20',
            'svg_path'   => 'required|string',
            'icon'       => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'key'        => 'required|string|max:100|unique:categories,key,' . $category->id,
            'name'       => 'required|string|max:100',
            'color'      => 'required|string|max:20',
            'svg_path'   => 'required|string',
            'icon'       => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $oldKey = $category->key;
        $category->update($validated);

        // Sync places when category key is renamed
        if ($oldKey !== $category->key) {
            Place::where('category', $oldKey)->update(['category' => $category->key]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $placesCount = Place::where('category', $category->key)->count();
        if ($placesCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Kategori '{$category->name}' masih digunakan oleh {$placesCount} tempat. Ubah kategori tempat tersebut terlebih dahulu.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
