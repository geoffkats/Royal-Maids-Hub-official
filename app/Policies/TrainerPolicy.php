<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('trainers'));
    }

    public function view(User $user, Trainer $trainer): bool
    {
        // Admin can view any trainer
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
        // Trainers can view any trainer if they have permission
        if ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('trainers')) {
            return true;
        }
        // Trainers can always view their own profile
        return $user->role === User::ROLE_TRAINER && $trainer->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('trainers'));
    }

    public function update(User $user, Trainer $trainer): bool
    {
        // Admin can update any trainer
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
        // Trainers with permission can update any trainer
        if ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('trainers')) {
            return true;
        }
        // Trainers can always update their own profile
        return $user->role === User::ROLE_TRAINER && $trainer->user_id === $user->id;
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('trainers'));
    }
}
