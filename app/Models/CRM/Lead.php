<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'crm_leads';

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'company',
        'job_title',
        'industry',
        'city',
        'address',
        'source_id',
        'owner_id',
        'status',
        'score',
        'interested_package_id',
        'notes',
        'client_id',
        'converted_at',
        'disqualified_at',
        'disqualified_reason',
        'last_contacted_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'converted_at' => 'datetime',
        'disqualified_at' => 'datetime',
        'last_contacted_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function interestedPackage(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'interested_package_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'related');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'crm_lead_tag');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(LeadStatusHistory::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    // Helper Methods
    public function isConverted(): bool
    {
        return $this->status === 'converted' && !is_null($this->client_id);
    }

    public function canBeConverted(): bool
    {
        return !$this->isConverted() && in_array($this->status, ['qualified', 'working']);
    }

    public function markAsConverted(int $clientId): void
    {
        $this->update([
            'status' => 'converted',
            'client_id' => $clientId,
            'converted_at' => now(),
        ]);
    }

    public function markAsDisqualified(string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => 'disqualified',
            'disqualified_at' => now(),
            'disqualified_reason' => $reason,
            'notes' => $notes ? $this->notes . "\n\n" . $notes : $this->notes,
        ]);
    }

    public function updateLastContacted(): void
    {
        $this->update(['last_contacted_at' => now()]);
    }

    public function getConversionDate(): ?string
    {
        return $this->converted_at?->format('M d, Y');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'working' => 'yellow',
            'qualified' => 'green',
            'disqualified' => 'red',
            'converted' => 'purple',
            default => 'zinc'
        };
    }

    /**
     * Check if lead has bookings
     */
    public function hasBookings(): bool
    {
        return $this->bookings()->exists();
    }

    /**
     * Get booking count
     */
    public function getBookingCount(): int
    {
        return $this->bookings()->count();
    }

    /**
     * Check if lead is linked to a client (before conversion)
     */
    public function isLinkedToClient(): bool
    {
        return !is_null($this->client_id);
    }
}
