<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetOwner;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

trait ViewsOwners
{
    public static function getDefaultName(): ?string
    {
        return 'viewOwner';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-auditing::resource.actions.view.owner'));

        $this->icon(fn (Model $record, GetOwner $owner): string | Htmlable | null => $owner->icon($record));

        $this->url(fn (Model $record, GetOwner $owner): ?string => $owner->url($record));

        $this->visible(fn (Model $record, GetOwner $owner): bool => $owner->visibility($record));
    }
}
