<?php

namespace App\Livewire\Clients;

use App\Mail\ClientEvaluationLinkMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientEvaluationLink;
use App\Models\ClientEvaluationResponse;
use App\Models\Deployment;
use App\Models\MaidContract;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public Client $client;
    public string $activeTab = 'overview';
    public bool $showSendEvaluationModal = false;
    public ?int $selectedBookingId = null;
    public int $linkExpiresInDays = 30;

    public function mount(Client $client): void
    {
        $this->client = $client->load(['user', 'package']);
        $this->authorize('view', $this->client);
    }

    public function restore(): void
    {
        $this->authorize('restore', $this->client);
        
        $this->client->restore();
        $this->client->refresh();
        
        session()->flash('success', 'Client restored successfully.');
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    #[Computed]
    public function accountAge(): string
    {
        $created = $this->client->created_at;
        if (!$created) {
            return __('N/A');
        }

        $now = now();

        $days = (int) $created->diffInDays($now);
        if ($days < 30) {
            return $days . ' ' . ($days !== 1 ? 'days' : 'day');
        }

        $months = (int) $created->diffInMonths($now);
        if ($months < 12) {
            return $months . ' ' . ($months !== 1 ? 'months' : 'month');
        }

        $years = (int) $created->diffInYears($now);
        return $years . ' ' . ($years !== 1 ? 'years' : 'year');
    }
    
    public function openSendEvaluationModal(): void
    {
        if (!auth()->user()->isAdminLike()) {
            abort(403);
        }
        
        $this->showSendEvaluationModal = true;
    }
    
    public function sendClientEvaluationLink(): void
    {
        if (!auth()->user()->isAdminLike()) {
            abort(403);
        }
        
        $this->validate([
            'selectedBookingId' => ['required', 'exists:bookings,id'],
        ]);
        
        $booking = Booking::query()
            ->with(['client.user', 'maid', 'package'])
            ->where('id', $this->selectedBookingId)
            ->where('client_id', $this->client->id)
            ->firstOrFail();
        
        $recipient = $booking->email ?: $booking->client?->user?->email;
        
        if (!$recipient) {
            session()->flash('error', __('Client email not found for this booking.'));
            return;
        }
        
        $expiresAt = now()->addDays($this->linkExpiresInDays);
        $token = Str::random(40);
        
        $link = ClientEvaluationLink::create([
            'booking_id' => $booking->id,
            'token' => $token,
            'status' => 'active',
            'sent_at' => now(),
            'expires_at' => $expiresAt,
            'sent_by' => auth()->id(),
        ]);
        
        $url = URL::temporarySignedRoute('client-evaluations.public', $expiresAt, [
            'token' => $token,
        ]);
        
        Mail::to($recipient)->send(new ClientEvaluationLinkMail($booking, $link, $url));
        
        $this->showSendEvaluationModal = false;
        $this->selectedBookingId = null;
        
        session()->flash('success', __('Client evaluation link sent successfully.'));
    }

    #[Computed]
    public function subscriptionDaysRemaining(): ?int
    {
        if (!$this->client->subscription_end_date) {
            return null;
        }

        $endDate = \Carbon\Carbon::parse($this->client->subscription_end_date);
        $today = now();

        if ($endDate->isPast()) {
            return 0;
        }

        return (int) $today->diffInDays($endDate, false);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $recentBookings = $this->client->bookings()
            ->with('maid')
            ->latest()
            ->take(5)
            ->get();
        
        $bookingsForEvaluation = $this->client->bookings()
            ->with('maid')
            ->latest()
            ->get();

        // Get client tickets with SLA information
        $tickets = Ticket::where('client_id', $this->client->id)
            ->with(['requester', 'assignedTo'])
            ->latest()
            ->paginate(10, ['*'], 'ticketsPage');

        $canViewFeedback = Gate::allows('viewAny', ClientEvaluationResponse::class);

        $feedback = $canViewFeedback
            ? ClientEvaluationResponse::query()
                ->where('client_id', $this->client->id)
                ->with(['booking', 'maid', 'package'])
                ->latest('submitted_at')
                ->paginate(10, ['*'], 'feedbackPage')
            : null;

        // Get active deployments for this client
        $deployments = Deployment::where('client_id', $this->client->id)
            ->where('status', 'active')
            ->with(['maid'])
            ->latest()
            ->take(5)
            ->get();

        // Get active contracts for maids deployed at this client
        $deploymentMaidIds = Deployment::where('client_id', $this->client->id)
            ->where('status', 'active')
            ->pluck('maid_id')
            ->unique();

        $contracts = MaidContract::whereIn('maid_id', $deploymentMaidIds)
            ->where('contract_status', 'active')
            ->with(['maid'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.clients.show', [
            'title' => $this->client->contact_person,
            'recentBookings' => $recentBookings,
            'tickets' => $tickets,
            'deployments' => $deployments,
            'contracts' => $contracts,
            'feedback' => $feedback,
            'canViewFeedback' => $canViewFeedback,
            'bookingsForEvaluation' => $bookingsForEvaluation,
        ]);
    }
}
