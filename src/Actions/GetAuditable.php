<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\Audit;
use Filament\FilamentManager;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

readonly class GetAuditable
{
    public function __construct(private FilamentManager $filament) {}

    public function __invoke(Audit $record): ?Model
    {
        return $record->auditable;
    }

    public function icon(Audit $record): string | Htmlable | null
    {
        $auditable = $this($record);

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        return $resource::getNavigationIcon();

    }

    public function url(Audit $record): ?string
    {
        $auditable = $this($record);

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        return $resource::getGlobalSearchResultUrl($auditable);
    }

    public function visibility(Audit $record): bool
    {
        if (! $auditable = $this($record)) {
            return false;
        }

        return ! empty($this->filament->getModelResource($auditable));
    }
}
