<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Place;
use App\Http\Requests\StorePlaceRequest;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    public function index(): JsonResponse
    {
        $places = Place::with('images')->get();

        $geojson = [
            'type'     => 'FeatureCollection',
            'features' => $places->map(fn($p) => [
                'type'       => 'Feature',
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [(float) $p->longitude, (float) $p->latitude],
                ],
                'properties' => [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'category'    => $p->category,
                    'description' => $p->description,
                    'image_url'   => $p->image_url,
                ],
            ]),
        ];

        return response()->json($geojson);
    }

    public function adminIndex()
    {
        $places = Place::with('images')->latest()->paginate(10);
        return view('admin.places.index', compact('places'));
    }

    public function show(Place $place)
    {
        $place->load('reviews');
        return view('place.detail', compact('place'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.places.create', compact('categories'));
    }

    public function store(StorePlaceRequest $request)
    {
        $media = app(MediaService::class);
        $data = $request->validated();
        $place = Place::create($data);
        $this->processUploads($request, $media, $place);

        return redirect()->route('admin.places.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Place $place)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.places.edit', compact('place', 'categories'));
    }

    public function update(StorePlaceRequest $request, Place $place)
    {
        $media = app(MediaService::class);
        $data = $request->validated();
        $place->update($data);
        $this->processUploads($request, $media, $place);

        return redirect()->route('admin.places.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Place $place)
    {
        if ($place->image_path) {
            Storage::disk('public')->delete($place->image_path);
        }

        foreach ($place->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            if ($img->thumb_path) {
                Storage::disk('public')->delete($img->thumb_path);
            }
        }

        $place->delete();

        return redirect()->route('admin.places.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function storeReview(Request $request, Place $place)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:100',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:2000',
        ]);

        $place->reviews()->create($validated);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    private function processUploads(Request $request, MediaService $media, Place $place): void
    {
        $sources = [
            'images' => 'images',
            'videos' => 'videos',
        ];

        foreach ($sources as $inputName => $type) {
            if (!$request->hasFile($inputName)) continue;

            foreach ($request->file($inputName) as $file) {
                $result = $media->store($file, 'places');

                // Skip duplicates
                $exists = $place->images()->where('file_hash', $result['hash'])->exists();
                if ($exists) continue;

                $place->images()->create([
                    'image_path' => $result['path'],
                    'thumb_path' => $result['thumb'],
                    'type'       => $result['type'],
                    'mime_type'  => $result['mime'],
                    'file_hash'  => $result['hash'],
                    'file_size'  => $result['size'],
                ]);
            }
        }

        // Auto-set hero from first gallery image if no hero exists
        if (!$place->image_path) {
            $first = $place->images()->orderBy('id')->first();
            if ($first) {
                $place->update(['image_path' => $first->image_path]);
            }
        }
    }
}
