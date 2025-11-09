<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service',
        'family_size',
        'message',
        'privacy_accepted',
        'ip_address',
        'user_agent',
        'status',
        'notes',
        'contacted_at',
    ];

    protected $casts = [
        'privacy_accepted' => 'boolean',
        'contacted_at' => 'datetime',
    ];

    /**
     * Get the service name in a readable format
     */
    public function getServiceNameAttribute()
    {
        $services = [
            'maidservant' => 'Professional Maidservant',
            'home-manager' => 'Home Manager',
            'bedside-nurse' => 'Bedside Nurse',
            'elderly-care' => 'Elderly Caretaker',
            'nanny' => 'Nanny Services',
            'temporary' => 'Temporary Maid',
            'stay-out' => 'Stay-out Maid',
            'fast-response' => 'Fast Response Service',
        ];

        return $services[$this->service] ?? $this->service;
    }

    /**
     * Scope for new submissions
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for contacted submissions
     */
    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    /**
     * Scope for converted submissions
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }
}