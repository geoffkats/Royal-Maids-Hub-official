<?php

namespace App\Jobs\CRM;

use App\Models\CRM\Opportunity;
use App\Models\CRM\Activity;
use App\Models\CRM\OpportunityStageHistory;
use App\Models\User;
use App\Notifications\CRM\ActivityOverdueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CheckSLABreaches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $this->checkActivitySLABreaches();
        $this->checkStageSLABreaches();
    }

    /**
     * Check for overdue activities and notify assigned users
     */
    protected function checkActivitySLABreaches(): void
    {
        $overdueActivities = Activity::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['assignedTo'])
            ->get();

        // Group by assigned user
        $activitiesByUser = $overdueActivities->groupBy('assigned_to');

        foreach ($activitiesByUser as $userId => $activities) {
            $user = User::find($userId);
            
            if ($user) {
                $user->notify(new ActivityOverdueNotification($activities));
            }
        }

        \Log::info('SLA Check: Processed ' . $overdueActivities->count() . ' overdue activities for ' . $activitiesByUser->count() . ' users');
    }

    /**
     * Check for opportunities breaching stage SLAs
     */
    protected function checkStageSLABreaches(): void
    {
        $breaches = [];

        // Get all open opportunities with stages that have SLAs
        $opportunities = Opportunity::whereNull('won_at')
            ->whereNull('lost_at')
            ->with(['stage', 'assignedTo', 'stageHistory'])
            ->get();

        foreach ($opportunities as $opportunity) {
            $stage = $opportunity->stage;
            
            if (!$stage) continue;

            // Get when opportunity entered this stage
            $stageEntry = $opportunity->stageHistory()
                ->where('to_stage_id', $stage->id)
                ->latest()
                ->first();

            if (!$stageEntry) continue;

            $hoursInStage = now()->diffInHours($stageEntry->changed_at);

            // Check first response SLA
            if ($stage->sla_first_response_hours && $hoursInStage > $stage->sla_first_response_hours) {
                $hasResponse = $opportunity->activities()
                    ->where('status', 'completed')
                    ->where('created_at', '>', $stageEntry->changed_at)
                    ->exists();

                if (!$hasResponse) {
                    $breaches[] = [
                        'opportunity' => $opportunity,
                        'type' => 'first_response',
                        'stage' => $stage,
                        'hours_overdue' => $hoursInStage - $stage->sla_first_response_hours,
                    ];
                }
            }

            // Check follow-up SLA
            if ($stage->sla_follow_up_hours) {
                $lastActivity = $opportunity->activities()
                    ->where('status', 'completed')
                    ->latest('completed_at')
                    ->first();

                if ($lastActivity) {
                    $hoursSinceLastActivity = now()->diffInHours($lastActivity->completed_at);
                    
                    if ($hoursSinceLastActivity > $stage->sla_follow_up_hours) {
                        $breaches[] = [
                            'opportunity' => $opportunity,
                            'type' => 'follow_up',
                            'stage' => $stage,
                            'hours_overdue' => $hoursSinceLastActivity - $stage->sla_follow_up_hours,
                        ];
                    }
                }
            }
        }

        // Create alert activities for breaches
        foreach ($breaches as $breach) {
            $this->createSLABreachActivity($breach);
        }

        \Log::info('SLA Check: Found ' . count($breaches) . ' stage SLA breaches');
    }

    /**
     * Create an activity for SLA breach
     */
    protected function createSLABreachActivity(array $breach): void
    {
        $opportunity = $breach['opportunity'];
        $type = $breach['type'];
        $hoursOverdue = $breach['hours_overdue'];

        $subject = $type === 'first_response' 
            ? 'URGENT: First Response SLA Breached' 
            : 'URGENT: Follow-up SLA Breached';

        $description = "Opportunity '{$opportunity->title}' has breached {$type} SLA by " . 
                      round($hoursOverdue, 1) . " hours in stage '{$breach['stage']->name}'. " .
                      "Immediate action required.";

        // Check if breach activity already exists today
        $existingBreach = $opportunity->activities()
            ->where('subject', 'LIKE', "%{$type}%SLA Breached%")
            ->where('created_at', '>', now()->startOfDay())
            ->exists();

        if (!$existingBreach) {
            $opportunity->activities()->create([
                'type' => 'task',
                'subject' => $subject,
                'description' => $description,
                'due_date' => now()->addHours(2), // 2 hours to resolve
                'priority' => 'high',
                'status' => 'pending',
                'related_type' => 'opportunity',
                'related_id' => $opportunity->id,
                'assigned_to' => $opportunity->assigned_to,
                'owner_id' => $opportunity->assigned_to,
                'created_by' => 1, // System
            ]);

            // Notify assigned user
            if ($opportunity->assignedTo) {
                // Could send immediate notification here
                \Log::warning("SLA Breach for opportunity {$opportunity->id}: {$subject}");
            }
        }
    }
}
