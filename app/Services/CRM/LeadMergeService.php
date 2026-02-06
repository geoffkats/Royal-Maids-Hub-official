<?php

namespace App\Services\CRM;

use App\Models\CRM\Lead;
use App\Models\CRM\Activity;
use App\Models\CRM\Opportunity;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadMergeService
{
    /**
     * Merge two leads, keeping the primary and discarding the duplicate
     */
    public function merge(Lead $primaryLead, Lead $duplicateLead, array $options = []): Lead
    {
        DB::beginTransaction();

        try {
            // Merge field data (keep primary unless duplicate has more complete data)
            $this->mergeFields($primaryLead, $duplicateLead, $options);

            // Re-link activities
            $this->relinkActivities($primaryLead, $duplicateLead);

            // Re-link opportunities
            $this->relinkOpportunities($primaryLead, $duplicateLead);

            // Re-link tickets
            $this->relinkTickets($primaryLead, $duplicateLead);

            // Update history records to point to primary
            $this->updateHistoryRecords($primaryLead, $duplicateLead);

            // Log the merge
            $this->logMerge($primaryLead, $duplicateLead);

            // Create merge activity on primary lead
            $primaryLead->activities()->create([
                'type' => 'note',
                'subject' => 'Lead Merged',
                'description' => "Merged duplicate lead: {$duplicateLead->full_name} (ID: {$duplicateLead->id}). All activities, opportunities, and tickets have been consolidated.",
                'status' => 'completed',
                'completed_at' => now(),
                'related_type' => 'lead',
                'related_id' => $primaryLead->id,
                'assigned_to' => $primaryLead->owner_id,
                'owner_id' => auth()->id() ?? $primaryLead->owner_id,
                'created_by' => auth()->id() ?? $primaryLead->owner_id,
            ]);

            // Always soft delete to protect data integrity.
            $duplicateLead->delete();

            DB::commit();

            return $primaryLead->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lead merge failed', [
                'primary_id' => $primaryLead->id,
                'duplicate_id' => $duplicateLead->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Merge field data from duplicate into primary
     */
    protected function mergeFields(Lead $primaryLead, Lead $duplicateLead, array $options): void
    {
        $fieldsToMerge = $options['fields'] ?? ['email', 'phone', 'company', 'job_title', 'industry', 'city', 'address', 'notes'];

        $updates = [];

        foreach ($fieldsToMerge as $field) {
            // Keep primary unless it's empty and duplicate has value
            if (empty($primaryLead->$field) && !empty($duplicateLead->$field)) {
                $updates[$field] = $duplicateLead->$field;
            }
        }

        // Merge notes (append duplicate's notes to primary)
        if (!empty($duplicateLead->notes) && $primaryLead->notes !== $duplicateLead->notes) {
            $updates['notes'] = trim($primaryLead->notes . "\n\n--- Merged from duplicate lead ---\n" . $duplicateLead->notes);
        }

        // Update score to higher value
        if ($duplicateLead->score > $primaryLead->score) {
            $updates['score'] = $duplicateLead->score;
        }

        // Keep earlier last_contacted_at if both exist
        if ($duplicateLead->last_contacted_at && 
            (!$primaryLead->last_contacted_at || $duplicateLead->last_contacted_at < $primaryLead->last_contacted_at)) {
            $updates['last_contacted_at'] = $duplicateLead->last_contacted_at;
        }

        if (!empty($updates)) {
            $primaryLead->update($updates);
        }
    }

    /**
     * Re-link all activities from duplicate to primary
     */
    protected function relinkActivities(Lead $primaryLead, Lead $duplicateLead): int
    {
        return Activity::where('related_type', Lead::class)
            ->where('related_id', $duplicateLead->id)
            ->update(['related_id' => $primaryLead->id]);
    }

    /**
     * Re-link all opportunities from duplicate to primary
     */
    protected function relinkOpportunities(Lead $primaryLead, Lead $duplicateLead): int
    {
        return Opportunity::where('lead_id', $duplicateLead->id)
            ->update(['lead_id' => $primaryLead->id]);
    }

    /**
     * Re-link all tickets from duplicate to primary
     */
    protected function relinkTickets(Lead $primaryLead, Lead $duplicateLead): int
    {
        return Ticket::where('requester_type', Lead::class)
            ->where('requester_id', $duplicateLead->id)
            ->update(['requester_id' => $primaryLead->id]);
    }

    /**
     * Update history records
     */
    protected function updateHistoryRecords(Lead $primaryLead, Lead $duplicateLead): void
    {
        // Update lead status history if it exists
        DB::table('lead_status_history')
            ->where('lead_id', $duplicateLead->id)
            ->update(['lead_id' => $primaryLead->id]);
    }

    /**
     * Log the merge operation
     */
    protected function logMerge(Lead $primaryLead, Lead $duplicateLead): void
    {
        Log::info('Lead merge completed', [
            'primary_lead_id' => $primaryLead->id,
            'primary_lead_name' => $primaryLead->full_name,
            'duplicate_lead_id' => $duplicateLead->id,
            'duplicate_lead_name' => $duplicateLead->full_name,
            'user_id' => auth()->id(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Bulk merge multiple duplicates into one primary lead
     */
    public function mergeBulk(Lead $primaryLead, array $duplicateLeadIds, array $options = []): Lead
    {
        foreach ($duplicateLeadIds as $duplicateId) {
            $duplicateLead = Lead::find($duplicateId);
            
            if ($duplicateLead && $duplicateLead->id !== $primaryLead->id) {
                $this->merge($primaryLead, $duplicateLead, $options);
            }
        }

        return $primaryLead->fresh();
    }

    /**
     * Preview what would be merged without executing
     */
    public function previewMerge(Lead $primaryLead, Lead $duplicateLead): array
    {
        return [
            'primary_lead' => [
                'id' => $primaryLead->id,
                'name' => $primaryLead->full_name,
                'email' => $primaryLead->email,
                'phone' => $primaryLead->phone,
                'company' => $primaryLead->company,
                'score' => $primaryLead->score,
                'activities_count' => $primaryLead->activities()->count(),
                'opportunities_count' => $primaryLead->opportunities()->count(),
                'tickets_count' => Ticket::where('requester_type', Lead::class)
                    ->where('requester_id', $primaryLead->id)
                    ->count(),
            ],
            'duplicate_lead' => [
                'id' => $duplicateLead->id,
                'name' => $duplicateLead->full_name,
                'email' => $duplicateLead->email,
                'phone' => $duplicateLead->phone,
                'company' => $duplicateLead->company,
                'score' => $duplicateLead->score,
                'activities_count' => $duplicateLead->activities()->count(),
                'opportunities_count' => $duplicateLead->opportunities()->count(),
                'tickets_count' => Ticket::where('requester_type', Lead::class)
                    ->where('requester_id', $duplicateLead->id)
                    ->count(),
            ],
            'fields_to_update' => $this->getFieldsToUpdate($primaryLead, $duplicateLead),
            'records_to_move' => [
                'activities' => $duplicateLead->activities()->count(),
                'opportunities' => $duplicateLead->opportunities()->count(),
                'tickets' => Ticket::where('requester_type', Lead::class)
                    ->where('requester_id', $duplicateLead->id)
                    ->count(),
            ],
        ];
    }

    /**
     * Get fields that would be updated in merge
     */
    protected function getFieldsToUpdate(Lead $primaryLead, Lead $duplicateLead): array
    {
        $fields = [];
        $fieldsToCheck = ['email', 'phone', 'company', 'job_title', 'industry', 'city', 'address'];

        foreach ($fieldsToCheck as $field) {
            if (empty($primaryLead->$field) && !empty($duplicateLead->$field)) {
                $fields[$field] = [
                    'from' => $primaryLead->$field,
                    'to' => $duplicateLead->$field,
                ];
            }
        }

        if ($duplicateLead->score > $primaryLead->score) {
            $fields['score'] = [
                'from' => $primaryLead->score,
                'to' => $duplicateLead->score,
            ];
        }

        return $fields;
    }
}
