<?php

namespace App\Livewire\CRM\Opportunities;

use Livewire\Component;
use App\Models\CRM\Opportunity;

class Show extends Component
{
    public Opportunity $opportunity;

    public function mount(Opportunity $opportunity)
    {
        try {
            $this->opportunity = $opportunity->load([
                'lead',
                'client', 
                'stage',
                'assignedTo',
                'createdBy',
                'activities',
                'tags'
            ]);
        } catch (\Exception $e) {
            // If there's an error loading the opportunity, redirect back
            session()->flash('error', 'Opportunity not found.');
            return redirect()->route('crm.opportunities.index');
        }
    }

    public function markWon()
    {
        try {
            // Use the model's markAsWon method
            $this->opportunity->markAsWon('Opportunity marked as won by ' . auth()->user()->name);
            
            // Update the lead status to "qualified" so it can be converted to client
            if ($this->opportunity->lead) {
                // Check if lead is truly converted (has client_id)
                $isTrulyConverted = $this->opportunity->lead->client_id !== null;
                
                if (!$isTrulyConverted) {
                    $this->opportunity->lead->update([
                        'status' => 'qualified'
                    ]);
                    
                    // Log activity on the lead
                    $this->opportunity->lead->activities()->create([
                        'type' => 'note',
                        'subject' => 'Lead Qualified - Opportunity Won',
                        'description' => 'Lead status updated to qualified after winning opportunity: ' . $this->opportunity->title,
                        'status' => 'completed',
                        'completed_at' => now(),
                        'created_by' => auth()->id(),
                        'related_type' => 'lead',
                        'related_id' => $this->opportunity->lead->id,
                    ]);
                }
            }
            
            // Refresh the opportunity with all relationships
            $this->opportunity = Opportunity::with([
                'lead',
                'client', 
                'stage',
                'assignedTo',
                'createdBy',
                'activities',
                'tags'
            ])->find($this->opportunity->id);
            
            session()->flash('message', 'Opportunity marked as won successfully! Lead is now qualified and ready for conversion.');
            
            // Dispatch browser event to show notification
            $this->dispatch('opportunity-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark opportunity as won: ' . $e->getMessage());
            \Log::error('Error marking opportunity as won: ' . $e->getMessage());
        }
    }

    public function markLost()
    {
        try {
            // Use the model's markAsLost method
            $this->opportunity->markAsLost('Opportunity marked as lost by ' . auth()->user()->name);
            
            // Refresh the opportunity with all relationships
            $this->opportunity = Opportunity::with([
                'lead',
                'client', 
                'stage',
                'assignedTo',
                'createdBy',
                'activities',
                'tags'
            ])->find($this->opportunity->id);
            
            session()->flash('message', 'Opportunity marked as lost.');
            
            // Dispatch browser event to show notification
            $this->dispatch('opportunity-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark opportunity as lost: ' . $e->getMessage());
            \Log::error('Error marking opportunity as lost: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.c-r-m.opportunities.show', [
            'opportunity' => $this->opportunity
        ]);
    }
}
