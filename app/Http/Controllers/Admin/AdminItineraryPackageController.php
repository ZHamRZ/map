<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItineraryPackage;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminItineraryPackageController extends Controller
{
    public function index()
    {
        $packages = ItineraryPackage::latest()->paginate(10);
        return view('admin.itinerary-packages.index', compact('packages'));
    }

    public function create()
    {
        $places = Place::all();
        return view('admin.itinerary-packages.create', compact('places'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'icon'        => 'nullable|string|max:100',
            'duration'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'places'      => 'nullable|array',
            'places.*'    => 'exists:places,id',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('itinerary-packages', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $places = $validated['places'] ?? null;
        unset($validated['places']);

        $package = ItineraryPackage::create($validated);

        if ($places) {
            $sync = [];
            foreach ($places as $i => $placeId) {
                $sync[$placeId] = ['order' => $i];
            }
            $package->places()->sync($sync);
        }

        return redirect()->route('admin.itinerary-packages.index')
            ->with('success', 'Paket itinerary berhasil ditambahkan.');
    }

    public function edit(ItineraryPackage $itineraryPackage)
    {
        $places = Place::all();
        return view('admin.itinerary-packages.edit', compact('itineraryPackage', 'places'));
    }

    public function update(Request $request, ItineraryPackage $itineraryPackage)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'icon'        => 'nullable|string|max:100',
            'duration'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,svg,avif|max:5120',
            'places'      => 'nullable|array',
            'places.*'    => 'exists:places,id',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($itineraryPackage->image_path) {
                Storage::disk('public')->delete($itineraryPackage->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('itinerary-packages', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $places = $validated['places'] ?? null;
        unset($validated['places']);

        $itineraryPackage->update($validated);

        if ($places !== null) {
            $sync = [];
            foreach ($places as $i => $placeId) {
                $sync[$placeId] = ['order' => $i];
            }
            $itineraryPackage->places()->sync($sync);
        } else {
            $itineraryPackage->places()->sync([]);
        }

        return redirect()->route('admin.itinerary-packages.index')
            ->with('success', 'Paket itinerary berhasil diperbarui.');
    }

    public function destroy(ItineraryPackage $itineraryPackage)
    {
        if ($itineraryPackage->image_path) {
            Storage::disk('public')->delete($itineraryPackage->image_path);
        }

        $itineraryPackage->places()->detach();
        $itineraryPackage->delete();

        return redirect()->route('admin.itinerary-packages.index')
            ->with('success', 'Paket itinerary berhasil dihapus.');
    }
}
