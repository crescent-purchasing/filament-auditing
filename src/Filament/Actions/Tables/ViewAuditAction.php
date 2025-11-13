<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables;

use Filament\Actions\ViewAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\RestoreAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\ViewAuditableAction;

class ViewAuditAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->extraModalFooterActions([
            RestoreAuditAction::make(),
            ViewAuditableAction::make(),
        ]);
    }
}
