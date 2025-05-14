<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsAuditables;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource as FilamentResource;
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
            $auditable = $getAuditable($record);

            /** @var class-string<FilamentResource> $resource */
            $resource = filament()->getModelResource($auditable);

            return __('filament-auditing::resource.actions.view.title', [
                'title' => $resource::getRecordTitle($auditable),
            ]);
        });
    }
}
