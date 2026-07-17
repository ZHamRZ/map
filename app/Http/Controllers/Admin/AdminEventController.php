<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'start_time'   => 'nullable',
            'end_time'     => 'nullable',
            'location'     => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'category'     => 'nullable|string|max:100',
            'video_url'    => 'nullable|url|max:500',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'start_time'   => 'nullable',
            'end_time'     => 'nullable',
            'location'     => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'category'     => 'nullable|string|max:100',
            'video_url'    => 'nullable|url|max:500',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil dihapus.');
    }
}
