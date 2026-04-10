<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Northwestern\FilamentTheme\NorthwesternTheme;

class DemoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('demo')
            ->path('/')
            ->maxContentWidth('full')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->viteTheme([
                'resources/css/filament/demo/theme.css',
                'resources/js/filament/demo/theme.js',
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): string => Blade::render('<x-filament-panels::theme-switcher />'),
            )
            ->plugins([
                NorthwesternTheme::make()
                    ->withoutAssetRegistration()
                    ->footer(),
            ]);
    }
}
