<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Maid;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public Booking $booking;
    
    // Original V3.0 fields
    public $maid_id;
    public $start_date;
    public $end_date;
    public $status;
    public $notes;
    
    // Section 1: Contact Information
    public $full_name;
    public $phone;
    public $email;
    public $country;
    public $city;
    public $division;
    public $parish;
    public $national_id;
    public $existing_national_id_path;
    
    // Section 2: Home & Environment
    public $home_type;
    public $bedrooms;
    public $bathrooms;
    public $outdoor_responsibilities = [];
    public $appliances = [];
    
    // Section 3: Household Composition
    public $adults;
    public $has_children;
    public $children_ages;
    public $has_elderly;
    public $pets;
    public $pet_kind;
    public $language;
    public $language_other;
    
    // Section 4: Job Role & Expectations
    public $service_tier;
    public $service_mode;
    public $work_days = [];
    public $working_hours;
    public $responsibilities = [];
    public $cuisine_type;
    public $atmosphere;
    public $manage_tasks;
    public $unspoken_rules;
    public $anything_else;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['maid']);
        $this->authorize('update', $this->booking);

        // Load all fields
        $this->maid_id = $this->booking->maid_id;
        $this->start_date = $this->booking->start_date?->format('Y-m-d');
        $this->end_date = $this->booking->end_date?->format('Y-m-d');
        $this->status = $this->booking->status;
        $this->notes = $this->booking->notes;
        
        // Section 1: Contact
        $this->full_name = $this->booking->full_name;
        $this->phone = $this->booking->phone;
        $this->email = $this->booking->email;
        $this->country = $this->booking->country;
        $this->city = $this->booking->city;
        $this->division = $this->booking->division;
        $this->parish = $this->booking->parish;
        $this->existing_national_id_path = $this->booking->national_id_path;
        
        // Section 2: Home
        $this->home_type = $this->booking->home_type;
        $this->bedrooms = $this->booking->bedrooms;
        $this->bathrooms = $this->booking->bathrooms;
        $this->outdoor_responsibilities = $this->booking->outdoor_responsibilities ?? [];
        $this->appliances = $this->booking->appliances ?? [];
        
        // Section 3: Household
        $this->adults = $this->booking->adults;
        $this->has_children = $this->booking->has_children;
        $this->children_ages = $this->booking->children_ages;
        $this->has_elderly = $this->booking->has_elderly;
        $this->pets = $this->booking->pets;
        $this->pet_kind = $this->booking->pet_kind;
        $this->language = $this->booking->language;
        $this->language_other = $this->booking->language_other;
        
        // Section 4: Job Expectations
        $this->service_tier = $this->booking->service_tier;
        $this->service_mode = $this->booking->service_mode;
        $this->work_days = $this->booking->work_days ?? [];
        $this->working_hours = $this->booking->working_hours;
        $this->responsibilities = $this->booking->responsibilities ?? [];
        $this->cuisine_type = $this->booking->cuisine_type;
        $this->atmosphere = $this->booking->atmosphere;
        $this->manage_tasks = $this->booking->manage_tasks;
        $this->unspoken_rules = $this->booking->unspoken_rules;
        $this->anything_else = $this->booking->anything_else;
    }

    public function rules(): array
    {
        return [
            'maid_id' => 'nullable|exists:maids,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,approved,rejected,confirmed,active,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            
            // Section 1: Contact Information
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'parish' => 'nullable|string|max:100',
            'national_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Section 2: Home & Environment
            'home_type' => 'required|in:house,apartment,villa,mansion,bungalow',
            'bedrooms' => 'required|integer|min:1|max:20',
            'bathrooms' => 'required|integer|min:1|max:20',
            'outdoor_responsibilities' => 'nullable|array',
            'appliances' => 'nullable|array',
            
            // Section 3: Household Composition
            'adults' => 'required|integer|min:1|max:20',
            'has_children' => 'required|in:Yes,No',
            'children_ages' => 'nullable|string|max:100',
            'has_elderly' => 'required|in:Yes,No',
            'pets' => 'required|in:Yes,No',
            'pet_kind' => 'nullable|string|max:100',
            'language' => 'required|in:english,luganda,swahili,other',
            'language_other' => 'nullable|string|max:50',
            
            // Section 4: Job Role & Expectations
            'service_tier' => 'required|in:silver,gold,platinum',
            'service_mode' => 'required|in:live-in,live-out',
            'work_days' => 'required|array|min:1',
            'working_hours' => 'nullable|string|max:50',
            'responsibilities' => 'required|array|min:1',
            'cuisine_type' => 'nullable|string|max:100',
            'atmosphere' => 'required|in:formal,casual,flexible',
            'manage_tasks' => 'required|in:Yes,No',
            'unspoken_rules' => 'nullable|string|max:1000',
            'anything_else' => 'nullable|string|max:1000',
        ];
    }

    public function update(): void
    {
        $this->validate();

        $data = [
            'maid_id' => $this->maid_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'notes' => $this->notes,
            
            // Section 1: Contact
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            
            // Section 2: Home
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities,
            'appliances' => $this->appliances,
            
            // Section 3: Household
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,
            
            // Section 4: Job Expectations
            'service_tier' => $this->service_tier,
            'service_mode' => $this->service_mode,
            'work_days' => $this->work_days,
            'working_hours' => $this->working_hours,
            'responsibilities' => $this->responsibilities,
            'cuisine_type' => $this->cuisine_type,
            'atmosphere' => $this->atmosphere,
            'manage_tasks' => $this->manage_tasks,
            'unspoken_rules' => $this->unspoken_rules,
            'anything_else' => $this->anything_else,
        ];

        // Handle file upload if new file provided
        if ($this->national_id) {
            $path = $this->national_id->store('national-ids', 'public');
            $data['national_id_path'] = $path;
            
            // Delete old file if exists
            if ($this->existing_national_id_path) {
                \Storage::disk('public')->delete($this->existing_national_id_path);
            }
        }
        
        $this->booking->update($data);

        session()->flash('success', __('Booking updated successfully.'));
        $this->redirect(route('bookings.show', $this->booking), navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $maids = Maid::orderBy('first_name')->get();

        return view('livewire.bookings.edit', [
            'maids' => $maids,
            'title' => __('Edit Booking #') . $this->booking->id,
        ]);
    }
}
