<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Models\Policies;

use CrescentPurchasing\FilamentAuditing\Tests\Models\User;

class UserPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->email === 'dr.morbius@example.com') {
            return true;
        }

        return null;
    }

    public function viewAny(): bool
    {
        return true;
    }

    public function view(?User $user, User $record): bool
    {
        return false;
    }

    public function create(?User $user): bool
    {
        return false;
    }

    public function update(?User $user, User $record): bool
    {
        return false;
    }

    public function delete(?User $user, User $record): bool
    {
        return false;
    }

    public function restoreAudit(?User $user, User $record): bool
    {
        return false;
    }

    public function goToOldAudit(?User $user, User $record): bool
    {
        return true;
    }
}
