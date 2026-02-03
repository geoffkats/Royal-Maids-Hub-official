<?php

namespace App\Policies;

use App\Models\Maid;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaidPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        // Trainers with 'maids' permission can view all
        if ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('maids')) {
            return true;
        }

        // Clients can browse available maids
        return $user->role === User::ROLE_CLIENT;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maid $maid): bool
    {
        // Admin can view all maids
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        // Trainer with 'maids' permission can view all maids in training
        if ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('maids')) {
            return $maid->status === 'in-training';
        }

        // Client can only view available maids
        if ($user->role === User::ROLE_CLIENT) {
            return $maid->status === 'available';
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maid $maid): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maid $maid): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Maid $maid): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Maid $maid): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can browse available maids (for clients).
     */
    public function browse(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_CLIENT;
    }

    /**
     * Determine whether the user can manage trainees (for trainers).
     */
    public function manageTrainees(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_TRAINER;
    }
}