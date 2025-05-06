<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Contracts\AuditSchemaContract;
use CrescentPurchasing\FilamentAuditing\Filament\AuditSchema;

trait HasAuditSchema
{
    protected string | Closure $auditSchema = AuditSchema::class;

    /**
     * @return class-string<AuditSchemaContract>
     */
    public function getAuditSchema(): string
    {
        return $this->evaluate($this->auditSchema);
    }

    public function invokeAuditSchema(array $keys, array $values): array
    {
        return $this->getAuditSchema()::invoke($keys, $values);
    }

    public function auditSchema(string | Closure $auditSchema): static
    {
        $this->auditSchema = $auditSchema;

        return $this;
    }
}
