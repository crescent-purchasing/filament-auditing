<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\ViewAuditableAction;
use Filament\Tables\Actions\ViewAction;

class ViewAuditAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalFooterActions([
            ViewAuditableAction::make(),
        ]);
    }

}
