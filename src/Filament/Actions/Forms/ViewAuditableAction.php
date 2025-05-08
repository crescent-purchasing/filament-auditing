<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditables;
use Filament\Forms\Components\Actions\Action;

class ViewAuditableAction extends Action
{
    use ViewsAuditables;

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton();
    }
}
