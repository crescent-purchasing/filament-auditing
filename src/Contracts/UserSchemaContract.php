<?php

namespace CrescentPurchasing\FilamentAuditing\Contracts;

use Filament\Forms\Components\Component;

interface UserSchemaContract
{
    /**
     * @return Component[]
     */
    public static function make(): array;
}
