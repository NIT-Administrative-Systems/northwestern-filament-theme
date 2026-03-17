<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Facades\FilamentAsset;
use Northwestern\FilamentTheme\Colors;
use Northwestern\FilamentTheme\Footer\FooterConfig;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('registers brand colors on the panel', function () {
    $panel = app(Panel::class)->id('test-colors');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $colors = $panel->getColors();

    expect($colors)->toHaveKey('primary')
        ->and($colors['primary'])->toBe(Colors::PRIMARY)
        ->and($colors)->toHaveKeys(['danger', 'gray', 'info', 'success', 'warning']);
});

it('registers the bundled theme CSS asset', function () {
    $panel = app(Panel::class)->id('test-css');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    expect($registeredIds)->toContain('nu-theme');
});

it('does not register footer CSS when footer is not enabled', function () {
    $panel = app(Panel::class)->id('test-no-footer');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    expect($registeredIds)->not->toContain('nu-footer');
});

it('skips theme CSS when withoutAssetRegistration is called', function () {
    $panel = app(Panel::class)->id('test-no-asset-reg');
    $plugin = NorthwesternTheme::make()->withoutAssetRegistration();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    expect($registeredIds)->not->toContain('nu-theme');
});

it('renders footer with inline styles', function () {
    $config = new FooterConfig(enabled: true);
    $html = view('northwestern-filament-theme::footer', ['config' => $config])->render();

    expect($html)
        ->toContain('<style>')
        ->toContain('.nu-footer');
});

it('sets default favicon when panel has none', function () {
    $panel = app(Panel::class)->id('test-favicon-default');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);
    $plugin->boot($panel);

    expect($panel->getFavicon())->toBe('https://common.northwestern.edu/v8/icons/favicon-32.png');
});

it('does not override a panel-configured favicon', function () {
    $panel = app(Panel::class)
        ->id('test-favicon-custom')
        ->favicon('https://example.com/custom-favicon.png');

    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);
    $plugin->boot($panel);

    expect($panel->getFavicon())->toBe('https://example.com/custom-favicon.png');
});

it('sets default brand logo when panel has none', function () {
    $panel = app(Panel::class)->id('test-logo-default');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);
    $plugin->boot($panel);

    expect($panel->getBrandLogo())->toBe('https://common.northwestern.edu/v8/css/images/northwestern.svg');
});

it('does not override a panel-configured brand logo', function () {
    $panel = app(Panel::class)
        ->id('test-logo-custom')
        ->brandLogo('https://example.com/custom-logo.svg');

    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);
    $plugin->boot($panel);

    expect($panel->getBrandLogo())->toBe('https://example.com/custom-logo.svg');
});

it('registers footer render hook when footer is enabled', function () {
    $panel = app(Panel::class)->id('test-footer-hook');
    $plugin = NorthwesternTheme::make()->footer();
    $plugin->register($panel);
    $plugin->boot($panel);

    $hooks = (new ReflectionProperty(Filament\Support\Facades\FilamentView::getFacadeRoot(), 'renderHooks'))->getValue(Filament\Support\Facades\FilamentView::getFacadeRoot());

    expect($hooks)->toHaveKey(Filament\View\PanelsRenderHook::BODY_END);
});

it('renders the footer view with custom office info', function () {
    $config = new FooterConfig(
        officeName: 'Test Office',
        officeEmail: 'test@northwestern.edu',
    );

    $html = view('northwestern-filament-theme::footer', ['config' => $config])->render();

    expect($html)
        ->toContain('Test Office')
        ->toContain('test@northwestern.edu')
        ->toContain('nu-footer')
        ->toContain(date('Y'));
});

it('warns when theme CSS is double-loaded in local environment', function () {
    $panel = app(Panel::class)->id('test-double-load');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $themeCssDir = resource_path('css/filament/test-double-load');
    Illuminate\Support\Facades\File::ensureDirectoryExists($themeCssDir);
    Illuminate\Support\Facades\File::put($themeCssDir . '/theme.css', implode("\n", [
        "@import 'tailwindcss';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
    ]));

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->andReturn(true);
    Illuminate\Support\Facades\Log::shouldReceive('warning')
        ->once()
        ->withArgs(fn (string $message) => str_contains($message, 'withoutAssetRegistration'));

    $plugin->boot($panel);

    Illuminate\Support\Facades\File::deleteDirectory($themeCssDir);
});

it('does not warn about double-load when withoutAssetRegistration is called', function () {
    $panel = app(Panel::class)->id('test-no-double-warn');
    $plugin = NorthwesternTheme::make()->withoutAssetRegistration();
    $plugin->register($panel);

    $themeCssDir = resource_path('css/filament/test-no-double-warn');
    Illuminate\Support\Facades\File::ensureDirectoryExists($themeCssDir);
    Illuminate\Support\Facades\File::put($themeCssDir . '/theme.css', implode("\n", [
        "@import 'tailwindcss';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
    ]));

    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $plugin->boot($panel);

    Illuminate\Support\Facades\File::deleteDirectory($themeCssDir);
});

it('does not warn about double-load in non-local environments', function () {
    $panel = app(Panel::class)->id('test-prod-no-warn');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $themeCssDir = resource_path('css/filament/test-prod-no-warn');
    Illuminate\Support\Facades\File::ensureDirectoryExists($themeCssDir);
    Illuminate\Support\Facades\File::put($themeCssDir . '/theme.css', implode("\n", [
        "@import 'tailwindcss';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
    ]));

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->andReturn(false);
    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $plugin->boot($panel);

    Illuminate\Support\Facades\File::deleteDirectory($themeCssDir);
});

it('renders the footer view with default office info', function () {
    $config = new FooterConfig();

    $html = view('northwestern-filament-theme::footer', ['config' => $config])->render();

    expect($html)
        ->toContain('Information Technology')
        ->toContain('1800 Sherman Ave')
        ->toContain('consultant@northwestern.edu');
});
