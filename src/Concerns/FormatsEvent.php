<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait FormatsEvent
{
    /**
     * @var Closure(?string): ?string|null
     */
    protected ?Closure $formatEventUsing = null;

    public function formatsEvent(): bool
    {
        return $this->formatEventUsing !== null;
    }

    public function formatEvent(?string $value): ?string
    {
        return ($this->formatEventUsing)($value);
    }

    public function formatEventUsing(?Closure $formatEventUsing): static
    {
        $this->formatEventUsing = $formatEventUsing;

        return $this;
    }
}
