<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;

trait HasPermission
{
    protected string | Closure | null $permission = null;

    public function getPermission(): string
    {
        if ($this->permission) {
            return $this->evaluate($this->permission);
        }

        return FilamentAuditingPlugin::get()->getRestorePermission();
    }

    public function permission(string | Closure $permission): static
    {
        $this->permission = $permission;

        return $this;
    }
}
