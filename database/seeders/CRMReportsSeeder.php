<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Activity;
use App\Models\CRM\Source;
use App\Models\CRM\Stage;
use App\Models\Package;
use Carbon\Carbon;

class CRMReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            echo "📊 Starting CRM Reports data seeding...\n";

            // Create historical data for better reports
            $this->createHistoricalLeads();
            echo "✓ Historical leads created.\n";

            $this->createHistoricalOpportunities();
            echo "✓ Historical opportunities created.\n";

            $this->createHistoricalActivities();
            echo "✓ Historical activities created.\n";

            $this->createWonLostOpportunities();
            echo "✓ Won/Lost opportunities created.\n";

            echo "\n🎉 CRM Reports data seeding completed successfully!\n";

        } catch (\Exception $e) {
            echo "❌ Error during CRM Reports seeding: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }

    private function createHistoricalLeads(): void
    {
        $sources = Source::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$sources->count() || !$adminUser) {
            return;
        }

        $statuses = ['new', 'working', 'qualified', 'converted', 'disqualified'];
        $industries = ['Technology', 'Healthcare', 'Legal', 'Real Estate', 'Corporate', 'Personal', 'Education', 'Finance'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Miami', 'Boston', 'Seattle', 'Phoenix', 'Denver'];

        // Create leads from the past 6 months
        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(0, 30));
            $status = $statuses[array_rand($statuses)];
            
            Lead::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'full_name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'company' => fake()->company(),
                'job_title' => fake()->jobTitle(),
                'industry' => $industries[array_rand($industries)],
                'city' => $cities[array_rand($cities)],
                'address' => fake()->address(),
                'source_id' => $sources->random()->id,
                'owner_id' => $adminUser->id,
                'status' => $status,
                'score' => rand(20, 100),
                'notes' => fake()->paragraph(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    private function createHistoricalOpportunities(): void
    {
        $leads = Lead::whereIn('status', ['qualified', 'working'])->get();
        $stages = Stage::all();
        $packages = Package::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$leads->count() || !$stages->count() || !$packages->count() || !$adminUser) {
            return;
        }

        // Create opportunities for existing leads
        foreach ($leads as $lead) {
            $createdAt = Carbon::now()->subDays(rand(1, 60));
            $stage = $stages->random();
            
            Opportunity::create([
                'lead_id' => $lead->id,
                'stage_id' => $stage->id,
                'title' => $lead->full_name . ' - ' . $packages->random()->name,
                'description' => 'Opportunity for ' . $lead->full_name . '. ' . $lead->notes,
                'amount' => rand(30000, 500000), // UGX amounts
                'currency' => 'UGX',
                'probability' => $stage->probability_default ?? 50,
                'close_date' => Carbon::now()->addDays(rand(7, 120)),
                'assigned_to' => $lead->owner_id,
                'created_by' => $adminUser->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    private function createHistoricalActivities(): void
    {
        $leads = Lead::all();
        $opportunities = Opportunity::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            return;
        }

        $activityTypes = ['call', 'email', 'meeting', 'note', 'task'];
        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'completed', 'cancelled'];

        // Create activities for leads
        foreach ($leads as $lead) {
            $activityCount = rand(1, 4);
            for ($i = 0; $i < $activityCount; $i++) {
                $createdAt = Carbon::now()->subDays(rand(1, 90));
                $completedAt = rand(0, 1) ? $createdAt->copy()->addDays(rand(1, 7)) : null;
                
                Activity::create([
                    'type' => $activityTypes[array_rand($activityTypes)],
                    'subject' => 'Activity for ' . $lead->full_name,
                    'description' => 'Activity related to lead: ' . $lead->full_name,
                    'status' => $completedAt ? 'completed' : $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => $createdAt->copy()->addDays(rand(1, 14)),
                    'completed_at' => $completedAt,
                    'related_type' => 'lead',
                    'related_id' => $lead->id,
                    'owner_id' => $lead->owner_id,
                    'assigned_to' => $lead->owner_id,
                    'created_by' => $adminUser->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // Create activities for opportunities
        foreach ($opportunities as $opportunity) {
            $activityCount = rand(1, 3);
            for ($i = 0; $i < $activityCount; $i++) {
                $createdAt = Carbon::now()->subDays(rand(1, 60));
                $completedAt = rand(0, 1) ? $createdAt->copy()->addDays(rand(1, 5)) : null;
                
                Activity::create([
                    'type' => $activityTypes[array_rand($activityTypes)],
                    'subject' => 'Activity for opportunity: ' . $opportunity->title,
                    'description' => 'Activity related to opportunity: ' . $opportunity->title,
                    'status' => $completedAt ? 'completed' : $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => $createdAt->copy()->addDays(rand(1, 10)),
                    'completed_at' => $completedAt,
                    'related_type' => 'opportunity',
                    'related_id' => $opportunity->id,
                    'owner_id' => $opportunity->assigned_to,
                    'assigned_to' => $opportunity->assigned_to,
                    'created_by' => $adminUser->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }

    private function createWonLostOpportunities(): void
    {
        $opportunities = Opportunity::whereNull('won_at')->whereNull('lost_at')->get();
        $wonStage = Stage::where('name', 'Closed Won')->first();
        $lostStage = Stage::where('name', 'Closed Lost')->first();
        
        if (!$wonStage || !$lostStage) {
            return;
        }

        // Mark some opportunities as won
        $wonOpportunities = $opportunities->random(min(5, $opportunities->count()));
        foreach ($wonOpportunities as $opportunity) {
            $wonAt = Carbon::now()->subDays(rand(1, 30));
            $opportunity->update([
                'stage_id' => $wonStage->id,
                'won_at' => $wonAt,
                'updated_at' => $wonAt,
            ]);
        }

        // Mark some opportunities as lost
        $lostOpportunities = $opportunities->whereNotIn('id', $wonOpportunities->pluck('id'))->random(min(3, $opportunities->count()));
        foreach ($lostOpportunities as $opportunity) {
            $lostAt = Carbon::now()->subDays(rand(1, 45));
            $opportunity->update([
                'stage_id' => $lostStage->id,
                'lost_at' => $lostAt,
                'loss_reason' => fake()->randomElement(['Budget', 'Timing', 'Competitor', 'No Decision', 'Other']),
                'loss_notes' => fake()->sentence(),
                'updated_at' => $lostAt,
            ]);
        }
    }
}
