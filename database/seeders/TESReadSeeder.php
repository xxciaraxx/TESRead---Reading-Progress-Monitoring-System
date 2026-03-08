<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Admin
        User::updateOrCreate(
            ['email' => 'admin@tesread.edu.ph'],
            [
                'name'           => 'TESRead Administrator',
                'password'       => Hash::make('Admin@1234'),
                'role'           => 'admin',
                'account_status' => 'Approved',
            ]
        );
    }
}