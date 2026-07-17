<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItineraryPackage;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MapController extends Controller
{
    /**
     * Tampilkan halaman peta utama (batas desa + marker + filter)
     */
    public function index(): View
    {
        $places = Place::with('images')->get();
        $placesJson = $places->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'lat' => (float) $p->latitude,
            'lng' => (float) $p->longitude,
            'category' => $p->category,
        ])->values();

        $categories = Category::active()->ordered()->get();

        return view('map', compact('placesJson', 'categories'));
    }

    /**
     * Tampilkan halaman Library Rekomendasi (paket itinerari)
     */
    public function library(): View
    {
        $packages = ItineraryPackage::published()->with('places')->get();

        if ($packages->isEmpty()) {
            $packages = collect([
                (object) [
                    'id'          => 'budaya',
                    'title'       => 'Tur Budaya & Edukasi',
                    'description' => 'Jelajahi potensi wisata dan pendidikan unggulan Desa Bilebante. Cocok untuk studi tour dan keluarga.',
                    'icon'        => 'fa-landmark',
                    'category_list'  => ['Wisata', 'Pendidikan'],
                    'places'      => collect([]),
                    'image_url'   => null,
                    'duration'    => null,
                    'slug'        => 'budaya',
                ],
                (object) [
                    'id'          => 'kuliner',
                    'title'       => 'Kuliner & Relaksasi',
                    'description' => 'Nikmati wisata kuliner dan tempat relaksasi di Bilebante. Pengalaman rasa dan ketenangan dalam satu paket.',
                    'icon'        => 'fa-utensils',
                    'category_list'  => ['Wisata', 'Ekonomi'],
                    'places'      => collect([]),
                    'image_url'   => null,
                    'duration'    => null,
                    'slug'        => 'kuliner',
                ],
                (object) [
                    'id'          => 'sehat',
                    'title'       => 'Fasilitas & Layanan',
                    'description' => 'Akses fasilitas kesehatan dan infrastruktur desa. Informasi penting bagi pengunjung dan penduduk.',
                    'icon'        => 'fa-hospital',
                    'category_list'  => ['Kesehatan', 'Infrastruktur'],
                    'places'      => collect([]),
                    'image_url'   => null,
                    'duration'    => null,
                    'slug'        => 'sehat',
                ],
            ]);
        }

        return view('library', compact('packages'));
    }

    /**
     * API GeoJSON untuk data tempat (marker)
     */
    public function apiPlaces(): JsonResponse
    {
        $places = Place::with('images')->get();

        $features = $places->map(function ($place) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $place->longitude, (float) $place->latitude],
                ],
                'properties' => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'category' => $place->category,
                    'description' => $place->description,
                    'image_url' => $place->image_url,
                    'latitude' => (float) $place->latitude,
                    'longitude' => (float) $place->longitude,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * API untuk membaca file GeoJSON batas desa
     */
    public function apiBoundary(): JsonResponse
    {
        $path = public_path('geojson/bilebante.geojson');

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'File GeoJSON tidak ditemukan.',
            ], 404);
        }

        $content = file_get_contents($path);
        $geojson = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'GeoJSON tidak valid: ' . json_last_error_msg(),
            ], 500);
        }

        return response()->json($geojson);
    }

    /**
     * API daftar kategori untuk peta
     */
    public function apiCategories(): JsonResponse
    {
        $categories = Category::active()->ordered()->get();

        return response()->json($categories);
    }
}
