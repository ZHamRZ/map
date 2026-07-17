<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'key' => 'Kesehatan',
                'name' => 'Kesehatan',
                'color' => '#E53935',
                'svg_path' => '<path d="M12 5v14M5 12h14"/>',
                'icon' => 'fa-heart',
                'sort_order' => 1,
            ],
            [
                'key' => 'Kantor Desa / Pemerintahan',
                'name' => 'Kantor Desa / Pemerintahan',
                'color' => '#2E7D32',
                'svg_path' => '<path d="M6 10h12v10H6z"/><path d="M4 10l8-6 8 6"/><path d="M10 14h4v6h-4z"/>',
                'icon' => 'fa-building-columns',
                'sort_order' => 2,
            ],
            [
                'key' => 'Pendidikan',
                'name' => 'Pendidikan',
                'color' => '#1976D2',
                'svg_path' => '<path d="M5 13l7 4 7-4V8l-7 4-7-4v5z"/><path d="M5 17l7 4 7-4"/>',
                'icon' => 'fa-graduation-cap',
                'sort_order' => 3,
            ],
            [
                'key' => 'Wisata Alam',
                'name' => 'Wisata Alam',
                'color' => '#43A047',
                'svg_path' => '<path d="M4 21h16l-5-8-3 4-2-3z"/><circle cx="13" cy="7" r="2.5"/>',
                'icon' => 'fa-mountain',
                'sort_order' => 4,
            ],
            [
                'key' => 'Kuliner',
                'name' => 'Kuliner',
                'color' => '#FB8C00',
                'svg_path' => '<path d="M7 4v10a2 2 0 004 0V4"/><path d="M9 16v4"/><path d="M17 4v16a2 2 0 004 0V4"/><path d="M15 4h4"/>',
                'icon' => 'fa-utensils',
                'sort_order' => 5,
            ],
            [
                'key' => 'Penginapan',
                'name' => 'Penginapan',
                'color' => '#3F51B5',
                'svg_path' => '<path d="M4 10h16v10H4z"/><path d="M8 14h2v3H8zM14 14h2v3h-2z"/><path d="M4 10l8-5 8 5"/>',
                'icon' => 'fa-bed',
                'sort_order' => 6,
            ],
            [
                'key' => 'UMKM / Ekonomi',
                'name' => 'UMKM / Ekonomi',
                'color' => '#FBC02D',
                'svg_path' => '<path d="M7 4h10v2H7z"/><path d="M5 8h14v12H5z"/><path d="M11 12h2v4h-2z"/>',
                'icon' => 'fa-store',
                'sort_order' => 7,
            ],
            [
                'key' => 'Tempat Ibadah',
                'name' => 'Tempat Ibadah',
                'color' => '#00897B',
                'svg_path' => '<path d="M12 4L5 10h2v8h10v-8h2z"/><circle cx="12" cy="14" r="2"/>',
                'icon' => 'fa-mosque',
                'sort_order' => 8,
            ],
            [
                'key' => 'Budaya',
                'name' => 'Budaya',
                'color' => '#8E24AA',
                'svg_path' => '<circle cx="8" cy="9" r="2.5"/><circle cx="16" cy="9" r="2.5"/><path d="M6 19l3-4h6l3 4"/>',
                'icon' => 'fa-landmark',
                'sort_order' => 9,
            ],
            [
                'key' => 'Infrastruktur',
                'name' => 'Infrastruktur',
                'color' => '#FF9800',
                'svg_path' => '<rect x="4" y="6" width="16" height="12" rx="2"/><path d="M12 6v12M4 12h16"/>',
                'icon' => 'fa-wrench',
                'sort_order' => 10,
            ],
            [
                'key' => 'Ruang Terbuka',
                'name' => 'Ruang Terbuka',
                'color' => '#66BB6A',
                'svg_path' => '<path d="M12 4a8 8 0 100 16 8 8 0 000-16z"/><path d="M12 8v5l3 2"/>',
                'icon' => 'fa-tree',
                'sort_order' => 11,
            ],
            [
                'key' => 'Umum',
                'name' => 'Umum',
                'color' => '#757575',
                'svg_path' => '<path d="M12 3a7 7 0 00-7 7c0 5 7 12 7 12s7-7 7-12a7 7 0 00-7-7z"/><circle cx="12" cy="10" r="2.5"/>',
                'icon' => 'fa-location-dot',
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
