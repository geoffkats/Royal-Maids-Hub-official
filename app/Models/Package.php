<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'tier',
        'description',
        'base_price',
        'base_family_size',
        'additional_member_cost',
        'training_weeks',
        'training_includes',
        'backup_days_per_year',
        'free_replacements',
        'evaluations_per_year',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'additional_member_cost' => 'decimal:2',
        'training_includes' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'base_family_size' => 'integer',
        'training_weeks' => 'integer',
        'backup_days_per_year' => 'integer',
        'free_replacements' => 'integer',
        'evaluations_per_year' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get bookings using this package.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Calculate price for a specific family size.
     */
    public function calculatePrice(int $familySize): float
    {
        if ($familySize <= $this->base_family_size) {
            return (float) $this->base_price;
        }

        $additionalMembers = $familySize - $this->base_family_size;
        return (float) ($this->base_price + ($additionalMembers * $this->additional_member_cost));
    }

    /**
     * Get package badge color.
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->name) {
            'Silver' => 'zinc',
            'Gold' => 'yellow',
            'Platinum' => 'purple',
            default => 'zinc',
        };
    }

    /**
     * Get formatted base price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price) . ' UGX/month';
    }

    /**
     * Scope active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Get package icon.
     */
    public function getIconAttribute(): string
    {
        return match($this->name) {
            'Silver' => 'shield',
            'Gold' => 'star',
            'Platinum' => 'sparkles',
            default => 'cube',
        };
    }
}
