<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function view(User $user, Client $client): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function update(User $user, Client $client): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }
}
