<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['maid']);
        $this->authorize('view', $this->booking);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.bookings.show', [
            'title' => __('Booking #') . $this->booking->id,
        ]);
    }
}
