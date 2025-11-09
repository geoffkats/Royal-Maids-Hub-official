<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CRM\Source;
use App\Models\CRM\Pipeline;
use App\Models\CRM\Stage;
use App\Models\CRM\Tag;
use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Activity;
use App\Models\Client;
use App\Models\Package;

class CRMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            echo "🚀 Starting comprehensive CRM seeding...\n";

            // Create default sources
            $sources = $this->createSources();
            echo "✓ " . count($sources) . " Sources created successfully.\n";

            // Create default pipeline and stages
            $pipeline = $this->createPipeline();
            $stages = $this->createStages($pipeline);
            echo "✓ Pipeline and " . count($stages) . " Stages created successfully.\n";

            // Create default tags
            $tags = $this->createTags();
            echo "✓ " . count($tags) . " Tags created successfully.\n";

            // Create sample leads with realistic data
            $leads = $this->createSampleLeads($sources);
            echo "✓ " . count($leads) . " Sample leads created successfully.\n";

            // Create opportunities from some leads
            $opportunities = $this->createOpportunities($leads, $stages);
            echo "✓ " . count($opportunities) . " Opportunities created successfully.\n";

            // Create activities for leads and opportunities
            $activities = $this->createActivities($leads, $opportunities);
            echo "✓ " . count($activities) . " Activities created successfully.\n";

            // Create some converted leads (leads that became clients)
            $this->createConvertedLeads($leads);
            echo "✓ Converted leads created successfully.\n";

            echo "\n🎉 Comprehensive CRM seeding completed successfully!\n";
            echo "📊 Created:\n";
            echo "   - " . count($sources) . " Sources\n";
            echo "   - 1 Pipeline\n";
            echo "   - " . count($stages) . " Stages\n";
            echo "   - " . count($tags) . " Tags\n";
            echo "   - " . count($leads) . " Leads\n";
            echo "   - " . count($opportunities) . " Opportunities\n";
            echo "   - " . count($activities) . " Activities\n";

        } catch (\Exception $e) {
            echo "❌ Error during CRM seeding: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }

    private function createSources(): array
    {
            $sources = [
                ['name' => 'Website', 'active' => true],
                ['name' => 'Referral', 'active' => true],
                ['name' => 'Social Media', 'active' => true],
            ['name' => 'Google Ads', 'active' => true],
            ['name' => 'Facebook Ads', 'active' => true],
                ['name' => 'Cold Call', 'active' => true],
                ['name' => 'Email Campaign', 'active' => true],
                ['name' => 'Trade Show', 'active' => true],
            ['name' => 'Partner Referral', 'active' => true],
            ['name' => 'Direct Mail', 'active' => true],
                ['name' => 'Other', 'active' => true],
            ];

        $createdSources = [];
            foreach ($sources as $source) {
            $createdSources[] = Source::firstOrCreate(['name' => $source['name']], $source);
        }
        return $createdSources;
            }

    private function createPipeline(): Pipeline
    {
        return Pipeline::firstOrCreate(
                ['name' => 'Royal Maids Sales Pipeline'],
            [
                'name' => 'Royal Maids Sales Pipeline',
                'description' => 'Main sales pipeline for Royal Maids services',
                'is_default' => true,
                'is_active' => true
            ]
        );
    }

    private function createStages(Pipeline $pipeline): array
    {
            $stages = [
            [
                'name' => 'Lead',
                'position' => 1,
                'sla_first_response_hours' => 24,
                'sla_follow_up_hours' => 72,
                'is_closed' => false,
                'probability_default' => 10
            ],
            [
                'name' => 'Qualified',
                'position' => 2,
                'sla_first_response_hours' => 12,
                'sla_follow_up_hours' => 48,
                'is_closed' => false,
                'probability_default' => 25
            ],
            [
                'name' => 'Proposal',
                'position' => 3,
                'sla_first_response_hours' => 8,
                'sla_follow_up_hours' => 24,
                'is_closed' => false,
                'probability_default' => 50
            ],
            [
                'name' => 'Negotiation',
                'position' => 4,
                'sla_first_response_hours' => 4,
                'sla_follow_up_hours' => 12,
                'is_closed' => false,
                'probability_default' => 75
            ],
            [
                'name' => 'Closed Won',
                'position' => 5,
                'sla_first_response_hours' => null,
                'sla_follow_up_hours' => null,
                'is_closed' => true,
                'probability_default' => 100
            ],
            [
                'name' => 'Closed Lost',
                'position' => 6,
                'sla_first_response_hours' => null,
                'sla_follow_up_hours' => null,
                'is_closed' => true,
                'probability_default' => 0
            ],
        ];

        $createdStages = [];
            foreach ($stages as $stage) {
            $createdStages[] = Stage::firstOrCreate(
                    ['name' => $stage['name'], 'pipeline_id' => $pipeline->id],
                    array_merge($stage, ['pipeline_id' => $pipeline->id])
                );
            }
        return $createdStages;
    }

    private function createTags(): array
    {
            $tags = [
                ['name' => 'High Priority'],
                ['name' => 'VIP Client'],
                ['name' => 'Follow Up'],
                ['name' => 'Hot Lead'],
                ['name' => 'Cold Lead'],
                ['name' => 'Corporate'],
                ['name' => 'Residential'],
                ['name' => 'Long Term'],
                ['name' => 'Short Term'],
                ['name' => 'Urgent'],
            ['name' => 'Budget Conscious'],
            ['name' => 'Premium Service'],
            ];

        $createdTags = [];
            foreach ($tags as $tag) {
            $createdTags[] = Tag::firstOrCreate(['name' => $tag['name']], $tag);
        }
        return $createdTags;
    }

    private function createSampleLeads(array $sources): array
    {
        $adminUser = User::where('role', 'admin')->first();
        $trainerUser = User::where('role', 'trainer')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@royalmaids.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

                $sampleLeads = [
            // High-value corporate leads
                    [
                        'first_name' => 'John',
                        'last_name' => 'Smith',
                        'full_name' => 'John Smith',
                'email' => 'john.smith@techcorp.com',
                        'phone' => '+1-555-0123',
                'company' => 'TechCorp Solutions',
                        'job_title' => 'CEO',
                        'industry' => 'Technology',
                        'city' => 'New York',
                'address' => '123 Business St, New York, NY 10001',
                'source_id' => $sources[0]->id, // Website
                        'owner_id' => $adminUser->id,
                'status' => 'qualified',
                'score' => 95,
                'notes' => 'Interested in premium maid services for corporate office. Budget: $50,000/year. Decision maker.',
                    ],
                    [
                        'first_name' => 'Sarah',
                        'last_name' => 'Johnson',
                        'full_name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@family.com',
                        'phone' => '+1-555-0124',
                        'company' => 'Johnson Family',
                        'job_title' => 'Homemaker',
                        'industry' => 'Personal',
                        'city' => 'Los Angeles',
                'address' => '456 Home Ave, Los Angeles, CA 90210',
                'source_id' => $sources[1]->id, // Referral
                        'owner_id' => $adminUser->id,
                        'status' => 'working',
                'score' => 80,
                'notes' => 'Looking for regular housekeeping services. Referred by existing client. Very interested.',
                    ],
                    [
                        'first_name' => 'Michael',
                        'last_name' => 'Brown',
                        'full_name' => 'Michael Brown',
                'email' => 'michael.brown@browncorp.com',
                        'phone' => '+1-555-0125',
                        'company' => 'Brown Corporation',
                        'job_title' => 'Operations Manager',
                        'industry' => 'Corporate',
                        'city' => 'Chicago',
                'address' => '789 Corporate Blvd, Chicago, IL 60601',
                'source_id' => $sources[2]->id, // Social Media
                        'owner_id' => $adminUser->id,
                        'status' => 'qualified',
                'score' => 90,
                'notes' => 'Corporate cleaning services for multiple locations. Budget approved. Ready to proceed.',
            ],
            // New leads
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'full_name' => 'Emily Davis',
                'email' => 'emily.davis@email.com',
                'phone' => '+1-555-0126',
                'company' => 'Davis Residence',
                'job_title' => 'Marketing Manager',
                'industry' => 'Personal',
                'city' => 'Miami',
                'address' => '321 Ocean Dr, Miami, FL 33139',
                'source_id' => $sources[3]->id, // Google Ads
                'owner_id' => $adminUser->id,
                'status' => 'new',
                'score' => 65,
                'notes' => 'Found us through Google Ads. Interested in weekly cleaning service.',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'full_name' => 'David Wilson',
                'email' => 'david.wilson@wilsonlaw.com',
                'phone' => '+1-555-0127',
                'company' => 'Wilson Law Firm',
                'job_title' => 'Partner',
                'industry' => 'Legal',
                'city' => 'Boston',
                'address' => '654 Legal St, Boston, MA 02108',
                'source_id' => $sources[4]->id, // Facebook Ads
                'owner_id' => $adminUser->id,
                'status' => 'new',
                'score' => 70,
                'notes' => 'Law firm looking for professional cleaning services. High-end clientele.',
            ],
            // Working leads
            [
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'full_name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@anderson.com',
                'phone' => '+1-555-0128',
                'company' => 'Anderson Real Estate',
                'job_title' => 'Broker',
                'industry' => 'Real Estate',
                'city' => 'Seattle',
                'address' => '987 Real Estate Ave, Seattle, WA 98101',
                'source_id' => $sources[5]->id, // Cold Call
                'owner_id' => $adminUser->id,
                'status' => 'working',
                'score' => 75,
                'notes' => 'Real estate office needs regular cleaning. Multiple properties.',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Taylor',
                'full_name' => 'Robert Taylor',
                'email' => 'robert.taylor@taylor.com',
                'phone' => '+1-555-0129',
                'company' => 'Taylor Medical Center',
                'job_title' => 'Administrator',
                'industry' => 'Healthcare',
                'city' => 'Phoenix',
                'address' => '147 Medical Blvd, Phoenix, AZ 85001',
                'source_id' => $sources[6]->id, // Email Campaign
                'owner_id' => $adminUser->id,
                'status' => 'working',
                'score' => 85,
                'notes' => 'Medical center requires specialized cleaning services. Compliance important.',
            ],
            // Converted leads (for testing)
            [
                'first_name' => 'Jennifer',
                'last_name' => 'Martinez',
                'full_name' => 'Jennifer Martinez',
                'email' => 'jennifer.martinez@martinez.com',
                'phone' => '+1-555-0130',
                'company' => 'Martinez Family',
                'job_title' => 'Homemaker',
                'industry' => 'Personal',
                'city' => 'Denver',
                'address' => '258 Family St, Denver, CO 80206',
                'source_id' => $sources[1]->id, // Referral
                'owner_id' => $adminUser->id,
                'status' => 'converted',
                'score' => 100,
                'notes' => 'Successfully converted to client. Very satisfied with service.',
            ],
        ];

        $createdLeads = [];
                foreach ($sampleLeads as $leadData) {
            $createdLeads[] = Lead::create($leadData);
        }
        return $createdLeads;
    }

    private function createOpportunities(array $leads, array $stages): array
    {
        $packages = Package::all();
        $adminUser = User::where('role', 'admin')->first();
        
        $opportunities = [];
        
        // Create opportunities for qualified and working leads
        foreach ($leads as $lead) {
            if (in_array($lead->status, ['qualified', 'working'])) {
                $stage = $stages[array_rand($stages)];
                $package = $packages->random();
                
                $opportunity = Opportunity::create([
                    'lead_id' => $lead->id,
                    'stage_id' => $stage->id,
                    'title' => $lead->full_name . ' - ' . $package->name,
                    'description' => 'Opportunity created from lead: ' . $lead->full_name . '. ' . $lead->notes,
                    'amount' => rand(50000, 200000), // Random amount in UGX
                    'currency' => 'UGX',
                    'probability' => $stage->probability_default ?? 50,
                    'close_date' => now()->addDays(rand(7, 90)),
                    'assigned_to' => $lead->owner_id,
                    'created_by' => $adminUser->id,
                ]);
                
                $opportunities[] = $opportunity;
            }
        }
        
        return $opportunities;
    }

    private function createActivities(array $leads, array $opportunities): array
    {
        $adminUser = User::where('role', 'admin')->first();
        $activities = [];
        
        $activityTypes = ['call', 'email', 'meeting', 'note', 'task'];
        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'completed', 'cancelled'];
        
        // Create activities for leads
        foreach ($leads as $lead) {
            $activityCount = rand(2, 5);
            for ($i = 0; $i < $activityCount; $i++) {
                $activity = Activity::create([
                    'type' => $activityTypes[array_rand($activityTypes)],
                    'subject' => 'Follow up with ' . $lead->full_name,
                    'description' => 'Activity related to lead: ' . $lead->full_name . '. ' . $lead->notes,
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => now()->addDays(rand(1, 30)),
                    'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                    'related_type' => 'lead',
                    'related_id' => $lead->id,
                    'owner_id' => $lead->owner_id,
                    'assigned_to' => $lead->owner_id,
                    'created_by' => $adminUser->id,
                ]);
                $activities[] = $activity;
            }
        }
        
        // Create activities for opportunities
        foreach ($opportunities as $opportunity) {
            $activityCount = rand(1, 3);
            for ($i = 0; $i < $activityCount; $i++) {
                $activity = Activity::create([
                    'type' => $activityTypes[array_rand($activityTypes)],
                    'subject' => 'Follow up on opportunity: ' . $opportunity->title,
                    'description' => 'Activity related to opportunity: ' . $opportunity->title,
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => now()->addDays(rand(1, 30)),
                    'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                    'related_type' => 'opportunity',
                    'related_id' => $opportunity->id,
                    'owner_id' => $opportunity->assigned_to,
                    'assigned_to' => $opportunity->assigned_to,
                    'created_by' => $adminUser->id,
                ]);
                $activities[] = $activity;
            }
        }
        
        return $activities;
    }

    private function createConvertedLeads(array $leads): void
    {
        // Find a lead that's already converted and create a client for it
        $convertedLead = collect($leads)->firstWhere('status', 'converted');
        
        if ($convertedLead) {
            // Create a user for the client
            $user = User::firstOrCreate(
                ['email' => $convertedLead->email],
                [
                    'name' => $convertedLead->full_name,
                    'email' => $convertedLead->email,
                    'password' => bcrypt('password'),
                    'role' => 'client',
                    'email_verified_at' => now(),
                ]
            );
            
            // Create the client
            $client = Client::create([
                'user_id' => $user->id,
                'contact_person' => $convertedLead->full_name,
                'phone' => $convertedLead->phone,
                'company_name' => $convertedLead->company,
                'address' => $convertedLead->address,
                'city' => $convertedLead->city,
                'district' => 'Downtown', // Default district
                'notes' => 'Converted from lead: ' . $convertedLead->full_name,
            ]);
            
            // Update the lead with client_id
            $convertedLead->update(['client_id' => $client->id]);
        }
    }
}
