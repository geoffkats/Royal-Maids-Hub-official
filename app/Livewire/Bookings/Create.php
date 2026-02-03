<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Maid;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $client_id = '';
    public $maid_id = '';
    public $booking_type = '';
    public $start_date = '';
    public $end_date = '';
    public $amount = '';
    public $notes = '';

    public function mount(): void
    {
        $this->authorize('create', Booking::class);
        
        // If user is a client, pre-select their client profile
        if (auth()->user()->role === 'client') {
            $client = Client::where('user_id', auth()->id())->first();
            if ($client) {
                $this->client_id = $client->id;
            }
        }
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'maid_id' => 'required|exists:maids,id',
            'booking_type' => 'required|in:brokerage,long-term,part-time,full-time',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $booking = Booking::create([
            'client_id' => $this->client_id,
            'maid_id' => $this->maid_id,
            'booking_type' => $this->booking_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        // Update client counters
        $client = Client::find($this->client_id);
        $client->increment('total_bookings');
        $client->increment('active_bookings');

        session()->flash('success', __('Booking created successfully.'));
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
        $this->redirect(route($prefix . 'bookings.index'), navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $clients = auth()->user()->role === 'admin' 
            ? Client::with('user')->orderBy('contact_person')->get()
            : Client::where('user_id', auth()->id())->get();
            
        $maids = Maid::where('status', 'available')->orderBy('first_name')->get();

        return view('livewire.bookings.create', [
            'clients' => $clients,
            'maids' => $maids,
            'title' => __('Create Booking'),
        ]);
    }
}
