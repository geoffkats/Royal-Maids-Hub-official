<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $statusFilter = 'all';
    public $priorityFilter = 'all';
    public $typeFilter = 'all';
    public $assignedFilter = 'all';
    public $tierFilter = 'all';
    public $dateFrom;
    public $dateTo;
    public $slaBreached = false;
    public $perPage = 20;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Quick actions
    public $selectedTickets = [];
    public $bulkAction = '';

    public function mount()
    {
        // Default to showing open tickets
        $this->statusFilter = 'open';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingAssignedFilter()
    {
        $this->resetPage();
    }

    public function updatingTierFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->priorityFilter = 'all';
        $this->typeFilter = 'all';
        $this->assignedFilter = 'all';
        $this->tierFilter = 'all';
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->slaBreached = false;
        $this->resetPage();
    }

    public function exportTickets()
    {
        // TODO: Implement ticket export functionality
        session()->flash('message', 'Export functionality will be implemented soon.');
    }

    public function bulkAssign()
    {
        if (empty($this->selectedTickets) || !$this->bulkAction) {
            return;
        }

        // TODO: Implement bulk assignment
        session()->flash('message', 'Bulk assignment functionality will be implemented soon.');
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->with(['requester', 'client', 'maid', 'booking', 'assignedTo', 'package'])
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('ticket_number', 'like', "%{$this->search}%")
                        ->orWhere('subject', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhereHas('client', function($q) {
                            $q->where('contact_person', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('maid', function($q) {
                            $q->where('first_name', 'like', "%{$this->search}%")
                              ->orWhere('last_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function($q) {
                if ($this->statusFilter === 'open') {
                    $q->whereIn('status', ['open', 'in_progress', 'pending']);
                } else {
                    $q->where('status', $this->statusFilter);
                }
            })
            ->when($this->priorityFilter !== 'all', fn($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->typeFilter !== 'all', fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->assignedFilter === 'me', fn($q) => $q->where('assigned_to', auth()->id()))
            ->when($this->assignedFilter === 'unassigned', fn($q) => $q->whereNull('assigned_to'))
            ->when($this->tierFilter !== 'all', fn($q) => $q->where('tier_based_priority', $this->tierFilter))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->slaBreached, fn($q) => $q->where('sla_breached', true))
            ->when($this->sortBy === 'priority', function($q) {
                $q->orderByRaw("FIELD(priority, 'critical', 'urgent', 'high', 'medium', 'low')");
            })
            ->when($this->sortBy !== 'priority', function($q) {
                $q->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);

        // Get filter options
        $statusOptions = ['open', 'pending', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled'];
        $priorityOptions = ['critical', 'urgent', 'high', 'medium', 'low'];
        $typeOptions = ['client_issue', 'maid_support', 'deployment_issue', 'billing', 'training', 'operations', 'general'];
        $tierOptions = ['platinum', 'gold', 'silver'];
        $assignees = User::whereIn('role', ['admin', 'trainer'])->get();

        // Get statistics
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'urgent' => Ticket::whereIn('priority', ['urgent', 'critical'])->whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'sla_breached' => Ticket::where('sla_breached', true)->count(),
        ];

        return view('livewire.tickets.index', [
            'tickets' => $tickets,
            'statusOptions' => $statusOptions,
            'priorityOptions' => $priorityOptions,
            'typeOptions' => $typeOptions,
            'tierOptions' => $tierOptions,
            'assignees' => $assignees,
            'stats' => $stats,
        ]);
    }
}
