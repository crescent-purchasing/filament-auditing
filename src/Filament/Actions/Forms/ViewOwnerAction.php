<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsOwners;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;

class ViewOwnerAction extends Action
{
    use ViewsOwners {
        setUp as baseSetUp;
    }

    protected function setUp(): void
    {
        $this->baseSetUp();

        $this->iconButton();

        $this->label(function (Model $record): string {
            $owner = $this->getOwner($record);

            /** @var class-string<FilamentResource> $ownerResource */
            $ownerResource = filament()->getModelResource($owner);

            return __('filament-auditing::resource.actions.view.title', [
                'title' => $ownerResource::getRecordTitle($record),
            ]);
        });
    }
}
