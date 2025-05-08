<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Audit;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

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

        $this->visible(function (Model $record): bool {
            if (! $owner = $this->getOwner($record)) {
                return false;
            }

            return ! empty(filament()->getModelResource($owner));
        });

        $this->url(function (Model $record): ?string {
            $owner = $this->getOwner($record);

            /** @var class-string<FilamentResource> $ownerResource */
            $ownerResource = filament()->getModelResource($owner);

            return $ownerResource::getGlobalSearchResultUrl($record);
        });

        $this->icon(function (Model $record): ?string {
            $owner = $this->getOwner($record);

            /** @var class-string<FilamentResource> $ownerResource */
            $ownerResource = filament()->getModelResource($owner);

            return $ownerResource::getNavigationIcon();
        });
    }

    private function getOwner(Model $record): Authenticatable | User | null
    {
        return match (true) {
            $record instanceof Authenticatable => $record,
            $record instanceof Audit => $record->owner,
            default => null,
        };
    }
}
