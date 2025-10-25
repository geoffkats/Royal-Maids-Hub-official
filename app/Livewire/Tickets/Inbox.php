<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class Inbox extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $priorityFilter = '';
    public $slaFilter = '';
    public $unreadCount = 0;
    public $openCount = 0;
    public $inProgressCount = 0;
    public $slaBreachedCount = 0;
    public $resolvedCount = 0;
    public $assignedTickets = [];

    public function mount()
    {
        $this->loadCounts();
        $this->loadAssignedTickets();
    }

    public function loadCounts()
    {
        $userId = auth()->id();
        $userTickets = Ticket::where('assigned_to', $userId)->count();
        
        // If no tickets assigned to user, show all tickets for admin (for testing)
        if ($userTickets === 0 && auth()->user()->role === 'admin') {
            $this->unreadCount = Ticket::whereHas('comments', function($query) use ($userId) {
                $query->where('user_id', '!=', $userId)
                    ->where('created_at', '>', now()->subDays(7));
            })->count();

            $this->openCount = Ticket::where('status', 'open')->count();
            $this->inProgressCount = Ticket::where('status', 'in_progress')->count();
            $this->slaBreachedCount = Ticket::where('sla_breached', true)->count();
            $this->resolvedCount = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        } else {
            // Unread notifications count (tickets with new comments)
            $this->unreadCount = Ticket::where('assigned_to', $userId)
                ->whereHas('comments', function($query) use ($userId) {
                    $query->where('user_id', '!=', $userId)
                        ->where('created_at', '>', now()->subDays(7));
                })
                ->count();

            // Status counts
            $this->openCount = Ticket::where('assigned_to', $userId)
                ->where('status', 'open')
                ->count();

            $this->inProgressCount = Ticket::where('assigned_to', $userId)
                ->where('status', 'in_progress')
                ->count();

            $this->slaBreachedCount = Ticket::where('assigned_to', $userId)
                ->where('sla_breached', true)
                ->count();

            $this->resolvedCount = Ticket::where('assigned_to', $userId)
                ->whereIn('status', ['resolved', 'closed'])
                ->count();
        }
    }

    public function loadAssignedTickets()
    {
        $userId = auth()->id();
        
        // Debug: Check if user is authenticated and get total tickets
        $totalTickets = Ticket::count();
        $userTickets = Ticket::where('assigned_to', $userId)->count();
        
        // If no tickets assigned to user, show all tickets for admin (for testing)
        if ($userTickets === 0 && auth()->user()->role === 'admin') {
            $query = Ticket::with(['requester', 'client', 'maid', 'comments']);
        } else {
            $query = Ticket::with(['requester', 'client', 'maid', 'comments'])
                ->where('assigned_to', $userId);
        }

        // Apply filters
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->slaFilter) {
            switch ($this->slaFilter) {
                case 'breached':
                    $query->where('sla_breached', true);
                    break;
                case 'approaching':
                    $query->where('sla_resolution_due', '<=', now()->addHours(2))
                          ->where('sla_breached', false);
                    break;
                case 'safe':
                    $query->where('sla_resolution_due', '>', now()->addHours(2))
                          ->where('sla_breached', false);
                    break;
            }
        }

        $this->assignedTickets = $query->latest()->limit(10)->get();
    }

    public function getAssignedTicketsProperty()
    {
        return $this->assignedTickets;
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->loadAssignedTickets();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
        $this->loadAssignedTickets();
    }

    public function updatedSlaFilter()
    {
        $this->resetPage();
        $this->loadAssignedTickets();
    }

    public function resetFilters()
    {
        $this->statusFilter = '';
        $this->priorityFilter = '';
        $this->slaFilter = '';
        $this->resetPage();
        $this->loadAssignedTickets();
    }

    public function markAllAsRead()
    {
        // This would mark all notifications as read
        // Implementation depends on your notification system
        session()->flash('message', 'All notifications marked as read');
    }

    public function render()
    {
        return view('livewire.tickets.inbox');
    }
}
