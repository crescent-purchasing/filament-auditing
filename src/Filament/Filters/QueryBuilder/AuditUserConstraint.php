<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder;

use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;

class AuditUserConstraint extends RelationshipConstraint
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-user');

        $this->label(__('filament-auditing::resource.fields.user.label'));
    }
}
