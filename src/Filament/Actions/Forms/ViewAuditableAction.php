<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditables;
use Filament\Forms\Components\Actions\Action;
use OwenIt\Auditing\Models\Audit;

class ViewAuditableAction extends Action
{
    use ViewsAuditables {
        setUp as baseSetUp;
    }

    protected function setUp(): void
    {
        $this->baseSetUp();

        $this->iconButton();

        $this->label(function (Audit $record, GetAuditable $getAuditable): string {
            $title = $getAuditable->title($record) ?? '';

            return __('filament-auditing::resource.actions.view.title', [
                'title' => $title,
            ]);
        });
    }
}
