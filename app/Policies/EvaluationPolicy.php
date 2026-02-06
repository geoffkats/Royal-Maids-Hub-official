<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    /**
     * Determine if the user can view any evaluations.
     */
    public function viewAny(User $user): bool
    {
        // Admin and trainers can view evaluations
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine if the user can view the evaluation.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        // Admin can view all
        if ($user->isAdminLike() || $user->isOperationsManager()) {
            return true;
        }

        // Trainer can view their own evaluations
        if ($user->role === User::ROLE_TRAINER) {
            return $evaluation->trainer->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create evaluations.
     */
    public function create(User $user): bool
    {
        // Admin and trainers can create evaluations
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine if the user can update the evaluation.
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        // Admin can update all
        if ($user->isAdminLike() || $user->isOperationsManager()) {
            return true;
        }

        // Trainer can update their own evaluations
        if ($user->role === User::ROLE_TRAINER) {
            return $evaluation->trainer->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the evaluation.
     */
    public function delete(User $user, Evaluation $evaluation): bool
    {
        // Only admin can delete evaluations
        return $user->isAdminLike() || $user->isOperationsManager();
    }
}
