<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Audit;

trait ViewsAuditable
{
    public static function getDefaultName(): ?string
    {
        return 'viewAuditable';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Audit $record): bool {
            if (! $record = $record->auditable) {
                return false;
            }

            return ! empty(filament()->getModelResource($record));
        });

        $this->label(function (Audit $record): string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = filament()->getModelResource($auditable);

            return __('filament-auditing::resource.actions.view_auditable_title', [
                'title' => $resource::getRecordTitle($auditable),
            ]);
        });

        $this->url(function (Audit $record): ?string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = filament()->getModelResource($auditable);

            return $resource::getGlobalSearchResultUrl($auditable);
        });

        $this->icon(function (Audit $record): ?string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = filament()->getModelResource($auditable);

            return $resource::getNavigationIcon();
        });
    }
}
