<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use Illuminate\Support\Arr;

class LeadScoringService
{
    public function calculate(Lead $lead): int
    {
        $w = config('crm_scoring.weights');
        $score = 0;

        // Profile completeness
        if (!empty($lead->email)) { $score += $w['has_email']; }
        if (!empty($lead->phone)) { $score += $w['has_phone']; }
        if (!empty($lead->company)) { $score += $w['has_company']; }
        if (!empty($lead->interested_package_id)) { $score += $w['interested_package']; }

        // Engagement: recent activities in last 7 days
        $recentActivities = $lead->activities()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $score += min($recentActivities * $w['activity_recent'], $w['activity_recent_cap']);

        // Recency of last contact
        if ($lead->last_contacted_at) {
            if ($lead->last_contacted_at->greaterThanOrEqualTo(now()->subDays(3))) {
                $score += $w['last_contact_3_days'];
            } elseif ($lead->last_contacted_at->greaterThanOrEqualTo(now()->subDays(7))) {
                $score += $w['last_contact_7_days'];
            }
        }

        // Source bonus
        $sourceName = $lead->source?->name;
        if ($sourceName && isset($w['source_bonus'][$sourceName])) {
            $score += $w['source_bonus'][$sourceName];
        }

        // Status penalty (e.g., disqualified)
        if ($lead->status && isset($w['status_penalty'][$lead->status])) {
            $score += $w['status_penalty'][$lead->status];
        }

        // Converted leads are maxed
        if (method_exists($lead, 'isConverted') && $lead->isConverted()) {
            $score = $w['max_score'];
        }

        // Clamp
        $score = max($w['min_score'], min($w['max_score'], $score));

        return (int) $score;
    }

    public function apply(Lead $lead): int
    {
        $score = $this->calculate($lead);
        $lead->update(['score' => $score]);
        return $score;
    }

    public function bulkRecalculate(): int
    {
        $count = 0;
        Lead::with(['activities', 'source'])->chunk(200, function ($leads) use (&$count) {
            foreach ($leads as $lead) {
                $this->apply($lead);
                $count++;
            }
        });
        return $count;
    }
}
