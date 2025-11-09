<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Opportunity extends Model
{
    use HasFactory;

    protected $table = 'crm_opportunities';

    protected $fillable = [
        'lead_id',
        'client_id',
        'stage_id',
        'title',
        'description',
        'amount',
        'currency',
        'probability',
        'close_date',
        'expected_close_date',
        'won_at',
        'lost_at',
        'loss_reason',
        'loss_notes',
        'package_id',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'integer',
        'close_date' => 'date',
        'expected_close_date' => 'date',
        'won_at' => 'datetime',
        'lost_at' => 'datetime',
    ];

    // Relationships
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'related');
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(OpportunityStageHistory::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'crm_opportunity_tag');
    }

    // Status Methods
    public function markAsWon(?string $notes = null): void
    {
        $this->update([
            'won_at' => now(),
            'lost_at' => null,
            'close_date' => now(),
            'probability' => 100,
            'loss_reason' => null,
            'loss_notes' => null,
        ]);

        // Log activity
        $this->activities()->create([
            'type' => 'note',
            'subject' => 'Opportunity Won',
            'description' => $notes ?? 'Opportunity marked as won',
            'status' => 'completed',
            'completed_at' => now(),
            'related_type' => 'opportunity',
            'related_id' => $this->id,
            'assigned_to' => $this->assigned_to,
            'owner_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);
    }

    public function markAsLost(string $reason, ?string $notes = null): void
    {
        $this->update([
            'lost_at' => now(),
            'won_at' => null,
            'close_date' => now(),
            'probability' => 0,
            'loss_reason' => $reason,
            'loss_notes' => $notes,
        ]);

        // Log activity
        $this->activities()->create([
            'type' => 'note',
            'subject' => 'Opportunity Lost',
            'description' => "Reason: {$reason}" . ($notes ? "\n\n{$notes}" : ''),
            'status' => 'completed',
            'completed_at' => now(),
            'related_type' => 'opportunity',
            'related_id' => $this->id,
            'assigned_to' => $this->assigned_to,
            'owner_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'won_at' => null,
            'lost_at' => null,
            'close_date' => null,
            'loss_reason' => null,
            'loss_notes' => null,
        ]);
    }

    // Helper Methods
    public function isWon(): bool
    {
        return !is_null($this->won_at);
    }

    public function isLost(): bool
    {
        return !is_null($this->lost_at);
    }

    public function isClosed(): bool
    {
        return $this->isWon() || $this->isLost();
    }

    public function isOpen(): bool
    {
        return !$this->isClosed();
    }

    public function getWeightedValue(): float
    {
        return ($this->amount * $this->probability) / 100;
    }

    public function getStatusBadgeColor(): string
    {
        if ($this->isWon()) return 'green';
        if ($this->isLost()) return 'red';
        return 'yellow';
    }

    public function getStatusLabel(): string
    {
        if ($this->isWon()) return 'Won';
        if ($this->isLost()) return 'Lost';
        return 'Open';
    }

    public function getDaysUntilClose(): ?int
    {
        if (!$this->expected_close_date) return null;
        return now()->diffInDays($this->expected_close_date, false);
    }

    public function isOverdue(): bool
    {
        if (!$this->expected_close_date || $this->isClosed()) return false;
        return $this->expected_close_date < now();
    }

    // Accessor for compatibility with views that expect 'name' field
    public function getNameAttribute(): string
    {
        return $this->title;
    }

    public function getExpectedValueAttribute(): float
    {
        return $this->amount * ($this->probability / 100);
    }
}
