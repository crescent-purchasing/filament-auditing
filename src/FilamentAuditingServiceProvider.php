<?php

namespace CrescentPurchasing\FilamentAuditing;

use CrescentPurchasing\FilamentAuditing\Commands\FilamentAuditingCommand;
use CrescentPurchasing\FilamentAuditing\Testing\TestsFilamentAuditing;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAuditingServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auditing';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name);

        $package->hasConfigFile();

        $package->hasTranslations();
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {

        // Testing
        Testable::mixin(new TestsFilamentAuditing);
    }
}
