<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait HasCursorPagination
{
    protected bool | Closure $cursorPagination = true;

    public function useCursorPagination(): bool
    {
        return $this->evaluate($this->cursorPagination);
    }

    public function cursorPagination(bool | Closure $cursorPagination): static
    {
        $this->cursorPagination = $cursorPagination;

        return $this;
    }
}
