<?php

namespace App\Policies\CRM;

use App\Models\CRM\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any activities.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users with CRM access can view activities
        return in_array($user->role, ['admin', 'sales', 'marketing', 'support']);
    }

    /**
     * Determine whether the user can view the activity.
     */
    public function view(User $user, Activity $activity): bool
    {
        // Admin can view all
        if ($user->role === 'admin') {
            return true;
        }

        // Users can view activities they own, are assigned to, or created
        if (in_array($user->role, ['sales', 'marketing', 'support'])) {
            return $activity->owner_id === $user->id 
                || $activity->assigned_to === $user->id 
                || $activity->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create activities.
     */
    public function create(User $user): bool
    {
        // Admin, Sales, Marketing, and Support can create activities
        return in_array($user->role, ['admin', 'sales', 'marketing', 'support']);
    }

    /**
     * Determine whether the user can update the activity.
     */
    public function update(User $user, Activity $activity): bool
    {
        // Admin can update all
        if ($user->role === 'admin') {
            return true;
        }

        // Users can update activities they own or are assigned to
        if (in_array($user->role, ['sales', 'marketing', 'support'])) {
            return $activity->owner_id === $user->id || $activity->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the activity.
     */
    public function delete(User $user, Activity $activity): bool
    {
        // Admin can delete all
        if ($user->role === 'admin') {
            return true;
        }

        // Users can delete activities they created and are not completed
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $activity->created_by === $user->id && !$activity->isCompleted();
        }

        return false;
    }

    /**
     * Determine whether the user can complete the activity.
     */
    public function complete(User $user, Activity $activity): bool
    {
        // Admin can complete any activity
        if ($user->role === 'admin') {
            return !$activity->isCompleted();
        }

        // Users can complete activities assigned to them
        if (in_array($user->role, ['sales', 'marketing', 'support'])) {
            return ($activity->assigned_to === $user->id || $activity->owner_id === $user->id) 
                && !$activity->isCompleted();
        }

        return false;
    }

    /**
     * Determine whether the user can reassign the activity.
     */
    public function reassign(User $user, Activity $activity): bool
    {
        // Admin can reassign any activity
        if ($user->role === 'admin') {
            return true;
        }

        // Activity owner can reassign
        if (in_array($user->role, ['sales', 'marketing'])) {
            return $activity->owner_id === $user->id && !$activity->isCompleted();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the activity.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the activity.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->role === 'admin';
    }
}
