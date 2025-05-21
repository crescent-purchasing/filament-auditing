<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use Filament\FilamentManager;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

readonly class GetAuditable
{
    private FilamentManager $filament;

    public function __construct()
    {
        $this->filament = filament();
    }

    public function __invoke(?Model $record): ?Model
    {
        return match (true) {
            $record instanceof Auditable => $record,
            $record instanceof Audit => $record->auditable,
            default => null,
        };
    }

    public function icon(?Model $record): string | Htmlable | null
    {
        if (! $auditable = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        return $resource::getNavigationIcon();

    }

    public function title(?Model $record): ?string
    {
        if (! $auditable = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        $title = $resource::getRecordTitle($auditable);

        if ($title instanceof Htmlable) {
            $title = $title->toHtml();
        }

        return $title;
    }

    public function url(?Model $record): ?string
    {
        if (! $auditable = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        return $resource::getGlobalSearchResultUrl($auditable);
    }

    public function visibility(?Model $record): bool
    {
        if (! $auditable = $this($record)) {
            return false;
        }

        return ! empty($this->filament->getModelResource($auditable));
    }
}
