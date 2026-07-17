<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:1000',
            'body'        => 'required|string',
            'category'    => 'nullable|string|max:100',
            'author'      => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:1000',
            'body'        => 'required|string',
            'category'    => 'nullable|string|max:100',
            'author'      => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
