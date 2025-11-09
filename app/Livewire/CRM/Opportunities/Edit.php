<?php

namespace App\Livewire\CRM\Opportunities;

use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Stage;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Opportunity $opportunity;
    
    // Form properties
    public $name;
    public $amount;
    public $probability;
    public $close_date;
    public $description;
    public $lead_id;
    public $stage_id;
    public $assigned_to;

    // Data for dropdowns
    public $leads;
    public $stages;
    public $users;

    protected $rules = [
        'name' => 'required|string|max:255',
        'amount' => 'nullable|numeric|min:0',
        'probability' => 'nullable|integer|min:0|max:100',
        'close_date' => 'nullable|date',
        'description' => 'nullable|string',
        'lead_id' => 'nullable|exists:crm_leads,id',
        'stage_id' => 'nullable|exists:crm_stages,id',
        'assigned_to' => 'nullable|exists:users,id',
    ];

    public function mount(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
        
        // Load form data
        $this->name = $opportunity->name ?? '';
        $this->amount = $opportunity->amount;
        $this->probability = $opportunity->probability;
        $this->close_date = $opportunity->close_date?->format('Y-m-d');
        $this->description = $opportunity->description;
        $this->lead_id = $opportunity->lead_id;
        $this->stage_id = $opportunity->stage_id;
        $this->assigned_to = $opportunity->assigned_to;

        // Load dropdown data
        $this->leads = Lead::select('id', 'first_name', 'last_name', 'email')->get();
        $this->stages = Stage::with('pipeline')->get();
        $this->users = User::select('id', 'name')->get();
    }

    public function save()
    {
        $this->validate();

        $this->opportunity->update([
            'name' => $this->name,
            'amount' => $this->amount,
            'probability' => $this->probability,
            'close_date' => $this->close_date,
            'description' => $this->description,
            'lead_id' => $this->lead_id,
            'stage_id' => $this->stage_id,
            'assigned_to' => $this->assigned_to,
        ]);

        session()->flash('message', 'Opportunity updated successfully!');
        
        return redirect()->route('crm.opportunities.show', $this->opportunity);
    }

    public function render()
    {
        return view('livewire.c-r-m.opportunities.edit');
    }
}
