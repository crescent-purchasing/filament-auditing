<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetUser;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait ViewsUsers
{
    public static function getDefaultName(): ?string
    {
        return 'viewUser';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-auditing::resource.actions.view.owner'));

        $this->icon(fn (Model $record, GetUser $user): string | Htmlable | null => $user->icon($record));

        $this->url(fn (Model $record, GetUser $user): ?string => $user->url($record));

        $this->visible(fn (Model $record, GetUser $user): bool => $user->visibility($record));
    }
}
