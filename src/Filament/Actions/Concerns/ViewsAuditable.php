<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Audit;
use Filament\FilamentManager;

trait ViewsAuditable
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(function (FilamentManager $filament, Audit $record): bool {
            if (! $record = $record->auditable) {
                return false;
            }

            return ! empty($filament->getModelResource($record));
        });

        $this->label(function (FilamentManager $filament, Audit $record): string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = $filament->getModelResource($auditable);

            return __('filament-auditing::resource.actions.view_auditable_title', [
                'title' => $resource::getRecordTitle($auditable),
            ]);
        });

        $this->url(function (FilamentManager $filament, Audit $record): ?string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = $filament->getModelResource($auditable);

            return $resource::getGlobalSearchResultUrl($auditable);
        });

        $this->icon(function (FilamentManager $filament, Audit $record): ?string {
            $auditable = $record->auditable;

            /** @var class-string<resource> $resource */
            $resource = $filament->getModelResource($auditable);

            return $resource::getNavigationIcon();
        });
    }

}
