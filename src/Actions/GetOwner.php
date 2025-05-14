<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use Filament\FilamentManager;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use OwenIt\Auditing\Models\Audit;

readonly class GetOwner
{
    private FilamentManager $filament;

    public function __construct()
    {
        $this->filament = filament();
    }

    public function __invoke(Model $record): ?User
    {
        return match (true) {
            $record instanceof User => $record,
            $record instanceof Audit => $record->user,
            default => null,
        };
    }

    public function icon(Model $record): string | Htmlable | null
    {
        if (! $owner = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($owner);

        return $resource::getNavigationIcon();
    }

    public function url(Model $record): ?string
    {
        if (! $owner = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($owner);

        return $resource::getGlobalSearchResultUrl($record);
    }

    public function visibility(Model $record): bool
    {
        if (! $owner = $this($record)) {
            return false;
        }

        return ! empty($this->filament->getModelResource($owner));
    }
}
