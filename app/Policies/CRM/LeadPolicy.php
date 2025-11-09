<?php

namespace App\Policies\CRM;

use App\Models\CRM\Lead;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeadPolicy
{
    /**
     * Determine whether the user can view any leads.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Sales, and Marketing can view leads
        return in_array($user->role, ['admin', 'sales', 'marketing']);
    }

    /**
     * Determine whether the user can view the lead.
     */
    public function view(User $user, Lead $lead): bool
    {
        // Admin can view all
        if ($user->role === 'admin') {
            return true;
        }

        // Sales and Marketing can view their own leads
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $lead->owner_id === $user->id;
        }

        // Support can view leads but read-only (limited access)
        if ($user->role === 'support') {
            return true; // Limited view capability
        }

        return false;
    }

    /**
     * Determine whether the user can create leads.
     */
    public function create(User $user): bool
    {
        // Admin, Sales, and Marketing can create leads
        return in_array($user->role, ['admin', 'sales', 'marketing']);
    }

    /**
     * Determine whether the user can update the lead.
     */
    public function update(User $user, Lead $lead): bool
    {
        // Admin can update all
        if ($user->role === 'admin') {
            return true;
        }

        // Sales and Marketing can update their own leads
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $lead->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the lead.
     */
    public function delete(User $user, Lead $lead): bool
    {
        // Only admin can delete leads
        if ($user->role === 'admin') {
            return true;
        }

        // Sales can delete their own leads if not converted
        if ($user->role === 'sales' && $lead->owner_id === $user->id) {
            return !$lead->isConverted();
        }

        return false;
    }

    /**
     * Determine whether the user can convert the lead to client.
     */
    public function convert(User $user, Lead $lead): bool
    {
        // Admin can convert any lead
        if ($user->role === 'admin') {
            return $lead->canBeConverted();
        }

        // Sales can convert their own qualified leads
        if ($user->role === 'sales' && $lead->owner_id === $user->id) {
            return $lead->canBeConverted();
        }

        return false;
    }

    /**
     * Determine whether the user can disqualify the lead.
     */
    public function disqualify(User $user, Lead $lead): bool
    {
        // Admin can disqualify any lead
        if ($user->role === 'admin') {
            return true;
        }

        // Sales and Marketing can disqualify their own leads
        if (in_array($user->role, ['sales', 'marketing']) && $lead->owner_id === $user->id) {
            return !$lead->isConverted();
        }

        return false;
    }

    /**
     * Determine whether the user can reassign the lead.
     */
    public function reassign(User $user, Lead $lead): bool
    {
        // Only admin can reassign leads
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the lead.
     */
    public function restore(User $user, Lead $lead): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the lead.
     */
    public function forceDelete(User $user, Lead $lead): bool
    {
        return $user->role === 'admin';
    }
}
