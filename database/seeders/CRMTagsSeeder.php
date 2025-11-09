<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CRM\Lead;
use App\Models\CRM\Tag;

class CRMTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            echo "🏷️ Starting CRM Tags seeding...\n";

            $this->attachTagsToLeads();
            echo "✓ Tags attached to leads successfully.\n";

            echo "\n🎉 CRM Tags seeding completed successfully!\n";

        } catch (\Exception $e) {
            echo "❌ Error during CRM Tags seeding: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }

    private function attachTagsToLeads(): void
    {
        $leads = Lead::all();
        $tags = Tag::all();
        
        if (!$leads->count() || !$tags->count()) {
            return;
        }

        foreach ($leads as $lead) {
            // Attach 1-3 random tags to each lead
            $randomTags = $tags->random(rand(1, 3));
            $lead->tags()->sync($randomTags->pluck('id'));
        }
    }
}
