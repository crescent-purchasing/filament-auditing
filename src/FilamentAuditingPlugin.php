<?php

namespace CrescentPurchasing\FilamentAuditing;

use CrescentPurchasing\FilamentAuditing\Concerns\FormatsAuditableType;
use CrescentPurchasing\FilamentAuditing\Concerns\FormatsEvent;
use CrescentPurchasing\FilamentAuditing\Concerns\HasCursorPagination;
use CrescentPurchasing\FilamentAuditing\Concerns\HasModel;
use CrescentPurchasing\FilamentAuditing\Concerns\HasNavigationGroup;
use CrescentPurchasing\FilamentAuditing\Concerns\HasNavigationIcon;
use CrescentPurchasing\FilamentAuditing\Concerns\HasUsers;
use CrescentPurchasing\FilamentAuditing\Concerns\HasUserSchema;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns\HasPermission;
use CrescentPurchasing\FilamentAuditing\Filament\AuditResource;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class FilamentAuditingPlugin implements Plugin
{
    use EvaluatesClosures;
    use FormatsAuditableType;
    use FormatsEvent;
    use HasCursorPagination;
    use HasModel;
    use HasNavigationGroup;
    use HasNavigationIcon;
    use HasUsers;
    use HasUserSchema;
    use HasPermission;

    public function getId(): string
    {
        return 'filament-auditing';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            AuditResource::class,
        ]);
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
