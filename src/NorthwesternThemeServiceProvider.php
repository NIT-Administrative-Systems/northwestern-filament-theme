<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme;

use Northwestern\FilamentTheme\Console\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Registers the theme's view, command, and publishable assets.
 */
class NorthwesternThemeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('northwestern-filament-theme')
            ->hasViews('northwestern-filament-theme')
            ->hasCommand(InstallCommand::class);
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/northwestern-filament-theme'),
        ], 'northwestern-filament-theme-views');
    }
}
