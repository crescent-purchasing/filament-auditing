<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder;

use Filament\Tables\Filters\QueryBuilder\Constraints\Constraint;

class AuditUserConstraint extends Constraint
{
    protected array $types = [];

    public function getTypes()
    {
        return $this->evaluate($this->types);
    }

    public function types(array $types): static
    {
        $this->types = $types;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-user');

        $this->label(__('filament-auditing::resource.fields.user.label'));

        $this->operators([
            AuditUserOperator::make()->types($this->getTypes()),
        ]);
    }
}
