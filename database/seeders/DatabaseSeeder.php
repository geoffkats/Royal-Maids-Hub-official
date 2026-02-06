<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Maid;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use App\Models\Evaluation;
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
            [
                'name' => 'Client User',
                'email' => 'client@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_CLIENT,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Trainer User',
                'email' => 'trainer@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_TRAINER,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
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

        // Seed clients for development
        Client::factory()->count(25)->create();
        // Create a few specific clients for testing
        Client::factory()->active()->create([
            'contact_person' => 'John Premium',
            'subscription_tier' => 'premium',
        ]);
        Client::factory()->pending()->create([
            'contact_person' => 'Jane Pending',
        ]);

        // Seed bookings for development
        $clients = Client::all();
        $maids = Maid::all();

        if ($clients->isNotEmpty() && $maids->isNotEmpty()) {
            // Create 50 bookings with random clients and maids
            for ($i = 0; $i < 50; $i++) {
                Booking::factory()->create([
                    'client_id' => $clients->random()->id,
                    'maid_id' => $maids->random()->id,
                ]);
            }

            // Update client booking counters
            foreach ($clients as $client) {
                $totalBookings = $client->bookings()->count();
                $activeBookings = $client->bookings()->whereIn('status', ['confirmed', 'active'])->count();
                
                $client->update([
                    'total_bookings' => $totalBookings,
                    'active_bookings' => $activeBookings,
                ]);
            }
        }

        // Seed trainers for development
        if (Trainer::count() === 0) {
            Trainer::factory()->count(10)->create();
        }

        // Seed training programs
        $trainers = Trainer::all();
        $maidsInTraining = Maid::where('status', 'in-training')->get();

        if ($trainers->isNotEmpty() && $maidsInTraining->isNotEmpty()) {
            // Create 30 training programs with existing trainers and maids
            for ($i = 0; $i < 30; $i++) {
                TrainingProgram::factory()->create([
                    'trainer_id' => $trainers->random()->id,
                    'maid_id' => $maidsInTraining->random()->id,
                ]);
            }
        }

        // Seed evaluations for completed programs
        $completedPrograms = TrainingProgram::where('status', 'completed')->get();
        
        if ($completedPrograms->isNotEmpty()) {
            foreach ($completedPrograms as $program) {
                // Create 1-2 evaluations per completed program
                $evalCount = rand(1, 2);
                for ($i = 0; $i < $evalCount; $i++) {
                    Evaluation::factory()->create([
                        'trainer_id' => $program->trainer_id,
                        'maid_id' => $program->maid_id,
                        'program_id' => $program->id,
                        'evaluation_date' => $program->end_date ?? now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Create some standalone evaluations with random distribution of ratings
        if ($trainers->isNotEmpty() && $maids->isNotEmpty()) {
            Evaluation::factory()->excellent()->count(5)->create([
                'trainer_id' => $trainers->random()->id,
                'maid_id' => $maids->random()->id,
            ]);
            
            Evaluation::factory()->average()->count(8)->create([
                'trainer_id' => $trainers->random()->id,
                'maid_id' => $maids->random()->id,
            ]);
            
            Evaluation::factory()->needsImprovement()->count(3)->create([
                'trainer_id' => $trainers->random()->id,
                'maid_id' => $maids->random()->id,
            ]);
        }

        // Seed ticket SLA rules for tier-based ticketing system
        $this->call(\Database\Seeders\TicketSlaRulesSeeder::class);
        
        // Seed tickets with various scenarios (tier-based, on-behalf, etc.)
        $this->call(\Database\Seeders\TicketSeeder::class);
        
        // Seed CRM data (sources, pipelines, stages, tags, sample leads)
        $this->call(\Database\Seeders\CRMSeeder::class);
        
        // Seed CRM reports data (historical data for better analytics)
        $this->call(\Database\Seeders\CRMReportsSeeder::class);
        
        // Seed CRM tags and relationships
        $this->call(\Database\Seeders\CRMTagsSeeder::class);
        
        // Seed CRM settings
        $this->call(\Database\Seeders\CRMSettingsSeeder::class);
        
        $this->call(\Database\Seeders\DeploymentSeeder::class);

        $this->call(\Database\Seeders\PackageSeeder::class);
        
        
    }
}
