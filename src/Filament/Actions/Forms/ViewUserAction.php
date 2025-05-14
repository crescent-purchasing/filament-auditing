<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Actions\GetUser;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsUsers;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;

class ViewUserAction extends Action
{
    use ViewsUsers {
        setUp as baseSetUp;
    }

    protected function setUp(): void
    {
        $this->baseSetUp();

        $this->iconButton();

        $this->label(function (Model $record, GetUser $getUser): string {
            $user = $getUser($record);

            /** @var class-string<FilamentResource> $resource */
            $resource = filament()->getModelResource($user);

            return __('filament-auditing::resource.actions.view.title', [
                'title' => $resource::getRecordTitle($user),
            ]);
        });
    }
}
