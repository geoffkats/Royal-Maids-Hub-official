<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function view(User $user, Trainer $trainer): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function update(User $user, Trainer $trainer): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }
}
