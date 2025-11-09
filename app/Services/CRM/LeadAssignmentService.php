<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeadAssignmentService
{
    /**
     * Assign a lead to a user using round-robin strategy
     */
    public function assignLeadRoundRobin(Lead $lead): ?User
    {
        // Get all eligible sales users
        $salesUsers = User::where('role', 'sales')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($salesUsers->isEmpty()) {
            return null;
        }

        // Get the last assigned user ID from the most recent lead
        $lastAssignedLead = Lead::whereIn('owner_id', $salesUsers->pluck('id'))
            ->latest('created_at')
            ->first();

        if (!$lastAssignedLead || !$lastAssignedLead->owner_id) {
            // No previous assignments, assign to first user
            $assignedUser = $salesUsers->first();
        } else {
            // Find the next user in round-robin
            $currentIndex = $salesUsers->search(function ($user) use ($lastAssignedLead) {
                return $user->id === $lastAssignedLead->owner_id;
            });

            if ($currentIndex === false || $currentIndex === $salesUsers->count() - 1) {
                // Current user not found or is last, wrap to first
                $assignedUser = $salesUsers->first();
            } else {
                // Assign to next user
                $assignedUser = $salesUsers[$currentIndex + 1];
            }
        }

        // Assign the lead
        $lead->update(['owner_id' => $assignedUser->id]);

        // Log the assignment activity
        $lead->activities()->create([
            'type' => 'note',
            'subject' => 'Lead Auto-Assigned',
            'description' => "Lead automatically assigned to {$assignedUser->name} via round-robin",
            'status' => 'completed',
            'completed_at' => now(),
            'related_type' => 'lead',
            'related_id' => $lead->id,
            'assigned_to' => $assignedUser->id,
            'owner_id' => $assignedUser->id,
            'created_by' => $assignedUser->id,
        ]);

        return $assignedUser;
    }

    /**
     * Assign multiple leads using round-robin
     */
    public function assignMultipleLeadsRoundRobin(array $leadIds): array
    {
        $results = [];

        foreach ($leadIds as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead && !$lead->owner_id) {
                $assignedUser = $this->assignLeadRoundRobin($lead);
                $results[$leadId] = [
                    'success' => true,
                    'assigned_to' => $assignedUser?->name,
                    'assigned_to_id' => $assignedUser?->id,
                ];
            } else {
                $results[$leadId] = [
                    'success' => false,
                    'reason' => $lead ? 'Already assigned' : 'Lead not found',
                ];
            }
        }

        return $results;
    }

    /**
     * Assign lead based on workload (least busy sales person)
     */
    public function assignLeadByWorkload(Lead $lead): ?User
    {
        // Get sales users with their current lead counts
        $salesUsers = User::where('role', 'sales')
            ->where('is_active', true)
            ->withCount(['ownedLeads' => function ($query) {
                $query->whereIn('status', ['new', 'working', 'qualified']);
            }])
            ->orderBy('owned_leads_count', 'asc')
            ->get();

        if ($salesUsers->isEmpty()) {
            return null;
        }

        // Assign to user with least leads
        $assignedUser = $salesUsers->first();

        $lead->update(['owner_id' => $assignedUser->id]);

        // Log the assignment
        $lead->activities()->create([
            'type' => 'note',
            'subject' => 'Lead Assigned by Workload',
            'description' => "Lead assigned to {$assignedUser->name} (least busy: {$assignedUser->owned_leads_count} active leads)",
            'status' => 'completed',
            'completed_at' => now(),
            'related_type' => 'lead',
            'related_id' => $lead->id,
            'assigned_to' => $assignedUser->id,
            'owner_id' => $assignedUser->id,
            'created_by' => $assignedUser->id,
        ]);

        return $assignedUser;
    }

    /**
     * Assign lead based on source expertise
     */
    public function assignLeadBySource(Lead $lead): ?User
    {
        // TODO: Implement source-based routing logic
        // For now, fallback to round-robin
        return $this->assignLeadRoundRobin($lead);
    }

    /**
     * Reassign all leads from one user to another
     */
    public function reassignLeads(int $fromUserId, int $toUserId): int
    {
        return DB::transaction(function () use ($fromUserId, $toUserId) {
            $toUser = User::findOrFail($toUserId);

            $leads = Lead::where('owner_id', $fromUserId)
                ->whereIn('status', ['new', 'working', 'qualified'])
                ->get();

            foreach ($leads as $lead) {
                $lead->update(['owner_id' => $toUserId]);

                // Log reassignment
                $lead->activities()->create([
                    'type' => 'note',
                    'subject' => 'Lead Reassigned',
                    'description' => "Lead reassigned to {$toUser->name}",
                    'status' => 'completed',
                    'completed_at' => now(),
                    'related_type' => 'lead',
                    'related_id' => $lead->id,
                    'assigned_to' => $toUserId,
                    'owner_id' => $toUserId,
                    'created_by' => auth()->id(),
                ]);
            }

            return $leads->count();
        });
    }
}
