<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Maid;
use App\Services\CRM\BookingToLeadService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateWizard extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Mode detection
    public ?Booking $booking = null;
    public bool $isEditing = false;

    // Wizard state
    public int $currentStep = 1;
    public int $totalSteps = 4;
    public bool $showValidationModal = false;

    // Step 1: Contact Information
    public $client_id = '';
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

    // Step 2: Home & Environment
    public $home_type = '';
    public $bedrooms = 0;
    public $bathrooms = 0;
    public $outdoor_responsibilities = [];
    public $appliances = [];

    // Step 3: Household Composition
    public $adults = 1;
    public $has_children = 'No';
    public $children_ages = '';
    public $has_elderly = 'No';
    public $pets = 'No';
    public $pet_kind = '';
    public $language = '';
    public $language_other = '';

    // Step 4: Job Role & Expectations
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

    // Basic booking fields
    public $maid_id = '';
    public $booking_type = 'long-term';
    public $start_date = '';
    public $end_date = '';
    public $status = 'pending';
    public $notes = '';

    public function mount(?Booking $booking = null): void
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error in CreateWizard mount: ' . $e->getMessage());
            // Don't rethrow the exception, let the component load
        }
    }

    /**
     * Load existing booking data for editing
     */
    protected function loadBookingData(): void
    {
        if (!$this->booking) return;

        // Load basic booking data
        $this->maid_id = $this->booking->maid_id;
        $this->booking_type = $this->booking->booking_type;
        $this->start_date = $this->booking->start_date?->format('Y-m-d');
        $this->end_date = $this->booking->end_date?->format('Y-m-d');
        $this->notes = $this->booking->notes;

        // National ID
        $this->existing_national_id_path = $this->booking->national_id_path ?? '';
        $this->national_id_path = $this->existing_national_id_path;

        // Load client data
        $client = $this->booking->client;
        if ($client) {
            $this->client_id = $client->id;
            $this->full_name = $client->contact_person;
            $this->phone = $client->phone;
            $this->email = $client->user?->email ?? '';
            $this->city = $client->city ?? '';
            $this->division = $client->district ?? '';
            // parish stored on booking snapshot if provided later
        }

        // Fallback to booking snapshot values if available
        $this->full_name = $this->full_name ?: ($this->booking->full_name ?? '');
        $this->phone = $this->phone ?: ($this->booking->phone ?? '');
        $this->email = $this->email ?: ($this->booking->email ?? '');
        $this->country = $this->booking->country ?? $this->country;
        $this->city = $this->city ?: ($this->booking->city ?? '');
        $this->division = $this->division ?: ($this->booking->division ?? '');
        $this->parish = $this->booking->parish ?? '';

        // Home & environment
        $this->home_type = $this->booking->home_type ?? '';
        $this->bedrooms = $this->booking->bedrooms ?? 0;
        $this->bathrooms = $this->booking->bathrooms ?? 0;
        $this->outdoor_responsibilities = is_array($this->booking->outdoor_responsibilities) ? $this->booking->outdoor_responsibilities : [];
        $this->appliances = is_array($this->booking->appliances) ? $this->booking->appliances : [];

        // Household
        $this->adults = $this->booking->adults ?? 1;
        $this->has_children = $this->booking->has_children ?? 'No';
        $this->children_ages = $this->booking->children_ages ?? '';
        $this->has_elderly = $this->booking->has_elderly ?? 'No';
        $this->pets = $this->booking->pets ?? 'No';
        $this->pet_kind = $this->booking->pet_kind ?? '';
        $this->language = $this->booking->language ?? '';
        $this->language_other = $this->booking->language_other ?? '';

        // Expectations
        $this->service_tier = $this->booking->service_tier ?? '';
        $this->service_mode = $this->booking->service_mode ?? '';
        $this->work_days = is_array($this->booking->work_days) ? $this->booking->work_days : [];
        $this->working_hours = $this->booking->working_hours ?? '';
        $this->responsibilities = is_array($this->booking->responsibilities) ? $this->booking->responsibilities : [];
        $this->atmosphere = $this->booking->atmosphere ?? '';
        $this->unspoken_rules = $this->booking->unspoken_rules ?? '';
        $this->anything_else = $this->booking->anything_else ?? '';
        $this->status = $this->booking->status ?? $this->status;
    }

    /**
     * Load client data for new bookings
     */
    protected function loadClientData(): void
    {
        try {
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
                    $this->email = $client->user?->email ?? auth()->user()->email;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error in loadClientData: ' . $e->getMessage());
        }
    }

    /**
     * Move to next step after validation
     */
    public function nextStep(): void
    {
        if ($this->validateCurrentStep()) {
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
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
     * Jump to specific step
     */
    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep + 1) {
            $this->currentStep = $step;
        }
    }

    /**
     * Validate current step
     */
    protected function validateCurrentStep(): bool
    {
        try {
            switch ($this->currentStep) {
                case 1:
                    $this->validate([
                        'full_name' => 'required|string|max:255',
                        'phone' => 'required|string|max:20',
                        'email' => 'required|email|max:255',
                        'city' => 'required|string|max:255',
                        'division' => 'required|string|max:255',
                    ]);
                    break;
                case 2:
                    $this->validate([
                        'home_type' => 'required|string',
                        'bedrooms' => 'required|integer|min:0',
                        'bathrooms' => 'required|integer|min:0',
                    ]);
                    break;
                case 3:
                    $this->validate([
                        'adults' => 'required|integer|min:1',
                        'has_children' => 'required|in:Yes,No',
                        'has_elderly' => 'required|in:Yes,No',
                        'pets' => 'required|in:Yes,No',
                    ]);
                    break;
                case 4:
                    $this->validate([
                        'service_tier' => 'required|string',
                        'service_mode' => 'required|string',
                        'working_hours' => 'required|string',
                        'work_days' => 'required|array|min:1',
                    ]);
                    break;
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Validation error in step ' . $this->currentStep . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Submit the final form
     */
    public function submit(): void
    {
        try {
            // Validate all steps
            if (!$this->validateAllSteps()) {
                $this->showValidationModal = true;
                return;
            }

            // Create or update booking
            if ($this->isEditing) {
                $this->updateBooking();
            } else {
                $this->createBooking();
            }

            session()->flash('success', $this->isEditing ? __('Booking updated successfully.') : __('Booking created successfully.'));
            $this->redirect(route('bookings.index'), navigate: true);
        } catch (\Exception $e) {
            \Log::error('Error in submit: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while saving the booking.');
        }
    }

    /**
     * Validate all steps
     */
    protected function validateAllSteps(): bool
    {
        try {
            $this->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'city' => 'required|string|max:255',
                'division' => 'required|string|max:255',
                'home_type' => 'required|string',
                'bedrooms' => 'required|integer|min:0',
                'bathrooms' => 'required|integer|min:0',
                'adults' => 'required|integer|min:1',
                'has_children' => 'required|in:Yes,No',
                'has_elderly' => 'required|in:Yes,No',
                'pets' => 'required|in:Yes,No',
                'service_tier' => 'required|string',
                'service_mode' => 'required|string',
                'working_hours' => 'required|string',
                'work_days' => 'required|array|min:1',
                'maid_id' => 'required|exists:maids,id',
                'booking_type' => 'required|in:brokerage,long-term,part-time,full-time',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'nullable|date|after:start_date',
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Validation error in submit: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new booking using lead-first approach
     */
    protected function createBooking(): void
    {
        // Prepare booking data for lead service
        $bookingData = [
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            'address' => $this->parish ?? $this->city,
        ];

        // Use BookingToLeadService to find or create lead
        $bookingToLeadService = app(BookingToLeadService::class);
        $lead = $bookingToLeadService->findOrCreateLeadFromBooking($bookingData);

        // Determine client_id
        $clientId = null;
        
        // If client_id was explicitly selected by admin/staff, use it
        if (!empty($this->client_id)) {
            $clientId = $this->client_id;
            // Link lead to this client if not already linked
            if (!$lead->client_id) {
                $lead->update(['client_id' => $clientId, 'status' => 'qualified']);
            }
        } elseif ($lead->client_id) {
            // Lead is already linked to a client (from duplicate detection)
            $clientId = $lead->client_id;
        }
        
        // Create booking linked to lead (and optionally client)
        Booking::create([
            'lead_id' => $lead->id, // Link to lead
            'client_id' => $clientId, // May be null initially, set when lead converts to client
            'maid_id' => $this->maid_id,
            'booking_type' => $this->booking_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status ?? 'pending',
            'notes' => $this->notes,

            // Contact snapshot
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,

            // Home & environment
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities,
            'appliances' => $this->appliances,

            // Household
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,

            // Expectations
            'service_tier' => $this->service_tier,
            'service_mode' => $this->service_mode,
            'work_days' => $this->work_days,
            'working_hours' => $this->working_hours,
            'responsibilities' => $this->responsibilities,
            'atmosphere' => $this->atmosphere,
            'unspoken_rules' => $this->unspoken_rules,
            'anything_else' => $this->anything_else,
        ]);
    }

    /**
     * Update existing booking
     */
    protected function updateBooking(): void
    {
        if (!$this->booking) return;

        // Update client
        $this->createOrUpdateClient();
        
        // Update booking
        $this->booking->update([
            'maid_id' => $this->maid_id,
            'booking_type' => $this->booking_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status ?? $this->booking->status,
            'notes' => $this->notes,

            // Contact snapshot
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,

            // Home & environment
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities,
            'appliances' => $this->appliances,

            // Household
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,

            // Expectations
            'service_tier' => $this->service_tier,
            'service_mode' => $this->service_mode,
            'work_days' => $this->work_days,
            'working_hours' => $this->working_hours,
            'responsibilities' => $this->responsibilities,
            'atmosphere' => $this->atmosphere,
            'unspoken_rules' => $this->unspoken_rules,
            'anything_else' => $this->anything_else,
        ]);
    }

    /**
     * Create or update client
     */
    protected function createOrUpdateClient(): Client
    {
        $clientData = [
            'contact_person' => $this->full_name,
            'phone' => $this->phone,
            'city' => $this->city,
            'district' => $this->division,
        ];

        if ($this->isEditing && $this->booking) {
            $client = $this->booking->client;
            $client->update($clientData);
            return $client;
        } else {
            $clientData['user_id'] = auth()->id();
            $clientData['subscription_tier'] = 'basic';
            $clientData['status'] = 'active';
            return Client::create($clientData);
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
     * Get progress percentage
     */
    public function getProgressPercentageProperty(): int
    {
        return (int) (($this->currentStep / $this->totalSteps) * 100);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        try {
            $clients = auth()->user()->role === 'admin' 
                ? Client::with('user')->orderBy('contact_person')->get()
                : Client::where('user_id', auth()->id())->get();
                
            $maids = Maid::where('status', 'available')->orderBy('first_name')->get();

            return view('livewire.bookings.create-wizard', [
                'clients' => $clients,
                'maids' => $maids,
                'title' => $this->isEditing 
                    ? __('Edit Booking #') . $this->booking->id 
                    : __('Create New Booking Request'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Render error in CreateWizard: ' . $e->getMessage());
            
            // Return a simple error view
            return view('livewire.bookings.create-wizard', [
                'clients' => collect(),
                'maids' => collect(),
                'title' => __('Create New Booking Request'),
                'error' => $e->getMessage(),
            ]);
        }
    }
}