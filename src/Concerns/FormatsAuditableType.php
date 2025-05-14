<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait FormatsAuditableType
{
    /**
     * @var Closure(?string): ?string|null
     */
    protected ?Closure $formatAuditableTypeUsing = null;

    public function formatsAuditableType(): bool
    {
        return $this->formatAuditableTypeUsing !== null;
    }

    public function formatAuditableType(?string $value): ?string
    {
        return ($this->formatAuditableTypeUsing)($value);
    }

    public function formatAuditableTypeUsing(?Closure $formatAuditableTypeUsing): static
    {
        $this->formatAuditableTypeUsing = $formatAuditableTypeUsing;

        return $this;
    }
}
