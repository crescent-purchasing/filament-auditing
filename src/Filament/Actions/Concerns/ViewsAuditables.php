<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use CrescentPurchasing\FilamentAuditing\Audit;

trait ViewsAuditables
{
    public static function getDefaultName(): ?string
    {
        return 'viewAuditable';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-auditing::resource.actions.view.auditable'));

        $this->icon(fn (Audit $record, GetAuditable $getAuditable): bool => $getAuditable->icon($record));

        $this->url(fn (Audit $record, GetAuditable $getAuditable): bool => $getAuditable->url($record));

        $this->visible(fn (Audit $record, GetAuditable $getAuditable): bool => $getAuditable->visibility($record));
    }
}
