<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'type',
        'category',
        'subcategory',
        'requester_id',
        'requester_type',
        'created_on_behalf_of',
        'created_on_behalf_type',
        'client_id',
        'maid_id',
        'booking_id',
        'deployment_id',
        'trainer_id',
        'program_id',
        'package_id',
        'subject',
        'description',
        'priority',
        'auto_priority',
        'tier_based_priority',
        'status',
        'assigned_to',
        'department',
        'due_date',
        'sla_response_due',
        'sla_resolution_due',
        'sla_breached',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'location_address',
        'location_lat',
        'location_lng',
        'resolution_notes',
        'satisfaction_rating',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'sla_response_due' => 'datetime',
        'sla_resolution_due' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'auto_priority' => 'boolean',
        'sla_breached' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            // Generate unique ticket number
            $ticket->ticket_number = 'TKT-' . date('Y') . '-' . str_pad(
                static::whereYear('created_at', date('Y'))->count() + 1, 
                6, 
                '0', 
                STR_PAD_LEFT
            );
            
            // Auto-apply tier-based priority boost
            $ticket->applyTierBasedPriority();
            
            // Calculate SLA deadlines based on priority and tier
            $ticket->calculateSLADeadlines();
        });

        static::updating(function ($ticket) {
            // Check for SLA breaches on status changes
            if ($ticket->isDirty('status')) {
                $ticket->checkSLABreach();
            }
        });
    }
    
    /**
     * Apply automatic priority boost based on client package tier
     */
    public function applyTierBasedPriority(): void
    {
        // Determine the package tier
        $packageTier = null;
        
        if ($this->package_id) {
            $package = Package::find($this->package_id);
            $packageTier = $package?->tier;
        } elseif ($this->client_id) {
            // Get from client's active booking/subscription
            $client = Client::with('activeBooking.package')->find($this->client_id);
            $packageTier = $client?->activeBooking?->package?->tier;
        } elseif ($this->booking_id) {
            $booking = Booking::with('package')->find($this->booking_id);
            $packageTier = $booking?->package?->tier;
        }
        
        if (!$packageTier) {
            return; // No tier found, keep user-selected priority
        }
        
        // Ensure tier is valid enum value
        if (in_array($packageTier, ['silver', 'gold', 'platinum'])) {
            $this->tier_based_priority = $packageTier;
        }
        
        // Priority boost matrix
        $boostMatrix = [
            'platinum' => [
                'low' => 'medium',
                'medium' => 'high',
                'high' => 'urgent',
                'urgent' => 'critical',
            ],
            'gold' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'urgent',
            ],
            'silver' => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
                'urgent' => 'high', // Cap urgent at high for silver
            ],
        ];
        
        $currentPriority = $this->priority ?? 'medium';
        $boostedPriority = $boostMatrix[$packageTier][$currentPriority] ?? $currentPriority;
        
        if ($boostedPriority !== $currentPriority) {
            $this->priority = $boostedPriority;
            $this->auto_priority = true;
        }
        
        // Override for critical issues (safety, legal)
        if ($this->isCriticalIssue()) {
            $this->priority = 'critical';
            $this->auto_priority = true;
        }
    }
    
    /**
     * Calculate SLA response and resolution deadlines
     */
    public function calculateSLADeadlines(): void
    {
        // SLA matrix (in minutes)
        $slaMatrix = [
            'platinum' => [
                'critical' => ['response' => 5, 'resolution' => 30],
                'urgent' => ['response' => 10, 'resolution' => 60],
                'high' => ['response' => 15, 'resolution' => 120],
                'medium' => ['response' => 30, 'resolution' => 240],
                'low' => ['response' => 30, 'resolution' => 240],
            ],
            'gold' => [
                'urgent' => ['response' => 15, 'resolution' => 120],
                'high' => ['response' => 30, 'resolution' => 240],
                'medium' => ['response' => 60, 'resolution' => 480],
                'low' => ['response' => 120, 'resolution' => 1440],
            ],
            'silver' => [
                'high' => ['response' => 60, 'resolution' => 720],
                'medium' => ['response' => 120, 'resolution' => 1440],
                'low' => ['response' => 240, 'resolution' => 2880],
            ],
        ];

        $tier = $this->tier_based_priority ?? 'silver';
        $priority = $this->priority ?? 'medium';
        
        $sla = $slaMatrix[$tier][$priority] ?? ['response' => 120, 'resolution' => 1440];
        
        $this->sla_response_due = now()->addMinutes($sla['response']);
        $this->sla_resolution_due = now()->addMinutes($sla['resolution']);
    }
    
    /**
     * Check if ticket involves critical safety/legal issues
     */
    protected function isCriticalIssue(): bool
    {
        $criticalKeywords = [
            'safety', 'danger', 'emergency', 'injury', 'abuse', 
            'harassment', 'threat', 'legal', 'police', 'visa', 
            'fraud', 'stolen', 'assault', 'fire', 'medical'
        ];
        
        $searchText = strtolower($this->subject . ' ' . $this->description);
        
        foreach ($criticalKeywords as $keyword) {
            if (str_contains($searchText, $keyword)) {
                return true;
            }
        }
        
        // Also check category
        if (in_array($this->category, ['Safety Concern', 'Legal Issue', 'Emergency', 'Harassment'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Check for SLA breach and update status
     */
    public function checkSLABreach(): void
    {
        if (!$this->isOpen()) {
            return;
        }
        
        $breached = false;
        
        // Check if response SLA breached
        if (!$this->first_response_at && $this->sla_response_due && now()->isAfter($this->sla_response_due)) {
            $breached = true;
        }
        
        // Check if resolution SLA breached
        if (!$this->resolved_at && $this->sla_resolution_due && now()->isAfter($this->sla_resolution_due)) {
            $breached = true;
        }
        
        if ($breached && !$this->sla_breached) {
            $this->sla_breached = true;
            $this->save();
            
            // TODO: Send SLA breach notification
        }
    }

    // Relationships
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function createdOnBehalfOf(): BelongsTo
    {
        // Polymorphic-like relationship based on created_on_behalf_type
        if ($this->created_on_behalf_type === 'client') {
            return $this->belongsTo(Client::class, 'created_on_behalf_of');
        } elseif ($this->created_on_behalf_type === 'maid') {
            return $this->belongsTo(Maid::class, 'created_on_behalf_of');
        }
        
        return $this->belongsTo(User::class, 'created_on_behalf_of');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function maid(): BelongsTo
    {
        return $this->belongsTo(Maid::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'pending']);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    public function scopePlatinumTier($query)
    {
        return $query->where('tier_based_priority', 'platinum');
    }

    public function scopeSLABreached($query)
    {
        return $query->where('sla_breached', true);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeCreatedOnBehalf($query)
    {
        return $query->whereNotNull('created_on_behalf_of');
    }

    // Helper Methods
    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'pending', 'in_progress']);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isOpen();
    }
    
    public function isSLABreached(): bool
    {
        if (!$this->isOpen()) {
            return false;
        }
        
        // Check if response SLA breached
        if (!$this->first_response_at && $this->sla_response_due && now()->isAfter($this->sla_response_due)) {
            return true;
        }
        
        // Check if resolution SLA breached
        if (!$this->resolved_at && $this->sla_resolution_due && now()->isAfter($this->sla_resolution_due)) {
            return true;
        }
        
        return false;
    }
    
    public function getTimeUntilSLABreach(): ?string
    {
        if (!$this->isOpen()) {
            return null;
        }
        
        $deadline = !$this->first_response_at ? $this->sla_response_due : $this->sla_resolution_due;
        
        if (!$deadline) {
            return null;
        }
        
        return $deadline->diffForHumans();
    }
    
    public function isPlatinumClient(): bool
    {
        return $this->tier_based_priority === 'platinum';
    }
    
    public function wasCreatedOnBehalf(): bool
    {
        return !is_null($this->created_on_behalf_of);
    }
    
    public function getCreatedByText(): string
    {
        if ($this->wasCreatedOnBehalf()) {
            $onBehalfName = '';
            
            if ($this->created_on_behalf_type === 'client') {
                $onBehalfName = $this->client?->contact_person ?? 'Client';
            } elseif ($this->created_on_behalf_type === 'maid') {
                $onBehalfName = $this->maid?->full_name ?? 'Maid';
            }
            
            return "Created by {$this->requester->name} on behalf of {$onBehalfName}";
        }
        
        return "Created by {$this->requester->name}";
    }

    public function getCreatedOnBehalfText(): string
    {
        if (!$this->wasCreatedOnBehalf()) {
            return 'Self';
        }
        
        if ($this->created_on_behalf_type === 'client') {
            return $this->client?->contact_person ?? 'Client';
        } elseif ($this->created_on_behalf_type === 'maid') {
            return $this->maid?->full_name ?? 'Maid';
        }
        
        return 'Unknown';
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'open' => 'blue',
            'pending' => 'yellow',
            'in_progress' => 'purple',
            'on_hold' => 'orange',
            'resolved' => 'green',
            'closed' => 'zinc',
            'cancelled' => 'red',
            default => 'zinc'
        };
    }

    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'critical' => 'red',
            'urgent' => 'orange',
            'high' => 'amber',
            'medium' => 'yellow',
            'low' => 'zinc',
            default => 'zinc'
        };
    }
    
    public function getTierBadgeColor(): string
    {
        return match($this->tier_based_priority) {
            'platinum' => 'purple',
            'gold' => 'yellow',
            'silver' => 'zinc',
            default => 'zinc'
        };
    }

    /**
     * Check if ticket is approaching SLA deadline (within 2 hours)
     */
    public function isApproachingSLA(): bool
    {
        if (!$this->isOpen() || !$this->sla_resolution_due) {
            return false;
        }
        
        return now()->addHours(2)->isAfter($this->sla_resolution_due) && !$this->isSLABreached();
    }

    /**
     * Check if ticket has unread comments for assigned user
     */
    public function hasUnreadComments(): bool
    {
        if (!$this->assigned_to) {
            return false;
        }
        
        return $this->comments()
            ->where('user_id', '!=', $this->assigned_to)
            ->where('created_at', '>', now()->subDays(7))
            ->exists();
    }

}