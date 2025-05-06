<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;

class AuditResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'title';

    protected static bool $isGloballySearchable = false;

    public static function getModel(): string
    {
        return FilamentAuditingPlugin::get()->getModel();
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return FilamentAuditingPlugin::get()->getNavigationIcon();
    }

    public static function getPages(): array
    {
        return [
            'index' => AuditPage::route('/'),
        ];
    }
}
