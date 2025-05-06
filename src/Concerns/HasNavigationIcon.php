<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

trait HasNavigationIcon
{
    protected string | Htmlable | Closure | null $navigationIcon = 'heroicon-o-clock';

    public function getNavigationIcon(): string
    {
        return $this->evaluate($this->navigationIcon);
    }

    public function navigationIcon(string | Htmlable | Closure | null $navigationIcon): static
    {
        $this->navigationIcon = $navigationIcon;

        return $this;
    }

}
