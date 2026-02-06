<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Soft deletes protect client records from hard removal.
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'profile_image',
        'company_name',
        'contact_person',
        'phone',
        'secondary_phone',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_relationship',
        'address',
        'city',
        'district',
        'identity_type',
        'identity_number',
        'package_id',
        'subscription_tier',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'preferred_maid_types',
        'special_requirements',
        'total_bookings',
        'active_bookings',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'preferred_maid_types' => 'array',
        'total_bookings' => 'integer',
        'active_bookings' => 'integer',
    ];

    /**
     * Hide sensitive identity data from default serialization.
     * Access is controlled via policies and gates.
     *
     * @var list<string>
     */
    protected $hidden = [
        'identity_type',
        'identity_number',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $client): void {
            $user = auth()->user();

            if (!$user) {
                return;
            }

            $identityChanged = $client->isDirty('identity_type') || $client->isDirty('identity_number');

            if ($identityChanged && Gate::denies('updateSensitiveIdentity', $client)) {
                throw new AuthorizationException('Unauthorized to update identity fields.');
            }
        });
    }

    /**
     * Get the user that owns the client profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package for the client.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the bookings for the client.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get client feedback responses.
     */
    public function evaluationResponses(): HasMany
    {
        return $this->hasMany(ClientEvaluationResponse::class);
    }

    /**
     * Get the most recent active booking for the client.
     */
    public function activeBooking(): HasOne
    {
        return $this->hasOne(Booking::class)
            ->whereIn('status', ['confirmed', 'active', 'in_progress'])
            ->latest();
    }

    /**
     * Check if subscription is active.
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->subscription_status !== 'active') {
            return false;
        }

        if (!$this->subscription_end_date) {
            return false;
        }

        return $this->subscription_end_date->isFuture();
    }

    /**
     * Get subscription status badge color.
     */
    public function getSubscriptionBadgeColorAttribute(): string
    {
        return match ($this->subscription_status) {
            'active' => 'green',
            'expired' => 'red',
            'pending' => 'yellow',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->district,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get all leads that converted to this client.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(\App\Models\CRM\Lead::class);
    }
}
