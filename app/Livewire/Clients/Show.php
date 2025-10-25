<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Client $client;

    public function mount(Client $client): void
    {
        $this->client = $client->load('user');
        $this->authorize('view', $this->client);
    }

    public function getAccountAgeProperty(): string
    {
        $created = $this->client->created_at;
        if (!$created) {
            return __('N/A');
        }

        $now = now();

        $days = $created->diffInDays($now);
        if ($days < 30) {
            return trans_choice(':count day|:count days', $days, ['count' => $days]);
        }

        $months = $created->diffInMonths($now);
        if ($months < 12) {
            return trans_choice(':count month|:count months', $months, ['count' => $months]);
        }

        $years = $created->diffInYears($now);
        return trans_choice(':count year|:count years', $years, ['count' => $years]);
    }

    public function getSubscriptionDaysRemainingProperty(): ?int
    {
        if (!$this->client->subscription_end_date) {
            return null;
        }

        $endDate = \Carbon\Carbon::parse($this->client->subscription_end_date);
        $today = now();

        if ($endDate->isPast()) {
            return 0;
        }

        return $today->diffInDays($endDate, false);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $recentBookings = $this->client->bookings()
            ->with('maid')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.clients.show', [
            'title' => $this->client->contact_person,
            'recentBookings' => $recentBookings,
        ]);
    }
}
