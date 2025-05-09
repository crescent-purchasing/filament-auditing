<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetOwner;
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

        $this->icon(fn (Model $record, GetOwner $getOwner): ?string => $getOwner->icon($record));

        $this->url(fn (Model $record, GetOwner $getOwner): ?string => $getOwner->url($record));

        $this->visible(fn (Model $record, GetOwner $getOwner): ?string => $getOwner->visibility($record));
    }
}
