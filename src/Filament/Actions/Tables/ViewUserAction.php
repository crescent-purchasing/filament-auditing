<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ViewUserAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'viewUser';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $userResource = FilamentAuditingPlugin::get()->getUserResource();

        if (! $userResource) {
            $this->hidden();

            return;
        }

        $this->visible();

        $this->label(__('filament-auditing::action.view_user'));

        $this->url(function (Model $record) use ($userResource): ?string {
            return $userResource::getGlobalSearchResultUrl($record);
        });

        $this->icon(function () use ($userResource): ?string {
            return $userResource::getNavigationIcon();
        });
    }

}
