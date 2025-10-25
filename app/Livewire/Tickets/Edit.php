<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Booking;
use App\Models\Package;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    
    // Form fields
    public $type;
    public $category;
    public $subcategory;
    public $priority;
    public $subject;
    public $description;
    public $status;
    public $assigned_to;
    public $department;
    public $due_date;
    public $location_address;
    public $resolution_notes;
    
    // Related entities
    public $related_client_id;
    public $related_maid_id;
    public $related_booking_id;
    
    // File uploads
    public $attachments = [];
    
    // Options
    public $clients = [];
    public $maids = [];
    public $bookings = [];
    public $packages = [];
    public $admins = [];

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        
        // Populate form fields
        $this->type = $ticket->type;
        $this->category = $ticket->category;
        $this->subcategory = $ticket->subcategory;
        $this->priority = $ticket->priority;
        $this->subject = $ticket->subject;
        $this->description = $ticket->description;
        $this->status = $ticket->status;
        $this->assigned_to = $ticket->assigned_to;
        $this->department = $ticket->department;
        $this->due_date = $ticket->due_date?->format('Y-m-d');
        $this->location_address = $ticket->location_address;
        $this->resolution_notes = $ticket->resolution_notes;
        
        // Related entities
        $this->related_client_id = $ticket->client_id;
        $this->related_maid_id = $ticket->maid_id;
        $this->related_booking_id = $ticket->booking_id;
        
        // Load options
        $this->clients = Client::orderBy('contact_person')->get();
        $this->maids = Maid::orderBy('first_name')->get();
        $this->bookings = Booking::with(['client', 'package'])->orderBy('id', 'desc')->get();
        $this->packages = Package::orderBy('name')->get();
        $this->admins = User::whereIn('role', ['admin', 'trainer'])->orderBy('name')->get();
    }

    public function rules()
    {
        return [
            'type' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent,critical',
            'subject' => 'required|string|max:191',
            'description' => 'required|string',
            'status' => 'required|in:open,pending,in_progress,on_hold,resolved,closed,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'department' => 'nullable|in:customer_service,operations,finance,hr,training,technical',
            'due_date' => 'nullable|date|after:today',
            'location_address' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
            'related_client_id' => 'nullable|exists:clients,id',
            'related_maid_id' => 'nullable|exists:maids,id',
            'related_booking_id' => 'nullable|exists:bookings,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    public function update()
    {
        $this->validate();

        $updateData = [
            'type' => $this->type,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'priority' => $this->priority,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'department' => $this->department,
            'due_date' => $this->due_date ? now()->parse($this->due_date) : null,
            'location_address' => $this->location_address,
            'resolution_notes' => $this->resolution_notes,
            'client_id' => $this->related_client_id,
            'maid_id' => $this->related_maid_id,
            'booking_id' => $this->related_booking_id,
        ];

        // Update resolved_at or closed_at timestamps
        if ($this->status === 'resolved' && !$this->ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        } elseif ($this->status === 'closed' && !$this->ticket->closed_at) {
            $updateData['closed_at'] = now();
        }

        $this->ticket->update($updateData);

        // Handle file uploads
        if ($this->attachments) {
            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('ticket-attachments', 'public');
                
                $this->ticket->attachments()->create([
                    'uploaded_by' => auth()->id(),
                    'file_name' => $attachment->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $attachment->getMimeType(),
                    'file_size' => $attachment->getSize(),
                ]);
            }
        }

        session()->flash('success', 'Ticket updated successfully!');
        return redirect()->route('tickets.show', $this->ticket);
    }

    public function render()
    {
        return view('livewire.tickets.edit');
    }
}
