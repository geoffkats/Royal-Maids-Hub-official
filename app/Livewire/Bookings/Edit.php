<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Maid;
use App\Models\Package;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public Booking $booking;
    
    // Basic booking fields
    public $maid_id;
    public $start_date;
    public $end_date;
    public $status;
    public $notes;
    
    // Section 1: Contact Information (matches public booking)
    public $full_name;
    public $phone;
    public $email;
    public $country;
    public $city;
    public $division;
    public $parish;
    public $national_id;
    public $existing_national_id_path;
    
    // Section 2: Home Details (matches public booking)
    public $village = '';
    public $house_type = '';
    public $number_of_rooms = '';
    public $bedrooms = 0;
    public $bathrooms = 0;
    public $outdoor_responsibilities = [];
    public $appliances = [];
    public $special_requirements = '';
    
    // Section 3: Household Information (matches public booking)
    public $family_size = '';
    public $children_count = 0;
    public $elderly_count = 0;
    public $pets = 'none';
    public $special_needs = '';
    
    // Legacy fields (for backward compatibility - not used in new view but initialized to prevent errors)
    public $has_children = null;
    public $has_elderly = null;
    public $children_ages = null;
    public $pet_kind = null;
    public $language = null;
    public $language_other = null;
    
    // Section 4: Service Expectations (matches public booking)
    public $package_id = '';
    public $service_tier = '';
    public $service_mode = '';
    public $work_days = [];
    public $working_hours = '';
    public $responsibilities = [];
    public $cuisine_type = '';
    public $cuisine_other = '';
    public $atmosphere = '';
    public $manage_tasks = '';
    public $unspoken_rules = '';
    public $additional_requirements = '';

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['maid', 'client', 'package']);
        $this->authorize('update', $this->booking);

        // Load basic booking fields
        $this->maid_id = $this->booking->maid_id;
        $this->start_date = $this->booking->start_date?->format('Y-m-d') ?? '';
        $this->end_date = $this->booking->end_date?->format('Y-m-d') ?? '';
        // Ensure status is valid - if booking has invalid status (like 'approved'), default to 'pending'
        $validStatuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        $bookingStatus = $this->booking->status ?? 'pending';
        $this->status = in_array($bookingStatus, $validStatuses) ? $bookingStatus : 'pending';
        $this->notes = $this->booking->notes ?? '';
        
        // Section 1: Contact - prefer booking snapshot, fallback to client
        $client = $this->booking->client;
        $this->full_name = $this->booking->full_name ?? $client?->contact_person ?? '';
        $this->phone = $this->booking->phone ?? $client?->phone ?? '';
        $this->email = $this->booking->email ?? $client?->user?->email ?? '';
        $this->country = $this->booking->country ?? 'Uganda';
        $this->city = $this->booking->city ?? $client?->city ?? '';
        $this->division = $this->booking->division ?? $client?->district ?? '';
        $this->parish = $this->booking->parish ?? '';
        $this->existing_national_id_path = $this->booking->national_id_path ?? '';
        
        // Section 2: Home Details - map from booking model fields
        // Map home_type to house_type (handle both field names)
        $homeType = $this->booking->home_type ?? '';
        // Normalize to match public booking options
        if ($homeType) {
            $normalized = strtolower($homeType);
            $typeMap = [
                'apartment' => 'Apartment',
                'house' => 'House',
                'townhouse' => 'Townhouse',
                'villa' => 'Villa',
                'mansion' => 'Mansion',
                'bungalow' => 'Bungalow',
            ];
            $this->house_type = $typeMap[$normalized] ?? ucfirst($homeType);
        }
        
        // Map bedrooms to number_of_rooms
        $this->number_of_rooms = (string)($this->booking->bedrooms ?? '');
        
        // Bedrooms and bathrooms
        $this->bedrooms = (int)($this->booking->bedrooms ?? 0);
        $this->bathrooms = (int)($this->booking->bathrooms ?? 0);
        
        // Outdoor responsibilities - handle array or JSON
        $outdoor = $this->booking->outdoor_responsibilities ?? [];
        if (is_string($outdoor)) {
            $outdoor = json_decode($outdoor, true) ?? [];
        }
        $this->outdoor_responsibilities = is_array($outdoor) ? $outdoor : [];
        
        // Appliances - handle array or JSON
        $appliances = $this->booking->appliances ?? [];
        if (is_string($appliances)) {
            $appliances = json_decode($appliances, true) ?? [];
        }
        $this->appliances = is_array($appliances) ? $appliances : [];
        
        // Village might be in parish or notes
        $this->village = $this->booking->parish ?? '';
        
        // Special requirements might be in notes or anything_else
        $this->special_requirements = $this->booking->notes ?? $this->booking->anything_else ?? '';
        
        // Section 3: Household Information - map from booking model
        // Map family_size (could be stored directly or derived)
        $familySize = $this->booking->family_size;
        if (is_numeric($familySize)) {
            // Convert numeric to range format used in public booking
            if ($familySize <= 2) {
                $this->family_size = '1-2';
            } elseif ($familySize <= 4) {
                $this->family_size = '3-4';
            } elseif ($familySize <= 6) {
                $this->family_size = '5-6';
            } else {
                $this->family_size = '7+';
            }
        } elseif ($familySize && in_array($familySize, ['1-2', '3-4', '5-6', '7+'])) {
            $this->family_size = $familySize;
        } else {
            // Default to 1-2 if no family size
            $this->family_size = '1-2';
        }
        
        // Children count - derive from has_children and children_ages
        if ($this->booking->has_children === 'Yes') {
            $ages = $this->booking->children_ages ?? '';
            if ($ages) {
                // Count commas + 1 (e.g., "3, 7, 12" = 3 children)
                $this->children_count = substr_count($ages, ',') + 1;
            } else {
                $this->children_count = 1; // Default to 1 if has children but no ages
            }
        } else {
            $this->children_count = 0;
        }
        
        // Elderly count - derive from has_elderly
        $this->elderly_count = ($this->booking->has_elderly === 'Yes') ? 1 : 0;
        
        // Pets - map Yes/No to public booking format
        $petsValue = $this->booking->pets ?? 'No';
        if ($petsValue === 'Yes') {
            $petKind = $this->booking->pet_kind ?? '';
            if (stripos($petKind, 'dog') !== false && stripos($petKind, 'cat') !== false) {
                $this->pets = 'both';
            } elseif (stripos($petKind, 'dog') !== false) {
                $this->pets = 'dogs';
            } elseif (stripos($petKind, 'cat') !== false) {
                $this->pets = 'cats';
            } else {
                $this->pets = 'other';
            }
        } else {
            $this->pets = 'none';
        }
        
        $this->special_needs = $this->booking->anything_else ?? '';
        
        // Section 4: Service Expectations
        $this->package_id = $this->booking->package_id ?? '';
        
        // Service tier from package or booking
        if ($this->booking->package) {
            $this->service_tier = $this->booking->package->name ?? '';
        } else {
        $this->service_tier = $this->booking->service_tier ?? '';
        }
        
        // Service mode - normalize to match public booking format
        $serviceMode = $this->booking->service_mode ?? '';
        if ($serviceMode) {
            $normalized = strtolower(str_replace('-', '-', $serviceMode));
            if (str_contains($normalized, 'live-in')) {
                $this->service_mode = 'Live-in';
            } elseif (str_contains($normalized, 'live-out')) {
                $this->service_mode = 'Live-out';
            } else {
                $this->service_mode = ucfirst($serviceMode);
            }
        }
        
        // Work days - ensure always array
        $workDays = $this->booking->work_days;
        if (is_array($workDays)) {
            $this->work_days = array_values(array_filter($workDays));
        } elseif (is_string($workDays) && !empty($workDays)) {
            $decoded = json_decode($workDays, true);
            $this->work_days = is_array($decoded) ? array_values(array_filter($decoded)) : [];
        } else {
            $this->work_days = [];
        }
        
        $this->working_hours = $this->booking->working_hours ?? '';
        
        // Responsibilities - ensure always array
        $responsibilities = $this->booking->responsibilities;
        if (is_array($responsibilities)) {
            $this->responsibilities = array_values(array_filter($responsibilities));
        } elseif (is_string($responsibilities) && !empty($responsibilities)) {
            $decoded = json_decode($responsibilities, true);
            $this->responsibilities = is_array($decoded) ? array_values(array_filter($decoded)) : [];
        } else {
            $this->responsibilities = [];
        }
        
        $this->atmosphere = $this->booking->atmosphere ?? '';
        $this->cuisine_type = $this->booking->cuisine_type ?? '';
        $this->cuisine_other = ''; // Not stored separately, might be in cuisine_type
        $this->manage_tasks = $this->booking->manage_tasks ?? '';
        $this->unspoken_rules = $this->booking->unspoken_rules ?? '';
        $this->additional_requirements = $this->booking->anything_else ?? '';
    }

    public function updatedPackageId($value): void
    {
        if ($value) {
            $package = Package::find($value);
            if ($package) {
                $this->service_tier = $package->name;
            }
        }
    }

    public function rules(): array
    {
        return [
            'maid_id' => 'nullable|exists:maids,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            
            // Section 1: Contact Information
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'parish' => 'required|string|max:100',
            'national_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Section 2: Home Details
            'village' => 'required|string|max:100',
            'house_type' => 'required|string|max:50',
            'number_of_rooms' => 'required|integer|min:1',
            'special_requirements' => 'nullable|string|max:1000',
            
            // Section 3: Household Information
            'family_size' => 'required|string|max:50',
            'children_count' => 'nullable|integer|min:0',
            'elderly_count' => 'nullable|integer|min:0',
            'pets' => 'required|in:none,dogs,cats,both,other',
            'special_needs' => 'nullable|string|max:1000',
            
            // Section 4: Service Expectations
            'package_id' => 'required|exists:packages,id',
            'service_tier' => 'nullable|string|max:50',
            'service_mode' => 'required|in:Live-in,Live-out',
            'work_days' => 'required|array|min:1',
            'working_hours' => 'nullable|string|max:50',
            'responsibilities' => 'nullable|array',
            'atmosphere' => 'nullable|string|max:50',
            'unspoken_rules' => 'nullable|string|max:1000',
            'additional_requirements' => 'nullable|string|max:1000',
        ];
    }

    public function update(): void
    {
        // Validate and sanitize status BEFORE validation
        $validStatuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        if (!in_array($this->status, $validStatuses)) {
            $this->status = 'pending';
        }
        
        $this->validate();

        // Normalize family_size to integer
        $normalizedFamilySize = match (trim($this->family_size)) {
            '1-2' => 2,
            '3-4' => 4,
            '5-6' => 6,
            '7+', '7 +' => 7,
            default => is_numeric($this->family_size) ? (int) $this->family_size : null,
        };

        // Sync service_tier from package if not provided
        if (!$this->service_tier && $this->package_id) {
            $package = Package::find($this->package_id);
            $this->service_tier = $package?->name ?? '';
        }

        // Map public booking fields back to booking model fields
        // Status is already validated above, but ensure it's a string
        $status = (string)$this->status;
        
        $data = [
            'maid_id' => $this->maid_id ?: null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date ?: null,
            'status' => $status,
            'notes' => $this->special_requirements ?: $this->notes,
            
            // Section 1: Contact
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            
            // Section 2: Home - map to booking model fields
            'home_type' => strtolower($this->house_type),
            'bedrooms' => $this->bedrooms ?: (int)$this->number_of_rooms,
            'bathrooms' => $this->bathrooms ?: 1,
            'outdoor_responsibilities' => $this->outdoor_responsibilities ?: [],
            'appliances' => $this->appliances ?: [],
            'parish' => $this->village, // Store village in parish if needed
            
            // Section 3: Household
            'family_size' => $normalizedFamilySize,
            'adults' => max(1, ($normalizedFamilySize ?? 2) - $this->children_count - $this->elderly_count),
            'has_children' => $this->children_count > 0 ? 'Yes' : 'No',
            'children_ages' => $this->children_count > 0 ? implode(', ', array_fill(0, $this->children_count, '')) : null,
            'has_elderly' => $this->elderly_count > 0 ? 'Yes' : 'No',
            'pets' => $this->pets !== 'none' ? 'Yes' : 'No',
            'pet_kind' => $this->pets !== 'none' ? ucfirst($this->pets) : null,
            
            // Section 4: Service Expectations
            'package_id' => $this->package_id,
            'service_tier' => $this->service_tier,
            'service_mode' => $this->service_mode,
            'work_days' => $this->work_days,
            'working_hours' => $this->working_hours,
            'responsibilities' => $this->responsibilities,
            'cuisine_type' => $this->cuisine_type ?: ($this->cuisine_other ? 'Other: ' . $this->cuisine_other : null),
            'atmosphere' => $this->atmosphere,
            'manage_tasks' => $this->manage_tasks,
            'unspoken_rules' => $this->unspoken_rules,
            'anything_else' => $this->additional_requirements ?: $this->special_needs,
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
        $packages = Package::active()->get();

        return view('livewire.bookings.edit', [
            'maids' => $maids,
            'packages' => $packages,
            'title' => __('Edit Booking #') . $this->booking->id,
        ]);
    }
}
