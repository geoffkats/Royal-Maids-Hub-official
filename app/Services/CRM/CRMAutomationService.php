<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CRMAutomationService
{
    /**
     * Process all automation rules
     */
    public function processAutomationRules()
    {
        Log::info('Starting CRM automation processing...');

        $processed = 0;

        // Auto-convert high-scoring leads
        if ($this->isSettingEnabled('auto_lead_conversion')) {
            $processed += $this->autoConvertHighScoringLeads();
        }

        // Auto-progress opportunity stages
        if ($this->isSettingEnabled('auto_opportunity_stage_progression')) {
            $processed += $this->autoProgressOpportunityStages();
        }

        // Auto-create follow-up activities
        if ($this->isSettingEnabled('auto_activity_creation')) {
            $processed += $this->autoCreateFollowUpActivities();
        }

        // Auto-assign leads
        if ($this->isSettingEnabled('auto_assign_leads')) {
            $processed += $this->autoAssignLeads();
        }

        Log::info("CRM automation processing completed. Processed {$processed} items.");
        return $processed;
    }

    /**
     * Auto-convert high-scoring leads to opportunities
     */
    public function autoConvertHighScoringLeads()
    {
        $threshold = $this->getSettingValue('lead_auto_qualify_score', 80);
        
        $leads = Lead::where('status', 'working')
            ->where('score', '>=', $threshold)
            ->whereDoesntHave('opportunities')
            ->get();

        $converted = 0;
        foreach ($leads as $lead) {
            try {
                $this->convertLeadToOpportunity($lead);
                $converted++;
                
                Log::info("Auto-converted lead {$lead->id} to opportunity");
            } catch (\Exception $e) {
                Log::error("Failed to auto-convert lead {$lead->id}: " . $e->getMessage());
            }
        }

        return $converted;
    }

    /**
     * Auto-progress opportunity stages based on activity
     */
    public function autoProgressOpportunityStages()
    {
        $opportunities = Opportunity::with(['stage', 'activities'])
            ->whereNull('won_at')
            ->whereNull('lost_at')
            ->get();

        $progressed = 0;
        foreach ($opportunities as $opportunity) {
            try {
                if ($this->shouldProgressOpportunity($opportunity)) {
                    $this->progressOpportunityStage($opportunity);
                    $progressed++;
                    
                    Log::info("Auto-progressed opportunity {$opportunity->id} to next stage");
                }
            } catch (\Exception $e) {
                Log::error("Failed to auto-progress opportunity {$opportunity->id}: " . $e->getMessage());
            }
        }

        return $progressed;
    }

    /**
     * Auto-create follow-up activities for leads and opportunities
     */
    public function autoCreateFollowUpActivities()
    {
        $followUpDays = $this->getSettingValue('lead_follow_up_days', 7);
        $cutoffDate = now()->subDays($followUpDays);

        // Find leads without recent activities
        $leads = Lead::where('status', 'working')
            ->where('updated_at', '<', $cutoffDate)
            ->whereDoesntHave('activities', function ($query) use ($cutoffDate) {
                $query->where('created_at', '>', $cutoffDate);
            })
            ->get();

        $created = 0;
        foreach ($leads as $lead) {
            try {
                $this->createFollowUpActivity($lead);
                $created++;
                
                Log::info("Auto-created follow-up activity for lead {$lead->id}");
            } catch (\Exception $e) {
                Log::error("Failed to create follow-up activity for lead {$lead->id}: " . $e->getMessage());
            }
        }

        return $created;
    }

    /**
     * Auto-assign leads to available users
     */
    public function autoAssignLeads()
    {
        $unassignedLeads = Lead::whereNull('owner_id')
            ->where('status', 'new')
            ->get();

        $assigned = 0;
        foreach ($unassignedLeads as $lead) {
            try {
                $user = $this->getNextAvailableUser();
                if ($user) {
                    $lead->update(['owner_id' => $user->id]);
                    $assigned++;
                    
                    Log::info("Auto-assigned lead {$lead->id} to user {$user->id}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to auto-assign lead {$lead->id}: " . $e->getMessage());
            }
        }

        return $assigned;
    }

    /**
     * Convert a lead to opportunity
     */
    private function convertLeadToOpportunity(Lead $lead)
    {
        $defaultStage = DB::table('crm_stages')
            ->where('pipeline_id', function ($query) {
                $query->select('id')
                    ->from('crm_pipelines')
                    ->where('is_default', true);
            })
            ->orderBy('position')
            ->first();

        $opportunity = Opportunity::create([
            'title' => $lead->full_name . ' - Opportunity',
            'description' => 'Auto-converted from lead: ' . $lead->full_name,
            'amount' => 0,
            'probability' => $defaultStage->probability_default ?? 50,
            'close_date' => now()->addDays(30),
            'lead_id' => $lead->id,
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id() ?? 1,
            'stage_id' => $defaultStage->id ?? null,
        ]);

        // Update lead status
        $lead->update(['status' => 'converted']);

        return $opportunity;
    }

    /**
     * Check if opportunity should progress to next stage
     */
    private function shouldProgressOpportunity(Opportunity $opportunity)
    {
        // Check if opportunity has recent completed activities
        $recentActivities = $opportunity->activities()
            ->where('status', 'completed')
            ->where('created_at', '>', now()->subDays(7))
            ->count();

        // Check if opportunity has been in current stage for too long
        $stageDuration = $opportunity->updated_at->diffInDays(now());
        
        return $recentActivities >= 2 && $stageDuration >= 14;
    }

    /**
     * Progress opportunity to next stage
     */
    private function progressOpportunityStage(Opportunity $opportunity)
    {
        $currentStage = $opportunity->stage;
        if (!$currentStage) {
            return;
        }

        $nextStage = DB::table('crm_stages')
            ->where('pipeline_id', $currentStage->pipeline_id)
            ->where('position', '>', $currentStage->position)
            ->orderBy('position')
            ->first();

        if ($nextStage) {
            $opportunity->update([
                'stage_id' => $nextStage->id,
                'probability' => $nextStage->probability_default ?? $opportunity->probability,
            ]);
        }
    }

    /**
     * Create follow-up activity for a lead
     */
    private function createFollowUpActivity(Lead $lead)
    {
        Activity::create([
            'lead_id' => $lead->id,
            'type' => 'call',
            'subject' => 'Follow-up call for ' . $lead->full_name,
            'description' => 'Automated follow-up activity for lead without recent contact',
            'due_date' => now()->addDays(1),
            'priority' => 'medium',
            'status' => 'pending',
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id() ?? 1,
        ]);
    }

    /**
     * Get next available user for lead assignment
     */
    private function getNextAvailableUser()
    {
        // Simple round-robin assignment
        $users = User::whereIn('role', ['admin', 'trainer'])
            ->orderBy('id')
            ->get();

        if ($users->isEmpty()) {
            return null;
        }

        // Find user with least assigned leads
        $userLeadCounts = [];
        foreach ($users as $user) {
            $userLeadCounts[$user->id] = Lead::where('owner_id', $user->id)
                ->where('status', '!=', 'converted')
                ->count();
        }

        $minCount = min($userLeadCounts);
        $availableUsers = array_keys($userLeadCounts, $minCount);
        
        return User::find($availableUsers[0]);
    }

    /**
     * Check if a setting is enabled
     */
    public function isSettingEnabled($key)
    {
        $value = DB::table('crm_settings')
            ->where('key', $key)
            ->value('value');
            
        return $value === '1' || $value === 'true';
    }

    /**
     * Get setting value
     */
    public function getSettingValue($key, $default = null)
    {
        $value = DB::table('crm_settings')
            ->where('key', $key)
            ->value('value');
            
        return $value !== null ? $value : $default;
    }
}
