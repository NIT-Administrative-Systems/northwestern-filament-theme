<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Northwestern\FilamentTheme\ImpersonationBanner\ImpersonationBannerConfig;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('is enabled by default', function () {
    $plugin = NorthwesternTheme::make();

    $reflection = new ReflectionProperty($plugin, 'impersonationBannerEnabled');

    expect($reflection->getValue($plugin))->toBeTrue();
});

it('is enabled when impersonationBanner() is called', function () {
    $plugin = NorthwesternTheme::make()->impersonationBanner();

    $reflection = new ReflectionProperty($plugin, 'impersonationBannerEnabled');

    expect($reflection->getValue($plugin))->toBeTrue();
});

it('registers the render hook when enabled', function () {
    $panel = app(Panel::class)->id('test-impersonation-enabled');
    $plugin = NorthwesternTheme::make()->impersonationBanner(visible: true);
    $plugin->register($panel);
    $plugin->boot($panel);

    $hooks = (new ReflectionProperty(FilamentView::getFacadeRoot(), 'renderHooks'))->getValue(FilamentView::getFacadeRoot());

    expect($hooks)->toHaveKey(PanelsRenderHook::TOPBAR_BEFORE);
});

it('does not register the render hook when disabled', function () {
    $panel = app(Panel::class)->id('test-impersonation-disabled');
    $plugin = NorthwesternTheme::make()->withoutImpersonationBanner();
    $plugin->register($panel);
    $plugin->boot($panel);

    $hooks = (new ReflectionProperty(FilamentView::getFacadeRoot(), 'renderHooks'))->getValue(FilamentView::getFacadeRoot());

    expect($hooks)->not->toHaveKey(PanelsRenderHook::TOPBAR_BEFORE);
});

it('returns the plugin instance for chaining from impersonationBanner()', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin->impersonationBanner())->toBe($plugin);
});

it('returns the plugin instance for chaining from withoutImpersonationBanner()', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin->withoutImpersonationBanner())->toBe($plugin);
});

it('re-enables the banner when impersonationBanner() is called after withoutImpersonationBanner()', function () {
    $plugin = NorthwesternTheme::make()
        ->withoutImpersonationBanner()
        ->impersonationBanner();

    $reflection = new ReflectionProperty($plugin, 'impersonationBannerEnabled');

    expect($reflection->getValue($plugin))->toBeTrue();
});

it('stores config when impersonationBanner() is called', function () {
    $plugin = NorthwesternTheme::make()->impersonationBanner(visible: true, label: 'Test');

    $reflection = new ReflectionProperty($plugin, 'impersonationBannerConfig');
    $config = $reflection->getValue($plugin);

    expect($config)->toBeInstanceOf(ImpersonationBannerConfig::class);
});

// --- Config unit tests ---

it('is not visible by default when lab404 is not installed', function () {
    $config = new ImpersonationBannerConfig();

    // Without lab404 installed, function_exists('is_impersonating') is false
    // so visibility falls back to false
    if (! function_exists('is_impersonating')) {
        expect($config->isVisible())->toBeFalse();
    } else {
        // If lab404 happens to be available in test env, just verify it returns a bool
        expect($config->isVisible())->toBeBool();
    }
});

it('resolves a bool for the visible state', function () {
    $config = new ImpersonationBannerConfig(visible: true);
    expect($config->isVisible())->toBeTrue();

    $config = new ImpersonationBannerConfig(visible: false);
    expect($config->isVisible())->toBeFalse();
});

it('resolves a closure for the visible state', function () {
    $config = new ImpersonationBannerConfig(visible: fn () => true);
    expect($config->isVisible())->toBeTrue();

    $config = new ImpersonationBannerConfig(visible: fn () => false);
    expect($config->isVisible())->toBeFalse();
});

it('resolves a custom string label', function () {
    $config = new ImpersonationBannerConfig(label: 'Acting as admin');

    expect($config->resolveLabel())->toBe('Acting as admin');
});

it('resolves a closure label', function () {
    $config = new ImpersonationBannerConfig(label: fn () => 'Dynamic label');

    expect($config->resolveLabel())->toBe('Dynamic label');
});

it('falls back to default label with user name', function () {
    $config = new ImpersonationBannerConfig();

    $label = $config->resolveLabel();

    // When no user is authenticated, falls back to "Unknown"
    expect($label)->toContain('Impersonating user');
});

it('resolves a custom leave URL', function () {
    $config = new ImpersonationBannerConfig(leaveUrl: '/custom/leave');

    expect($config->resolveLeaveUrl())->toBe('/custom/leave');
});

it('resolves a closure leave URL', function () {
    $config = new ImpersonationBannerConfig(leaveUrl: fn () => '/dynamic/leave');

    expect($config->resolveLeaveUrl())->toBe('/dynamic/leave');
});

it('returns null leave URL when no route is available', function () {
    $config = new ImpersonationBannerConfig();

    // Without lab404 installed, route('impersonate.leave') will throw
    expect($config->resolveLeaveUrl())->toBeNull();
});

// --- Legacy detection tests ---

it('does not log warning when legacy banner view is absent', function () {
    $plugin = NorthwesternTheme::make();
    $method = new ReflectionMethod($plugin, 'detectLegacyImpersonationBanner');

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->once()->andReturn(true);
    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $method->invoke($plugin);
});

it('skips legacy impersonation banner detection in non-local environments', function () {
    $plugin = NorthwesternTheme::make();
    $method = new ReflectionMethod($plugin, 'detectLegacyImpersonationBanner');

    Illuminate\Support\Facades\App::shouldReceive('isLocal')->once()->andReturn(false);
    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $method->invoke($plugin);
});

it('skips legacy impersonation banner detection when banner is disabled', function () {
    $plugin = NorthwesternTheme::make()->withoutImpersonationBanner();
    $method = new ReflectionMethod($plugin, 'detectLegacyImpersonationBanner');

    Illuminate\Support\Facades\Log::shouldReceive('warning')->never();

    $method->invoke($plugin);
});

// --- View tests ---

it('renders the impersonation banner view', function () {
    $config = new ImpersonationBannerConfig(label: 'Test User', leaveUrl: '/leave');
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('Test User')
        ->toContain('nu-impersonate-banner')
        ->toContain('role="alert"')
        ->toContain('<svg')
        ->toContain('Leave Impersonation')
        ->toContain('action="/leave"');
});

it('renders the banner without the leave button when no URL is available', function () {
    $config = new ImpersonationBannerConfig(label: 'Test User', leaveUrl: null);
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('Test User')
        ->not->toContain('Leave Impersonation');
});

it('renders the leave form with DELETE method spoofing', function () {
    $config = new ImpersonationBannerConfig(label: 'Test User', leaveUrl: '/leave', leaveMethod: 'DELETE');
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('method="POST"')
        ->toContain('action="/leave"')
        ->toContain('name="_method"')
        ->toContain('value="DELETE"');
});

it('renders the leave form as GET without CSRF token', function () {
    $config = new ImpersonationBannerConfig(label: 'Test User', leaveUrl: '/leave', leaveMethod: 'GET');
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('method="GET"')
        ->toContain('action="/leave"')
        ->not->toContain('name="_token"');
});

it('defaults leaveMethod to POST', function () {
    $config = new ImpersonationBannerConfig(label: 'Test User', leaveUrl: '/leave');

    expect($config->leaveMethod)->toBe('POST');

    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('method="POST"')
        ->toContain('name="_token"');
});

it('renders inline styles for the banner', function () {
    $config = new ImpersonationBannerConfig(label: 'Test');
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)
        ->toContain('<style>')
        ->toContain('.nu-impersonate-banner');
});

it('hides the banner on print', function () {
    $config = new ImpersonationBannerConfig(label: 'Test');
    $html = view('northwestern-filament-theme::impersonation-banner', ['config' => $config])->render();

    expect($html)->toContain('@media print');
});

it('has the impersonation banner view file', function () {
    $viewPath = __DIR__ . '/../../resources/views/impersonation-banner.blade.php';

    expect(file_exists($viewPath))->toBeTrue();
});
