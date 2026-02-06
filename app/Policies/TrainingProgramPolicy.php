<?php

namespace App\Policies;

use App\Models\{TrainingProgram, User};

class TrainingProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->role === User::ROLE_TRAINER;
    }

    public function view(User $user, TrainingProgram $program): bool
    {
        if ($user->isAdminLike() || $user->isOperationsManager()) {
            return true;
        }

        // Trainer can view their own programs
        if ($user->role === User::ROLE_TRAINER) {
            return $program->trainer->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->role === User::ROLE_TRAINER;
    }

    public function update(User $user, TrainingProgram $program): bool
    {
        if ($user->isAdminLike() || $user->isOperationsManager()) {
            return true;
        }

        // Trainer can update their own programs
        if ($user->role === User::ROLE_TRAINER) {
            return $program->trainer->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, TrainingProgram $program): bool
    {
        // Only admin can delete
        return $user->isAdminLike() || $user->isOperationsManager();
    }
}
