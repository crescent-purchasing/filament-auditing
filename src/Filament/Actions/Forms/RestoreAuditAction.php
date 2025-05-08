<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\RestoresAudits;
use Filament\Forms\Components\Actions\Action;

class RestoreAuditAction extends Action
{
    use RestoresAudits;

}
