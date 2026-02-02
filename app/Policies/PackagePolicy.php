<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PackagePolicy
{
    /**
     * Determine whether the user can view any models.
     * Admins can view all, clients can view active packages, trainers with permission can view all.
     */
    public function viewAny(User $user): bool
    {
        if ($user->role === 'admin' || $user->role === 'client') {
            return true;
        }

        // Trainers with packages permission
        if ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('packages')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     * Admins can view all, clients can only view active packages, trainers with permission can view all.
     */
    public function view(User $user, Package $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'client') {
            return $package->is_active;
        }

        // Trainers with packages permission
        if ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('packages')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create packages.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update packages.
     */
    public function update(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete packages (soft delete only if bookings exist).
     */
    public function delete(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore packages.
     */
    public function restore(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can force delete packages.
     */
    public function forceDelete(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }
}
