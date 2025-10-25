<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing packages
        Package::query()->delete();

        // Create Silver package
        Package::factory()->silver()->create();

        // Create Gold package
        Package::factory()->gold()->create();

        // Create Platinum package
        Package::factory()->platinum()->create();

        $this->command->info('✓ Created 3 subscription packages: Silver, Gold, Platinum');
    }
}
