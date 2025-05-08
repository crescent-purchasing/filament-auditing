<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\RestoresAudits;
use Filament\Actions\Action;

class RestoreAuditAction extends Action
{
    use RestoresAudits;
}
