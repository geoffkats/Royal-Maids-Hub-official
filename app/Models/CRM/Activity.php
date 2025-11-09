<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'crm_activities';

    protected $fillable = [
        'type',
        'subject',
        'description',
        'due_date',
        'priority',
        'status',
        'outcome',
        'completed_at',
        'related_type',
        'related_id',
        'assigned_to',
        'owner_id',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Status Methods
    public function markAsCompleted(?string $outcome = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome' => $outcome,
        ]);
    }

    public function markAsCancelled(?string $outcome = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'completed_at' => now(),
            'outcome' => $outcome,
        ]);
    }

    // Helper Methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && !$this->isCancelled() && $this->due_date && $this->due_date < now();
    }

    public function isDueSoon(): bool
    {
        if (!$this->due_date || $this->isCompleted() || $this->isCancelled()) {
            return false;
        }
        
        $hoursUntilDue = now()->diffInHours($this->due_date, false);
        return $hoursUntilDue >= 0 && $hoursUntilDue <= 24;
    }

    public function getTypeBadgeColor(): string
    {
        return match($this->type) {
            'call' => 'blue',
            'email' => 'cyan',
            'meeting' => 'purple',
            'task' => 'yellow',
            'note' => 'zinc',
            default => 'zinc'
        };
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'zinc'
        };
    }

    public function getPriorityBadgeColor(): string
    {
        return match($this->priority) {
            'low' => 'zinc',
            'medium' => 'yellow',
            'high' => 'red',
            default => 'zinc'
        };
    }
}
