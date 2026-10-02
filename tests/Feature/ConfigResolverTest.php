<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Northwestern\FilamentTheme\ConfigResolver;
use Northwestern\FilamentTheme\Footer\SocialNetwork;
use Northwestern\FilamentTheme\NorthwesternTheme;

/** The legacy config/northwestern-theme.php as northwestern-laravel-ui publishes it, with a v2 app's values. */
function legacyThemeConfig(): array
{
    return [
        'lockup' => 'images/lockup.png',
        'office' => [
            'name' => 'Research Computing',
            'addr' => '2020 Ridge Ave',
            'city' => 'Evanston, IL 60208',
            'phone' => '',
            'email' => 'research@northwestern.edu',
            'fax' => '847-555-0199',
        ],
        'sentry-dsn' => null,
        'sentry-enable-apm-js' => true,
        'sentry-traces-sample-rate' => 0.0,
        'globalAlerts' => [],
    ];
}

/** Footer HTML without its inlined stylesheet, with whitespace collapsed and the copyright year fixed. */
function comparableFooter(string $html): string
{
    $html = (string) preg_replace('#<style>.*?</style>#s', '', $html);
    $html = (string) preg_replace('/&copy; \d{4}/', '&copy; YEAR', $html);
    $html = (string) preg_replace('/>\s+</', '><', $html);

    return trim((string) preg_replace('/\s+/', ' ', $html));
}

it('ships its config with null branding values, so the legacy keys still apply', function () {
    expect(config('northwestern-filament-theme.lockup'))->toBeNull()
        ->and(config('northwestern-filament-theme.unit'))->toBe([
            'name' => null,
            'address' => null,
            'city' => null,
            'phone' => null,
            'fax' => null,
            'email' => null,
        ])
        ->and(config('northwestern-filament-theme.footer.links'))->toBe([])
        ->and(config('northwestern-filament-theme.footer.social'))->toBe(SocialNetwork::NORTHWESTERN);
});

it('publishes the config file under its own tag', function () {
    $paths = Illuminate\Support\ServiceProvider::pathsToPublish(null, 'northwestern-filament-theme-config');

    expect($paths)->toHaveCount(1)
        ->and(array_key_first($paths))->toEndWith('config/northwestern-filament-theme.php')
        ->and(array_values($paths)[0])->toBe(config_path('northwestern-filament-theme.php'));
});

it('falls back to the built-in unit when nothing is configured', function () {
    expect(ConfigResolver::unit())->toBe([
        'name' => 'Information Technology',
        'address' => '1800 Sherman Ave',
        'city' => 'Evanston, IL 60201',
        'phone' => '847-491-4357 (1-HELP)',
        'fax' => null,
        'email' => 'consultant@northwestern.edu',
    ]);
});

it('reads the legacy office keys, mapping addr to address', function () {
    config()->set('northwestern-theme', legacyThemeConfig());

    expect(ConfigResolver::unit())->toBe([
        'name' => 'Research Computing',
        'address' => '2020 Ridge Ave',
        'city' => 'Evanston, IL 60208',
        'phone' => null,
        'fax' => '847-555-0199',
        'email' => 'research@northwestern.edu',
    ]);
});

it('prefers the new config over the legacy keys, field by field', function () {
    config()->set('northwestern-theme', legacyThemeConfig());
    config()->set('northwestern-filament-theme.unit.name', 'Feinberg IT');
    config()->set('northwestern-filament-theme.unit.address', '420 E Superior St');
    config()->set('northwestern-filament-theme.unit.phone', '312-503-0000');

    expect(ConfigResolver::unit())->toBe([
        'name' => 'Feinberg IT',
        'address' => '420 E Superior St',
        'city' => 'Evanston, IL 60208',
        'phone' => '312-503-0000',
        'fax' => '847-555-0199',
        'email' => 'research@northwestern.edu',
    ]);
});

it('prefers an explicit argument over both configs', function () {
    config()->set('northwestern-theme', legacyThemeConfig());
    config()->set('northwestern-filament-theme.unit.name', 'Feinberg IT');

    expect(ConfigResolver::unit(['name' => 'Plugin Office', 'fax' => null])['name'])->toBe('Plugin Office')
        ->and(ConfigResolver::unit(['name' => 'Plugin Office', 'fax' => null])['fax'])->toBe('847-555-0199');
});

it('hides a field set to an empty string at any step', function () {
    config()->set('northwestern-theme.office', ['name' => '', 'city' => 'Chicago, IL 60611']);
    config()->set('northwestern-filament-theme.unit.email', '');

    expect(ConfigResolver::unit(['phone' => '']))->toBe([
        'name' => null,
        'address' => '1800 Sherman Ave',
        'city' => 'Chicago, IL 60611',
        'phone' => null,
        'fax' => null,
        'email' => null,
    ]);
});

it('hides a legacy field that is present but null, as v4.0 did', function () {
    config()->set('northwestern-theme.office', ['name' => null]);

    expect(ConfigResolver::unit()['name'])->toBeNull()
        ->and(ConfigResolver::unit()['address'])->toBe('1800 Sherman Ave');
});

it('falls back to the legacy key and the default when a published config leaves unit fields out', function () {
    config()->set('northwestern-filament-theme.unit', ['name' => 'Partial Unit']);
    config()->set('northwestern-theme.office.city', 'Chicago, IL 60611');

    expect(ConfigResolver::unit())
        ->name->toBe('Partial Unit')
        ->city->toBe('Chicago, IL 60611')
        ->email->toBe('consultant@northwestern.edu');
});

it('resolves the lockup from the new config, then the legacy key, then nothing', function () {
    expect(ConfigResolver::lockup())->toBeNull();

    config()->set('northwestern-theme.lockup', 'images/legacy.png');
    expect(ConfigResolver::lockup())->toBe('images/legacy.png');

    config()->set('northwestern-filament-theme.lockup', 'images/unit.svg');
    expect(ConfigResolver::lockup())->toBe('images/unit.svg');

    config()->set('northwestern-filament-theme.lockup', '');
    expect(ConfigResolver::lockup())->toBeNull();
});

it('takes quick links from an explicit argument, then the config', function () {
    expect(ConfigResolver::links())->toBe([]);

    config()->set('northwestern-filament-theme.footer.links', ['Help' => 'https://example.com/help', 'Broken' => null]);
    expect(ConfigResolver::links())->toBe(['Help' => 'https://example.com/help'])
        ->and(ConfigResolver::links(['Status' => '/status']))->toBe(['Status' => '/status']);
});

it('takes social accounts from the config, keeping its order and skipping unknown networks', function () {
    config()->set('northwestern-filament-theme.footer.social', [
        'linkedin' => 'https://www.linkedin.com/company/unit',
        'myspace' => 'https://myspace.com/unit',
        'x' => 'https://x.com/unit',
    ]);

    expect(ConfigResolver::social())->toBe([
        'linkedin' => 'https://www.linkedin.com/company/unit',
        'x' => 'https://x.com/unit',
    ]);
});

it('uses the university accounts when a published config leaves social out, and none for []', function () {
    config()->set('northwestern-filament-theme.footer', ['links' => []]);
    expect(ConfigResolver::social())->toBe(SocialNetwork::NORTHWESTERN);

    config()->set('northwestern-filament-theme.footer.social', []);
    expect(ConfigResolver::social())->toBe([]);
});

it('renders the v4.0 footer unchanged for a v2 app with only the legacy config', function (array $legacy, string $fixture) {
    config()->set('northwestern-theme', $legacy);

    bootPluginOnPanel(NorthwesternTheme::make(), 'test-footer-v2-' . md5($fixture));

    expect(comparableFooter(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml()))
        ->toBe(comparableFooter((string) file_get_contents(__DIR__ . '/../Fixtures/' . $fixture)));
})->with([
    'customized legacy file' => [legacyThemeConfig(), 'footer-v4.0-legacy-custom.html'],
    'legacy file defaults' => [[
        'lockup' => 'https://common.northwestern.edu/v8/css/images/northwestern.svg',
        'office' => [
            'name' => 'Information Technology',
            'addr' => '1800 Sherman Ave',
            'city' => 'Evanston, IL 60201',
            'phone' => '847-491-4357 (1-HELP)',
            'email' => 'consultant@northwestern.edu',
        ],
    ], 'footer-v4.0-legacy-defaults.html'],
]);
