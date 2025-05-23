<?php

namespace CrescentPurchasing\FilamentAuditing\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingServiceProvider;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\TestingPanelProvider;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use OwenIt\Auditing\AuditingServiceProvider;
use OwenIt\Auditing\Resolvers\IpAddressResolver;
use OwenIt\Auditing\Resolvers\UrlResolver;
use OwenIt\Auditing\Resolvers\UserAgentResolver;
use OwenIt\Auditing\Resolvers\UserResolver;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Audit
        config()->set('audit.drivers.database.table', 'audit_testing');
        config()->set('audit.drivers.database.connection', 'testing');
        config()->set('audit.user.morph_prefix', 'user');
        config()->set('audit.user.resolver', UserResolver::class);
        config()->set('audit.user.guards', [
            'web',
            'api',
        ]);
        config()->set('auth.guards.api', [
            'driver' => 'session',
            'provider' => 'users',
        ]);

        config()->set('audit.resolvers.url', UrlResolver::class);
        config()->set('audit.resolvers.ip_address', IpAddressResolver::class);
        config()->set('audit.resolvers.user_agent', UserAgentResolver::class);

        config()->set('audit.console', true);
        config()->set('audit.empty_values', true);
        config()->set('audit.queue.enable', true);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $user = User::factory()->create([
            'is_admin' => true,
            'first_name' => 'Michael',
            'last_name' => 'Morbius',
            'email' => 'dr.morbius@example.com',
        ]);

        test()->admin = $user;
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            AuditingServiceProvider::class,
            FilamentAuditingServiceProvider::class,
            TestingPanelProvider::class,
        ];
    }
}
