<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bilebante.id'],
            [
                'name'     => 'Admin Desa Bilebante',
                'email'    => 'admin@bilebante.id',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
