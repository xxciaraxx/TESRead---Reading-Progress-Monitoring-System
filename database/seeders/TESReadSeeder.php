<?php

namespace Database\Seeders;

use App\Models\ReadingLevel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TESReadSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        User::firstOrCreate(
            ['email' => 'admin@tesread.edu.ph'],
            [
                'name'     => 'School Administrator',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
            ]
        );

        // Seed default reading levels
        $levels = [
            [
                'level_name'  => 'Non-Reader',
                'description' => 'The pupil cannot read at all, does not recognize letters or words, and requires immediate intensive reading intervention.',
            ],
            [
                'level_name'  => 'Frustration',
                'description' => 'The pupil struggles significantly with the text, making frequent errors and showing signs of frustration. Reading is below the independent level.',
            ],
            [
                'level_name'  => 'Instructional',
                'description' => 'The pupil can read the material with teacher guidance and support. This is the optimal level for classroom instruction.',
            ],
            [
                'level_name'  => 'Independent',
                'description' => 'The pupil can read the material fluently and with full comprehension without teacher assistance.',
            ],
        ];

        foreach ($levels as $level) {
            ReadingLevel::firstOrCreate(['level_name' => $level['level_name']], $level);
        }

        $this->command->info('✅ TESRead seeder completed.');
        $this->command->info('   Admin email:    admin@tesread.edu.ph');
        $this->command->info('   Admin password: Admin@1234');
    }
}
