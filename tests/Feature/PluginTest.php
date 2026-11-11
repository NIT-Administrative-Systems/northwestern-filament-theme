<?php

declare(strict_types=1);

use Northwestern\FilamentTheme\Colors;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('can be instantiated', function () {
    $plugin = new NorthwesternTheme();

    expect($plugin)->toBeInstanceOf(NorthwesternTheme::class);
});

it('has the correct plugin id', function () {
    $plugin = new NorthwesternTheme();

    expect($plugin->getId())->toBe('northwestern-theme');
});

it('has a static make method', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin)->toBeInstanceOf(NorthwesternTheme::class);
});

it('has all CSS module files', function () {
    $cssPath = __DIR__ . '/../../resources/css';

    $modules = [
        'variables.css',
        'typography.css',
        'layout.css',
        'buttons.css',
        'forms.css',
        'tables.css',
        'navigation.css',
        'modals.css',
        'dropdowns.css',
        'badges.css',
        'notifications.css',
        'widgets.css',
        'sections.css',
        'pagination.css',
        'components.css',
        'utilities.css',
    ];

    foreach ($modules as $module) {
        expect(file_exists($cssPath . '/' . $module))->toBeTrue("Missing CSS module: {$module}");
    }
});

it('defines primary color constants as RGB triplet array', function () {
    expect(Colors::PRIMARY)->toBeArray();
    expect(Colors::PRIMARY)->toHaveKeys([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]);
    expect(Colors::PRIMARY[600])->toBe('78, 42, 132'); // Northwestern Purple
});

it('defines all hex color constants', function () {
    expect(Colors::PURPLE_100)->toBe('#4E2A84');
    expect(Colors::ORANGE)->toBe('#EF553F');
    expect(Colors::GREEN)->toBe('#58B947');
    expect(Colors::GOLD)->toBe('#FFC520');
    expect(Colors::BLUE)->toBe('#5091CD');
});

it('uses the current Rich Black tints from the brand palette', function () {
    expect(Colors::RICH_BLACK_80)->toBe('#1F1F1F')
        ->and(Colors::RICH_BLACK_50)->toBe('#606060')
        ->and(Colors::RICH_BLACK_20)->toBe('#B8B8B8')
        ->and(Colors::RICH_BLACK_10)->toBe('#DADADA');
});

it('gives the theme CSS the same Rich Black tints as Colors', function () {
    $css = (string) file_get_contents(__DIR__ . '/../../resources/css/variables.css');

    foreach (['80' => Colors::RICH_BLACK_80, '50' => Colors::RICH_BLACK_50, '20' => Colors::RICH_BLACK_20, '10' => Colors::RICH_BLACK_10] as $tint => $hex) {
        expect($css)->toContain("--nu-black-{$tint}: " . strtolower($hex) . ';');
    }
});
