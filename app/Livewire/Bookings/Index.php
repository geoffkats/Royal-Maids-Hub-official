<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $booking_type = '';

    #[Url]
    public int $perPage = 15;

    public array $statusOptions = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
    public array $typeOptions = ['brokerage', 'long-term', 'part-time', 'full-time'];

    public function mount(): void
    {
        $this->authorize('viewAny', Booking::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingBookingType(): void
    {
        $this->resetPage();
    }

    public function delete(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->authorize('delete', $booking);

        $booking->delete();

        session()->flash('success', __('Booking deleted successfully.'));
        
        // Reset to first page if current page becomes empty after deletion
        if ($this->getPage() > 1 && $this->queryBookings()->isEmpty()) {
            $this->resetPage();
        }
    }

    public function updateStatus(int $bookingId, string $newStatus): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->authorize('update', $booking);

        $booking->update(['status' => $newStatus]);

        session()->flash('success', __('Booking status updated successfully.'));
    }

    protected function queryBookings()
    {
        $user = auth()->user();
        
        $query = Booking::query()
            ->with(['client.user', 'maid'])
            ->latest();

        // If user is a client, only show their bookings
        if ($user->role === 'client') {
            $query->whereHas('client', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('client', function ($clientQuery) {
                    $clientQuery->where('contact_person', 'like', "%{$this->search}%")
                        ->orWhere('company_name', 'like', "%{$this->search}%");
                })
                ->orWhereHas('maid', function ($maidQuery) {
                    $maidQuery->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%");
                });
            });
        }

        // Apply status filter
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Apply booking type filter
        if ($this->booking_type) {
            $query->where('booking_type', $this->booking_type);
        }

        return $query;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $bookings = $this->queryBookings()->paginate($this->perPage);

        return view('livewire.bookings.index', [
            'bookings' => $bookings,
            'title' => __('Bookings'),
        ]);
    }
}
