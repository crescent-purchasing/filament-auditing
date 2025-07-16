<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use Closure;

trait HasPermission
{
    protected string | Closure | null $permission = 'restoreAudit';

    public function getPermission(): string | null
    {
        return $this->evaluate($this->permission);
    }

    public function permission(string | Closure $permission): static
    {
        $this->permission = $permission;

        return $this;
    }
}
