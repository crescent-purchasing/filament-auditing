<?php

namespace CrescentPurchasing\FilamentAuditing;

use CrescentPurchasing\FilamentAuditing\Concerns\HasAuditSchema;
use CrescentPurchasing\FilamentAuditing\Concerns\HasCursorPagination;
use CrescentPurchasing\FilamentAuditing\Concerns\HasForm;
use CrescentPurchasing\FilamentAuditing\Concerns\HasModel;
use CrescentPurchasing\FilamentAuditing\Concerns\HasNavigationIcon;
use CrescentPurchasing\FilamentAuditing\Concerns\HasTable;
use CrescentPurchasing\FilamentAuditing\Concerns\HasUserResource;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class FilamentAuditingPlugin implements Plugin
{

    use EvaluatesClosures;
    use HasAuditSchema;
    use HasCursorPagination;
    use HasForm;
    use HasModel;
    use HasNavigationIcon;
    use HasTable;
    use HasUserResource;


    public function getId(): string
    {
        return 'filament-auditing';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        if (! ($currentPanel = filament()->getCurrentPanel())) {
            return app(static::class);
        }

        /** @var static */
        return $currentPanel->getPlugin(app(static::class)->getId());
    }
}
