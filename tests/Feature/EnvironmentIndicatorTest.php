<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Northwestern\FilamentTheme\EnvironmentIndicator\EnvironmentIndicatorConfig;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('is enabled by default', function () {
    $plugin = NorthwesternTheme::make();

    $reflection = new ReflectionProperty($plugin, 'environmentIndicatorEnabled');

    expect($reflection->getValue($plugin))->toBeTrue();
});

it('registers the environment indicator render hook by default', function () {
    $panel = app(Panel::class)->id('test-env-default');
    $plugin = NorthwesternTheme::make();
    $plugin->register($panel);
    $plugin->boot($panel);

    $hooks = (new ReflectionProperty(FilamentView::getFacadeRoot(), 'renderHooks'))->getValue(FilamentView::getFacadeRoot());

    expect($hooks)->toHaveKey(PanelsRenderHook::GLOBAL_SEARCH_BEFORE);
});

it('does not register the render hook when disabled', function () {
    $panel = app(Panel::class)->id('test-env-disabled');
    $plugin = NorthwesternTheme::make()->withoutEnvironmentIndicator();
    $plugin->register($panel);
    $plugin->boot($panel);

    $hooks = (new ReflectionProperty(FilamentView::getFacadeRoot(), 'renderHooks'))->getValue(FilamentView::getFacadeRoot());

    expect($hooks)->not->toHaveKey(PanelsRenderHook::GLOBAL_SEARCH_BEFORE);
});

it('returns the plugin instance for chaining from environmentIndicator()', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin->environmentIndicator())->toBe($plugin);
});

it('returns the plugin instance for chaining from withoutEnvironmentIndicator()', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin->withoutEnvironmentIndicator())->toBe($plugin);
});

it('re-enables the indicator when environmentIndicator() is called after withoutEnvironmentIndicator()', function () {
    $plugin = NorthwesternTheme::make()
        ->withoutEnvironmentIndicator()
        ->environmentIndicator();

    $reflection = new ReflectionProperty($plugin, 'environmentIndicatorEnabled');

    expect($reflection->getValue($plugin))->toBeTrue();
});

it('stores custom visibility config when environmentIndicator() is called', function () {
    $plugin = NorthwesternTheme::make()->environmentIndicator(visible: false);

    $reflection = new ReflectionProperty($plugin, 'environmentIndicatorConfig');
    $config = $reflection->getValue($plugin);

    expect($config)->toBeInstanceOf(EnvironmentIndicatorConfig::class);
    expect($config->isVisible())->toBeFalse();
});

it('is visible in non-production environments by default', function () {
    $config = new EnvironmentIndicatorConfig();

    Illuminate\Support\Facades\App::shouldReceive('isProduction')->andReturn(false);

    expect($config->isVisible())->toBeTrue();
});

it('is hidden in production environments by default', function () {
    $config = new EnvironmentIndicatorConfig();

    Illuminate\Support\Facades\App::shouldReceive('isProduction')->andReturn(true);

    expect($config->isVisible())->toBeFalse();
});

it('can be explicitly disabled with visible false', function () {
    $config = new EnvironmentIndicatorConfig(visible: false);

    expect($config->isVisible())->toBeFalse();
});

it('resolves a closure for the visible state', function () {
    $config = new EnvironmentIndicatorConfig(visible: fn () => true);
    expect($config->isVisible())->toBeTrue();

    $config = new EnvironmentIndicatorConfig(visible: fn () => false);
    expect($config->isVisible())->toBeFalse();
});

it('renders the environment indicator view', function () {
    $config = new EnvironmentIndicatorConfig();
    $html = view('northwestern-filament-theme::environment-indicator', ['config' => $config])->render();

    expect($html)
        ->toContain('Environment:')
        ->toContain('--nu-gold')
        ->toContain('<svg')
        ->toContain('border-top: 4px solid var(--nu-gold)')
        ->toContain('nu-env-indicator');
});

it('displays the current environment name', function () {
    $config = new EnvironmentIndicatorConfig();
    $html = view('northwestern-filament-theme::environment-indicator', ['config' => $config])->render();

    expect($html)->toContain('Environment: ' . ucfirst(app()->environment()));
});

it('does not log warning when legacy indicator is absent', function () {
    $plugin = NorthwesternTheme::make();
    $method = new ReflectionMethod($plugin, 'detectLegacyEnvironmentIndicator');

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->once()->andReturn(true);
    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $method->invoke($plugin);
});

it('skips legacy environment indicator detection in non-local environments', function () {
    $plugin = NorthwesternTheme::make();
    $method = new ReflectionMethod($plugin, 'detectLegacyEnvironmentIndicator');

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->once()->andReturn(false);
    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $method->invoke($plugin);
});

it('has the environment indicator view file', function () {
    $viewPath = __DIR__ . '/../../resources/views/environment-indicator.blade.php';

    expect(file_exists($viewPath))->toBeTrue();
});
