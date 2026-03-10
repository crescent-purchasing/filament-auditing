<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions;

use Filament\Actions\ViewAction;

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
