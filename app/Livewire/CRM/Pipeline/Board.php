<?php

namespace App\Livewire\CRM\Pipeline;

use App\Models\CRM\Pipeline;
use App\Models\CRM\Stage;
use App\Models\CRM\Opportunity;
use App\Models\CRM\OpportunityStageHistory;
use App\Models\CRM\Lead;
use Livewire\Component;

class Board extends Component
{
    public $pipelineId;
    public ?Pipeline $pipeline = null;
    public $stages;
    public $opportunitiesByStage = [];
    public $leads = [];
    public $selectedOpportunity = null;
    public $showOpportunityModal = false;

    public function mount($pipelineId = null)
    {
        // Load default pipeline or specified pipeline
        if ($pipelineId) {
            $this->pipeline = Pipeline::with('stages')->findOrFail($pipelineId);
        } else {
            // Try to find default pipeline, otherwise get the first pipeline
            $this->pipeline = Pipeline::where('is_default', true)
                ->with('stages')
                ->first();
            
            if (!$this->pipeline) {
                // If no default, get the first pipeline
                $this->pipeline = Pipeline::with('stages')->first();
            }
            
            // If still no pipeline exists, redirect to settings
            if (!$this->pipeline) {
                session()->flash('error', 'No pipeline found. Please create a pipeline first.');
                return $this->redirect(route('crm.settings.index'), navigate: true);
            }
        }

        // Ensure pipeline is not null before accessing
        if (!$this->pipeline) {
            return $this->redirect(route('crm.settings.index'), navigate: true);
        }

        $this->pipelineId = $this->pipeline->id;
        $this->loadBoard();
    }

    public function loadBoard()
    {
        // Load stages with their opportunities
        $this->stages = Stage::where('pipeline_id', $this->pipelineId)
            ->orderBy('position')
            ->get();

        // Load opportunities grouped by stage
        $this->opportunitiesByStage = [];
        foreach ($this->stages as $stage) {
            $this->opportunitiesByStage[$stage->id] = Opportunity::where('stage_id', $stage->id)
                ->with(['lead', 'client', 'assignedTo', 'stage', 'package'])
                ->orderBy('close_date', 'asc')
                ->get();
        }

        // Load qualified leads (not converted and not already in opportunities) that can be dragged into stages
        $leadsWithOpportunities = Opportunity::pluck('lead_id')->toArray();
        
        $this->leads = Lead::where('status', 'qualified')
            ->whereNull('converted_at')
            ->whereNotIn('id', $leadsWithOpportunities)
            ->with(['owner', 'source', 'interestedPackage'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    public function updateOpportunityStage($opportunityId, $newStageId, $oldStageId)
    {
        try {
            $opportunity = Opportunity::findOrFail($opportunityId);
            $newStage = Stage::findOrFail($newStageId);
            $oldStage = Stage::findOrFail($oldStageId);

            // Check authorization (allow if user can update opportunity)
            if (!auth()->user()->can('update', $opportunity)) {
                $this->dispatch('error', message: 'You do not have permission to update this opportunity stage.');
                return;
            }

            // Update opportunity stage
            $opportunity->update([
                'stage_id' => $newStageId,
                'probability' => $newStage->probability_default ?? $opportunity->probability,
            ]);

            // Check if moved to closed stage
            if ($newStage->is_closed) {
                if ($newStage->name === 'Won' || $newStage->name === 'Closed Won') {
                    $opportunity->markAsWon();
                } elseif ($newStage->name === 'Lost' || $newStage->name === 'Closed Lost') {
                    $opportunity->markAsLost('Moved to ' . $newStage->name, null);
                }
            }

            // Log stage change history
            OpportunityStageHistory::create([
                'opportunity_id' => $opportunityId,
                'from_stage_id' => $oldStageId,
                'to_stage_id' => $newStageId,
                'changed_by' => auth()->id(),
                'notes' => "Moved from {$oldStage->name} to {$newStage->name}",
            ]);

            // Log activity
            $opportunity->activities()->create([
                'type' => 'note',
                'subject' => 'Stage Changed',
                'description' => "Opportunity moved from {$oldStage->name} to {$newStage->name}",
                'status' => 'completed',
                'completed_at' => now(),
                'related_type' => 'opportunity',
                'related_id' => $opportunityId,
                'assigned_to' => $opportunity->assigned_to,
                'created_by' => auth()->id(),
            ]);

            // Reload board
            $this->loadBoard();

            $this->dispatch('success', message: 'Opportunity stage updated successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to update stage: ' . $e->getMessage());
        }
    }

    public function convertLeadToOpportunity($leadId, $stageId)
    {
        try {
            $lead = Lead::findOrFail($leadId);
            $stage = Stage::findOrFail($stageId);

            // Create opportunity from lead
            $opportunity = Opportunity::create([
                'title' => $lead->full_name . ' - Opportunity',
                'name' => $lead->full_name . ' - Opportunity',
                'description' => 'Converted from lead: ' . $lead->full_name,
                'amount' => 0,
                'probability' => $stage->probability_default ?? 50,
                'close_date' => now()->addDays(30),
                'status' => 'open',
                'stage_id' => $stage->id,
                'lead_id' => $lead->id,
                'assigned_to' => $lead->owner_id,
                'created_by' => auth()->id(),
            ]);

            // Don't mark as converted yet - just link to opportunity
            // Lead will be marked as converted when opportunity is won and converted to client

            // Log activity
            $opportunity->activities()->create([
                'type' => 'note',
                'subject' => 'Lead Converted',
                'description' => "Lead '{$lead->full_name}' was converted to opportunity and placed in stage '{$stage->name}'",
                'status' => 'completed',
                'completed_at' => now(),
                'related_type' => 'opportunity',
                'related_id' => $opportunity->id,
                'assigned_to' => $opportunity->assigned_to,
                'created_by' => auth()->id(),
            ]);

            // Reload board
            $this->loadBoard();

            // Show success message
            session()->flash('message', "Lead '{$lead->full_name}' converted to opportunity successfully!");

            // Force Livewire to refresh the view
            $this->dispatch('lead-converted', opportunityId: $opportunity->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to convert lead: ' . $e->getMessage());
            \Log::error('Lead conversion failed: ' . $e->getMessage(), [
                'leadId' => $leadId,
                'stageId' => $stageId,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function showOpportunity($opportunityId)
    {
        $this->selectedOpportunity = Opportunity::with(['lead', 'client', 'assignedTo', 'package', 'stage'])
            ->findOrFail($opportunityId);
        $this->showOpportunityModal = true;
    }

    public function closeOpportunityModal()
    {
        $this->showOpportunityModal = false;
        $this->selectedOpportunity = null;
    }

    public function getPipelineValueProperty()
    {
        $total = 0;
        foreach ($this->opportunitiesByStage as $opportunities) {
            $total += $opportunities->sum('amount');
        }
        return $total;
    }

    public function getWeightedValueProperty()
    {
        $weighted = 0;
        foreach ($this->opportunitiesByStage as $opportunities) {
            foreach ($opportunities as $opp) {
                $weighted += ($opp->amount * $opp->probability / 100);
            }
        }
        return $weighted;
    }

    public function render()
    {
        return view('livewire.c-r-m.pipeline.board', [
            'pipelineValue' => $this->pipelineValue,
            'weightedValue' => $this->weightedValue,
        ])->layout('components.layouts.app', ['title' => __('Pipeline Board')]);
    }
}
