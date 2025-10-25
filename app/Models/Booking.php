<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        // Original V3.0 fields (keeping for compatibility)
        'client_id',
        'maid_id',
        'package_id', // NEW: Link to subscription package
        'booking_type',
        'start_date',
        'end_date',
        'status',
        'notes',
        
        // Section 1: Contact Information
        'full_name',
        'phone',
        'email',
        'country',
        'city',
        'division',
        'parish',
        'national_id_path',
        
        // Section 2: Home & Environment
        'home_type',
        'bedrooms',
        'bathrooms',
        'outdoor_responsibilities',
        'appliances',
        
        // Section 3: Household Composition
        'adults',
        'family_size', // NEW: Total family size for pricing
        'has_children',
        'children_ages',
        'has_elderly',
        'pets',
        'pet_kind',
        'language',
        'language_other',
        
        // Section 4: Job Role & Expectations
        'service_tier', // Silver, Gold, Platinum (now linked to package)
        'calculated_price', // NEW: Auto-calculated from package + family size
        'service_mode',
        'work_days',
        'working_hours',
        'responsibilities',
        'cuisine_type',
        'atmosphere',
        'manage_tasks',
        'unspoken_rules',
        'anything_else',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'calculated_price' => 'decimal:2',
        // JSON casts for array fields
        'outdoor_responsibilities' => 'array',
        'appliances' => 'array',
        'work_days' => 'array',
        'responsibilities' => 'array',
    ];

    /**
     * Get the client that owns the booking.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the maid assigned to the booking.
     */
    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    /**
     * Get the package for this booking.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Calculate the booking price based on package and family size.
     */
    public function calculateBookingPrice(): ?float
    {
        if (!$this->package) {
            return null;
        }

        $familySize = $this->family_size ?? $this->adults ?? 3;
        return $this->package->calculatePrice($familySize);
    }

    /**
     * Get the booking status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'active' => 'green',
            'completed' => 'zinc',
            'cancelled' => 'red',
            default => 'zinc',
        };
    }

    /**
     * Check if the booking is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the booking duration in days.
     */
    public function getDurationInDaysAttribute(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get full contact information as an array.
     */
    public function getContactInfo(): array
    {
        return [
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'division' => $this->division,
            'parish' => $this->parish,
            'national_id_path' => $this->national_id_path,
        ];
    }

    /**
     * Get home & environment details as an array.
     */
    public function getHomeDetails(): array
    {
        return [
            'home_type' => $this->home_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'outdoor_responsibilities' => $this->outdoor_responsibilities ?? [],
            'appliances' => $this->appliances ?? [],
        ];
    }

    /**
     * Get household composition as an array.
     */
    public function getHouseholdComposition(): array
    {
        return [
            'adults' => $this->adults,
            'has_children' => $this->has_children,
            'children_ages' => $this->children_ages,
            'has_elderly' => $this->has_elderly,
            'pets' => $this->pets,
            'pet_kind' => $this->pet_kind,
            'language' => $this->language,
            'language_other' => $this->language_other,
        ];
    }

    /**
     * Get job expectations as an array.
     */
    public function getJobExpectations(): array
    {
        return [
            'service_tier' => $this->service_tier,
            'service_mode' => $this->service_mode,
            'work_days' => $this->work_days ?? [],
            'working_hours' => $this->working_hours,
            'responsibilities' => $this->responsibilities ?? [],
            'cuisine_type' => $this->cuisine_type,
            'atmosphere' => $this->atmosphere,
            'manage_tasks' => $this->manage_tasks,
            'unspoken_rules' => $this->unspoken_rules,
            'anything_else' => $this->anything_else,
        ];
    }

    /**
     * Check if National ID has been uploaded.
     */
    public function hasNationalId(): bool
    {
        return !empty($this->national_id_path);
    }

    /**
     * Get the National ID file URL.
     */
    public function getNationalIdUrlAttribute(): ?string
    {
        if (!$this->hasNationalId()) {
            return null;
        }

        return \Storage::url($this->national_id_path);
    }

    /**
     * Get service tier pricing (will be integrated with Packages module later).
     */
    public function getEstimatedPrice(): ?float
    {
        // Placeholder: Will be replaced when Packages module is implemented
        return match ($this->service_tier) {
            'Silver' => 300000.00,  // Example: 300k UGX
            'Gold' => 500000.00,    // Example: 500k UGX
            'Platinum' => 800000.00, // Example: 800k UGX
            default => null,
        };
    }

    /**
     * Get formatted service tier with icon.
     */
    public function getServiceTierBadgeAttribute(): string
    {
        return match ($this->service_tier) {
            'Silver' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Silver</span>',
            'Gold' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200">Gold</span>',
            'Platinum' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-200">Platinum</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Not Set</span>',
        };
    }

    /**
     * Get formatted package badge with brand colors.
     */
    public function getPackageBadgeHtmlAttribute(): string
    {
        return match ($this->package?->name) {
            'Silver' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#A8A9AD]/20 text-white border border-[#A8A9AD]">Silver</span>',
            'Gold' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#F5B301]/20 text-[#F5B301] border border-[#F5B301]">Gold</span>',
            'Platinum' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#B9A0DC]/20 text-[#B9A0DC] border border-[#B9A0DC]">Platinum</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D1C4E9]/20 text-[#D1C4E9] border border-[#D1C4E9]/50">Not Set</span>',
        };
    }
}
