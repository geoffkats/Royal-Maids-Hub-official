<?php

namespace App\Policies;

use App\Models\ClientEvaluationQuestion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientEvaluationQuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientEvaluationQuestion $clientEvaluationQuestion): bool
    {
        return $user->isAdminLike();
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
    public function update(User $user, ClientEvaluationQuestion $clientEvaluationQuestion): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientEvaluationQuestion $clientEvaluationQuestion): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClientEvaluationQuestion $clientEvaluationQuestion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClientEvaluationQuestion $clientEvaluationQuestion): bool
    {
        return false;
    }
}
