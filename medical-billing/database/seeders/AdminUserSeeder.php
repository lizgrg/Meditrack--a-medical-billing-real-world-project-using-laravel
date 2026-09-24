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
            ['email' => 'admin@medicalbilling.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@12345'), // change this after first login
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
