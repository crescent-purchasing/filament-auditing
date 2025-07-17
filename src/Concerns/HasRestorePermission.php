<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait HasRestorePermission
{
    protected string | Closure $restorePermission = 'restoreAudit';

    public function getRestorePermission(): ?string
    {
        return $this->evaluate($this->restorePermission);
    }

    public function restorePermission(string | Closure $restorePermission): static
    {
        $this->restorePermission = $restorePermission;

        return $this;
    }
}
