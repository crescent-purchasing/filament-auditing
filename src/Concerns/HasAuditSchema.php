<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Contracts\AuditSchemaContract;
use CrescentPurchasing\FilamentAuditing\Filament\Schemas\AuditSchema;
use Filament\Forms\Components\Component;

trait HasAuditSchema
{
    /** @var class-string<AuditSchemaContract>|Closure */
    protected string | Closure $auditSchema = AuditSchema::class;

    /**
     * @return class-string<AuditSchemaContract>
     */
    public function getAuditSchema(): string
    {
        return $this->evaluate($this->auditSchema);
    }

    /**
     * @return Component[]
     */
    public function makeAuditSchema(array $keys, array $values): array
    {
        return $this->getAuditSchema()::make($keys, $values);
    }

    /**
     * @param  class-string<AuditSchemaContract>|Closure  $auditSchema
     * @return $this
     */
    public function auditSchema(string | Closure $auditSchema): static
    {
        $this->auditSchema = $auditSchema;

        return $this;
    }
}
