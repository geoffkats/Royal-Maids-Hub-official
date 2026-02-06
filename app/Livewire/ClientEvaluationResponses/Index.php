<?php

namespace App\Livewire\ClientEvaluationResponses;

use App\Mail\ClientEvaluationLinkMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientEvaluationLink;
use App\Models\ClientEvaluationResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL as UrlGenerator;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 15;

    public string $activeTab = 'feedback';
    public ?int $selectedClientId = null;
    public int $linkExpiresInDays = 30;

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function sendClientEvaluationLink(int $bookingId): void
    {
        if (!auth()->user()->isAdminLike()) {
            abort(403);
        }

        $this->validate([
            'selectedClientId' => ['required', 'exists:clients,id'],
        ]);

        $booking = Booking::query()
            ->with(['client.user', 'maid', 'package'])
            ->where('id', $bookingId)
            ->where('client_id', $this->selectedClientId)
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

        $url = UrlGenerator::temporarySignedRoute('client-evaluations.public', $expiresAt, [
            'token' => $token,
        ]);

        Mail::to($recipient)->send(new ClientEvaluationLinkMail($booking, $link, $url));

        session()->flash('success', __('Client evaluation link sent successfully.'));
    }

    public function render()
    {
        $this->authorize('viewAny', ClientEvaluationResponse::class);

        $term = trim($this->search);
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        $responses = ClientEvaluationResponse::query()
            ->with(['booking', 'client.user', 'maid', 'package'])
            ->when($term !== '', function ($query) use ($like) {
                $query->where('respondent_name', 'like', $like)
                    ->orWhere('respondent_email', 'like', $like)
                    ->orWhereHas('client.user', fn ($q) => $q->where('name', 'like', $like))
                    ->orWhereHas('maid', fn ($q) => $q->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like));
            })
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));

        $clients = Client::query()
            ->with('user')
            ->orderBy('contact_person')
            ->get();

        $clientBookings = $this->selectedClientId
            ? Booking::query()
                ->with(['maid', 'package'])
                ->where('client_id', $this->selectedClientId)
                ->latest('start_date')
                ->get()
            : collect();

        return view('livewire.client-evaluation-responses.index', [
            'responses' => $responses,
            'clients' => $clients,
            'clientBookings' => $clientBookings,
        ])->layout('components.layouts.app', ['title' => __('Client Feedback')]);
    }
}
