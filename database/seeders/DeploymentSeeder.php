<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Maid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing deployed maids and clients
        $deployedMaids = Maid::where('status', 'deployed')->get();
        $clients = Client::all();

        if ($deployedMaids->isEmpty() || $clients->isEmpty()) {
            $this->command->warn('⚠️  No deployed maids or clients found. Creating sample deployments anyway...');
        }

        // Create active deployments for deployed maids
        $activeCount = 0;
        foreach ($deployedMaids->take(10) as $maid) {
            Deployment::factory()
                ->active()
                ->create([
                    'maid_id' => $maid->id,
                    'client_id' => $clients->random()->id ?? null,
                ]);
            $activeCount++;
        }

        // Create completed deployments
        $completedCount = Deployment::factory()
            ->count(8)
            ->completed()
            ->create([
                'maid_id' => fn() => Maid::inRandomOrder()->first()?->id ?? Maid::factory(),
                'client_id' => fn() => $clients->random()?->id ?? Client::factory(),
            ])
            ->count();

        // Create terminated deployments
        $terminatedCount = Deployment::factory()
            ->count(3)
            ->terminated()
            ->create([
                'maid_id' => fn() => Maid::inRandomOrder()->first()?->id ?? Maid::factory(),
                'client_id' => fn() => $clients->random()?->id ?? Client::factory(),
            ])
            ->count();

        $this->command->info("✓ Created {$activeCount} active deployments");
        $this->command->info("✓ Created {$completedCount} completed deployments");
        $this->command->info("✓ Created {$terminatedCount} terminated deployments");
        $this->command->info("✓ Total: " . ($activeCount + $completedCount + $terminatedCount) . " deployments seeded");
    }
}
