<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms;

use CrescentPurchasing\FilamentAuditing\Audit;
use Filament\FilamentManager;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;

class ViewAuditableAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'viewAuditable';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $getAuditable = fn (Audit $record) => $record->auditable;

        if (! $auditable = $this->evaluate($getAuditable)) {
            $this->visible(false);

            return;
        }

        $getResource = fn (): ?string => filament()->getCurrentPanel()->getModelResource($auditable);

        /** @var class-string<resource> $resource */
        $resource = $this->evaluate($getResource);

        if (! $resource) {
            $this->visible(false);

            return;
        }

        $this->label(function () use ($auditable, $resource): string {
            return __('filament-auditing::resource.actions.view_auditable_title', [
                'title' => $resource::getRecordTitle($auditable),
            ]);
        });

        $this->url(function () use ($auditable, $resource): ?string {
            return $resource::getGlobalSearchResultUrl($auditable);
        });

        $this->icon(function () use ($resource): ?string {
            return $resource::getNavigationIcon();
        });

        $this->iconButton();
    }
}
