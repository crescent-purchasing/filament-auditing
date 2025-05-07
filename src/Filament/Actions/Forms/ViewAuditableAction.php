<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditable;
use Filament\Forms\Components\Actions\Action;

class ViewAuditableAction extends Action
{
    use ViewsAuditable;

    public static function getDefaultName(): ?string
    {
        return 'viewAuditable';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton();
    }
}
