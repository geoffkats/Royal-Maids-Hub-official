<?php

namespace App\Livewire\CRM\Opportunities;

use Livewire\Component;
use App\Models\CRM\Lead;
use App\Models\CRM\Stage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    // Form fields
    public $name = '';
    public $amount = '';
    public $probability = '';
    public $close_date = '';
    public $stage_id = '';
    public $lead_id = '';
    public $assigned_to = '';
    public $description = '';
    public $status = 'open';

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'amount' => 'nullable|numeric|min:0',
        'probability' => 'nullable|integer|min:0|max:100',
        'close_date' => 'nullable|date|after:today',
        'stage_id' => 'required|exists:crm_stages,id',
        'lead_id' => 'nullable|exists:crm_leads,id',
        'assigned_to' => 'nullable|exists:users,id',
        'description' => 'nullable|string',
        'status' => 'required|in:open,won,lost',
    ];

    // Custom validation messages
    protected $messages = [
        'name.required' => 'The opportunity name is required.',
        'stage_id.required' => 'Please select a stage.',
        'stage_id.exists' => 'The selected stage is invalid.',
        'lead_id.exists' => 'The selected lead is invalid.',
        'assigned_to.exists' => 'The selected user is invalid.',
        'amount.numeric' => 'The amount must be a valid number.',
        'amount.min' => 'The amount must be at least 0.',
        'probability.integer' => 'The probability must be a whole number.',
        'probability.min' => 'The probability must be at least 0.',
        'probability.max' => 'The probability must not exceed 100.',
        'close_date.date' => 'The close date must be a valid date.',
        'close_date.after' => 'The close date must be in the future.',
    ];

    public function mount()
    {
        // Set default assigned user to current user
        $this->assigned_to = Auth::id();
        
        // Set default probability
        $this->probability = 50;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        try {
            $opportunity = \App\Models\CRM\Opportunity::create([
                'name' => $this->name,
                'amount' => $this->amount ?: 0,
                'probability' => $this->probability ?: 0,
                'close_date' => $this->close_date,
                'stage_id' => $this->stage_id,
                'lead_id' => $this->lead_id,
                'assigned_to' => $this->assigned_to,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Opportunity created successfully!');
            
            return redirect()->route('crm.opportunities.show', $opportunity);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create opportunity: ' . $e->getMessage());
        }
    }

    public function getLeadsProperty()
    {
        return Lead::orderBy('first_name')->orderBy('last_name')->get();
    }

    public function getStagesProperty()
    {
        return Stage::orderBy('position')->get();
    }

    public function getUsersProperty()
    {
        return User::whereIn('role', ['admin', 'trainer'])->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.c-r-m.opportunities.create', [
            'leads' => $this->leads,
            'stages' => $this->stages,
            'users' => $this->users,
        ]);
    }
}
