<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait HasNavigationGroup
{
    protected string | Closure | null $navigationGroup = null;

    public function getNavigationGroup(): ?string
    {
        return $this->evaluate($this->navigationGroup);
    }

    public function navigationGroup(string | Closure | null $navigationGroup): static
    {
        $this->navigationGroup = $navigationGroup;

        return $this;
    }
}
