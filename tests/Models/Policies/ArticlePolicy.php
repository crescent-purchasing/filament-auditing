<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Models\Policies;

use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;

class ArticlePolicy
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

    public function view(?User $user, Article $record): bool
    {
        return false;
    }

    public function create(?User $user): bool
    {
        return false;
    }

    public function update(?User $user, Article $record): bool
    {
        return false;
    }

    public function delete(?User $user, Article $record): bool
    {
        return false;
    }

    public function restoreAudit(?User $user, Article $record): bool
    {
        return false;
    }

    public function goToOldAudit(?User $user, Article $record): bool
    {
        return true;
    }
}
