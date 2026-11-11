<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('has no lockup URL when none is configured', function () {
    expect(NorthwesternTheme::lockupUrl())->toBeNull();
});

it('resolves the configured lockup to a URL, then the legacy key', function () {
    config()->set('northwestern-theme.lockup', 'images/legacy.png');
    expect(NorthwesternTheme::lockupUrl())->toBe(asset('images/legacy.png'));

    config()->set('northwestern-filament-theme.lockup', 'images/unit-white.svg');
    expect(NorthwesternTheme::lockupUrl())->toBe(asset('images/unit-white.svg'));

    config()->set('northwestern-filament-theme.lockup', 'https://cdn.example.edu/unit-white.svg');
    expect(NorthwesternTheme::lockupUrl())->toBe('https://cdn.example.edu/unit-white.svg');
});

it('gives the panel the same lockup URL as its brand logo', function () {
    config()->set('northwestern-filament-theme.lockup', 'images/unit-white.svg');

    $panel = bootPluginOnPanel(NorthwesternTheme::make(), 'test-lockup-brand-logo');

    expect($panel->getBrandLogo())->toBe(NorthwesternTheme::lockupUrl());
});

it('renders the configured lockup outside a panel', function () {
    config()->set('northwestern-filament-theme.lockup', 'images/unit-white.svg');
    config()->set('northwestern-filament-theme.unit.name', 'Feinberg IT');

    // The test case boots only the theme's service provider, not Filament's.
    expect(app()->bound('filament'))->toBeFalse();

    $html = Blade::render('<x-northwestern-filament-theme::lockup href="https://www.northwestern.edu/" />');

    expect($html)
        ->toMatch('#<a [^>]*class="nu-lockup"[^>]*>#')
        ->toMatch('#<a [^>]*href="https://www.northwestern.edu/"[^>]*>#')
        ->toMatch('#<a [^>]*style="--nu-lockup-height: 1.5rem;"[^>]*>#')
        ->toContain('<img src="' . asset('images/unit-white.svg') . '" alt="Northwestern University Feinberg IT" />')
        ->not->toContain('<svg class="nu-wordmark"');
});

it('renders the white wordmark when no lockup is configured', function () {
    $html = Blade::render('<x-northwestern-filament-theme::lockup />');

    expect($html)
        ->toContain('<span style="--nu-lockup-height: 1.5rem;" class="nu-lockup">')
        ->toContain('class="nu-wordmark"')
        ->toContain('aria-label="Northwestern University"')
        ->toContain('color: #fff;')
        ->not->toContain('<img');
});

it('keeps the lockup clear space and size in a flex row', function () {
    $html = Blade::render('<x-northwestern-filament-theme::lockup height="2rem" label="Unit home" class="me-4" />');

    expect($html)
        ->toContain('class="nu-lockup me-4"')
        ->toContain('--nu-lockup-height: 2rem;')
        ->toContain('aria-label="Unit home"')
        ->toContain('--nu-lockup-clear-space: calc(var(--nu-lockup-height) / 2);')
        ->toContain('flex-shrink: 0;')
        ->toContain('max-width: none;');
});
