<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use Illuminate\Contracts\Support\Htmlable;
use OwenIt\Auditing\Models\Audit;

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

        $this->icon(fn (Audit $record, GetAuditable $auditable): string | Htmlable | null => $auditable->icon($record));

        $this->url(fn (Audit $record, GetAuditable $auditable): ?string => $auditable->url($record));

        $this->visible(fn (Audit $record, GetAuditable $auditable): bool => $auditable->visibility($record));
    }
}
