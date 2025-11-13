<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use Filament\Actions\Action;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\RestoresAudits;

class RestoreAuditAction extends Action
{
    use RestoresAudits;
}
