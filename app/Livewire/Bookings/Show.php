<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\ClientEvaluationLink;
use App\Mail\ClientEvaluationLinkMail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['maid', 'client.user', 'package']);
        $this->authorize('view', $this->booking);
    }

    public function sendClientEvaluationLink(): void
    {
        $recipient = $this->booking->email ?: $this->booking->client?->user?->email;

        if (!$recipient) {
            session()->flash('error', __('Client email not found for this booking.'));
            return;
        }

        $expiresAt = now()->addDays(30);
        $token = Str::random(40);

        $link = ClientEvaluationLink::create([
            'booking_id' => $this->booking->id,
            'token' => $token,
            'status' => 'active',
            'sent_at' => now(),
            'expires_at' => $expiresAt,
            'sent_by' => auth()->id(),
        ]);

        $url = URL::temporarySignedRoute('client-evaluations.public', $expiresAt, [
            'token' => $token,
        ]);

        Mail::to($recipient)->send(new ClientEvaluationLinkMail($this->booking, $link, $url));

        session()->flash('success', __('Client evaluation link sent successfully.'));
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.bookings.show', [
            'title' => __('Booking #') . $this->booking->id,
        ]);
    }
}
