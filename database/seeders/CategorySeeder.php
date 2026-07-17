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
                'svg_path' => '<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H8v-2h4V8h2v4h4v2z"/>',
                'icon' => 'fa-heart',
                'sort_order' => 1,
            ],
            [
                'key' => 'Kantor Desa / Pemerintahan',
                'name' => 'Kantor Desa / Pemerintahan',
                'color' => '#2E7D32',
                'svg_path' => '<path d="M12 2L1 11h3v9h5v-6h6v6h5v-9h3L12 2z"/>',
                'icon' => 'fa-building-columns',
                'sort_order' => 2,
            ],
            [
                'key' => 'Pendidikan',
                'name' => 'Pendidikan',
                'color' => '#1976D2',
                'svg_path' => '<path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18V17l7 4 7-4v-3.82L12 17l-7-3.82z"/>',
                'icon' => 'fa-graduation-cap',
                'sort_order' => 3,
            ],
            [
                'key' => 'Wisata Alam',
                'name' => 'Wisata Alam',
                'color' => '#43A047',
                'svg_path' => '<path d="M1 21h22L12 2 1 21zm3.47-2l4.5-7.02L14 17.5l2.5-3.5L19.53 19H4.47z"/><circle cx="18" cy="6" r="3" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M18 4v1M20 7h-1M15 7h1M18 10V9"/>',
                'icon' => 'fa-mountain',
                'sort_order' => 4,
            ],
            [
                'key' => 'Kuliner',
                'name' => 'Kuliner',
                'color' => '#FB8C00',
                'svg_path' => '<path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>',
                'icon' => 'fa-utensils',
                'sort_order' => 5,
            ],
            [
                'key' => 'Penginapan',
                'name' => 'Penginapan',
                'color' => '#3F51B5',
                'svg_path' => '<path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v10h2v-4h4v4h2V7z"/>',
                'icon' => 'fa-bed',
                'sort_order' => 6,
            ],
            [
                'key' => 'UMKM / Ekonomi',
                'name' => 'UMKM / Ekonomi',
                'color' => '#FBC02D',
                'svg_path' => '<path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2z"/>',
                'icon' => 'fa-store',
                'sort_order' => 7,
            ],
            [
                'key' => 'Tempat Ibadah',
                'name' => 'Tempat Ibadah',
                'color' => '#00897B',
                'svg_path' => '<path d="M12 3L4 9v12h5v-7h6v7h5V9l-8-6zm0 2.5l5 3.75V19h-2v-5H9v5H7V9.25l5-3.75z"/>',
                'icon' => 'fa-mosque',
                'sort_order' => 8,
            ],
            [
                'key' => 'Budaya',
                'name' => 'Budaya',
                'color' => '#8E24AA',
                'svg_path' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>',
                'icon' => 'fa-landmark',
                'sort_order' => 9,
            ],
            [
                'key' => 'Infrastruktur',
                'name' => 'Infrastruktur',
                'color' => '#FF9800',
                'svg_path' => '<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM7 13h2v2H7v-2zm0 4h2v2H7v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2z"/>',
                'icon' => 'fa-wrench',
                'sort_order' => 10,
            ],
            [
                'key' => 'Ruang Terbuka',
                'name' => 'Ruang Terbuka',
                'color' => '#66BB6A',
                'svg_path' => '<path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20c4 0 6-2 6-2 0 1.1.9 2 2 2h6V8h-5z"/>',
                'icon' => 'fa-tree',
                'sort_order' => 11,
            ],
            [
                'key' => 'Umum',
                'name' => 'Umum',
                'color' => '#757575',
                'svg_path' => '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>',
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
