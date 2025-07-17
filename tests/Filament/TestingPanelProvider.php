<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\ArticleResource;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\UserResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Event;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use OwenIt\Auditing\Events\Auditing;

class TestingPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        Event::listen(function (Auditing $event): bool {
            if (! $event->model instanceof User) {
                return true;
            }

            return $event->model->email !== 'dr.morbius@example.com';
        });
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->pages([
            ])
            ->resources([
                ArticleResource::class,
                UserResource::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentAuditingPlugin::make(),
            ]);
    }
}
