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

it('registers all core CSS assets', function () {
    $panel = app(Panel::class)->id('test-css');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    $expectedAssets = [
        'nu-variables',
        'nu-typography',
        'nu-layout',
        'nu-buttons',
        'nu-forms',
        'nu-tables',
        'nu-navigation',
        'nu-components',
        'nu-utilities',
    ];

    foreach ($expectedAssets as $id) {
        expect($registeredIds)->toContain($id);
    }
});

it('does not register footer CSS when footer is not enabled', function () {
    $panel = app(Panel::class)->id('test-no-footer');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    expect($registeredIds)->not->toContain('nu-footer');
});

it('registers footer CSS when footer is enabled', function () {
    $panel = app(Panel::class)->id('test-with-footer');
    $plugin = NorthwesternTheme::make()->footer();
    $plugin->register($panel);

    $styles = FilamentAsset::getStyles(['northwestern-sysdev/northwestern-filament-theme']);
    $registeredIds = array_map(fn (Filament\Support\Assets\Css $asset) => $asset->getId(), $styles);

    expect($registeredIds)->toContain('nu-footer');
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

it('renders the footer view with default office info', function () {
    $config = new FooterConfig();

    $html = view('northwestern-filament-theme::footer', ['config' => $config])->render();

    expect($html)
        ->toContain('Information Technology')
        ->toContain('1800 Sherman Ave')
        ->toContain('consultant@northwestern.edu');
});
