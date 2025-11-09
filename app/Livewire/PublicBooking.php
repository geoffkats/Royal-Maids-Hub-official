<?php

namespace App\Livewire;

use App\Services\CRM\BookingToLeadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class PublicBooking extends Component
{
    use WithFileUploads;
    // Wizard state
    public int $currentStep = 1;
    public int $totalSteps = 4;
    public bool $showSuccessMessage = false;

    // Step 1: Contact Information
    public $full_name = '';
    public $phone = '';
    public $email = '';
    public $country = 'Uganda';
    public $city = '';
    public $division = '';
    public $parish = '';
    public $national_id = null;
    public $showValidationModal = false;

    // Step 2: Home Details
    public $village = '';
    public $house_type = '';
    public $number_of_rooms = '';
    public $bedrooms = 0;
    public $bathrooms = 0;
    public $outdoor_responsibilities = [];
    public $appliances = [];
    public $special_requirements = '';

    // Step 3: Household Information
    public $family_size = '';
    public $children_count = 0;
    public $elderly_count = 0;
    public $pets = 'none';
    public $special_needs = '';

    // Step 4: Service Expectations
    public $service_tier = '';
    public $service_mode = '';
    public $work_days = [];
    public $start_date = '';
    public $end_date = '';
    public $package_id = '';
    public $additional_requirements = '';
    public $maid_id = '';
    public $working_hours = '';
    public $responsibilities = [];
    public $cuisine_type = '';
    public $cuisine_other = '';
    public $atmosphere = '';
    public $manage_tasks = '';
    public $unspoken_rules = '';

    public function mount(): void
    {
        // Prefill maid from query string if arriving from maids listing
        $this->maid_id = request('maid_id', $this->maid_id) ?: '';
        // If redirected back with success flag from classic POST
        if (session('booking_submitted')) {
            $this->showSuccessMessage = true;
        }

        // Repopulate fields from old input when validation fails on classic POST
        $old = session()->getOldInput();
        if (!empty($old)) {
            $this->full_name = $old['full_name'] ?? $this->full_name;
            $this->phone = $old['phone'] ?? $this->phone;
            $this->email = $old['email'] ?? $this->email;
            $this->country = $old['country'] ?? $this->country;
            $this->city = $old['city'] ?? $this->city;
            $this->division = $old['division'] ?? $this->division;
            $this->parish = $old['parish'] ?? $this->parish;

            $this->village = $old['village'] ?? $this->village;
            $this->house_type = $old['house_type'] ?? $this->house_type;
            $this->number_of_rooms = $old['number_of_rooms'] ?? $this->number_of_rooms;
            $this->bedrooms = (int)($old['bedrooms'] ?? $this->bedrooms);
            $this->bathrooms = (int)($old['bathrooms'] ?? $this->bathrooms);
            $this->outdoor_responsibilities = isset($old['outdoor_responsibilities']) ? (array)$old['outdoor_responsibilities'] : $this->outdoor_responsibilities;
            $this->appliances = isset($old['appliances']) ? (array)$old['appliances'] : $this->appliances;
            $this->special_requirements = $old['special_requirements'] ?? $this->special_requirements;

            $this->family_size = $old['family_size'] ?? $this->family_size;
            $this->children_count = (int)($old['children_count'] ?? $this->children_count);
            $this->elderly_count = (int)($old['elderly_count'] ?? $this->elderly_count);
            $this->pets = $old['pets'] ?? $this->pets;
            $this->special_needs = $old['special_needs'] ?? $this->special_needs;

            $this->package_id = $old['package_id'] ?? $this->package_id;
            $this->service_tier = $old['service_tier'] ?? $this->service_tier;
            $this->service_mode = $old['service_mode'] ?? $this->service_mode;
            $this->work_days = isset($old['work_days']) ? (array)$old['work_days'] : $this->work_days;
            $this->start_date = $old['start_date'] ?? $this->start_date;
            $this->additional_requirements = $old['additional_requirements'] ?? $this->additional_requirements;
            $this->maid_id = $old['maid_id'] ?? $this->maid_id;
            $this->responsibilities = isset($old['responsibilities']) ? (array)$old['responsibilities'] : $this->responsibilities;
            $this->cuisine_type = $old['cuisine_type'] ?? $this->cuisine_type;
            $this->cuisine_other = $old['cuisine_other'] ?? $this->cuisine_other;
            $this->atmosphere = $old['atmosphere'] ?? $this->atmosphere;
            $this->manage_tasks = $old['manage_tasks'] ?? $this->manage_tasks;

            // Move wizard to the step that contains errors
            $errors = session('errors');
            if ($errors) {
                $errorKeys = array_keys($errors->toArray());
                $step = 1;
                foreach ($errorKeys as $key) {
                    if (in_array($key, ['village', 'house_type', 'number_of_rooms', 'bedrooms', 'bathrooms', 'outdoor_responsibilities', 'appliances', 'special_requirements'])) {
                        $step = max($step, 2);
                    }
                    if (in_array($key, ['family_size', 'children_count', 'elderly_count', 'pets', 'special_needs'])) {
                        $step = max($step, 3);
                    }
                    if (in_array($key, ['package_id', 'service_tier', 'service_mode', 'work_days', 'start_date', 'responsibilities', 'cuisine_type', 'atmosphere', 'manage_tasks', 'additional_requirements'])) {
                        $step = max($step, 4);
                    }
                }
                $this->currentStep = $step;
            }
        }
    }

    public function updatedPackageId($value): void
    {
        if ($value) {
            $pkg = \App\Models\Package::find($value);
            $this->service_tier = $pkg?->name ?? $this->service_tier;
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
            // Validation failed, errors will be displayed
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
            'national_id' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:2048',
        ];
    }

    /**
     * Step 2: Home Details validation rules
     */
    protected function getStep2Rules(): array
    {
        return [
            'village' => 'required|string|max:50',
            'house_type' => 'required|string|max:50',
            'number_of_rooms' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
        ];
    }

    /**
     * Step 3: Household Information validation rules
     */
    protected function getStep3Rules(): array
    {
        return [
            'family_size' => 'required|string|max:50',
        ];
    }

    /**
     * Step 4: Service Expectations validation rules
     */
    protected function getStep4Rules(): array
    {
        return [
            'service_tier' => 'required|string|max:50',
            'service_mode' => 'required|string|max:50',
            'work_days' => 'required|array|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'package_id' => 'required|exists:packages,id',
            'working_hours' => 'nullable|string|max:100',
            'responsibilities' => 'nullable|array',
            'cuisine_type' => 'nullable|string|max:50',
            'cuisine_other' => 'nullable|string|max:100',
            'atmosphere' => 'nullable|string|max:50',
            'manage_tasks' => 'nullable|string|max:100',
            'unspoken_rules' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Test method to debug button clicks
     */
    public function testSubmit(): void
    {
        $this->showSuccessMessage = true;
    }

    /**
     * Submit the complete booking form
     */
    public function submitBooking(): void
    {
        try {
            // Ensure service_tier is synced from selected package if not provided explicitly
            if (!$this->service_tier && $this->package_id) {
                $pkg = \App\Models\Package::find($this->package_id);
                $this->service_tier = $pkg?->name ?? null;
            }
            // Validate all steps
            $this->validate(array_merge(
                $this->getStep1Rules(),
                $this->getStep2Rules(),
                $this->getStep3Rules(),
                $this->getStep4Rules()
            ));

            // Prepare booking data
            $bookingData = [
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'city' => $this->city,
                'division' => $this->division,
                'parish' => $this->parish,
                'address' => $this->village ?? $this->city,
                'package_id' => $this->package_id,
                'service_tier' => $this->service_tier,
                'start_date' => $this->start_date,
                'special_requirements' => $this->special_requirements,
                'additional_requirements' => $this->additional_requirements,
                'work_days' => $this->work_days,
            ];

            // Use BookingToLeadService to find or create lead
            $bookingToLeadService = app(BookingToLeadService::class);
            $lead = $bookingToLeadService->findOrCreateLeadFromBooking($bookingData);

            // Check if user is authenticated and has a client account
            $clientId = null;
            if (auth()->check() && auth()->user()->role === 'client') {
                $clientModel = \App\Models\Client::where('user_id', auth()->id())->first();
                if ($clientModel) {
                    $clientId = $clientModel->id;
                    // Link lead to existing client if not already linked
                    if (!$lead->client_id) {
                        $lead->update(['client_id' => $clientId, 'status' => 'qualified']);
                    }
                }
            } else if ($lead->client_id) {
                // Lead is already linked to a client (from duplicate detection)
                $clientId = $lead->client_id;
            }

            // Store National ID / Passport file
            $nationalIdPath = null;
            if ($this->national_id) {
                $nationalIdPath = $this->national_id->store('national_ids', 'public');
            }

            // Persist booking
            // Normalize family size to integer if user selected a range label
            $normalizedFamilySize = match (trim((string) $this->family_size)) {
                '1-2' => 2,
                '3-4' => 4,
                '5-6' => 6,
                '7+', '7 +' => 7,
                default => is_numeric($this->family_size) ? (int) $this->family_size : null,
            };

            // Sync service_tier with package (final guard)
            if (!$this->service_tier && $this->package_id) {
                $pkg = \App\Models\Package::find($this->package_id);
                $this->service_tier = $pkg?->name ?? null;
            }

            $booking = \App\Models\Booking::create([
                'lead_id' => $lead->id, // Link booking to lead
                'client_id' => $clientId, // May be null initially, set when lead converts to client
                'maid_id' => $this->maid_id ?: null,
                'package_id' => $this->package_id ?: null,
                'booking_type' => 'brokerage',
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'status' => 'pending',
                'notes' => $this->additional_requirements ?: null,

                // Contact
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'country' => $this->country,
                'city' => $this->city,
                'division' => $this->division,
                'parish' => $this->parish,
                'national_id_path' => $nationalIdPath,

                // Home & environment
                'home_type' => $this->house_type ?: null,
                'bedrooms' => $this->bedrooms ?: ($this->number_of_rooms ?: 1),
                'bathrooms' => $this->bathrooms ?: 1,
                'outdoor_responsibilities' => $this->outdoor_responsibilities ?: [],
                'appliances' => $this->appliances ?: [],

                // Household
                'family_size' => $normalizedFamilySize,
                'pets' => $this->pets ?: null,

                // Expectations
                'service_tier' => $this->service_tier ?: null,
                'service_mode' => $this->service_mode,
                'work_days' => $this->work_days,
                'working_hours' => $this->working_hours ?: null,
                'responsibilities' => $this->responsibilities ?: [],
                'cuisine_type' => $this->cuisine_type ?: null,
                'atmosphere' => $this->atmosphere ?: null,
                'manage_tasks' => $this->manage_tasks ?: null,
                'unspoken_rules' => $this->unspoken_rules ?: null,
                'anything_else' => $this->additional_requirements ?: null,
            ]);

            // Optional: compute estimated price
            try {
                $booking->refresh();
                $price = $booking->calculateBookingPrice();
                if ($price !== null) {
                    $booking->calculated_price = $price;
                    $booking->save();
                }
            } catch (\Throwable $e) {
                \Log::warning('Could not calculate booking price: ' . $e->getMessage());
            }

            $this->showSuccessMessage = true;
            
            // Reset form for potential new submissions
            $this->resetForm();
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed, errors will be displayed
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Booking submission error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            // Show a generic error message
            session()->flash('error', 'There was an error submitting your booking. Please try again.');
            session()->flash('error_detail', app()->hasDebugModeEnabled() ? $e->getMessage() : null);
        }
    }

    /**
     * Developer helper: fill the form with test data (local only)
     */
    public function fillTestData(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        // Step 1
        $this->full_name = 'Test Client';
        $this->phone = '+256700000000';
        $this->email = 'test@example.com';
        $this->country = 'Uganda';
        $this->city = 'Kampala';
        $this->division = 'Makindye';
        $this->parish = 'Bukoto';

        // Step 2
        $this->village = 'Ntinda';
        $this->house_type = 'House';
        $this->number_of_rooms = 3;
        $this->bedrooms = 3;
        $this->bathrooms = 2;
        $this->outdoor_responsibilities = [];
        $this->appliances = [];
        $this->special_requirements = '';

        // Step 3
        $this->family_size = '3-4';
        $this->children_count = 0;
        $this->elderly_count = 0;
        $this->pets = 'none';
        $this->special_needs = '';

        // Step 4
        $this->service_mode = 'Live-out';
        $this->work_days = ['Monday', 'Wednesday'];
        $this->start_date = now()->toDateString();
        $this->responsibilities = ['Cleaning', 'Cooking'];
        $this->cuisine_type = '';
        $this->atmosphere = '';
        $this->manage_tasks = '';

        $firstPackage = \App\Models\Package::active()->first();
        if ($firstPackage) {
            $this->package_id = (string) $firstPackage->id;
        }

        $this->currentStep = 4;
    }

    /**
     * Reset form data
     */
    protected function resetForm(): void
    {
        $this->full_name = '';
        $this->phone = '';
        $this->email = '';
        $this->country = 'Uganda';
        $this->city = '';
        $this->division = '';
        $this->parish = '';
        $this->national_id = null;
        $this->village = '';
        $this->house_type = '';
        $this->number_of_rooms = '';
        $this->bedrooms = 0;
        $this->bathrooms = 0;
        $this->outdoor_responsibilities = [];
        $this->appliances = [];
        $this->special_requirements = '';
        $this->family_size = '';
        $this->children_count = 0;
        $this->elderly_count = 0;
        $this->pets = 'none';
        $this->special_needs = '';
        $this->service_tier = '';
        $this->service_mode = '';
        $this->work_days = [];
        $this->start_date = '';
        $this->end_date = '';
        $this->package_id = '';
        $this->additional_requirements = '';
        $this->working_hours = '';
        $this->responsibilities = [];
        $this->cuisine_type = '';
        $this->cuisine_other = '';
        $this->atmosphere = '';
        $this->manage_tasks = '';
        $this->unspoken_rules = '';
        $this->currentStep = 1;
    }

    /**
     * Close validation modal
     */
    public function closeValidationModal(): void
    {
        $this->showValidationModal = false;
    }

    /**
     * Get available packages
     */
    public function getPackages()
    {
        return \App\Models\Package::active()->get();
    }

    public function render()
    {
        return view('livewire.public-booking', [
            'packages' => $this->getPackages()
        ])->layout('components.layouts.simple');
    }
}
