<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Actions\GetUser;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\ViewsUsers;
use Filament\Forms\Components\Actions\Action;
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
            $title = $getUser->title($record) ?? '';

            return __('filament-auditing::resource.actions.view.title', [
                'title' => $title,
            ]);
        });
    }
}
