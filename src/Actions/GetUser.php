<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use Filament\FilamentManager;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use OwenIt\Auditing\Models\Audit;

readonly class GetUser
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
        if (! $user = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($user);

        return $resource::getNavigationIcon();
    }

    public function title(Model $record): ?string
    {
        if (! $user = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = filament()->getModelResource($user);

        $title = $resource::getRecordTitle($user);

        if ($title instanceof Htmlable) {
            $title = $title->toHtml();
        }

        return $title;

    }

    public function url(Model $record): ?string
    {
        if (! $user = $this($record)) {
            return null;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($user);

        return $resource::getGlobalSearchResultUrl($user);
    }

    public function visibility(Model $record): ?bool
    {
        if (! $user = $this($record)) {
            return false;
        }
        return auth()->user()->can('view', $user) && ! empty($this->url($record));
    }
}
