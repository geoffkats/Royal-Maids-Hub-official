<?php

namespace App\Livewire\CRM\Activities;

use App\Models\CRM\Activity;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $assignedFilter = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedActivities = [];
    public $selectPage = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'assignedFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->typeFilter = request('type', '');
        $this->statusFilter = request('status', '');
        $this->priorityFilter = request('priority', '');
        $this->assignedFilter = request('assigned', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
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
            $this->selectedActivities = $this->activities->pluck('id')->toArray();
        } else {
            $this->selectedActivities = [];
        }
    }

    public function updatedSelectedActivities()
    {
        $this->selectPage = false;
    }

    public function selectAll()
    {
        $this->selectedActivities = $this->activities->pluck('id')->toArray();
        $this->selectPage = true;
    }

    public function clearSelection()
    {
        $this->selectedActivities = [];
        $this->selectPage = false;
    }

    public function deleteSelected()
    {
        Activity::whereIn('id', $this->selectedActivities)->delete();
        $this->selectedActivities = [];
        $this->selectPage = false;
        session()->flash('message', 'Selected activities deleted successfully.');
    }

    public function markCompleted($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $activity->markAsCompleted();
        session()->flash('message', 'Activity marked as completed successfully.');
    }

    public function getActivitiesProperty()
    {
        return Activity::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->assignedFilter, function ($query) {
                $query->where('assigned_to', $this->assignedFilter);
            })
            ->with(['assignedTo', 'owner', 'createdBy', 'related'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getUsersProperty()
    {
        return User::whereIn('role', ['admin', 'trainer'])->get();
    }

    public function getTypeOptionsProperty()
    {
        return [
            'call' => 'Call',
            'email' => 'Email',
            'meeting' => 'Meeting',
            'task' => 'Task',
            'note' => 'Note',
        ];
    }

    public function getStatusOptionsProperty()
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function getPriorityOptionsProperty()
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
        ];
    }

    public function render()
    {
        return view('livewire.c-r-m.activities.index', [
            'activities' => $this->activities,
            'users' => $this->users,
            'typeOptions' => $this->typeOptions,
            'statusOptions' => $this->statusOptions,
            'priorityOptions' => $this->priorityOptions,
        ]);
    }
}
