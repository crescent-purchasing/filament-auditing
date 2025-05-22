<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament;

use CrescentPurchasing\FilamentAuditing\Filament\AuditResource;
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
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TestingPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        Gate::define('restoreAudit', function (User $user) {
            return $user->email === 'dr.morbius@example.com';
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
                AuditResource::class,
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
                FilamentAuditingPlugin::make()
                    ->users([User::class]),
            ]);
    }
}
