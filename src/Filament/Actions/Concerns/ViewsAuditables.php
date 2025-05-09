<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetOwner;
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

        $this->icon(fn (Audit $record, GetOwner $getOwner): bool => $getOwner->icon($record));

        $this->url(fn (Audit $record, GetOwner $getOwner): bool => $getOwner->url($record));

        $this->visible(fn (Audit $record, GetOwner $getOwner): bool => $getOwner->visibility($record));
    }
}
