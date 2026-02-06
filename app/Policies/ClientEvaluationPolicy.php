<?php

namespace App\Policies;

use App\Models\ClientEvaluation;
use App\Models\User;

class ClientEvaluationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientEvaluation $clientEvaluation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->role === User::ROLE_TRAINER && $clientEvaluation->trainer?->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClientEvaluation $clientEvaluation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->role === User::ROLE_TRAINER && $clientEvaluation->trainer?->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientEvaluation $clientEvaluation): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClientEvaluation $clientEvaluation): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClientEvaluation $clientEvaluation): bool
    {
        return $user->isSuperAdmin();
    }
}
