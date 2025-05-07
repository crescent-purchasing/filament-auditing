<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditable;
use Filament\Actions\Action;

class ViewAuditableAction extends Action
{
    use ViewsAuditable;
}
