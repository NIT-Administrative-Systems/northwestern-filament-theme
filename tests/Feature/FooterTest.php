<?php

declare(strict_types=1);

use Northwestern\FilamentTheme\Footer\FooterConfig;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('is disabled by default', function () {
    $plugin = NorthwesternTheme::make();

    $reflection = new ReflectionProperty($plugin, 'footerConfig');

    expect($reflection->getValue($plugin))->toBeNull();
});

it('enables the footer when footer() is called', function () {
    $plugin = NorthwesternTheme::make()->footer();

    $reflection = new ReflectionProperty($plugin, 'footerConfig');
    $config = $reflection->getValue($plugin);

    expect($config)->toBeInstanceOf(FooterConfig::class);
    expect($config->isEnabled())->toBeTrue();
});

it('can be explicitly disabled', function () {
    $config = new FooterConfig(enabled: false);

    expect($config->isEnabled())->toBeFalse();
});

it('resolves a closure for the enabled state', function () {
    $config = new FooterConfig(enabled: fn () => true);
    expect($config->isEnabled())->toBeTrue();

    $config = new FooterConfig(enabled: fn () => false);
    expect($config->isEnabled())->toBeFalse();
});

it('stores office information', function () {
    $config = new FooterConfig(
        officeName: 'Test Office',
        officeAddr: '123 Main St',
        officeCity: 'Evanston, IL 60201',
        officePhone: '847-555-0000',
        officeEmail: 'test@northwestern.edu',
    );

    expect($config->officeName)->toBe('Test Office');
    expect($config->officeAddr)->toBe('123 Main St');
    expect($config->officeCity)->toBe('Evanston, IL 60201');
    expect($config->officePhone)->toBe('847-555-0000');
    expect($config->officeEmail)->toBe('test@northwestern.edu');
});

it('defaults office fields to null', function () {
    $config = new FooterConfig();

    expect($config->officeName)->toBeNull();
    expect($config->officeAddr)->toBeNull();
    expect($config->officeCity)->toBeNull();
    expect($config->officePhone)->toBeNull();
    expect($config->officeEmail)->toBeNull();
});

it('passes office overrides through the fluent api', function () {
    $plugin = NorthwesternTheme::make()->footer(
        officeName: 'My Office',
        officeEmail: 'me@northwestern.edu',
    );

    $reflection = new ReflectionProperty($plugin, 'footerConfig');
    $config = $reflection->getValue($plugin);

    expect($config->officeName)->toBe('My Office');
    expect($config->officeEmail)->toBe('me@northwestern.edu');
    expect($config->officeAddr)->toBeNull();
});

it('returns the plugin instance for chaining', function () {
    $plugin = NorthwesternTheme::make();
    $chainedPlugin = $plugin->footer();

    expect($chainedPlugin)->toBe($plugin);
});

it('has the footer view file', function () {
    $viewPath = __DIR__ . '/../../resources/views/footer.blade.php';

    expect(file_exists($viewPath))->toBeTrue();
});
