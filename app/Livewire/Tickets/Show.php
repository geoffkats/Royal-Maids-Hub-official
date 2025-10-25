<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketStatusHistory;
use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Show extends Component
{
    use AuthorizesRequests;

    public Ticket $ticket;
    public $newComment = '';
    public $status;
    public $priority;
    public $assigned_to;
    public $department;
    public $due_date;
    public $resolution_notes = '';
    public $satisfaction_rating;
    
    // For assignment and comments
    public $admins = [];
    public $comments = [];
    public $statusHistory = [];
    public $attachments = [];
    
    // UI state
    public $showCommentForm = false;
    public $showResolutionForm = false;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load([
            'requester',
            'client', 
            'maid', 
            'booking',
            'package',
            'assignedTo',
            'comments.user',
            'attachments',
            'statusHistory.changedBy'
        ]);
        
        $this->status = $ticket->status;
        $this->priority = $ticket->priority;
        $this->assigned_to = $ticket->assigned_to;
        $this->department = $ticket->department;
        $this->due_date = $ticket->due_date?->format('Y-m-d');
        $this->resolution_notes = $ticket->resolution_notes;
        $this->satisfaction_rating = $ticket->satisfaction_rating;
        
        // Load related data
        $this->comments = $ticket->comments;
        $this->statusHistory = $ticket->statusHistory;
        $this->attachments = $ticket->attachments;
        
        // Load admins and trainers for assignment dropdown
        $this->admins = User::whereIn('role', ['admin', 'trainer'])->orderBy('name')->get();
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|min:3'
        ]);

        // Update first_response_at if this is first staff response
        if (!$this->ticket->first_response_at && in_array(auth()->user()->role, ['admin', 'trainer'])) {
            $this->ticket->update(['first_response_at' => now()]);
        }

        // Create comment
        TicketComment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'comment_type' => 'comment',
            'body' => $this->newComment,
            'is_internal' => false,
        ]);

        $this->newComment = '';
        $this->ticket->refresh();
        $this->comments = $this->ticket->comments;
        
        session()->flash('success', 'Comment added successfully!');
    }

    public function addInternalNote()
    {
        $this->validate([
            'newComment' => 'required|string|min:3'
        ]);

        // Create internal note
        TicketComment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'comment_type' => 'internal_note',
            'body' => $this->newComment,
            'is_internal' => true,
        ]);

        $this->newComment = '';
        $this->ticket->refresh();
        $this->comments = $this->ticket->comments;
        
        session()->flash('success', 'Internal note added successfully!');
    }

    public function updateTicket()
    {
        $changes = [];
        
        // Track status change
        if ($this->status !== $this->ticket->status) {
            $changes['old_status'] = $this->ticket->status;
            $changes['new_status'] = $this->status;
            
            // Record status change in history
            TicketStatusHistory::create([
                'ticket_id' => $this->ticket->id,
                'changed_by' => auth()->id(),
                'old_status' => $this->ticket->status,
                'new_status' => $this->status,
                'notes' => 'Status updated by ' . auth()->user()->name,
            ]);
            
            // Update resolved_at or closed_at timestamps
            if ($this->status === 'resolved' && !$this->ticket->resolved_at) {
                $this->ticket->resolved_at = now();
            } elseif ($this->status === 'closed' && !$this->ticket->closed_at) {
                $this->ticket->closed_at = now();
            }
        }
        
        // Track assignment change
        if ($this->assigned_to !== $this->ticket->assigned_to) {
            $changes['assigned_to'] = $this->assigned_to;
            
            // Record assignment change
            TicketComment::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => auth()->id(),
                'comment_type' => 'assignment_change',
                'body' => 'Ticket assigned to ' . ($this->assigned_to ? User::find($this->assigned_to)->name : 'Unassigned'),
                'is_internal' => true,
            ]);
        }
        
        // Track priority change
        if ($this->priority !== $this->ticket->priority) {
            $changes['priority'] = $this->priority;
        }

        // Update ticket
        $this->ticket->update([
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'department' => $this->department,
            'due_date' => $this->due_date ? now()->parse($this->due_date) : null,
            'resolution_notes' => $this->resolution_notes,
        ]);
        
        $this->ticket->refresh();
        $this->statusHistory = $this->ticket->statusHistory;
        $this->comments = $this->ticket->comments;
        
        session()->flash('success', 'Ticket updated successfully!');
    }

    public function resolveTicket()
    {
        $this->validate([
            'resolution_notes' => 'required|string|min:10'
        ]);

        $this->ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $this->resolution_notes,
        ]);

        // Record status change
        TicketStatusHistory::create([
            'ticket_id' => $this->ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $this->ticket->status,
            'new_status' => 'resolved',
            'notes' => 'Ticket resolved by ' . auth()->user()->name,
        ]);

        $this->status = 'resolved';
        $this->ticket->refresh();
        $this->statusHistory = $this->ticket->statusHistory;
        
        session()->flash('success', 'Ticket resolved successfully!');
    }

    public function closeTicket()
    {
        $this->ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        // Record status change
        TicketStatusHistory::create([
            'ticket_id' => $this->ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $this->ticket->status,
            'new_status' => 'closed',
            'notes' => 'Ticket closed by ' . auth()->user()->name,
        ]);

        $this->status = 'closed';
        $this->ticket->refresh();
        $this->statusHistory = $this->ticket->statusHistory;
        
        session()->flash('success', 'Ticket closed successfully!');
    }

    public function rateSatisfaction()
    {
        $this->validate([
            'satisfaction_rating' => 'required|integer|min:1|max:5'
        ]);

        $this->ticket->update([
            'satisfaction_rating' => $this->satisfaction_rating,
        ]);

        $this->ticket->refresh();
        
        session()->flash('success', 'Thank you for your feedback!');
    }

    public function render()
    {
        return view('livewire.tickets.show');
    }
}
