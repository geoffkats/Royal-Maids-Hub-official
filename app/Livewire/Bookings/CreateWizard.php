<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Maid;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateWizard extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Mode detection
    public ?Booking $booking = null; // If set, we're editing
    public bool $isEditing = false;

    // Wizard state
    public int $currentStep = 1;
    public int $totalSteps = 4;
    public bool $showValidationModal = false;

    // Original V3.0 fields
    public $client_id = '';
    public $maid_id = '';
    public $booking_type = 'long-term';
    public $start_date = '';
    public $end_date = '';
    public $status = 'pending';
    public $notes = '';

    // Section 1: Contact Information
    public $full_name = '';
    public $phone = '';
    public $email = '';
    public $country = 'Uganda';
    public $city = '';
    public $division = '';
    public $parish = '';
    public $national_id; // File upload
    public $national_id_path = '';
    public $existing_national_id_path = '';

    // Section 2: Home & Environment
    public $home_type = '';
    public $bedrooms = 0;
    public $bathrooms = 0;
    public $outdoor_responsibilities = [];
    public $appliances = [];

    // Section 3: Household Composition
    public $adults = 1;
    public $has_children = 'No';
    public $children_ages = '';
    public $has_elderly = 'No';
    public $pets = 'No';
    public $pet_kind = '';
    public $language = '';
    public $language_other = '';

    // Section 4: Job Role & Expectations
    public $service_tier = '';
    public $service_mode = '';
    public $work_days = [];
    public $working_hours = '';
    public $responsibilities = [];
    public $cuisine_type = '';
    public $atmosphere = '';
    public $manage_tasks = '';
    public $unspoken_rules = '';
    public $anything_else = '';

    public function mount(?Booking $booking = null): void
    {
        // Determine if we're editing
        if ($booking && $booking->exists) {
            $this->isEditing = true;
            $this->booking = $booking->load(['maid']);
            $this->authorize('update', $this->booking);
            $this->loadBookingData();
        } else {
            $this->authorize('create', Booking::class);
            $this->loadClientData();
        }
    }

    /**
     * Load existing booking data for editing
     */
    protected function loadBookingData(): void
    {
        // Load all fields from existing booking
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

    /**
     * Load client data for new bookings
     */
    protected function loadClientData(): void
    {
        
        // If user is a client, pre-populate contact information
        if (auth()->user()->role === 'client') {
            $client = Client::where('user_id', auth()->id())->first();
            
            // Create client record if it doesn't exist
            if (!$client) {
                $client = Client::create([
                    'user_id' => auth()->id(),
                    'contact_person' => auth()->user()->name,
                    'phone' => '',
                    'address' => '',
                    'subscription_tier' => 'basic',
                    'status' => 'active',
                ]);
            }
            
            if ($client) {
                $this->client_id = $client->id;
                $this->full_name = $client->contact_person ?? auth()->user()->name;
                $this->phone = $client->phone ?? '';
                $this->email = $client->user->email ?? auth()->user()->email;
            }
        }
    }

    /**
     * Move to next step after validation
     */
    public function nextStep(): void
    {
        try {
            $this->validateCurrentStep();
            
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showValidationModal = true;
            throw $e;
        }
    }

    /**
     * Move to previous step
     */
    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Close validation modal
     */
    public function closeValidationModal(): void
    {
        $this->showValidationModal = false;
    }

    /**
     * Go directly to a specific step (if already validated)
     */
    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep + 1) {
            $this->currentStep = $step;
        }
    }

    /**
     * Validate current step before proceeding
     */
    protected function validateCurrentStep(): void
    {
        $rules = match ($this->currentStep) {
            1 => $this->getStep1Rules(),
            2 => $this->getStep2Rules(),
            3 => $this->getStep3Rules(),
            4 => $this->getStep4Rules(),
            default => [],
        };

        $this->validate($rules);
    }

    /**
     * Step 1: Contact Information validation rules
     */
    protected function getStep1Rules(): array
    {
        return [
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'country' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'division' => 'required|string|max:50',
            'parish' => 'required|string|max:50',
            'national_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ];
    }

    /**
     * Step 2: Home & Environment validation rules
     */
    protected function getStep2Rules(): array
    {
        return [
            'home_type' => 'required|string|max:50',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'outdoor_responsibilities' => 'nullable|array',
            'appliances' => 'nullable|array',
        ];
    }

    /**
     * Step 3: Household Composition validation rules
     */
    protected function getStep3Rules(): array
    {
        return [
            'adults' => 'required|integer|min:1',
            'has_children' => 'required|in:Yes,No',
            'children_ages' => 'required_if:has_children,Yes|nullable|string|max:255',
            'has_elderly' => 'required|in:Yes,No',
            'pets' => 'required|string|max:100',
            'pet_kind' => 'required_if:pets,Yes with duties,Yes no duties|nullable|string|max:100',
            'language' => 'required|string|max:50',
            'language_other' => 'required_if:language,Other|nullable|string|max:100',
        ];
    }

    /**
     * Step 4: Job Role & Expectations validation rules
     */
    protected function getStep4Rules(): array
    {
        return [
            'service_tier' => 'required|in:Silver,Gold,Platinum',
            'service_mode' => 'required|in:Live-in,Live-out',
            'work_days' => 'required|array|min:1',
            'working_hours' => 'required|string|max:100',
            'responsibilities' => 'required|array|min:1',
            'cuisine_type' => 'required|string|max:50',
            'atmosphere' => 'required|string|max:50',
            'manage_tasks' => 'required|string|max:100',
            'unspoken_rules' => 'nullable|string|max:1000',
            'anything_else' => 'nullable|string|max:2000',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }

    /**
     * Submit the complete booking form
     */
    public function submit(): void
    {
        // Validate all steps
        $this->validate(array_merge(
            $this->getStep1Rules(),
            $this->getStep2Rules(),
            $this->getStep3Rules(),
            $this->getStep4Rules()
        ));

        if ($this->isEditing) {
            $this->updateBooking();
        } else {
            $this->createBooking();
        }
    }

    /**
     * Create a new booking
     */
    protected function createBooking(): void
    {
        // Handle file upload
        if ($this->national_id) {
            $this->national_id_path = $this->national_id->store('national-ids', 'public');
        }

        // Ensure we have a client_id (create client if needed)
        if (!$this->client_id) {
            // Check if a client exists with this email
            $user = \App\Models\User::where('email', $this->email)->first();
            
            if ($user && $user->role === 'client') {
                $client = Client::where('user_id', $user->id)->first();
                if ($client) {
                    $this->client_id = $client->id;
                }
            }
            
            // If still no client, create one
            if (!$this->client_id) {
                // Create user account if doesn't exist
                if (!$user) {
                    $user = \App\Models\User::create([
                        'name' => $this->full_name,
                        'email' => $this->email,
                        'password' => bcrypt(str()->random(16)), // Random password
                        'role' => 'client',
                        'email_verified_at' => now(),
                    ]);
                }
                
                // Create client record
                $client = Client::create([
                    'user_id' => $user->id,
                    'contact_person' => $this->full_name,
                    'phone' => $this->phone,
                    'address' => "{$this->division}, {$this->city}, {$this->country}",
                    'city' => $this->city,
                    'district' => $this->division,
                    'subscription_tier' => 'basic',
                    'subscription_status' => 'active',
                ]);
                
                $this->client_id = $client->id;
            }
        }

        // Create booking with all data
        $booking = Booking::create([
            // Original V3.0 fields
            'client_id' => $this->client_id,
            'maid_id' => $this->maid_id ?: null,
            'booking_type' => $this->booking_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'pending', // Always start as pending
            'notes' => $this->notes,

            // Section 1: Contact Information
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            'national_id_path' => $this->national_id_path,

            // Section 2: Home & Environment
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities,
            'appliances' => $this->appliances,

            // Section 3: Household Composition
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,

            // Section 4: Job Role & Expectations
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
        ]);

        // Update client counters if client_id exists
        if ($this->client_id) {
            $client = Client::find($this->client_id);
            if ($client) {
                $client->increment('total_bookings');
                $client->increment('active_bookings');
            }
        }

        session()->flash('success', __('Booking request submitted successfully! We will review and assign a maid soon.'));
        $this->redirect(route('bookings.show', $booking), navigate: true);
    }

    /**
     * Update existing booking
     */
    protected function updateBooking(): void
    {
        $data = $this->getBookingData();

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

        session()->flash('success', __('Booking updated successfully!'));
        $this->redirect(route('bookings.show', $this->booking), navigate: true);
    }

    /**
     * Get booking data array
     */
    protected function getBookingData(): array
    {
        return [
            // Original V3.0 fields
            'client_id' => $this->client_id,
            'maid_id' => $this->maid_id ?: null,
            'booking_type' => $this->booking_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->isEditing ? $this->status : 'pending',
            'notes' => $this->notes,

            // Section 1: Contact Information
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            'national_id_path' => $this->national_id_path ?: $this->existing_national_id_path,

            // Section 2: Home & Environment
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities,
            'appliances' => $this->appliances,

            // Section 3: Household Composition
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,

            // Section 4: Job Role & Expectations
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
    }

    /**
     * Save as draft (optional feature)
     */
    public function saveDraft(): void
    {
        // TODO: Implement draft saving functionality
        session()->flash('info', __('Draft saved successfully.'));
    }

    /**
     * Get progress percentage (Livewire computed property)
     */
    public function getProgressPercentageProperty(): int
    {
        return (int) (($this->currentStep / $this->totalSteps) * 100);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $maids = Maid::where('status', 'available')->orderBy('first_name')->get();

        return view('livewire.bookings.create-wizard', [
            'maids' => $maids,
            'title' => $this->isEditing 
                ? __('Edit Booking #') . $this->booking->id 
                : __('Create New Booking Request'),
        ]);
    }
}
