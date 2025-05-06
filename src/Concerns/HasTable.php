<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Filament\AuditTable;
use Filament\Tables\Table;

trait HasTable
{
    protected string | Closure $table = AuditTable::class;

    /**
     * @return class-string<Table>
     */
    public function getTable(): string
    {
        return $this->evaluate($this->table);
    }

    public function table(string | Closure $table): static
    {
        $this->table = $table;

        return $this;
    }
}
