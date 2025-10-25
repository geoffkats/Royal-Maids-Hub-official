<?php

namespace App\Policies;

use App\Models\{TrainingProgram, User};

class TrainingProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_TRAINER;
    }

    public function view(User $user, TrainingProgram $program): bool
    {
        if ($user->role === User::ROLE_ADMIN) {
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
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_TRAINER;
    }

    public function update(User $user, TrainingProgram $program): bool
    {
        if ($user->role === User::ROLE_ADMIN) {
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
        return $user->role === User::ROLE_ADMIN;
    }
}
