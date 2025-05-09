<?php

namespace CrescentPurchasing\FilamentAuditing\Contracts;

use Filament\Forms\Components\Component;

interface AuditSchemaContract
{
    /**
     * @return Component[]
     */
    public static function make(array $keys, array $values): array;
}
