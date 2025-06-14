<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'satriobudyo.id@gmail.com'],
            [
                'name' => 'Satrio Budyo',
                'niss' => 'SS-00-020',
                'password' => Hash::make('2025selalusukses_11M#'),
            ]
        );

        $admin->assignRole('admin');

        // Buat profil admin jika belum ada
        UserProfile::firstOrCreate([
            'user_id' => $admin->id
        ], [
            'status' => 'aktif'
        ]);
    }
}

