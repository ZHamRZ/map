<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::ordered()->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string|max:10000',
            'category'    => 'nullable|string|max:100',
            'order'       => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published', true);

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string|max:10000',
            'category'    => 'nullable|string|max:100',
            'order'       => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published', true);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }
}
