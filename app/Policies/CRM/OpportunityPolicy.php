<?php

namespace App\Policies\CRM;

use App\Models\CRM\Opportunity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OpportunityPolicy
{
    /**
     * Determine whether the user can view any opportunities.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Sales, and Marketing can view opportunities
        return in_array($user->role, ['admin', 'sales', 'marketing']);
    }

    /**
     * Determine whether the user can view the opportunity.
     */
    public function view(User $user, Opportunity $opportunity): bool
    {
        // Admin can view all
        if ($user->role === 'admin') {
            return true;
        }

        // Sales and Marketing can view their assigned opportunities
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $opportunity->assigned_to === $user->id || $opportunity->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create opportunities.
     */
    public function create(User $user): bool
    {
        // Admin, Sales, and Marketing can create opportunities
        return in_array($user->role, ['admin', 'sales', 'marketing']);
    }

    /**
     * Determine whether the user can update the opportunity.
     */
    public function update(User $user, Opportunity $opportunity): bool
    {
        // Admin can update all
        if ($user->role === 'admin') {
            return true;
        }

        // Sales and Marketing can update their assigned opportunities
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $opportunity->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the opportunity.
     */
    public function delete(User $user, Opportunity $opportunity): bool
    {
        // Only admin can delete opportunities
        if ($user->role === 'admin') {
            return true;
        }

        // Sales can delete their own opportunities if still open
        if ($user->role === 'sales' && $opportunity->assigned_to === $user->id) {
            return $opportunity->isOpen();
        }

        return false;
    }

    /**
     * Determine whether the user can update the stage of the opportunity.
     */
    public function updateStage(User $user, Opportunity $opportunity): bool
    {
        // Admin can change stages
        if ($user->role === 'admin') {
            return true;
        }

        // Sales can change stages of their assigned opportunities
        if ($user->role === 'sales' && $opportunity->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can mark opportunity as won.
     */
    public function markWon(User $user, Opportunity $opportunity): bool
    {
        // Admin can mark as won
        if ($user->role === 'admin') {
            return $opportunity->isOpen();
        }

        // Sales can mark their own opportunities as won
        if ($user->role === 'sales' && $opportunity->assigned_to === $user->id) {
            return $opportunity->isOpen();
        }

        return false;
    }

    /**
     * Determine whether the user can mark opportunity as lost.
     */
    public function markLost(User $user, Opportunity $opportunity): bool
    {
        // Admin can mark as lost
        if ($user->role === 'admin') {
            return $opportunity->isOpen();
        }

        // Sales can mark their own opportunities as lost
        if ($user->role === 'sales' && $opportunity->assigned_to === $user->id) {
            return $opportunity->isOpen();
        }

        return false;
    }

    /**
     * Determine whether the user can reassign the opportunity.
     */
    public function reassign(User $user, Opportunity $opportunity): bool
    {
        // Only admin can reassign opportunities
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the opportunity.
     */
    public function restore(User $user, Opportunity $opportunity): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the opportunity.
     */
    public function forceDelete(User $user, Opportunity $opportunity): bool
    {
        return $user->role === 'admin';
    }
}
