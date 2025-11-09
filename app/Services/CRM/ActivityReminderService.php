<?php

namespace App\Services\CRM;

use App\Models\CRM\Activity;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Lead;
use Carbon\Carbon;

class ActivityReminderService
{
    /**
     * Create first response activity based on stage SLA
     */
    public function createFirstResponseActivity(Opportunity $opportunity): ?Activity
    {
        $stage = $opportunity->stage;
        
        if (!$stage || !$stage->sla_first_response_hours) {
            return null;
        }

        // Check if first response activity already exists
        $existingActivity = $opportunity->activities()
            ->where('type', 'task')
            ->where('subject', 'LIKE', '%First Response%')
            ->where('status', 'pending')
            ->first();

        if ($existingActivity) {
            return $existingActivity;
        }

        // Create first response activity
        return $opportunity->activities()->create([
            'type' => 'task',
            'subject' => 'First Response Required',
            'description' => "Opportunity entered {$stage->name} stage. First response required within {$stage->sla_first_response_hours} hours.",
            'due_date' => now()->addHours($stage->sla_first_response_hours),
            'priority' => 'high',
            'status' => 'pending',
            'related_type' => 'opportunity',
            'related_id' => $opportunity->id,
            'assigned_to' => $opportunity->assigned_to,
            'owner_id' => $opportunity->assigned_to,
            'created_by' => $opportunity->assigned_to,
        ]);
    }

    /**
     * Create follow-up activity based on stage SLA
     */
    public function createFollowUpActivity(Opportunity $opportunity, ?string $context = null): ?Activity
    {
        $stage = $opportunity->stage;
        
        if (!$stage || !$stage->sla_follow_up_hours) {
            return null;
        }

        return $opportunity->activities()->create([
            'type' => 'task',
            'subject' => 'Follow-up Required',
            'description' => $context ?? "Follow-up required for opportunity in {$stage->name} stage within {$stage->sla_follow_up_hours} hours.",
            'due_date' => now()->addHours($stage->sla_follow_up_hours),
            'priority' => 'medium',
            'status' => 'pending',
            'related_type' => 'opportunity',
            'related_id' => $opportunity->id,
            'assigned_to' => $opportunity->assigned_to,
            'owner_id' => $opportunity->assigned_to,
            'created_by' => $opportunity->assigned_to,
        ]);
    }

    /**
     * Create follow-up activity for a lead
     */
    public function createLeadFollowUpActivity(Lead $lead, int $hoursUntilDue = 24, string $reason = 'Regular follow-up'): Activity
    {
        return $lead->activities()->create([
            'type' => 'task',
            'subject' => 'Follow-up with Lead',
            'description' => $reason,
            'due_date' => now()->addHours($hoursUntilDue),
            'priority' => 'medium',
            'status' => 'pending',
            'related_type' => 'lead',
            'related_id' => $lead->id,
            'assigned_to' => $lead->owner_id,
            'owner_id' => $lead->owner_id,
            'created_by' => $lead->owner_id,
        ]);
    }

    /**
     * Get all overdue activities
     */
    public function getOverdueActivities(): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['assignedTo', 'related'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Get activities due soon (within next 24 hours)
     */
    public function getActivitiesDueSoon(int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addHours($hours)])
            ->with(['assignedTo', 'related'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Get overdue activities for a specific user
     */
    public function getOverdueActivitiesForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('status', 'pending')
            ->where('assigned_to', $userId)
            ->where('due_date', '<', now())
            ->with(['related'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Get activities due soon for a specific user
     */
    public function getActivitiesDueSoonForUser(int $userId, int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('status', 'pending')
            ->where('assigned_to', $userId)
            ->whereBetween('due_date', [now(), now()->addHours($hours)])
            ->with(['related'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Check if opportunity needs SLA activities
     */
    public function ensureOpportunitySLAActivities(Opportunity $opportunity): array
    {
        $created = [];

        // Create first response activity if needed
        $firstResponse = $this->createFirstResponseActivity($opportunity);
        if ($firstResponse) {
            $created[] = $firstResponse;
        }

        return $created;
    }

    /**
     * Bulk create follow-up activities for stale leads
     */
    public function createFollowUpsForStaleLeads(int $daysWithoutContact = 7): int
    {
        $staleLeads = Lead::whereIn('status', ['new', 'working', 'qualified'])
            ->where(function($query) use ($daysWithoutContact) {
                $query->where('last_contacted_at', '<', now()->subDays($daysWithoutContact))
                    ->orWhereNull('last_contacted_at');
            })
            ->get();

        $count = 0;
        foreach ($staleLeads as $lead) {
            // Check if follow-up already exists
            $hasOpenActivity = $lead->activities()
                ->where('status', 'pending')
                ->where('due_date', '>', now())
                ->exists();

            if (!$hasOpenActivity) {
                $this->createLeadFollowUpActivity(
                    $lead, 
                    24, 
                    "No contact in {$daysWithoutContact} days. Follow-up required."
                );
                $count++;
            }
        }

        return $count;
    }
}
