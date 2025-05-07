<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\Actions\Action;
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

        $this->label(function (Model $record) use ($userResource): ?string {
            return __('filament-auditing::resource.actions.view_user_title', [
                'title' => $userResource::getRecordTitle($record),
            ]);
        });

        $this->url(function (Model $record) use ($userResource): ?string {
            return $userResource::getGlobalSearchResultUrl($record);
        });

        $this->icon(function () use ($userResource): ?string {
            return $userResource::getNavigationIcon();
        });

        $this->iconButton();
    }
}
