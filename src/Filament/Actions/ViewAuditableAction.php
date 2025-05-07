<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions;

use CrescentPurchasing\FilamentAuditing\Audit;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditable;
use Filament\Actions\Action;
use Filament\FilamentManager;
use Filament\Resources\Resource;

class ViewAuditableAction extends Action
{

    use ViewsAuditable;

    public static function getDefaultName(): ?string
    {
        return 'viewAuditable';
    }

}
