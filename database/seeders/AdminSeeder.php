<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bladeroom.com'],
            [
                'name' => 'The Blade Room Admin',
                'role' => 'admin',
                'password' => Hash::make('BladeRoom2026!'),
                'email_verified_at' => now(),
            ],
        );
    }
}
