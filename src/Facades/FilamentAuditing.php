<?php

namespace CrescentPurchasing\FilamentAuditing\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CrescentPurchasing\FilamentAuditing\FilamentAuditing
 */
class FilamentAuditing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CrescentPurchasing\FilamentAuditing\FilamentAuditing::class;
    }
}
