<?php

namespace App\Policies;

use App\Models\EvaluationTask;
use App\Models\User;

class EvaluationTaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminLike() || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EvaluationTask $evaluationTask): bool
    {
        return $user->isAdminLike() || $user->role === User::ROLE_TRAINER;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EvaluationTask $evaluationTask): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EvaluationTask $evaluationTask): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EvaluationTask $evaluationTask): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EvaluationTask $evaluationTask): bool
    {
        return $user->isAdminLike();
    }
}
