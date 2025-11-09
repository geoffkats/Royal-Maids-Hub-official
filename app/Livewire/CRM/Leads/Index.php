<?php

namespace App\Livewire\CRM\Leads;

use App\Models\CRM\Lead;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $assignedFilter = '';
    public $sourceFilter = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedLeads = [];
    public $selectPage = false;
    
    // Modal state
    public $showConvertModal = false;
    public $leadToConvert = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'assignedFilter' => ['except' => ''],
        'sourceFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->statusFilter = request('status', '');
        $this->assignedFilter = request('assigned', '');
        $this->sourceFilter = request('source', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedAssignedFilter()
    {
        $this->resetPage();
    }

    public function updatedSourceFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selectedLeads = $this->leads->pluck('id')->toArray();
        } else {
            $this->selectedLeads = [];
        }
    }

    public function updatedSelectedLeads()
    {
        $this->selectPage = false;
    }

    public function selectAll()
    {
        $this->selectedLeads = $this->leads->pluck('id')->toArray();
        $this->selectPage = true;
    }

    public function clearSelection()
    {
        $this->selectedLeads = [];
        $this->selectPage = false;
    }

    public function deleteSelected()
    {
        Lead::whereIn('id', $this->selectedLeads)->delete();
        $this->selectedLeads = [];
        $this->selectPage = false;
        session()->flash('message', 'Selected leads deleted successfully.');
    }

    public function convertToOpportunity($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        
        // Create opportunity from lead
        $opportunity = \App\Models\CRM\Opportunity::create([
            'title' => $lead->full_name . ' - Opportunity',
            'description' => 'Converted from lead: ' . $lead->full_name,
            'amount' => 0, // Default amount, can be updated later
            'probability' => 50, // Default probability
            'close_date' => now()->addDays(30), // Default close date
            'lead_id' => $lead->id,
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id(),
        ]);
        
        // Update lead status
        $lead->update([
            'status' => 'converted', 
            'converted_at' => now()
        ]);
        
        session()->flash('message', 'Lead converted to opportunity successfully.');
    }

    public function openConvertModal($leadId)
    {
        $this->leadToConvert = Lead::findOrFail($leadId);
        $this->showConvertModal = true;
    }

    public function closeConvertModal()
    {
        $this->showConvertModal = false;
        $this->leadToConvert = null;
    }

    public function confirmConvertToOpportunity()
    {
        if (!$this->leadToConvert) {
            return;
        }

        $lead = $this->leadToConvert;
        
        // Create opportunity from lead
        $opportunity = \App\Models\CRM\Opportunity::create([
            'title' => $lead->full_name . ' - Opportunity',
            'description' => 'Converted from lead: ' . $lead->full_name,
            'amount' => 0, // Default amount, can be updated later
            'probability' => 50, // Default probability
            'close_date' => now()->addDays(30), // Default close date
            'lead_id' => $lead->id,
            'assigned_to' => $lead->owner_id,
            'created_by' => auth()->id(),
        ]);
        
        // Update lead status
        $lead->update([
            'status' => 'converted', 
            'converted_at' => now()
        ]);

        $this->closeConvertModal();
        session()->flash('message', 'Lead converted to opportunity successfully!');
    }

    public function getLeadsProperty()
    {
        return Lead::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('company', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->assignedFilter, function ($query) {
                $query->where('assigned_to', $this->assignedFilter);
            })
            ->when($this->sourceFilter, function ($query) {
                $query->where('source', $this->sourceFilter);
            })
            ->with(['assignedTo', 'createdBy'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getUsersProperty()
    {
        return User::whereIn('role', ['admin', 'trainer'])->get();
    }

    public function getStatusOptionsProperty()
    {
        return [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'unqualified' => 'Unqualified',
            'converted' => 'Converted',
        ];
    }

    public function getSourceOptionsProperty()
    {
        return [
            'website' => 'Website',
            'referral' => 'Referral',
            'social_media' => 'Social Media',
            'advertisement' => 'Advertisement',
            'cold_call' => 'Cold Call',
            'email' => 'Email',
            'other' => 'Other',
        ];
    }

    public function render()
    {
        return view('livewire.c-r-m.leads.index', [
            'leads' => $this->leads,
            'users' => $this->users,
            'statusOptions' => $this->statusOptions,
            'sourceOptions' => $this->sourceOptions,
        ]);
    }
}
