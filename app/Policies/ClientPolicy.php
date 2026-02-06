<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->isCustomerSupport()
            || $user->isFinanceOfficer()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function view(User $user, Client $client): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->isCustomerSupport()
            || $user->isFinanceOfficer()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function create(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function update(User $user, Client $client): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('clients'));
    }

    /**
     * Sensitive identity fields should only be visible to super admins.
     */
    public function viewSensitiveIdentity(User $user, Client $client): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Sensitive identity fields should only be editable by super admins.
     */
    public function updateSensitiveIdentity(User $user, Client $client): bool
    {
        return $user->isSuperAdmin();
    }
}
