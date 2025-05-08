<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditables;
use Filament\Actions\Action;

class ViewAuditableAction extends Action
{
    use ViewsAuditables;
}
