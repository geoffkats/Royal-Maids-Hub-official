<?php

namespace App\Policies;

use App\Models\Deployment;
use App\Models\User;

class DeploymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->isFinanceOfficer()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('deployments'));
    }

    public function view(User $user, Deployment $deployment): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || $user->isFinanceOfficer()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('deployments'));
    }

    public function create(User $user): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('deployments'));
    }

    public function update(User $user, Deployment $deployment): bool
    {
        return $user->isAdminLike()
            || $user->isOperationsManager()
            || ($user->role === User::ROLE_TRAINER && $user->trainer && $user->trainer->hasAccessTo('deployments'));
    }

    public function delete(User $user, Deployment $deployment): bool
    {
        return $user->isAdminLike();
    }

    /**
     * Financial fields should only be visible to super admins or finance officers.
     */
    public function viewSensitiveFinancials(User $user, Deployment $deployment): bool
    {
        return $user->isSuperAdmin() || $user->isFinanceOfficer();
    }

    /**
     * Financial fields should only be editable by super admins or finance officers.
     */
    public function updateSensitiveFinancials(User $user, Deployment $deployment): bool
    {
        return $user->isSuperAdmin() || $user->isFinanceOfficer();
    }
}
