<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Maid;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users for development/testing
        User::query()->updateOrCreate(
            ['email' => 'client@example.com'],
            array_merge(
                User::factory()->withoutTwoFactor()->make([
                    'name' => 'Client User',
                    'role' => \App\Models\User::ROLE_CLIENT,
                ])->toArray(),
                ['email' => 'client@example.com']
            )
        );

        User::query()->updateOrCreate(
            ['email' => 'trainer@example.com'],
            array_merge(
                User::factory()->withoutTwoFactor()->make([
                    'name' => 'Trainer User',
                    'role' => \App\Models\User::ROLE_TRAINER,
                ])->toArray(),
                ['email' => 'trainer@example.com']
            )
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            array_merge(
                User::factory()->withoutTwoFactor()->make([
                    'name' => 'Admin User',
                    'role' => \App\Models\User::ROLE_ADMIN,
                ])->toArray(),
                ['email' => 'admin@example.com']
            )
        );

        // Seed some maids for development demos
        Maid::factory()->count(40)->create();
        // Ensure a few specific records to help with predictable tests/demos
        Maid::factory()->create([
            'first_name' => 'Alice',
            'last_name' => 'Available',
            'status' => 'available',
            'role' => 'nanny',
        ]);
        Maid::factory()->create([
            'first_name' => 'Diana',
            'last_name' => 'Deployed',
            'status' => 'deployed',
            'role' => 'housekeeper',
        ]);
    }
}
