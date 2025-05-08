<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use Closure;

trait HasPermission
{
    protected string | Closure $permission = 'restoreAudit';

    public function getPermission(): string
    {
        return $this->evaluate($this->permission);
    }

    public function permission(string | Closure $permission): static
    {
        $this->permission = $permission;

        return $this;
    }
}
