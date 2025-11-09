<?php

namespace App\Livewire\CRM\Opportunities;

use App\Models\CRM\Opportunity;
use App\Models\CRM\Stage;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $stageFilter = '';
    public $assignedFilter = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedOpportunities = [];
    public $selectPage = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'stageFilter' => ['except' => ''],
        'assignedFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->statusFilter = request('status', '');
        $this->stageFilter = request('stage', '');
        $this->assignedFilter = request('assigned', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedStageFilter()
    {
        $this->resetPage();
    }

    public function updatedAssignedFilter()
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
            $this->selectedOpportunities = $this->opportunities->pluck('id')->toArray();
        } else {
            $this->selectedOpportunities = [];
        }
    }

    public function updatedSelectedOpportunities()
    {
        $this->selectPage = false;
    }

    public function selectAll()
    {
        $this->selectedOpportunities = $this->opportunities->pluck('id')->toArray();
        $this->selectPage = true;
    }

    public function clearSelection()
    {
        $this->selectedOpportunities = [];
        $this->selectPage = false;
    }

    public function deleteSelected()
    {
        Opportunity::whereIn('id', $this->selectedOpportunities)->delete();
        $this->selectedOpportunities = [];
        $this->selectPage = false;
        session()->flash('message', 'Selected opportunities deleted successfully.');
    }

    public function markWon($opportunityId)
    {
        $opportunity = Opportunity::with('lead')->findOrFail($opportunityId);
        $opportunity->markAsWon();
        
        // Update the lead status to "qualified" so it can be converted to client
        if ($opportunity->lead) {
            // Check if lead is truly converted (has client_id)
            $isTrulyConverted = $opportunity->lead->client_id !== null;
            
            if (!$isTrulyConverted) {
                $opportunity->lead->update([
                    'status' => 'qualified'
                ]);
                
                // Log activity on the lead
                $opportunity->lead->activities()->create([
                    'type' => 'note',
                    'subject' => 'Lead Qualified - Opportunity Won',
                    'description' => 'Lead status updated to qualified after winning opportunity: ' . $opportunity->title,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'created_by' => auth()->id(),
                    'related_type' => 'lead',
                    'related_id' => $opportunity->lead->id,
                ]);
            }
        }
        
        session()->flash('message', 'Opportunity marked as won successfully. Lead is now qualified and ready for conversion.');
    }

    public function markLost($opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);
        $opportunity->markAsLost('Lost via CRM interface');
        session()->flash('message', 'Opportunity marked as lost successfully.');
    }

    public function getOpportunitiesProperty()
    {
        return Opportunity::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'open') {
                    $query->whereNull('won_at')->whereNull('lost_at');
                } elseif ($this->statusFilter === 'won') {
                    $query->whereNotNull('won_at');
                } elseif ($this->statusFilter === 'lost') {
                    $query->whereNotNull('lost_at');
                }
            })
            ->when($this->stageFilter, function ($query) {
                $query->where('stage_id', $this->stageFilter);
            })
            ->when($this->assignedFilter, function ($query) {
                $query->where('assigned_to', $this->assignedFilter);
            })
            ->with(['stage', 'assignedTo', 'createdBy', 'client'])
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
            'open' => 'Open',
            'won' => 'Won',
            'lost' => 'Lost',
        ];
    }

    public function getStageOptionsProperty()
    {
        return Stage::orderBy('position')->pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.c-r-m.opportunities.index', [
            'opportunities' => $this->opportunities,
            'users' => $this->users,
            'statusOptions' => $this->statusOptions,
            'stageOptions' => $this->stageOptions,
        ]);
    }
}
