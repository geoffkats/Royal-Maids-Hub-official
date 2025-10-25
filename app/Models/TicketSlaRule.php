<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketSlaRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_name',
        'ticket_type',
        'package_tier',
        'priority',
        'first_response_minutes',
        'resolution_minutes',
        'escalation_minutes',
        'auto_boost_priority',
        'boosted_priority',
        'escalate_to_user_id',
        'escalate_to_department',
        'is_active',
        'applies_to_business_hours',
    ];

    protected $casts = [
        'auto_boost_priority' => 'boolean',
        'is_active' => 'boolean',
        'applies_to_business_hours' => 'boolean',
    ];

    public function escalateToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalate_to_user_id');
    }
}
