<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\Client;
use App\Models\Maid;
use App\Models\Package;
use App\Models\Booking;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use WithFileUploads;

    // Form fields
    public $type = '';
    public $category = '';
    public $subcategory = '';
    public $priority = 'medium';
    public $subject = '';
    public $description = '';
    public $related_client_id;
    public $related_maid_id;
    public $related_booking_id;
    public $related_deployment_id;
    public $location_address = '';
    public $location_lat;
    public $location_lng;
    public $attachments = [];

    // On behalf of system
    public $on_behalf_type = 'self';
    public $on_behalf_client_id;
    public $on_behalf_maid_id;

    // For dynamic UI and tier-based priority
    public $priority_preview;
    public $auto_boosted_priority;
    public $tier;
    public $package_id;
    public $userRole;
    public $clients = [];
    public $maids = [];
    public $bookings = [];
    public $trainers = [];
    public $packages = [];
    public $selectedClient;
    public $selectedMaid;
    public $slaResponseTime;
    public $slaResolutionTime;

    protected function rules()
    {
        return [
            'type' => ['required', Rule::in(['client_issue', 'maid_support', 'deployment_issue', 'billing', 'training', 'operations', 'general'])],
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'priority' => ['required', Rule::in(['low','medium','high','urgent'])],
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'related_client_id' => 'nullable|exists:clients,id',
            'related_maid_id' => 'nullable|exists:maids,id',
            'related_booking_id' => 'nullable|exists:bookings,id',
            'location_address' => 'nullable|string|max:500',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
            'on_behalf_type' => ['required', Rule::in(['self','client','maid'])],
            'on_behalf_client_id' => 'required_if:on_behalf_type,client|nullable|exists:clients,id',
            'on_behalf_maid_id' => 'required_if:on_behalf_type,maid|nullable|exists:maids,id',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->userRole = $user->role;
        $this->clients = Client::orderBy('contact_person')->get();
        $this->maids = Maid::orderBy('first_name')->get();
        $this->bookings = Booking::orderBy('id','desc')->limit(50)->get();
        $this->trainers = Trainer::orderBy('id')->get();
        $this->packages = Package::where('is_active', true)->get();

        // Pre-fill from query parameters (integration points)
        $this->related_client_id = request()->query('client_id');
        $this->related_maid_id = request()->query('maid_id');
        $this->related_booking_id = request()->query('booking_id');
        
        // Set default on behalf type based on user role
        if (in_array($this->userRole, ['admin', 'trainer'])) {
            $this->on_behalf_type = 'self';
        } else {
            $this->on_behalf_type = 'self';
        }
        
        $this->updatePriorityPreview();
    }


    public function updated($property, $value)
    {
        // Dynamic updates for priority preview and tier
        if (in_array($property, ['priority', 'tier', 'related_client_id', 'on_behalf_client_id', 'on_behalf_maid_id'])) {
            $this->updatePriorityPreview();
        }
        
        if ($property === 'related_client_id') {
            $client = Client::find($value);
            if ($client) {
                $this->tier = strtolower($client->subscription_tier ?? 'silver');
                $this->package_id = $client->package_id ?? null;
                $this->selectedClient = $client;
            } else {
                $this->tier = null;
                $this->package_id = null;
                $this->selectedClient = null;
            }
        }
        
        if ($property === 'on_behalf_client_id') {
            $client = Client::find($value);
            if ($client) {
                $this->tier = strtolower($client->subscription_tier ?? 'silver');
                $this->package_id = $client->package_id ?? null;
                $this->selectedClient = $client;
            } else {
                $this->tier = null;
                $this->package_id = null;
                $this->selectedClient = null;
            }
        }
        
        if ($property === 'on_behalf_maid_id') {
            $maid = Maid::find($value);
            if ($maid) {
                $this->selectedMaid = $maid;
            } else {
                $this->selectedMaid = null;
            }
        }
        
        if ($property === 'package_id') {
            $package = Package::find($value);
            if ($package) {
                $this->tier = strtolower($package->tier);
            }
        }
        
        if ($property === 'on_behalf_type') {
            $this->on_behalf_client_id = null;
            $this->on_behalf_maid_id = null;
            $this->selectedClient = null;
            $this->selectedMaid = null;
        }
    }

    public function updatePriorityPreview()
    {
        $priority = $this->priority;
        $tier = $this->tier ?? 'silver';
        
        // Priority boost matrix
        $boostMatrix = [
            'platinum' => [
                'low' => 'medium',
                'medium' => 'high',
                'high' => 'urgent',
                'urgent' => 'critical',
            ],
            'gold' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'urgent',
            ],
            'silver' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'high',
            ],
        ];
        
        $boosted = $boostMatrix[$tier][$priority] ?? $priority;
        $this->auto_boosted_priority = $boosted;
        $this->priority_preview = $boosted;
        
        // Calculate SLA times
        $this->calculateSLAPreview($tier, $boosted);
    }
    
    public function calculateSLAPreview($tier, $priority)
    {
        $slaMatrix = [
            'platinum' => [
                'critical' => ['response' => 5, 'resolution' => 30],
                'urgent' => ['response' => 10, 'resolution' => 60],
                'high' => ['response' => 15, 'resolution' => 120],
                'medium' => ['response' => 30, 'resolution' => 240],
                'low' => ['response' => 30, 'resolution' => 240],
            ],
            'gold' => [
                'urgent' => ['response' => 15, 'resolution' => 120],
                'high' => ['response' => 30, 'resolution' => 240],
                'medium' => ['response' => 60, 'resolution' => 480],
                'low' => ['response' => 120, 'resolution' => 1440],
            ],
            'silver' => [
                'high' => ['response' => 60, 'resolution' => 720],
                'medium' => ['response' => 120, 'resolution' => 1440],
                'low' => ['response' => 240, 'resolution' => 2880],
            ],
        ];
        
        $sla = $slaMatrix[$tier][$priority] ?? ['response' => 120, 'resolution' => 1440];
        $this->slaResponseTime = $sla['response'];
        $this->slaResolutionTime = $sla['resolution'];
    }

    public function submit()
    {
        $this->validate();

        $user = Auth::user();
        
        // Create ticket with all the advanced features
        $ticket = Ticket::create([
            'type' => $this->type,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'requester_id' => $user->id,
            'requester_type' => $user->role,
            'created_on_behalf_of' => $this->on_behalf_type === 'client' ? $this->on_behalf_client_id : 
                                     ($this->on_behalf_type === 'maid' ? $this->on_behalf_maid_id : null),
            'created_on_behalf_type' => $this->on_behalf_type === 'client' ? 'client' : 
                                      ($this->on_behalf_type === 'maid' ? 'maid' : null),
            'client_id' => $this->related_client_id,
            'maid_id' => $this->related_maid_id,
            'booking_id' => $this->related_booking_id,
            'deployment_id' => $this->related_deployment_id,
            'package_id' => $this->package_id,
            'location_address' => $this->location_address,
            'location_lat' => $this->location_lat,
            'location_lng' => $this->location_lng,
            'status' => 'open',
        ]);

        // Handle file attachments
        if (is_array($this->attachments) && count($this->attachments)) {
            foreach ($this->attachments as $file) {
                if ($file) {
                    $path = $file->store('tickets/' . $ticket->id, 'public');
                    TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'uploaded_by' => $user->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }

        // TODO: Send notifications
        // - Notify assigned staff
        // - Notify client if created on behalf
        // - Send email/SMS notifications

        $prefix = $user->role === 'trainer' ? 'trainer.' : '';
        session()->flash('success', 'Ticket created successfully! Ticket #' . $ticket->ticket_number);
        return redirect()->route($prefix . 'tickets.show', $ticket);
    }

    public function render()
    {
        return view('livewire.tickets.create');
    }
}
